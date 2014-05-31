<?php
if(!defined('ROOT_DIR'))
{
    define('ROOT_DIR', realpath(__DIR__.'/../'));
}

require_once ROOT_DIR . '/Lib/DB/Table.php';

function view($page)
{
    return  ROOT_DIR . '/Views/'.$page.'.tpl.php';
}