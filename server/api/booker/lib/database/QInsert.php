<?php
namespace database;
/**
 * Description of QInsert
 * class for INSERT query
 * @author yoink
 */
class QInsert extends \database\Query
{
	/**
	 *
	 * @return \self
	 */
	public static function instance()
	{
		return new self();
	}

	/**
	 *
	 * @return string query
	 */
	public function getStringQuery()
	{
		$query = '';
		$query .= 'INSERT INTO ' . "{$this->table} SET ";
		foreach ($this->params as $k => $v)
		{
			$query .= "`{$k}` = '{$v}', ";
		}
		return substr($query,0,-2);
	}
}
