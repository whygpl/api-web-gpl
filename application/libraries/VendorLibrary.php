<?php defined('BASEPATH') OR exit('No direct script access allowed');
use LZCompressor\LZString as LZString;

class ClassNameLibrary {

    public $class;

    public function __construct()
    {
        $this->class = new ClassName();
    } 

    public function clear($data)
    {
        return $this->class->clean($data);
    }
}
