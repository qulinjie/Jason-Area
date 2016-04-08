<?php
/**
 * api方式index
 */

define("IN_API", true);

require_once dirname(dirname(__FILE__))."/bootstrap.php";

//api接口调用合法性检测
ApiController::securityValidate();

/**
 * 使用redis存储session
 */
session_redis::init(false);

//api接口调用进行user登录
ApiController::loginByLoginkey();

/**
 * 启动网站进程
*/
doit::run();

