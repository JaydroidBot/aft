<?php
    // Defines simple logger to log events to a file
    class Logger {
        // Define global variables
        private
            $file,
            $timestamp;

        // Instantiate logger variables
        public function __construct($filename) {
            $this->file = $filename;
            $this->timestamp = date("D M d 'y h.i A")." >> ";
        }

        // Open log file
        // Insert logs
        public function insert($insert) {
            if (isset($this->timestamp)) {
                file_put_contents($this->file, $this->timestamp.$insert."\n", FILE_APPEND);
            }
        }
    }
?>