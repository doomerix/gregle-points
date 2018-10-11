<?php

namespace Gregle\Users\impl;

use Gregle\Users\Metadata\Role;
use Gregle\Users\Metadata\StudentClass;
use Gregle\Users\User;

class Student extends User
{

    private $studentId;
    private $firstName;
    private $surName;
    private $surNamePrefix;
    /**
     * @var StudentClass
     */
    private $class;

    public function __construct($userName, $studentId, $firstName, $surName, $surNamePrefix, Role $role, StudentClass $class)
    {
        parent::__construct($userName, $role);
        $this->studentId = $studentId;
        $this->firstName = $firstName;
        $this->surName = $surName;
        $this->surNamePrefix = $surNamePrefix;
        $this->class = $class;
    }
}