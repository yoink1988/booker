<?php
include_once dirname(__FILE__) . '/config.php';
include_once BOOKER_LIBS . '/config.php';
include_once BOOKER_LIBS . '/functions.php';

function autoloadTests($className)
{
	$className = TESTS_ROOT . '/' . str_replace('\\', '/', $className) . '.php';

	if (file_exists($className))
	{
		include_once $className;
	}
}

spl_autoload_register('autoload');
spl_autoload_register('autoloadTests');