<?php

namespace Ifm\Import;

require(IFM_INC . '/importer/class-importer.php');

class CsvImporter extends Importer
{
    public $steps;

    public $file_path;
    public $limit;
    public $offset;

    public $header;
    public $records;
    public $record;

    // maintain an array of ids to reference in later steps _on the same record_
    public $ids = array();

    public function setup($file_path, $steps, $offset = 0, $limit = -1)
    {
        $this->steps = $steps;
        $this->readCSV($file_path, $limit, $offset);
    }

    public function readCSV($file_path, $limit, $offset)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setHeaderOffset(0);
        $this->header = $csv->getHeader();

        if ($limit >= 0) {
            $stmt = (new Statement())
                ->offset($offset)
                ->limit($limit);
            $this->records = $stmt->process($csv);
        } else {
            $this->records = $csv->getRecords();
        }
    }

    public function run()
    {
        foreach ($this->records as $record) {
            $this->record = $record;
            $this->ids = array();
            foreach ($this->steps as $step) {
                // set wet_map to map containing values drawn from
                // csv records or posts/users created while processing record
                $this->step = $this->hydrate($step);

                // run function specified in step, e.g., 'get_user_by_email'
                $step_method = $this->step['method'];
                $this->$step_method();
            }
        }
    }

    public function hydrate($step)
    {
        // take the values from the php array and set the right hand side 
        // to a value from the CSV record, a generated value, or a string literal
        // goes 2-d right now
        $wet_step = array();

        foreach ($step as $key => $value) {
            if (is_array($value)) :
                foreach ($step[$key] as $map_key => $map_value) :
                    $wet_step[$key][$map_key] = $this->evaluate($map_value);
                endforeach;
            else :
                $wet_step[$key] = $this->evaluate($value);
            endif;
        }
        return $wet_step;
    }

    public function evaluate($value)
    {
        // '@' denotes a reference to a value previously set by the import 
        // process (user or post generated)
        if ($value[0] === "@") {
            // get a generated id
            $id = substr($value, 1, strlen($value) - 1);
            $wet = array_key_exists($id, $this->ids) ? $this->ids[$id] : null;

            // '$' denotes a value name in the CSV record
        } elseif ($value[0] === "$") {

            // get the csv value
            $value = substr($value, 1, strlen($value) - 1);
            $wet = array_key_exists($value, $this->record) ? $this->record[$value] : null;

            // everything else is a string literal
        } else {
            $wet = $value;
        }

        return $wet;
    }

    public function get_user_by_email()
    {
        $key = $this->step['id'];
        $email = $this->step['map']['email'];
        $value = get_user_by_email($email)->ID;
        $this->ids[$key] = $value;
    }

    // public function create_user()
    // {
    //     $user_id =  wp_insert_user(
    //         $args
    //     );

    //     return $user_id;
    // }

    public function create_post()
    {
        $args = array();
        foreach ($this->step['map'] as $key => $value) {
            $args[$key] = $value;
        }
        $post_id = wp_insert_post(
            $args
        );

        $key = $this->step['id'];

        $this->ids[$key] = $post_id;
    }

    public function update_user_meta()
    {
        $user_id = $this->step['user_id'];
        foreach ($this->step['map'] as $key => $value) {
            update_user_meta($user_id, $key, $value);
        }
    }

    public function update_post($post_id)
    { }

    public function add_post_terms()
    {
        $post_id = $this->step['post_id'];
        $terms = explode(",", $this->step['map']['terms']);
        $term_type = $this->step['term_type'];
        wp_set_object_terms($post_id, $terms, $term_type, true);
    }

    public function add_acf_meta()
    {
        $post_id = $this->step['post_id'];

        foreach ($this->step['map'] as $key => $value) :

            $field_object = get_field_object($key);
            $type = $field_object['type'];

            switch ($type):
                case 'text':
                case 'Wysiwyg Editor':
                case 'oEmbed':
                case 'user':
                    update_field($key, $value, $post_id);
                    break;
                case 'image':
                    $attachment_id = media_sideload_image($value, $post_id, "", "id");
                    update_field($key, $attachment_id, $post_id);
                    break;
                case 'gallery':
                    $gal_arr = array();
                    foreach (explode(",", $value) as $img_url) :
                        $gal_arr[] = media_sideload_image($value, $post_id, "", "id");
                    endforeach;
                    update_field($key, $gal_arr, $post_id);
                    break;
                case 'repeater':
                    break;
                default:
                    update_field($key, $value, $post_id);
            endswitch;
        endforeach;
    }
}
