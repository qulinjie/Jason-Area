<?php
//echo 'my webservices php !';

ini_set("soap.wsdl_cache_enabled", "0");
include("Callback.php");
$Server=new SoapServer('pay_callback.wsdl');
$Server->setClass("Callback");
$Server->handle();




