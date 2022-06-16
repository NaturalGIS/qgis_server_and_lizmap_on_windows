<?php

namespace Jelix\ComposerPlugin;


class DebugLogger
{


    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function log($message)
    {
        $f = fopen($this->file, 'a');
        fwrite($f, $message."\n");
        fclose($f);
    }

}
