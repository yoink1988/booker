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
		if(!\Models\Auth::isAdmin() && ($params == null))
		{
			throw new \Exception(403);
		}

        return $this->model->getUsers($params);
    }

    public function postUsers(array $params)
    {

        if(\Models\Auth::isAdmin())
        {
            return $this->model->addUser($params);
        }
		throw new \Exception(403);
    }
    
    public function putUsers(array $params)
    {
        if(!\Models\Auth::isAdmin())
        {
			throw new \Exception(403);
		}
        return $this->model->editUser($params);
    }
    
    public function deleteUsers($params)
    {
		if($_SERVER['PHP_AUTH_USER'] == $params['id'])
		{
			return ERR_SELF_DELETE;
		}
        return $this->model->deleteUser($params['id']);
    }
}
