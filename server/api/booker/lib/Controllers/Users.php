<?php
namespace Controllers;
/**
 * Users Controller
 */
class Users
{
	/** @var \Models\Users */
    private $model;

	/**
	 * gets instance of \Models\Users
	 */
    public function __construct()
    {
        $this->model = new \Models\Users();
    }


	/**
	 * main GET request logic
	 *
	 * @param type $params
	 * @return type
	 * @throws \Exception
	 */
    public function getUsers($params = null)
    {
		if(!\Models\Auth::isAdmin() && ($params == null))
		{
			throw new \Exception(403);
		}
        return $this->model->getUsers($params);
    }

	/**
	 * main POST request logic
	 *
	 * @param array $params
	 * @return type
	 * @throws \Exception
	 */
    public function postUsers(array $params)
    {
        if(\Models\Auth::isAdmin())
        {
            return $this->model->addUser($params);
        }
		throw new \Exception(403);
    }

	/**
	 * main PUT request logic
	 *
	 * @param array $params
	 * @return type
	 * @throws \Exception
	 */
    public function putUsers(array $params)
    {
        if(!\Models\Auth::isAdmin())
        {
			throw new \Exception(403);
		}
        return $this->model->editUser($params);
    }

	/**
	 * main DELETE request logic
	 *
	 * @param type $params
	 * @return type
	 */
    public function deleteUsers($params)
    {
		if($_SERVER['PHP_AUTH_USER'] == $params['id'])
		{
			return ERR_SELF_DELETE;
		}
        return $this->model->deleteUser($params['id']);
    }
}
