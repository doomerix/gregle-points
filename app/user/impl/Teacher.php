<?php

namespace Gregle\Users\impl;

use Gregle\Users\User;

class Teacher extends User
{

    private $teacherId;
    private $firstName;
    private $surName;
    private $surNamePrefix;

    public function __construct($userName, $role, $teacherId, $firstName, $surName, $surNamePrefix)
    {
        parent::__construct($userName, $role);
        $this->teacherId = $teacherId;
        $this->firstName = $firstName;
        $this->surName = $surName;
        $this->surNamePrefix = $surNamePrefix;
    }
}