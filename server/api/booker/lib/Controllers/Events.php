<?php
namespace Controllers;

/**
 * Events Controller
 */
class Events
{
	/** @var \Models\Events */
	public $model;

	/**
	 * gets instance of \Models\Events
	 */
	public function __construct()
	{
		$this->model = new \Models\Events();
	}

	/**
	 * main GET request logic
	 *
	 * @param array $params
	 * @return type
	 */
	public function getEvents(array $params)
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
			$uId = false;
			$id = $params['id'];

			if(isset($params['user']))
			{
				$uId = $params['user'];
			}
			if(\Models\Auth::isAdmin())
			{
				$uId = null;
			}

			if(isset($params['count']))
			{
				$count = true;
			}
			return $this->model->getEvent($id, $count, $uId);
		}
		
	}


	/**
	 * main POST request logic
	 *
	 * @param array $params
	 * @return type
	 */
	public function postEvents(array $params)
	{
		return $this->model->addEvent($params);
	}

	/**
	 * main PUT request logic
	 *
	 * @param array $params
	 * @return type
	 */
	public function putEvents(array $params)
	{
		$uId = $params['details']['id_user'];
		if(\Models\Auth::isAdmin())
		{
			$uId = null;
		}
		return $this->model->updateEvents($params, $uId);
	}

	/**
	 * main DELETE request logic
	 *
	 * @param array $params
	 * @return type
	 */
	public function deleteEvents(array $params)
	{
		$uId = $params['user'];
		
		if(\Models\Auth::isAdmin())
		{
			$uId = null;
		}
		return $this->model->deleteEvents($params, $uId);
	}
}

