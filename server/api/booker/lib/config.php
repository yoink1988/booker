<?php

define ('ROOT_DIR', __DIR__);

define('MODE_LIVE', 1);
define('MODE_DEV', 2);
define('MODE_TEST', 3);
date_default_timezone_set('Europe/Kiev');
if (PHP_SAPI === 'cli')
{
	define('RUN_MODE', MODE_TEST);
	define ('DB_HOST','mysql:host=localhost;dbname=booker;charset=utf8');
	define ('DB_USER','root');
	define ('DB_PWD','');
}
else
{
	define('RUN_MODE', MODE_DEV);

//	define ('DB_HOST','mysql:host=localhost;dbname=booker;charset=utf8');
//	define ('DB_USER','root');
//	define ('DB_PWD','');

define ('DB_HOST','mysql:host=localhost;dbname=user9;charset=utf8');
define ('DB_USER','user9');
define ('DB_PWD','tuser9');
}

define('ROLE_USER', '1');
define('ROLE_ADMIN', '2');



define('START_HOUR',8);
define('END_HOUR',20);
define('START_MIN',0);
define('END_MIN',0);

define('SQL_START_TIME','08:00:00');
define('SQL_END_TIME','20:00:00');

define('SQL_FORMAT', 'Y-m-d H:i:s');


define('ERR_NAME', 'Incorrect Name');
define('ERR_EMAIL', 'Incorrect Email');
define('ERR_PASS', 'Incorrect Password');
define('ERR_EMAIL_EXIST', 'Email already exist');

define('SUCCESS', 'SUCCESS');
define('ERR_ALREADY_BOOKED', 'Room already booked at this time ');
define('ERR_WEEKEND_DAY', 'No Events on weekend days ');
define('ERR_AVALIABLE_TIME', 'Avalibale time 8:00 - 20:00');
define('ERR_PAST_TIME', 'Cant book room on past time');
define('ERR_DESCRIPTION', 'Check description field');
define('ERR_SELF_DELETE', 'You cant delete yourself');
define('ERR_DURATION', 'Check duration field');
