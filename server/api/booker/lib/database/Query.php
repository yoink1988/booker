<?php //
namespace database;

/**
 * Description of Query
 *
 * @author yoink
 */
abstract class Query
{
	/** @var string table */
	protected $table = '';
	/** @var string where */
	protected $where = '';
	/** @var string columns */
	protected $columns = '';
	/** @var string join */
	protected $join = '';
	/** @var int limit */
	protected $limit = null;
	/** @var string order by */
	protected $order = '';
	/** @var boolean order ascend/descend */
	protected $sortAsc = null;
	/** @var array params*/
	protected $params = array();
	

	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	public function setWhere($where)
	{
		$this->where = $where;
		return $this;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
		return $this;
	}

	public function setJoin($join)
	{
		$this->join = $join;
		return $this;
	}

	public function setLimit($limit)
	{
		$this->limit = $limit; 
		return $this;
	}

	public function setOrder($order)
	{
		$this->order = $order; 
		return $this;
	}

	public function setAsc()
	{
		$this->sortAsc = true; //asc
		return $this;
	}

	public function setDesc()
	{
		$this->sortAsc = false; //desc
		return $this;
	}

	public function setParams(array $params)
	{
		$this->params = $params;
		return $this;
	}

	abstract public function getStringQuery();
}
