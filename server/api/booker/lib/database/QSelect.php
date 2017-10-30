<?php
namespace database;

/**
 * Description of QSelect
 *
 * @author yoink
 */
class QSelect extends Query
{
	public static function instance()
	{
		return new self();
	}

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