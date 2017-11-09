<?php
namespace database;

/**
 * Description of QSelect
 * class for SELECT query
 * @author yoink
 */
class QSelect extends Query
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
	 * @return string SELECT query
	 */
	public function getStringQuery()
	{
		$query = '';
		$query .= 'SELECT ' . ($this->columns ?: '*') . ' FROM '
				. "{$this->table} ";
		if($this->join)
		{
			$query .= "{$this->join} ";
		}
		if($this->where)
		{
			$query .= "WHERE {$this->where}";
		}
		if($this->order)
		{
			$query .= " ORDER BY {$this->order} ";
		}
		if (is_bool($this->sortAsc))
		{
			$query .= ($this->sortAsc) ? ' asc' : ' desc';
		}
		if ($this->limit)
		{
			$query .= " LIMIT {$this->limit}";
		}
		
		return $query;
	}
}