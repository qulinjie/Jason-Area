<?php
require_once dirname(dirname(__FILE__)).'/init.php';

//8:30-18:00更新
$str = " only update at 8:30-18:00 \r\n";
$begin = strtotime(date("Y-m-d") . " 8:30:00");
$end = strtotime(date("Y-m-d") . " 18:00:00");
$now = time();
if($now < $begin || $now > $end){
	exit($str);
}

echo date("Y-m-d H:i:s")."--- begin --- \r\n";

//更新流水定时任务
doit::singleton('BcsTradeController')->spd_loadAccountTradeList_exec();

echo date("Y-m-d H:i:s")."--- end --- \r\n\r\n";