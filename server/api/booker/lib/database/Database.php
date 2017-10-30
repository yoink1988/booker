<?php
namespace database;
/**
 * Description of Database
 *
 * @author yoink
 */
class Database
{
	private static $instance = null;

	private $lastExecResult = 0;

	/** @var \PDO */
	private $pdo = null;

	private function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 *
	 * @return \database\Database
	 */
	public static function getInstance()
	{
		if (self::$instance instanceof self)
		{
			return self::$instance;
		}

		$pdo = new \PDO(DB_HOST,DB_USER,DB_PWD, array(
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC));
		
		return self::$instance = new self($pdo);
	}

	public function select(\database\QSelect $query)
	{
		$stmnt = $this->pdo->query($query->getStringQuery());
		$result = array();
		if ($stmnt instanceof \PDOStatement)
		{
			foreach ($stmnt as $row)
			{
				$result[] = $row;
			}
		}

		return $result;
	}

	public function insert(\database\QInsert $query)
	{
		$res = $this->pdo->exec($query->getStringQuery());

		return $res !== false;
	}

	public function delete(\database\QDelete $query)
	{
		$res = $this->pdo->exec($query->getStringQuery());

		return $res !==false;
	}

	public function update(\database\QUpdate $query)
	{
		$this->pdo->exec($query);

		return $res !==false;
	}

	public function getAffectedRows()
	{
		return $this->lastExecResult;
	}

	public function getLastInsertID()
	{
		return $this->pdo->lastInsertId();
	}

	public function selectOne(\database\QSelect $query)
	{
		$res = $this->select($query);
		return $res[0];
	}

	public function selectField(\database\QSelect $query)
	{
		return array_shift($this->selectOne($query));
	}
	
	public function selectCount(\database\QSelect $query)
	{
		$query = clone $query;
		$query->setColumns('count(*)');
		return (int)$this->selectField($query);
	}

	public function getError()
	{
		$errInfo = $this->pdo->errorInfo();

		if ($errInfo[2])
		{
			return "[{$errInfo[0]}]: {$errInfo[2]}";
		}
		return '';
	}

	/**
	 *
	 * @param  $str
	 */
    public function clearString($str)
    {
        return $this->pdo->quote($str);
    }

	public function clearParams(array $params)
	{
		$cleared = [];
		foreach ($params as $k => $v)
		{
			$cleared[$k] = $this->pdo->quote($v);
		}
		return $cleared;
	}
}
