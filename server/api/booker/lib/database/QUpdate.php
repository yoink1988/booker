<?php
namespace database;
/**
 * Description of QUpdate
 *
 * @author yoink
 */
class QUpdate extends Query
{
	public static function instance()
	{
		return new self();
	}
/**
 *
	 * @return string $query\ returns an empty string if no "where in query"
 */
	public function getStringQuery()
	{
		$query = '';
		$query .= 'UPDATE ' . "{$this->table} SET ";
		foreach ($this->params as $k => $v)
		{
			$query .= "`{$k}` = '{$v}', ";
		}

		$query = substr($query, 0, -2);
		
		if ($this->where)
		{
			$query .= " where {$this->where}";
		}
		else
		{
			//sorry, no where no update
			return '';
		}

		if ($this->limit)
		{
			$query .= " limit {$this->limit}";
		}

		return $query;
	}
}
