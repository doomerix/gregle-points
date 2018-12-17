<?php

class Role
{

    private $name;

    public function __construct($roleName)
    {
        $this->name = $roleName;
    }

    //  methods
    public static function fromUserId(mysqli $sql, $userId) {
        $selectClass = $sql->prepare("SELECT role FROM user LEFT JOIN role ON role_id = role.id WHERE user_id = ? ;");
        $selectClass->bind_param("s", $userId);
        $selectClass->execute();
        $selectClass->bind_result($roleName);
        $selectClass->fetch();
        $selectClass->free_result();
        return new Role($roleName);
    }

    //  getters
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    //  functions
    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->name == 'administrator';
    }

    /**
     * @return bool
     */
    public function isTeacher() {
        return $this->name == 'docent';
    }

    /**
     * @return bool
     */
    public function isStudent() {
        return $this->name == 'student';
    }
}