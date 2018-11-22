<?php

interface CRUD
{

    function create(mysqli $sql, array $params);
    function read(mysqli $sql);
    function update(mysqli $sql);
    function delete(mysqli $sql);

}