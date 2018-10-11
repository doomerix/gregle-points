<?php

namespace Gregle\Users\Metadata;

class StudentClass
{

    private $name;  //  is unique

    public function __construct($name)
    {
        $this->name = $name;
    }

    //  getters

    public function getName()
    {
        return $this->name;
    }

}