<?php

namespace Ifm\Import;

require './vendor/autoload.php';

use League\Csv\Reader;
use League\Csv\Writer;

class CsvEdit
{
    public $steps;
    public $step;
    public $records;
    public $record;
    public $csv_out;
    public $header;
    public $header_out;
    public $records_out;
    public $record_out;

    public function __construct($in, $out, $steps)
    {
        $this->steps = $steps;
        $this->csv_out = $out;
        $csv = Reader::createFromPath($in, 'r');
        $csv->setHeaderOffset(0);
        $this->header = $csv->getHeader();
        $this->records = $csv->getRecords();
    }

    public function run()
    {
        $this->header_out = $this->header;
        $this->records_out = array();

        // filter header
        foreach ($this->steps as $filter => $step) {
            $this->step = $step;
            $this->$filter(true);
        }

        // filter records
        foreach ($this->records as $record) {
            $this->record_out = $record;
            foreach ($this->steps as $filter => $step) {
                $this->step = $step;
                $this->$filter();
            }
            $this->records_out[] = $this->record_out;
        }
        $writer = Writer::createFromPath($this->csv_out, 'w+');
        $writer->insertOne($this->header_out);
        $writer->insertAll($this->records_out);
    }

    public function duplicate_col($is_header = false)
    {
        if ($is_header && !in_array($this->step['to'], $this->header_out)) :
            $this->header_out[] = $this->step['from'];
        else :
            $this->record_out[$this->step['to']] = $this->record_out[$this->step['from']];
        endif;
    }

    public function trim($is_header = false)
    {
        // need to fill this function out with more offset and limit capabilities 
        if (!$is_header) :
            $exploded = explode($this->step['delimiter'], $this->record_out[$this->step['column']]);
            $sliced = array_slice($exploded, $this->step['offset'], $this->step['length']);
            $new_string = implode($this->step['delimiter'], $sliced);
            $this->record_out[$this->step['column']] = $new_string;
        endif;
    }

    public function drop_first($is_header = false)
    {
        if (!$is_header) :
            $terms = explode(",", $this->record_out[$this->step['column']]);
            // drop first element of array
            array_shift($terms);
            $this->record_out[$this->step['column']] = implode(',', $terms);
        endif;
    }

    public function drop_all_but_first($is_header = false)
    {
        if (!$is_header) :
            $terms = explode(",", $this->record_out[$this->step['column']]);
            // drop first element of array
            $this->record_out[$this->step['column']] = $terms[0];
        endif;
    }

    public function prepend_string($is_header = false)
    {
        if (!$is_header) :
            $this->record_out[$this->step['column']] = $this->step['string'] . $this->record_out[$this->step['column']];
        endif;
    }

    public function str_replace($is_header = false)
    {
        if (!$is_header) :
            foreach ($this->step['strings'] as $string_from => $string_to) :
                $this->record_out[$this->step['column']] = str_replace($string_from, $string_to, $this->record_out[$this->step['column']]);
            endforeach;
        endif;
    }

    public function str_to_lower($is_header = false)
    {
        if (!$is_header) :
            $this->record_out[$this->step['column']] = strtolower($this->record_out[$this->step['column']]);
        endif;
    }

    public function strip_special($is_header = false)
    {
        if (!$is_header) :
            $this->record_out[$this->step['column']] = preg_replace('/[^A-Za-z0-9\-]/', '', $this->record_out[$this->step['column']]);
        endif;
    }

    public function parse_facebook($url)
    {
        return preg_replace('/https?:\/\/(www\.)?facebook\.com\/(pages\/)?/', '', $url);
    }

    public function parse_twitter($url)
    {
        return preg_replace('/https?:\/\/(www\.)?twitter\.com\/@?/', '', $url);
    }

    public function parse_youtube($url)
    {
        return preg_replace('/https?:\/\/(www\.)?youtube\.com\/(user\/)?/', '', $url);
    }

    public function parse_reverb($url)
    {
        return preg_replace('/https?:\/\/(www\.)?reverbnation\.com\/(user\/)?/', '', $url);
    }

    public function parse_myspace($url)
    {
        return preg_replace('/https?:\/\/[(new\.)|(new\.)]?myspace\.com\/(user\/)?/', '', $url);
    }

    public function parse_iframe($url)
    {
        return preg_replace('/\<iframe.+? src="(http.+)"\>\<\/\iframe\>/', '$1', $url);
    }

    public function nicename_from_email($email)
    {
        return preg_replace('/(.+?)@(.+?)\.(.+)?/', '$1-at-$2-dot-$3', $email);
    }
}
