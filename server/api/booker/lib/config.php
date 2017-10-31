<?php

define ('ROOT_DIR', __DIR__);

define('MODE_LIVE', 1);
define('MODE_DEV', 2);
define('MODE_TEST', 3);
date_default_timezone_set('Europe/Kiev');
if (PHP_SAPI === 'cli')
{
	define('RUN_MODE', MODE_TEST);
	define ('DB_HOST','mysql:host=localhost;dbname=unittest_bookshop;charset=utf8');
	define ('DB_USER','root');
	define ('DB_PWD','');
}
else
{
	define('RUN_MODE', MODE_DEV);

#	define ('DB_HOST','mysql:host=localhost;dbname=booker;charset=utf8');
#	define ('DB_USER','root');
#	define ('DB_PWD','');

define ('DB_HOST','mysql:host=localhost;dbname=user9;charset=utf8');
define ('DB_USER','user9');
define ('DB_PWD','tuser9');
}
