<?php

function autoload($className)
{
	$className = ROOT_DIR . '/' . str_replace('\\', '/', $className) . '.php';

	if (file_exists($className))
	{
		include_once $className;
	}
}

function dump($var)
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}
