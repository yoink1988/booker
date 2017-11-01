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
        $query = \database\QSelect::instance()->setColumns('id, name, email')
                                            ->setTable('employees');

        if($params['id'])
        {
            $query->setWhere("id = {$params['id']}");
        }
        

        return $this->db->select($query);
    }
    
    public function addUser($params)
    {
        //toDo dobavit VALIDATSIU
        $params['id_role'] = '1';
        $params['pass'] = md5($params['pass']);

        $query = \database\QInsert::instance()->setTable('employees')
                                            ->setParams($params);

        return $this->db->insert($query);
    }  

    public function editUser($params)
    {
        $query = \database\QUpdate::instance()->setTable('employeers')
                                            ->setParams($params)
                                            ->setWhere("id = {$params['id']}");
            
        return $this->db->update($params);
    }

    public function deleteUser($params)
    {
        $query = \database\QDelete::instance()->setTable('employeers')
                                        ->setWhere("id = {$params['id']}");

        return $this->db->delete($query);
    } 

}

