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
        //toDo dobavit autentifikaciu
        return $this->model->getUsers($params);
    }

    public function postUsers(array $params)
    {
       // dump($params);exit;
        if(\Models\Auth::isAdmin())
        {
            return $this->model->addUser($params);
        }
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
