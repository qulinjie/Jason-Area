<?php
/** 
 * 网页web方式index
 */

require_once dirname(__FILE__)."/bootstrap.php";

/**
 * 使用redis存储session
 */
session_redis::init();

/**
 * 防重复提交
*/
//$GLOBALS['processlock_obj'] = new processlock($_SERVER['REQUEST_URI']);
//$GLOBALS['processlock_obj']->lock();

/**
 * 启动网站进程
*/
doit::run();
