<?php
namespace database;
/**
 * Description of Database
 * DB class
 * @author yoink
 */
class Database
{
	/** @var self */
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

	/**
	 * Executs SELECT queries
	 *
	 * @param \database\QSelect $query
	 * @return array
	 */
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

	/**
	 * Executs INSERT queries
	 *
	 * @param \database\QInsert $query
	 * @return boolean
	 */
	public function insert(\database\QInsert $query)
	{
		$res = $this->pdo->exec($query->getStringQuery());

		return $res !== false;
	}

	/**
	 *  Executs DELETE queries
	 *
	 * @param \database\QDelete $query
	 * @return boolean
	 */
	public function delete(\database\QDelete $query)
	{
		$res = $this->pdo->exec($query->getStringQuery());

		return $res !==false;
	}

	/**
	 * Executs UPDATE queries
	 *
	 * @param \database\QUpdate $query
	 * @return type
	 */
	public function update(\database\QUpdate $query)
	{
		$res = $this->pdo->exec($query->getStringQuery());

		return $res !==false;
	}

	/**
	 *
	 * @return int
	 */
	public function getAffectedRows()
	{
		return $this->lastExecResult;
	}

	/**
	 *
	 * @return int
	 */
	public function getLastInsertID()
	{
		return $this->pdo->lastInsertId();
	}

	/**
	 * gets one row from selec array
	 *
	 * @param \database\QSelect $query
	 * @return array
	 */
	public function selectOne(\database\QSelect $query)
	{
		$res = $this->select($query);
		return $res[0];
	}

	/**
	 *
	 * @param \database\QSelect $query
	 * @return array
	 */
	public function selectField(\database\QSelect $query)
	{
		return array_shift($this->selectOne($query));
	}

	/**
	 * count of rows in select query
	 *
	 * @param \database\QSelect $query
	 * @return int
	 */
	public function selectCount(\database\QSelect $query)
	{
		$query = clone $query;
		$query->setColumns('count(*)');
		return (int)$this->selectField($query);
	}

	/**
	 *
	 * @return string
	 */
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
	 * @param string $str
	 * @return string, quoted string
	 */
    public function clearString($str)
    {
        return $this->pdo->quote($str);
    }
}
