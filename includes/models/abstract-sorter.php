<?php
    abstract class abstractSorter
    {
        private $sorter;

        public function __construct($sorter)
        {
            $this->sorter = $sorter;
        }

        abstract public function define_post_type();
        abstract public function define_post_meta_on_load();

    } //end class abstractSorter
