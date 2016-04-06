<?php 	
    $session = Controller::instance('session');
    $encrypt = Controller::instance('encrypt');
    $login_token = $encrypt->tokenCode('login:' . $session->get_id());
    $other_token = $encrypt->tokenCode('other:' . $session->get_id());
?>
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
	<link href="<?php echo Router::getBaseUrl();?>css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jquery-1.11.3.min.js"></script>
</head>
<body>

<input type="hidden" id="token"   value="<?php echo UserController::getToken();?>">
<input type="hidden" id="isLogin" value="<?php echo UserController::isLogin();?>"/>
<input type="hidden" id="isAdminLogin" value="<?php echo AdminController::isLogin();?>"/>
<input type="hidden" id="view_page_type" value="<?php echo $page_type;?>"/>
<input type="hidden" id="view_controller" value="<?php echo doit::$controller;?>"/>
<input type="hidden" id="user_id" value="<?php echo UserController::getCurrentUserId(); ?>" />
<input type="hidden" id="user_type" value="<?php echo $session->get(UserController::$userSessionKey)['user_type']; ?>" />
<?php //echo json_encode($session->get(UserController::$userSessionKey)); exit;?>

<!-- login-modal -->
<div class="modal fade" id="admin-login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                                          <!-- style="background-image:url(<?php echo Router::getBaseUrl();?>view/images/loginbak.jpg);background-size: 100% 100%; "> -->
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top:52%;">
			<div class="modal-header" style="text-align: center;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<!-- 
				<img alt="大大买钢" src="<?php echo Router::getBaseUrl();?>view/images/logo.jpg" style=""/>
				 -->
				<h3 class="modal-title" id="myModalLabel">大大买钢支付管理系统</h3>
			</div>
			<div class="modal-body">
				    <form class="form-horizontal">
					<div class="form-group">
					    <div class="col-sm-1"></div>
					    <div class="col-sm-10">
							<input type="hidden" id="login-csrf" value="<?php echo $login_token;?>">
							<input type="text" id="admin-login-account" class="form-control" placeholder="用户名" autofocus value="110001"><br/>
							<input type="password" id="admin-login-password" class="form-control" placeholder="密码" value="1"><br/>
							<!--
							<div class="pincode" style="display:none">
								<input type="text" id="admin-login-pincode" class="form-control pincode" placeholder="验证码">
									<img class="pincode-img" id="login-pincode-img"
									   src="< ?php echo Router::getBaseUrl()?>admin/pincode/xpp< ?php echo rand(); ? >"
									   alt="点击刷新" class="img-rounded"
									   onclick="this.src='< ?php echo Router::getBaseUrl();? >admin/pincode/xpp' + Math.random();">
								<div class="clearfix"></div>
							</div>
							<label class="checkbox" style="display:none">
							      <input type="checkbox" value="remember-me" id="login-remember" checked="checked"> 下次自动登录<br/>&nbsp;
							</label>
							
							<label class="checkbox">
							      <input type="checkbox" value="1" id="login-manager" checked="checked">客户经理<br/>&nbsp;
							</label>
							-->
							<button class="btn btn-lg btn-primary btn-block" type="button" id="btn-admin-login">登录</button>
							<div class="alert alert-danger" role="alert" id="admin-login-hint"></div>
						</div>
					</div>
					</form>
			   </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

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

<?php if(doit::$controller == 'Admin' || AdminController::isAdmin()){?>
    <?php if(AdminController::isLogin()){?>
    <!-- top横幅 -->
    <nav class="navbar-fixed-top" style="background-color:#4775AA; height:69px;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- <a class="navbar-brand" href="<?php echo Router::getBaseUrl();?>"></a> 
          <img alt="运营系统" src="<?php echo Router::getBaseUrl();?>view/images/banner.png" style="margin-top: 10px;"/>
          <div style='color: white;font-size: 24px;margin-top: 6px;'>大大买钢支付管理系统</div>
          -->
          <div class="logo" style="float:left; padding-left: 15px;"><img src="<?php echo Router::getBaseUrl();?>view/images/index/logo_reg.png"/></div>
          <div style="color: white;font-size: 24px;margin-top: 20px; margin-left:260px; font-weight:700;">大大买钢支付管理系统</div>
        </div>
        <div id="navbar" class="navbar-collapse collapse" style="position:relative;">
          <ul class="nav navbar-nav navbar-right" style="padding-right:200px;">
            <li style="color: white;vertical-align:middle;line-height:69px;">
                <b style="width:22px; height:23px; display:block; background:url(<?php echo Router::getBaseUrl();?>view/images/index/icon-03.png) no-repeat; position:absolute; top:25px; right:99px;"></b>
                <?php echo '&nbsp; 管理员 &nbsp;'; echo UserController::getLoginUser()['account']; ?>
            </li>
            <li style="color: white;vertical-align:middle;line-height:69px;">
                &nbsp;&nbsp; <?php echo $session->get('loginUser')['name']; ?>
                &nbsp;&nbsp;
            </li>
<!--             <li><a id="user-chg-pwd-btn" href="#">修改密码</a></li> -->
            <li style="color: white;vertical-align:middle;line-height:69px;">
                <span><a id="amdin-loginOut-btn" href="#" style="color: white;">退出</a></span>
            </li>
          </ul>
          <input type="hidden" id="type-is-admin-tip" value="<?php if( AdminController::isAdmin()){?>1<?php } else {?>2<?php }?>"/>
          <!-- <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
           -->
        </div>
      </div>
    </nav>
    
      <!-- 左侧菜单 -->
      <div class="container-fluid" style="width: 1905px;">
      <div class="row" >
        <div class="col-sm-3 col-md-2 sidebar" style="background-color: white;width: 320px;">
          <ul id="li-menu-list">
            <li>
				<!-- Start Freebie -->
				<ul class="expmenu">
    		      <!--
    			<li <?php if(doit::$controller == 'BcsMarket'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsMarket/getInfo">市场信息</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'User'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>user/getIndex">用户管理</a>
        	    </li>       
        	    <li <?php if(doit::$controller == 'BcsRegister'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsRegister/getIndex">开户管理</a>
        	    </li>
        	    -->
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/messages.png);background-position:10px 0px;">
        	                   开户管理
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsCustomer2'){?> class="active" <?php } ?> >
            			<a href="#">开户申请</a>
            	    </li>
            	    <li <?php if(doit::$controller == 'BcsCustomer'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsCustomer/getIndex">开户管理</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   收款管理
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsTrade' && strval($inout) == '1'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex_in">收款明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   付款管理
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'TradeRecord' && strval($audit_level) == '2'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>tradeRecord/searchListSecond">付款审批(二级)</a>
            	    </li>
            	    <li <?php if(doit::$controller == 'BcsTrade' && strval($inout) == '0'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex_out">付款明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   账户查询
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsTrade' && strval($inout) == ''){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex">收支明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   开户银行查询
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'SpdInternetBank'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>spdInternetBank/getIndex">开户银行查询</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    <!--
    			<li <?php if(doit::$controller == 'BcsTransfer'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsTransfer/getIndex">出入金</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'BcsInflow'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsInflow/getIndex">银行出入金</a>
        	    </li>
    			<li <?php if(doit::$controller == 'BcsIncomPay'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsIncomPay/getIndex">银行交易明细</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'BcsTrade' && $page_type == 'bcsTradeStatusQuery'){?> class="active" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/tradeStatusQueryIndex">交易状态查询</a>
        	    </li>
        	    -->
        	    
        	    </ul>
				<!-- End Freebie -->
			</li>
          </ul>
        </div>
        
        <!-- 右侧内容页面 -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" >
          <?php if($page_type == 'bcsMarket'){?>
                <script src="<?php echo Router::getBaseUrl();?>js/bcsMarketInfo.js"></script>
                <?php echo $bcsMarket_html; ?>
          <?php }else if($page_type == 'user'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/user.js"></script>
    			<?php echo $user_html;?>
           <?php }else if($page_type == 'bcsRegister'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/bcsRegister.js"></script>
    			<?php echo $bcsRegister_html;?>
          <?php }else if($page_type == 'bcsCustomer'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/bcsCustomer.js"></script>
		        <?php echo $bcsCustomer_html;?>
          <?php }else if($page_type == 'bcsTrade'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/bcsTrade.js"></script>
		        <?php echo $bcsTrade_html;?>
		  <?php }else if($page_type == 'tradeRecord'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/tradeRecord.js"></script>
		        <?php echo $tradeRecord_html;?>
          <?php }else if($page_type == 'bcsTransfer'){?>
    			<script src="<?php echo Router::getBaseUrl();?>js/bcsTransfer.js"></script>
		        <?php echo $bcsTransfer_html;?>
         <?php	}else if($page_type == 'bcsTradeStatusQuery'){?>
			    <script src="<?php echo Router::getBaseUrl();?>js/bcsTradeStatusQuery.js"></script>
			    <?php echo $bcsTradeStatusQuery_html;?>
         <?php	}else if($page_type == 'bcsInflow'){?>
                  <script src="<?php echo Router::getBaseUrl();?>js/bcsInflow.js"></script>
				  <?php echo $bcsInflow_html;?>
		 <?php	}else if($page_type == 'spdInternetBank'){?>
              <script src="<?php echo Router::getBaseUrl();?>js/spdInternetBank.js"></script>
			  <?php echo $spdInternetBank_html;?>
		  <?php } else if($page_type == 'bcsIncomPay'){?>
		          <script src="<?php echo Router::getBaseUrl();?>js/bcsIncomPay.js"></script>
				  <?php echo $bcsIncomPay_html;?>
          <?php }?>
        </div>
      </div>
    </div>
    
    <div id="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 col-md-12" id="realfooter" style="overflow-x: hidden;">
					<p class="text-center" style="width: 118%;">Copyright © 2015 大大买钢 All Rights Reserved</p>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
	<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/admin.js"></script>
<?php } else {?>

<?php if(!UserController::isLogin()){?>
<script type="text/javascript" >window.location.href='<?php echo Router::getBaseUrl();?>';</script>
<?php } ?>

<nav class="navbar-fixed-top" style="background-color:#4775AA; height:69px;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- <a class="navbar-brand" href="<?php echo Router::getBaseUrl();?>"></a> 
          <img alt="运营系统" src="<?php echo Router::getBaseUrl();?>view/images/banner.png" style="margin-top: 10px;"/>
          <div style='color: white;font-size: 24px;margin-top: 6px;'>大大买钢支付管理系统</div>
          -->
          <div class="logo" style="float:left; padding-left: 15px;"><img src="<?php echo Router::getBaseUrl();?>view/images/index/logo_reg.png"/></div>
          <div style="color: white;font-size: 24px;margin-top: 20px; margin-left:260px; font-weight:700;">大大买钢支付管理系统</div>
        </div>
        <div id="navbar" class="navbar-collapse collapse" style="position:relative;">
          <ul class="nav navbar-nav navbar-right" style="padding-right:200px;">
            <li style="color: white;vertical-align:middle;line-height:69px;">
                <b style="width:22px; height:23px; display:block; background:url(<?php echo Router::getBaseUrl();?>view/images/index/icon-03.png) no-repeat; position:absolute; top:25px; right:99px;"></b>
                <span><?php echo '&nbsp; 合伙人 &nbsp;'; echo UserController::getLoginUser()['account']; ?></span>
            </li>
            <li style="color: white;vertical-align:middle;line-height:69px;">
                &nbsp;&nbsp; <?php echo $session->get(UserController::$userSessionKey)['name']; ?>
                &nbsp;&nbsp;
            </li>
<!--             <li><a id="user-chg-pwd-btn" href="#">修改密码</a></li> -->
            <li style="color: white;vertical-align:middle;line-height:69px;">
                <span><a href="javascript:void(0);" id="logoutBtn" style="color: white;">退出</a></span>
            </li>
          </ul>
          <input type="hidden" id="type-is-admin-tip" value="<?php if( AdminController::isAdmin()){?>1<?php } else {?>2<?php }?>"/>
          <!-- <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
           -->
        </div>
      </div>
    </nav>
    
      <!-- 左侧菜单 -->
      <div class="container-fluid" style="width: 1905px;">
      <div class="row" >
        <div class="col-sm-3 col-md-2 sidebar" id="div-menu-list" style="background-color: white;width: 320px;display: block;">
          <ul id="li-menu-list" >
          <li>
				<!-- Start Freebie -->
				<ul class="expmenu">
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/messages.png);background-position:10px 0px;">
        	                   账户信息
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsCustomer' && doit::$params[0] !='getInflow' && doit::$params[0] !='getIncomePay'){?> class="active" <?php } ?>>
            			<a href="<?php echo Router::getBaseUrl();?>bcsCustomer/getInfo">账户信息</a>
            	    </li>
            	    </ul>
            	    <?php if(UserController::isGeneralUser()){?>
	            	    <ul class="menu">
	            	    <li <?php if(doit::$controller == 'PayPassword'){?> class="active" <?php } ?>>
	            	        <a href="<?php echo Router::getBaseUrl();if(PayPasswordController::checkExist()){?>payPassword/reset<?php }else{?>payPassword/set<?php }?>">支付密码管理</a>
	            	    </li>
	            	    </ul>	            	    
            	    <?php }?>            	    
        	    </li>
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   收款管理
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsTrade' && strval($inout) == '1'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex_in">收款明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   付款管理
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <?php if(UserController::isFirstAuditUser()){?>
            	    <li <?php if(doit::$controller == 'TradeRecord' && strval($audit_level) == '1'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>tradeRecord/searchListFrist">付款审批(一级)</a>
            	    </li>
            	    <?php }?>        	    
            	    <li <?php if(doit::$controller == 'TradeRecord' && strval($audit_level) == ''){?> class="active" <?php } ?> >        	    
            			<a href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">申请付款</a>
            	    </li>
            	    <li <?php if(doit::$controller == 'BcsTrade' && strval($inout) == '0'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex_out">付款明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   账户查询
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'BcsTrade'  && strval($inout) == ''){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>bcsTrade/getIndex">收支明细</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    <li>
        	       <div class="header">
        	       <span class="labels" style="background-image: url(<?php echo Router::getBaseUrl();?>view/images/index/searchs.png);background-position:10px 0px;">
        	                   开户银行查询
        	       </span>
				   <span class="arrow up"></span>
        	       </div>
            	    <ul class="menu">
            	    <li <?php if(doit::$controller == 'SpdInternetBank'){?> class="active" <?php } ?> >
            			<a href="<?php echo Router::getBaseUrl();?>spdInternetBank/getIndex">开户银行查询</a>
            	    </li>
            	    </ul>
        	    </li>
        	    
        	    <!--  
        	    <li <?php if(doit::$controller == 'TradeRecord' && doit::$params[0] =='getIndexBill'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndexBill">收款信息</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'TradeRecord'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">付款信息</a>
        	    </li>
        	    <li <?php if(doit::$controller == 'BcsTransfer'){?> class="discolor" <?php } ?>>
        			<a href="<?php echo Router::getBaseUrl();?>bcsTransfer/getIndex">出入金</a>
        	    </li>						
				<li <?php if(doit::$controller == 'User'){?> class="discolor" <?php } ?>>
					<a href="<?php echo Router::getBaseUrl();?>user/passwordReset">重置登录密码</a>
				</li>        		   
        		<li <?php if(doit::$controller == 'Message'){?> class="discolor" <?php } ?>>
        			  <a href="<?php echo Router::getBaseUrl();?>message/getIndex">消息提醒</a>
        		</li>
        		-->
        		</ul>
				<!-- End Freebie -->
			</li>
          </ul>
        </div>
        
        <!-- 右侧内容页面 -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
              <?php if($page_type == 'bcsCustomer'){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/bcsCustomerInfo.js"></script>
        		    <?php echo $bcsCustomer_html; ?>
        	  <?php }else if($page_type == 'bcsTrade'){?>
    			    <script src="<?php echo Router::getBaseUrl();?>js/bcsTrade.js"></script>
		            <?php echo $bcsTrade_html;?>
        	  <?php }else if($page_type == 'tradeRecordBill'){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/tradeRecordBill.js"></script>
                    <?php echo $tradeRecordBill_html; ?>
              <?php }else if($page_type == 'tradeRecord' && doit::$params[0] =='createApply' ){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/tradeRecordCreate.js"></script>
                    <?php echo $tradeRecordCreate_html; ?>
        	  <?php }else if($page_type == 'tradeRecord'){?>
        	        <script src="<?php echo Router::getBaseUrl();?>js/tradeRecord.js"></script>
                    <?php echo $tradeRecord_html; ?>
        	  <?php }else if($page_type == 'bcsTransfer'){?>
    			    <script src="<?php echo Router::getBaseUrl();?>js/bcsTransfer.js"></script>
		            <?php echo $bcsTransfer_html;?>        	 
        	  <?php } else if($page_type == 'user'){?>
        			<?php echo $password_html; ?>
        	  <?php }else if($page_type == 'message'){?>
        		    <script src="<?php echo Router::getBaseUrl();?>js/sysMessage.js"></script>
        		    <div id="message-list"><?php echo $message_html;?></div>        	 
        	  <?php }else if($page_type == 'payPassword'){?>
        	  	  <script src="<?php echo Router::getBaseUrl();?>js/payPassword.js"></script>
				  <?php echo $password_html; ?>
			  <?php	}else if($page_type == 'spdInternetBank'){?>
                  <script src="<?php echo Router::getBaseUrl();?>js/spdInternetBank.js"></script>
    			  <?php echo $spdInternetBank_html;?>
              <?php }?>
              
        </div>
      </div>
    </div>
    
    <div id="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 col-md-12" id="realfooter" style="overflow-x: hidden;">
					<p class="text-center" style="width: 118%;">Copyright © 2015 大大买钢 All Rights Reserved</p>
				</div>
			</div>
		</div>
	</div>

<div class="clear"></div>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/custom.js"></script>

<?php }?>

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
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/messenger.min.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/messenger-theme-future.js"></script>
</body>
</html>