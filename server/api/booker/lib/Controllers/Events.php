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
		if($params)
		{
			$start = new \DateTime();
			$start->setDate($params['year'],$params['month'], 1);
			$arr['start'] = $start->format('Y-m-d 08-00-00');
			$arr['end'] = $start->format('Y-m-t 20-00-00');
			$arr['id_room'] = $params['room'];
//			dump($arr);
//			exit;
		}
		return $this->model->getEvents($arr);
	}
}

