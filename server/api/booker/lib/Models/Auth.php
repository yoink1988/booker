<?php
namespace Models;
/**
 * Description of Auth
 *
 * @author yoink
 */
class Auth
{

    private $db;

    public function __construct()
    {
        $this->db = \database\Database::getInstance();
    }

    public function checkLogData($params)
    {
        if(!\Utils\Validator::validEmail($params['login']))
        {
            return false;
        }

        if(!\Utils\Validator::validPassword($params['pass']))
        {
            return false;
        }

        $q = \database\QSelect::instance()->setColumns('pass')
            ->setTable('employees')
            ->setWhere("email = {$this->db->clearString($params['login'])}");

        if($res = $this->db->select($q))
        {
            if($res[0]['pass'] == md5($params['pass']))
            {
                return true;
            }
        }
        return false;
    }

    public function login($params)
    {
        $params['hash'] = $this->generateHash();

        $q = \database\QUpdate::instance()->setTable('employees')->setParams(array('hash' => "{$params['hash']}"))
            ->setWhere("email = {$this->db->clearString($params['login'])}");


        if($res = $this->db->update($q))
        {
            $q = \database\QSelect::instance()->setColumns('id, name, email, id_role, hash')->setTable('employees')
                ->setWhere("email = {$this->db->clearString($params['login'])} "
                . "and pass = {$this->db->clearString(md5($params['pass']))}");

            return $this->db->select($q);
        }
        return false;
    }

    public function checkAuth($params)
    {
        $q = \database\QSelect::instance()->setColumns('id_role')->setTable('employees')
            ->setWhere("id = {$this->db->clearString($params['id'])}"
            . " and hash = {$this->db->clearString($params['hash'])}");

        if($res = $this->db->select($q))
        {
            return true;
        }
        return false;
    }

    private function generateHash($length=20)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length)
        {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }

    public static function isAdmin()
    {

        $db = \database\Database::getInstance();

        $id = $db->clearString($_SERVER['PHP_AUTH_USER']);
        $hash = $db->clearString($_SERVER['PHP_AUTH_PW']);

        $q = \database\QSelect::instance()->setTable('employees')->setColumns('name')
            ->setWhere("id = {$id} and hash = {$hash} and id_role = '2'");
        if($res = $db->select($q))
        {
            return true;
        }
        return false;
    }

}
