<?php
namespace Book\BookApi\Controller;

class UserController
{
    public function __construct($user_m)
    {
        $this->user_m = $user_m;
    }
    public function userList()
    {
        // echo "UserList function inside the userController";
        $this->user_m->userListModel();
    }
}

?>