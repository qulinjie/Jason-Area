<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo Router::getBaseUrl();?>asset/ico/favicon.ico">
	<title>DDMG大大买钢支付系统</title>
	<link href="<?php echo Router::getBaseUrl();?>css/messenger.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/messenger-theme-flat.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/messenger-theme-future.css" type="text/css" rel="stylesheet" />
	<link href="http://libs.useso.com/js/bootstrap/3.1.1/css/bootstrap.min.css"	rel="stylesheet">
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-theme.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-switch-3.1.0.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/prettify.css" type="text/css" rel="stylesheet" />
	<!-- Custom styles for this template -->
	<link href="<?php echo Router::getBaseUrl();?>css/custom.css" rel="stylesheet">
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo Router::getBaseUrl();?>css/index.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jquery-1.11.3.min.js"></script>
</head>
<body>
<input type="hidden" id="token"   value="<?php echo UserController::getToken();?>">
<input type="hidden" id="isLogin" value="<?php echo UserController::isLogin();?>"/>

<div class="modal fade" id="confirm-admin-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">提示</h5>
			</div>
			<div class="modal-body">
			     <h4 id="confirm-admin-body" class="text-center">确认操作！</h4>
			</div>
			<div class="alert alert-danger" id="confirm-admin-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="confirm-admin-btn">确定</button>
			</div>
		</div>
	</div>
</div>

<div class="entirety">

    <div class="t1" style="background:#f4f4f4">
        <div class="top1">
            <div class="left"><span>您好！</span><span><?php echo  UserController::getLoginUser()['account']; ?></span><span><a href="javascript:void(0);" id="logoutBtn">退出</a></span></div>
            <div class="right"><span><a href="">大大买钢首页</a></span><span><a href="">大大买钢APP</a></span></div>
        </div>
    </div>
    <div class="t2">
        <div class="top2">
                <div class="logo"><a href="index.html"><img src="<?php echo Router::getBaseUrl();?>/view/images/logo.png" /></a></div>
         </div>
      </div>
    <div class="t3">
            <div class="nav">
                <ul>
                    <li <?php if(doit::$controller == 'TradeRecord'){?> class="color" <?php } ?>>
                        <a href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">我的大大付款</a>
                    </li>
                    <li <?php if(doit::$controller != 'TradeRecord'){?> class="color" <?php } ?>>
                        <a href="<?php echo Router::getBaseUrl();?>authorizationCode/getIndex">账户管理</a>
                    </li>
                </ul>
            </div>
     </div>
     <div class="clear"></div>
      
<?php if($page_type == 'tradeRecord'){?>
     <div class="t4">
     		<div class="left">
            	<div class="tx"><img src="<?php echo Router::getBaseUrl();?>/view/images/headImg.jpg" /></div>
                <div class="tm">
                	<p>账户：<?php echo  UserController::getLoginUser()['account']; ?></p>
                </div>
            </div>
            <div class="right">
            	<div class="money">
                    <div class="balance">可用余额</div>
                    <div class="how"><em><?php echo $bcsCustomerInfo['ACCT_BAL'];?></em><span>元</span>
                        <span>
                            <a href="">充值</a>
                            <a href="">提现</a>
                        </span>
                        <a href="">查看</a>
                    </div>
                </div>
            </div>
     </div>
     <div class="clear"></div>
     <div class="tab">
        <ul class="tab_conbox" id="tab_conbox">
            <script src="<?php echo Router::getBaseUrl();?>js/tradeRecord.js"></script>
            <?php echo $tradeRecord_html; ?>
        </ul>
    </div>
    
<?php } else {?>

<div class="account" style="width: 1300px;">
    	<div class="left" style="height: 415px;">
        	<ul>
        	    <li <?php if(doit::$controller == 'BcsTrade' && $page_type == 'bcsTradeStatusQuery'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/tradeStatusQueryIndex">交易状态查询</a>
        	    </li>
            	<li <?php if(doit::$controller == 'BcsTrade'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex">交易流水</a>
        	    </li>
        		<li <?php if(doit::$controller == 'AuthorizationCode'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>authorizationCode/getIndex">授权码管理</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'BcsCustomer'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsCustomer/getInfo">账户信息</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'BcsMarket'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsMarket/getInfo">市场信息</a>
        	    </li>
				<li <?php if(doit::$controller == 'User'){?> class="discolor" <?php } ?>>
					<a href="<?php echo Router::getBaseUrl();?>user/passwordReset">重置登录密码</a>
				</li>
        		<li <?php if(doit::$controller == 'PayPassword'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();if(PayPasswordController::check()){?>payPassword/reset<?php }else{?>payPassword/notice<?php }?>">重置支付密码</a>
        		</li>
        		<li <?php if(doit::$controller == 'BcsRegister'){?> class="discolor" <?php } ?>>
        			  <a href="<?php echo Router::getBaseUrl();?>bcsRegister/create">绑定银行卡</a>
        		</li>
        		<li <?php if(doit::$controller == 'Message'){?> class="discolor" <?php } ?>>
        			  <a href="<?php echo Router::getBaseUrl();?>message/getIndex">消息提醒</a>
        		</li>
            </ul>
        </div>
        <div class="right">
              <?php if($page_type == 'authorizationCode'){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/authorizationCode.js"></script>
        			<?php echo $authorizationCode_html; ?>
        	  <?php } else if($page_type == 'bcsTrade'){?>
                    <script src="<?php echo Router::getBaseUrl();?>js/bcsTrade.js"></script>
                    <?php echo $bcsTrade_html; ?>
        	  <?php } else if($page_type == 'bcsCustomer'){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/bcsCustomerInfo.js"></script>
        		    <?php echo $bcsCustomer_html; ?>
              <?php } else if($page_type == 'bcsMarket'){?>
                    <script src="<?php echo Router::getBaseUrl();?>js/bcsMarketInfo.js"></script>
                    <?php echo $bcsMarket_html; ?>
        	  <?php } else if($page_type == 'user'){?>
        			<?php echo $password_html; ?>
        	  <?php }else if($page_type == 'message'){?>
        		    <script src="<?php echo Router::getBaseUrl();?>js/sysMessage.js"></script>
        		    <div id="message-list"><?php echo $message_html;?></div>
        	  <?php	}else if($page_type == 'bcsRegister'){?>
        			<script src="<?php echo Router::getBaseUrl();?>js/bcsRegister.js"></script>
        			<?php echo $bcsRegister_html;?>
        	  <?php }else if($page_type='payPassword'){?>
				  <?php echo $password_html; ?>
        	  <?php	}else if($page_type == 'bcsTradeStatusQuery'){?>
        			<script src="<?php echo Router::getBaseUrl();?>js/bcsTradeStatusQuery.js"></script>
        			<?php echo $bcsTradeStatusQuery_html;?>
        	  <?php }?>
        </div>
</div>
<div class="clear"></div>

<?php }?>

    <div class="bottom">

    	<div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div> <!-- end class="entirety" -->


<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="<?php echo Router::getBaseUrl();?>js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="http://libs.useso.com/js/jquery/2.1.1/jquery.min.js"></script>-->
<script type="text/javascript" src="http://libs.useso.com/js/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!-- <script src="http://libs.useso.com/js/bootstrap-switch/3.0.1/js/bootstrap-switch.min.js"></script> -->
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-switch-3.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/prettify.js"></script>
<script type="text/javascript">var BASE_PATH="<?php echo Router::getBaseUrl();?>";$(document).scrollTop(1);</script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/custom.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/messenger.min.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/messenger-theme-future.js"></script>
</body>
</html>

