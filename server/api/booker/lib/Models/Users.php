<?php
namespace Models;

/**
 * Model for users operations
 */
class Users
{
	/** @var \database\Database */
    private $db;

	/**
	 * gets instance of \database\Database
	 */
    public function __construct()
    {
        $this->db = \database\Database::getInstance();
    }

	/**
	 * gets users data from db
	 *
	 * @param array $params
	 * @return array of user\s info, empty array if not found any
	 */
    public function getUsers(array $params = null)
    {
        $query = \database\QSelect::instance()
								->setColumns('id, name, email, id_role, hash')
								->setTable('employees');

        if($params['id'])
        {
            $query->setWhere("id = {$params['id']}");
        }
        return $this->db->select($query);
    }

	/**
	 * checks form data name, email [,pass] if given
	 *
	 * @param array $params
	 * @param boolean $checkpass
	 * @return string if not valid
	 */
	private function checkForm(array $params, $checkpass=false)
	{
		if(!\Utils\Validator::validName($params['name']))
		{
			return ERR_NAME;
		}
		if(!\Utils\Validator::validEmail($params['email']))
		{
			return ERR_EMAIL;
		}
		if($checkpass)
		{
			if(!\Utils\Validator::validPassword($params['pass']))
			{
				return ERR_PASS;
			}
		}
	}

	/**
	 * checks that email is unique in db
	 *
	 * @param string $email
	 * @param string\int $id
	 * @return boolean
	 */
	private function isEmailUnique($email, $id = null)
	{
		$q = \database\QSelect::instance()->setTable('employees')
						->setColumns('id')
						->setWhere("email = {$this->db->clearString($email)}");

		if($id)
		{
			$q->setWhere($q->getWhere()." and id != {$id}");
		}

		if(!$res = $this->db->select($q))
		{
			return true;
		}
	}

	/**
	 * adds db record of user
	 *
	 * @param array $params
	 * @return boolean true if success/string error otherwise
	 */
    public function addUser(array $params)
    {
		if($error = $this->checkForm($params, true))
		{
			return $error;
		}

		if(!$this->isEmailUnique($params['email']))
		{
			return ERR_EMAIL_EXIST;
		}

        $params['id_role'] = ROLE_USER;
        $params['pass'] = md5($params['pass']);

        $query = \database\QInsert::instance()->setTable('employees')
                                              ->setParams($params);

        return $this->db->insert($query);
    }  


	/**
	 * update user in db
	 *
	 * @param array $params
	 * @return boolean true if success/string error otherwise
	 */
    public function editUser(array $params)
    {
		if($error = $this->checkForm($params))
		{
			return $error;
		}
		if(!$this->isEmailUnique($params['email'], $params['id']))
		{
			return ERR_EMAIL_EXIST;
		}

		$id = $this->db->clearString($params['id']);
		unset($params['id']);

        $query = \database\QUpdate::instance()->setTable('employees')
                                              ->setParams($params)
                                              ->setWhere("id = {$id}");
            
        return $this->db->update($query);
    }

	/**
	 * deletes user and his events if they not passed
	 *
	 * @param string\int $id
	 * @return boolean
	 */
    public function deleteUser($id)
    {
        $query = \database\QDelete::instance()->setTable('employees')
                                              ->setWhere("id = {$id}");

      if($this->db->delete($query))
		{
			return $this->deleteUsersEvents($id);
		}
		return false;
    }

	/**
	 * deletes users events and event details if they not passed
	 *
	 * @param string/int $uId
	 * @return boolean
	 */
	private function deleteUsersEvents($uId)
	{
		if(!$events = $this->getUserEvents($uId))
		{
			return true;
		}

		$qd = \database\QDelete::instance()->setTable('event_details')
										->setWhere("id_employee = {$uId} "
												 . "and start > now()");

		if($this->db->delete($qd))
		{
			$ids = [];

			foreach ($events as $e)
			{
				$ids[] = $e['id'];
			}

				foreach($ids as $idEv)
				{
					$query = \database\QSelect::instance()
											->setTable('event_details')
											->setColumns('id')
											->setWhere("(id = {$idEv} "
											. "and id_employee != {$uId} "
											. "and start > now()) "
											. "or ( id = {$idEv} "
											. "and id_employee = {$uId} "
											. "and start < now())");

					if(!$exist = $this->db->select($query))
					{
						$this->clearEvents($idEv);
					}
				}
		}
		return true;
	}

	/**
	 * gets users events from current time
	 *
	 * @param string\int $uId
	 * @return array if events/empty array
	 */
	private function getUserEvents($uId)
	{
		$q = \database\QSelect::instance()->setColumns('distinct id')
										  ->setTable('event_details')
										  ->setWhere("id_employee = {$uId} "
													. "and start > now()");

		return $this->db->select($q);
	}

	/**
	 * deletes event from `event` table by id
	 *
	 * @param string\int $id
	 * @return boolean
	 */
	private function clearEvents($id)
	{
		$q = \database\QDelete::instance()->setTable('events')
										->setWhere("id = {$id}");

		return $this->db->delete($q);
	}

}

