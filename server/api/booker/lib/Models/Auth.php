<?php
namespace Models;
/**
 * Description of Auth
 * Auth Model
 * @author yoink
 */
class Auth
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
	 * checks user\password in db
	 *
	 * @param array $params
	 * @return boolean
	 */
    public function checkLogData(array $params)
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

	/**
	 * login operation
	 *
	 * @param array $params
	 * @return boolean false | array of users data
	 */
    public function login(array $params)
    {
        $params['hash'] = $this->generateHash();

        $q = \database\QUpdate::instance()
								->setTable('employees')
								->setParams(array('hash' => "{$params['hash']}"))
								->setWhere("email = {$this->db->clearString($params['login'])}");


        if($res = $this->db->update($q))
        {
            $q = \database\QSelect::instance()
					->setColumns('id, name, email, id_role, hash')
					->setTable('employees')
					->setWhere("email = {$this->db->clearString($params['login'])} "
							 . "and pass = {$this->db->clearString(md5($params['pass']))}");

            return $this->db->select($q);
        }
        return false;
    }

	/**
	 * autentification check
	 *
	 * @param array $params
	 * @return boolean
	 */
    public function checkAuth(array $params)
    {
        $q = \database\QSelect::instance()
								->setColumns('id')
								->setTable('employees')
								->setWhere("id = {$this->db->clearString($params['id'])}"
								. " and hash = {$this->db->clearString($params['hash'])}");

        if($res = $this->db->select($q))
        {
            return true;
        }
        return false;
    }

	/**
	 * generates hash of $length symbols
	 *
	 * @param int $length
	 * @return string
	 */
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

	/**
	 * admin autentification check
	 *
	 * @return boolean
	 */
    public static function isAdmin()
    {
        $db = \database\Database::getInstance();

		if((isset($_SERVER['PHP_AUTH_USER'])) && (isset($_SERVER['PHP_AUTH_PW'])))
		{
			$id = $db->clearString($_SERVER['PHP_AUTH_USER']);
	        $hash = $db->clearString($_SERVER['PHP_AUTH_PW']);

			$q = \database\QSelect::instance()
									->setTable('employees')
									->setColumns('name')
									->setWhere("id = {$id} and hash = {$hash} "
											. "and id_role = ".ROLE_ADMIN);

			if($res = $db->select($q))
			{
				return true;
			}
		}
        return false;
    }

}
