<?php
namespace Controllers;
/**
 * Description of Auth
 * Auth Controller
 * @author yoink
 */
class Auth
{
	/** @var \Models\Users */
    private $model;

	/**
	 * gets \Models\Auth instance
	 */
    public function __construct()
    {
        $this->model = new \Models\Auth();
	}

	/**
	 * main GET requet logic
	 * 
	 * @return type
	 */
	public function getAuth()
	{
		$params = array();
		$params['id'] = $_SERVER['PHP_AUTH_USER'];
		$params['hash'] = $_SERVER['PHP_AUTH_PW'];
		return $this->model->checkAuth($params);
	}

	/**
	 * main PUT request logic
	 *
	 * @param type $params
	 * @return boolean
	 */
	public function putAuth($params)
	{
		if($this->model->checkLogData($params))
		{
			return $this->model->login($params);
		}
		return false;
	}
}
