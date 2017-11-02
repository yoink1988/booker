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
    
    public function addUser($params)
    {
		if(!\Utils\Validator::validName($params['name']))
		{
			return 'Invalid Name';
		}
		if(!\Utils\Validator::validEmail($params['email']))
		{
			return 'Invalid Email';
		}
		if(!\Utils\Validator::validPassword($params['pass']))
		{
			return 'Invalid Password';
		}
        $params['id_role'] = ROLE_USER;
        $params['pass'] = md5($params['pass']);

        $query = \database\QInsert::instance()->setTable('employees')
                                            ->setParams($params);

        return $this->db->insert($query);
    }  

    public function editUser($params)
    {
		if(!\Utils\Validator::validName($params['name']))
		{
			return 'Invalid Name';
		}
		if(!\Utils\Validator::validEmail($params['email']))
		{
			return 'Invalid Email';
		}

		$id = $this->db->clearString($params['id']);
		unset($params['id']);

        $query = \database\QUpdate::instance()->setTable('employees')
                                            ->setParams($params)
                                            ->setWhere("id = {$id}");
            
        return $this->db->update($query);
    }

    public function deleteUser($params)
    {
        $query = \database\QDelete::instance()->setTable('employees')
                                        ->setWhere("id = {$params['id']}");

        return $this->db->delete($query);
    } 

}

