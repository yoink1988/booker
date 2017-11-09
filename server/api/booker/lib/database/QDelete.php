<?php
namespace database;

/**
 * Description of QDelete
 * class for DELETE query
 * @author yoink
 */
class QDelete extends \database\Query
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
		$query .= 'delete from ' . "{$this->table} ";

		if ($this->where)
		{
			$query .= " where {$this->where}";
		}
		else
		{
			//sorry no where - no delete
			return '';
		}

		if ($this->limit)
		{
			$query .= " limit {$this->limit}";
		}

		return $query;
	}
}

