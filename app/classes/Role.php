<?php

class Role
{

    private $name;

    public function __construct(string $roleName)
    {
        $this->name = $roleName;
    }

    //  methods
    public static function fromUserId(mysqli $sql, string $userId) {
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