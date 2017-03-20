<?php
    abstract class abstractSorter
    {
        private $sorter;

        public function __construct($sorter)
        {
            $this->sorter = $sorter;
        }

        abstract public function define_post_type();
        abstract public function define_post_meta();
        abstract public function define_user_meta();

    } //end class abstractSorter
