<?php

class Student implements CRUD
{

    private $firstName;
    private $prefix;
    private $surName;
    private $studentId;
    private $studentClass;

    public function __construct(string $firstName, string $prefix, string $surName, string $studentId, string $studentClass)
    {
        $this->firstName = $firstName;
        $this->prefix = $prefix;
        $this->surName = $surName;
        $this->studentId = $studentId;
        $this->studentClass = $studentClass;
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
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * @return string
     */
    public function getStudentClass()
    {
        return $this->studentClass;
    }

    //  functions
    /**
     * @return bool
     */
    public function isValid() {
        return $this->firstName != null && $this->surName != null && $this->studentId != null && $this->studentClass != null;
    }

    //  crud
    public function create(mysqli $sql)
    {
        //  default password for new accounts, password change is being enforced on first login
        $defaultPassword = password_hash("welkom" . date("Y"), PASSWORD_BCRYPT);
        $insertIntoUsers = $sql->prepare("INSERT INTO user (user_id, passwordhash, role_id) VALUES (?, ?, (SELECT id FROM role WHERE role = 'student')) ;");
        $insertIntoUsers->bind_param("ss", $this->studentId, $defaultPassword);

        $insertIntoStudents = $sql->prepare("INSERT INTO student (studentnumber, firstname, surname_prefix, surname, class_id) VALUES (?, ?, ?, ?, (SELECT id FROM class WHERE class = ?)) ;");
        $insertIntoStudents->bind_param("sssss", $this->studentId, $this->firstName, $this->prefix, $this->surName, $this->studentClass);
        return $insertIntoUsers->execute() == true && $insertIntoStudents->execute() == true;
    }

    public function read(mysqli $sql)
    {
    }

    public function update(mysqli $sql)
    {
        $updateStudents = $sql->prepare("UPDATE student SET firstname = ?, surname_prefix = ?, surname = ? WHERE studentnumber = ? ;");
        $updateStudents->bind_param("ssss", $this->firstName, $this->prefix, $this->surName, $this->studentId);
        return $updateStudents->execute() == true;
    }

    public function delete(mysqli $sql)
    {
        $deleteFromStudents = $sql->prepare("DELETE FROM student WHERE studentnumber = ? ;");
        $deleteFromStudents->bind_param("s", $this->studentId);
        return $deleteFromStudents->execute();
    }
}