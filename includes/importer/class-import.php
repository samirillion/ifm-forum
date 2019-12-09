<?php
require get_template_directory() . '/client/inc/importer/class-importer.php';
require get_template_directory() . '/client/inc/importer/class-csv-edit.php';

use Ifm\Import\CsvEdit;
use Ifm\Import\CsvImporter;

class Import
{

    protected $steps;

    static function register()
    {
        new Importer(self::$steps);
    }

    static function add_steps($steps)
    {
        self::$steps = $steps;
    }


    function cea_run_s2member_edit()
    {
        $file_in = get_template_directory() . '/client/inc/importer/assets/s2member_records_only.csv';
        $file_out = get_template_directory() . '/client/inc/importer/assets/s2member_records_formatted.csv';

        // $steps pull in here
        require get_template_directory() . '/client/inc/importer/import-steps/pre-s2member-edit.php';

        $editor = new CsvEdit($file_in, $file_out, $steps);
        $editor->run();
    }

    function cea_run_csv_edit()
    {
        $file_in = get_template_directory() . '/client/inc/importer/assets/cea-talent.csv';
        $file_out = get_template_directory() . '/client/inc/importer/assets/cea-talent-formatted.csv';

        // $steps pull in here
        require get_template_directory() . '/client/inc/importer/import-steps/cea-csv-edit.php';

        $editor = new CsvEdit($file_in, $file_out, $steps);
        $editor->run();
    }

    function cea_run_csv_import()
    {
        $cea_csv_filepath = get_template_directory() . '/client/inc/importer/assets/cea-talent-formatted.csv';

        // $steps
        require get_template_directory() . '/client/inc/importer/import-steps/cea-profile-import.php';

        // Steps Applied On Each Record
        // $ refers to csv column names,
        // @ refers to generated values like post ids, 
        // everything else is a plain string
        $importer = new Importer();
        $importer->setup($cea_csv_filepath, $steps, 550, 200);
        $importer->run();
    }
}
