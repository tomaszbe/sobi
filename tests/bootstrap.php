<?php
require __DIR__.'/../vendor/autoload.php';

function d($var)
{
	fwrite(STDERR, print_r($var, TRUE));
}