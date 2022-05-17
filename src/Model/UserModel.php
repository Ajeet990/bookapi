<?php

namespace Book\BookApi\Model;

class UserModel
{
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function userListModel()
    {
        echo "listing all users from model";
    }
}

