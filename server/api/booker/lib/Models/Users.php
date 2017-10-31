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
        $query = \database\QSelect::instance()->setColumns('id, login, email, pass')
                                            ->setTable('employees');

        if($params['id'])
        {
            $query->setWhere("id = {$params['id']}");
        }
        

        return $this->db->select($query);
    }
    
    public function addUser($params)
    {
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

