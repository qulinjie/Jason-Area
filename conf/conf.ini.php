<?php
return array(

// 	    'CSBankSoapUrl'     => 'http://58.20.40.249:43294/icop/services/JTService?wsdl',
        'CSBankSoapUrl'     => 'http://61.187.87.90:43294/icop/services/JTService?wsdl',
        'ddmg_java_url'	      => 'http://120.25.1.102:8081/erpinterface/',
        'ddmg_erp_url'	      => 'http://test-api.gt-xx.com/',
//         'ddmg_erp_url'	      => 'http://erp-api.gt-xx.com/',
		'ddmg_pay_url'     => 'http://127.0.0.1/ddmg_pay/index.php/',
		'ddmg_pay_timeout' => '',
	    'ddmg_payapi_url'     => 'http://127.0.0.1/ddmg_payapi/index.php/',
		'ddmg_payapi_timeout' => '',
        'ddmg_server_url'     => 'http://127.0.0.1/ddmg_server/index.php/',
        'ddmg_server_timeout' => '',
        'ddmg_spd_sign_url'	  =>    'http://192.168.0.100:4437/',
        'ddmg_spd_send_url'	  =>	'http://192.168.0.100:5777/',
//         'ddmg_spd_sign_url'	  =>    'http://120.24.231.10:4437/',
//         'ddmg_spd_send_url'	  =>	'http://120.24.231.10:5777/',
        'ddmg_spd_masterID'	  =>	'2000040752',
        'ddmg_spd_acctNo'	  =>	'6224080600234',
//         'ddmg_spd_masterID'	  =>	'2012541443',
//         'ddmg_spd_acctNo'	  =>	'66100155260001330',

		'attachment_file_path'    =>  'attachmentFile/',

		'auto_login_timeout' => 604800,
		'auth_id_md5_salt' => 'auth_id_md5_salt',
		'article_zip_size_limit' => 10485760,
		//'article_file_path'	=> '/alidata/xpp/article_file_path/',
		//'article_zip_path'	=> '/alidata/xpp/article_zip_path/',
		'article_file_path'	=> 'D:\\AppServ\\www\\xplusplus_web\\image\\view\\',
		'article_zip_path'	=> 'D:\\paco-work\\article_zip_path\\',
		'article_image_format' => array('.jpg', '.gif', '.png', '.jpeg', '.bmp'),
		'image_watermark'	=> 'Xplusplus.cn',
		
		'avatar_path'	=> 'D:\\AppServ\\www\\xplusplus_web\\image\\avatar\\',
		'avatar_upload_size_limit' => 104857600,
		'avatar_size'	=>	420,
		
		'short_article_line_cnt'	=> 10,//文章缩略视图正文行数
		'keep_max_cnt'	=>	10,
		'follow_max_cnt'	=>	3,
		'home_page_article_cnt_per_update'	=> 5,//home页每页展示文章数
		'solr_url_prefix'	=>	'http://localhost:8080/solr/collection1/',
		'solr_res_article_page_cnt' => 10,
		'solr_res_user_page_cnt'	=> 10,
		'new_article_page_cnt'	=> 5,
		'recommend_article_page_cnt'	=>	5,
		'recommend_user_page_cnt'	=> 3,
		'smtp_host'	=> 'mail.xplusplus.cn',
		'stmp_port'	=>	25,
		'smtp_user' => 'nonreply@xplusplus.cn',
		'smtp_password' => 'Isd!@#user',
		'smtp_sender' => 'nonreply@xplusplus.cn',
		
		'page_count_default' => 10,
		
		'code_expire'	=> 86400,//one day
		'code_length'	=> 16,
		'register_mail_template_subject'	=> '【xplusplus.cn】验证邮箱',
		'register_mail_template_content'	=> 'hi, %s：<BR><BR>请点击下面链接验证您的xplusplus.cn邮箱：<BR><a href="' . Router::getDomainAndBaseUrl() .
		'user/verify_register/%s">' . Router::getDomainAndBaseUrl() .'user/verify_register/%s</a><BR><BR>' .
		'如果链接无法点击，复制链接到浏览器地址栏访问即可。<BR>' .
		'这是<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a>自动发送的邮件，请不要回复。<BR>' .
		'-------------<BR>' .
		'<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a> - IT技术精英的知识分享社区<BR>' . 
		date('Y年m月d日'),
		
		'alter_mail_template_subject'	=> '【xplusplus.cn】验证更改后的邮箱',
		'alter_mail_template_content'	=> 'hi, %s：<BR><BR>请点击下面链接验证您的xplusplus.cn新邮箱：<BR><a href="' . Router::getDomainAndBaseUrl() .
		'user/verify_alter/%s">' . Router::getDomainAndBaseUrl() .'user/verify_alter/%s</a><BR><BR>' .
		'如果链接无法点击，复制链接到浏览器地址栏访问即可。<BR>' .
		'这是<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a>自动发送的邮件，请不要回复。<BR>' .
		'-------------<BR>' .
		'<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a> - IT技术精英的知识分享社区<BR>' . 
		date('Y年m月d日'),
		
		'find_password_mail_template_subject' => '【xplusplus.cn】找回密码',
		'find_password_mail_template_content' => 'hi, %s：<BR><BR>请点击下面链接找回您的xplusplus.cn密码：<BR><a href="' . Router::getDomainAndBaseUrl() .
		'user/reset_pwd/%s">' . Router::getDomainAndBaseUrl() .'user/reset_pwd/%s</a><BR><BR>' .
		'如果链接无法点击，复制链接到浏览器地址栏访问即可。<BR>' .
		'这是<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a>自动发送的邮件，请不要回复。<BR>' .
		'-------------<BR>' .
		'<a href="' . Router::getDomainAndBaseUrl() . '">xplusplus.cn</a> - IT技术精英的知识分享社区<BR>' . 
		date('Y年m月d日'),
		
		'rsa_modulus'	=>	'112508591555319134931937329065823977014282686348560268836288738967501042747140726435121208584145052301669271478398122286009913688919502233098731480863226117519495710886201698362513714060100164324761090838646001867474072903852638004105478721773837440812425090394652092524403508691222407927841998468879563311487.0000000000',
		'rsa_private'	=>	'41455322472463592632198950551314790071881506781589535252738155066408520076566737292755343468421421837751339970709032408602276511894473960127380987837178750524311746988834123812118723009262694649577431973797061427717357324841799307669069775388716740225734994680781848177426208272344032876579888374700046538977.0000000000',
		'rsa_public'	=>	'65537',
		'rsa_key_len' => '1024',
		'token_life_time' => 86400,
		'xpp_droped_token_session_key' => 'xpp_droped_token_session_key',
		'xpp_processing_token_session_key' => 'xpp_processing_token_session_key',
    
        // BCS-str
        'MCH_NO' => 8001529592,
        'pay_user_id' =>1039, //1021,
        // BCS-end
    
    'private_key'	=>	'-----BEGIN PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAKA3sYbZ3Dbo2XON
9wgAFmhrnheR87LnxQbBb0QIevFQubGNxvtoEPKYEfKd/8YBacC8Q8kOAF93qKOe
Q6Gn7cyi9L9DDw64+r7pNgwCwISum3t6k5kVFC8WD7Ayz3DRbAhQbYNTeSn3JTFT
M7MGAx8sC9ArC3WrL9gVd8jZi0l/AgMBAAECgYA7CMps+dFemiWlomWnmQCosR6r
aKf2/9dlSdkJpGZIjIofljH/aLT17nqOfxFDkDm7PqNrbbFNe/WzdBlPc2T3MKGV
UaoqRe2WDGYcLLYw3YItKcXUbrj3kVUYXksZ67sx9lXE+vcudcBMZxcT1cCGmgGH
JuFkWdRtUY0uemPY4QJBANNqPR4HaBI89dKzGvk2P8XSMjjXh5JdaBwAasUXcM+E
mfffKnER2QqHsB1zCGnum1IYrNCw3iDVIiaj8JVsJisCQQDCAW8ntPYo+BfFWKhT
LD192cDh3gL1s7MmMkej4DKmzk8pcl33GHhEMfFAO3DYVYS3DIbJ6oXzV16kYUQF
rTP9AkEAzPNH7o2FkXCxaqIg+wlhR3gfIcWncvfETqkE2K6BuVs2G/qnXVfNDY0+
6T20IWWkAzFSjzl3DWMxKT5yo5hlYwJBAJruLEeIc4DaR5l5KMtrLBxB9pAqDTEM
dggN5TpQxAKEBaHyzGmVKA5F2ATUs1SATwEjxsyfNqPTZShe6MSjwj0CQQCQAo/B
dazgg4gyitGXm9+aFFJuSPr7TxeqmN9naA1WFxGqL2ElzqeLweX6m7a5gmlt586Q
BXBufFUxUeazsmW0
-----END PRIVATE KEY-----',
    
);
?>
