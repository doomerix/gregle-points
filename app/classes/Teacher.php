<?php

class Teacher implements CRUD
{

    private $firstName;
    private $prefix;
    private $surName;
    private $teacherId;
    private $classes;
    private $isAdmin;

    public function __construct($firstName, $prefix, $surName, $teacherId, $classes, $isAdmin)
    {
        $this->firstName = $firstName;
        $this->prefix = $prefix;
        $this->surName = $surName;
        $this->teacherId = $teacherId;
        $this->classes = $classes;
        $this->isAdmin = $isAdmin;
    }

    //  getters
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @return string
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }

    /**
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    //  functions
    /**
     * @return bool
     */
    public function isValid() {
        return $this->firstName != null && $this->surName != null && $this->teacherId != null;
    }

    //  crud
    public function create(mysqli $sql)
    {
        //  default password for new accounts, password change is being enforced on first login
        $defaultPassword = password_hash("welkom" . date("Y"), PASSWORD_BCRYPT);
        $insertIntoUsers = $sql->prepare("INSERT INTO user (user_id, passwordhash, role_id) VALUES (?, ?, (SELECT id FROM role WHERE role = ".($this->isAdmin ? "'administrator'" : "'docent'").")) ;");
        $insertIntoUsers->bind_param("ss", $this->teacherId, $defaultPassword);

        $insertIntoTeachers = $sql->prepare("INSERT INTO docent (docentnumber, firstname, surname_prefix, surname) VALUES (?, ?, ?, ?) ;");
        $insertIntoTeachers->bind_param("ssss", $this->teacherId, $this->firstName, $this->prefix, $this->surName);

        return $insertIntoUsers->execute() == true && $insertIntoTeachers->execute() == true;
    }

    public function read(mysqli $sql)
    {
    }

    public function update(mysqli $sql)
    {
        $deleteFromDocentClasses = $sql->prepare("DELETE FROM docent_classes WHERE docentnumber = ?");
        $deleteFromDocentClasses->bind_param("s",$this->teacherId);
        $deleteFromDocentClasses->execute();

        foreach ($this->classes as $class) {
            $insertIntoTeacherClasses = $sql->prepare("INSERT INTO docent_classes (docentnumber, class_id) VALUES (?, (SELECT id FROM class WHERE class = ?)) ;");
            $insertIntoTeacherClasses->bind_param("ss", $this->teacherId,$class);
            $insertIntoTeacherClasses->execute();
        }

        $updateUser = $sql->prepare("UPDATE user SET role_id = (SELECT id FROM role WHERE role = ".($this->isAdmin ? "'administrator'" : "'docent'").") WHERE user_id = ? ;");
        $updateUser->bind_param("s", $this->teacherId);
        $updateUser->execute();

        $updateDocents = $sql->prepare("UPDATE docent SET firstname = ?, surname_prefix = ?, surname = ? WHERE docentnumber = ? ;");
        $updateDocents->bind_param("ssss", $this->firstName, $this->prefix, $this->surName, $this->teacherId);
        return $updateDocents->execute() == true;
    }

    public function delete(mysqli $sql)
    {
        $deleteFromDocents = $sql->prepare("DELETE FROM user WHERE user_id = ? ;");
        $deleteFromDocents->bind_param("s", $this->teacherId);
        return $deleteFromDocents->execute();
    }
}