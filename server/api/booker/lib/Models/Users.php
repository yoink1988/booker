<?php
namespace Models;

class Users
{
    private $db;

    public function __construct()
    {
        $this->db = \database\Database::getInstance();
    }

    public function getUsers($params = null)
    {
        $query = \database\QSelect::instance()->setColumns('id, name, email, id_role, hash')
                                            ->setTable('employees');

        if($params['id'])
        {
            $query->setWhere("id = {$params['id']}");
        }
        return $this->db->select($query);
    }

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

	private function isEmailUnique($email, $id = null)
	{
		$q = \database\QSelect::instance()->setTable('employees')->setColumns('id')->setWhere("email = {$this->db->clearString($email)}");

		if($id)
		{
			$q->setWhere($q->getWhere()." and id != {$id}");
		}

		if(!$res = $this->db->select($q))
		{
			return true;
		}
	}

    public function addUser($params)
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



    public function editUser($params)
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

	private function deleteUsersEvents($uId)
	{
		$qd = \database\QDelete::instance()->setTable('event_details')
											->setWhere("id_employee = {$uId} and start > now()");

		if($this->db->delete($qd))
		{
			if($events = $this->getUserEvents($uId))
			{
				return true;
			}
			return $this->clearEvents($events);
		}
	}

	private function getUserEvents($uId)
	{
		$q = \database\QSelect::instance()->setColumns('id')->setTable('event_details')
										->setWhere("id_employee = {$uId}");

		return $this->db->select($q);
	}

	private function clearEvents(array $events)
	{
		$err = 0;
		$q = \database\QDelete::instance()->setTable('events');

		foreach ($events as $ev)
		{
			$q->setWhere("id = {$ev['id']}");

			if(!$this->db->delete($q))
			{
				$err++;
			}
		}
		if($err == 0)
		{
			return true;
		}
		return false;
	}

}

