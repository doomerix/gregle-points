<?php

namespace Gregle\Users\Metadata;


class Role
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