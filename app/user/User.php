<?php
namespace Gregle\Users;

use Gregle\Users\Metadata\Role;

class User
{
    private $userName;
    /**
     * @var Role
     */
    private $role;

    public function __construct($userName, Role $role)
    {
        $this->userName = $userName;
        $this->role = $role;
    }

    //  getters & setters
    public function getUserName()
    {
        return $this->userName;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

}