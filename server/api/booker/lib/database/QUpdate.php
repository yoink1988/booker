<?php
namespace database;
/**
 * Description of QUpdate
 * class for UPDATE query
 * @author yoink
 */
class QUpdate extends Query
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
	 * @return string $query\ returns an empty string if no "WHERE in query"
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
