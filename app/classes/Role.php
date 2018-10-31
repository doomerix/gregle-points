<?php

class Role
{

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    //  getters
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    //  functions
    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->name == 'administrator';
    }

    /**
     * @return bool
     */
    public function isTeacher(): bool {
        return $this->name == 'docent';
    }

    /**
     * @return bool
     */
    public function isStudent(): bool {
        return $this->name == 'student';
    }
}