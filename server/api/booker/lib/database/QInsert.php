<?php
namespace database;
/**
 * Description of QInsert
 *
 * @author yoink
 */
class QInsert extends \database\Query
{
	public static function instance()
	{
		return new self();
	}

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
