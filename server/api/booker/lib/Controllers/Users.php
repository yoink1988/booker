<?php
namespace Controllers;

class Users
{
    private $model;

    public function __construct()
    {
        $this->model = new \Models\Users();
    }

    public function getUsers($params = null)
    {
        return $this->model->getUsers($params);
    }

    public function postUsers()
    {
        return $this->model->addUser();
    }
    public function putUsers()
    {
        return $this->model->editUser();
    }
    public function deleteUsers()
    {
        return $this->model->deleteUser();
    }
}
