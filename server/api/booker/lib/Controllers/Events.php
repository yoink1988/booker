<?php
namespace Controllers;

class Events
{
	public $model;

	public function __construct()
	{
		$this->model = new \Models\Events();
	}

	public function getEvents($params = null)
	{

		if(!isset($params['id']))
		{
			$start = new \DateTime();
			$start->setDate($params['year'],$params['month'], 1);
			$arr['start'] = $start->format('Y-m-d '.SQL_START_TIME);
			$arr['end'] = $start->format('Y-m-t '.SQL_END_TIME);
			$arr['id_room'] = $params['room'];
			return $this->model->getEvents($arr);
		}

		if(isset($params['id']))
		{
			$count = false;
			$id = $params['id'];
			if(isset($params['count']))
			{
				$count = true;
			}
			return $this->model->getEvent($id, $count);
		}
		
	}


	public function postEvents(array $params)
	{
		return $this->model->addEvent($params);
	}

	public function putEvents(array $params)
	{
		return $this->model->updateEvents($params);
	}

	public function deleteEvents(array $params)
	{
		return $this->model->deleteEvents($params);
	}
}

