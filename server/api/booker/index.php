<?php
include_once 'lib/config.php';
include_once 'lib/functions.php';

spl_autoload_register('autoload');

//dump($_SERVER);
//exit;
try{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: PUT, POST, GET, DELETE');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');
    $router = new RestServer;
	$router->run();
}

//catch (\PDOException $ex)
//{
//	echo $ex->getMessage();
//}

catch (\Exception $e)
{
	if(RUN_MODE == MODE_LIVE)
	{
		\Utils\Response::ErrorResponse($e->getMessage());
//		echo $e->getMessage();
	}
	else
	{
//		\Utils\Response::SuccessResponse(200);
//		\Utils\Response::doResponse($e->getMessage());
		echo $e->getMessage().'<br>';
		echo $e->getTraceAsString();
	}

}

//$db= \database\Database::getInstance();
//
//
//$currDate = date('r');
//$firstDay = date("Y-m-01 00:00:00");
//$lastDay = date("Y-m-t 23:59:59");
//
//$n = new DateTime();
//
//$n->modify('+1 month');
//echo $n->format('Y-m-d H-i-s').'<br>';;
//
//echo $firstDay .'<br>';
//echo $lastDay;
//
//$q = \database\QSelect::instance()->setTable('event_details')
//								->setColumns('id, start, end')
//								->setWhere("start between '{$firstDay}' and '{$lastDay}'");
//
//dump($q->getStringQuery());
//
//$res = $db->select($q);
//
//dump($res);
?>
