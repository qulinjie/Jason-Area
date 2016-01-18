$(document).ready(function(){
	$(".form_datetime").datetimepicker({
		language:  'zh-CN',
		format: 'yyyy-mm-dd hh:ii',
		weekStart: 1,
	    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
	    showMeridian: 0
	});

	$(document).on('click', '#entity-search-btn', function(event){
		search_entity(1);
	});

	$(document).on('click', '#entity-clear-btn', function(event){
		search_clearFields();
	    search_entity(1);
		return;
	});

	$(document).on('click', '#entity-list-prev', function(event){
		var cur_page = $("#entity-current-page").html();
		var total_page = $("#entity-total-page").html();
		
		if(cur_page == '1') {
			Messenger().post('已经是第一页了！');
			return ;
		}
		var page = cur_page * 1 - 1;
		search_entity(page);
	});

	$(document).on('click', '#entity-list-next', function(event){
		var cur_page = $("#entity-current-page").html();
		var total_page = $("#entity-total-page").html();
		if(cur_page == total_page) {
			Messenger().post('已经是最后一页了！');
			return ;
		}
		var page = cur_page * 1 + 1;
	    search_entity(page);
	});

	$(document).on('change', '#entity-custom-page', function(event){
		var sel_page = $('#entity-custom-page').val();
		var cur_page = $("#entity-current-page").html();
		var total_page = $("#entity-total-page").html();
		search_entity(sel_page);
	});

	function entitySetSelectedPage(){
		if($('#entity-custom-page') && $("#entity-current-page") && $("#entity-total-page")){
			var cur_page = $("#entity-current-page").html();
			var total_page = $("#entity-total-page").html();
			var selObj = $('#entity-custom-page');
			selObj.empty();
			for(var i=1; i<=total_page; i++){
				selObj.append("<option value='" + i +"'>" + i +"</option>");
			}
			selObj.val(cur_page);
		}
	}
	$(function(){
		entitySetSelectedPage();
	});

	function search_entity(page){
		var cur_page = $("#entity-current-page").html();
		var total_page = $("#entity-total-page").html();
		if(page < 1 || page > total_page * 1) {
			Messenger().post('页码错误！');
			return ;
		}
		
		$("#entity-search-btn").attr('disabled', 'disabled');
		$("#search-entity-hint").html('').fadeOut();
		
	    var time1 = $("#entity-search-time1").val();
		var time2 = $("#entity-search-time2").val();
		var SIT_NO = $("#entity-search-SIT_NO").val();
		var ACCOUNT_NO = $("#entity-search-ACCOUNT_NO").val();
		var status = $("#entity-search-status").val();
		var account = $("#entity-search-account").val();
		
		if(-1 == status) { status =""; }
		
	    //查找
	    $.post(BASE_PATH + 'bcsRegister/searchList', {
		    	'time1':time1,
		    	'time2':time2,
		    	'SIT_NO':SIT_NO,
		    	'ACCOUNT_NO':ACCOUNT_NO,
		    	'status':status,
		    	'account':account,
		        'page':page
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#entity-list").html(result.data.entity_list_html);
	            }
	            $("#entity-search-btn").removeAttr('disabled');
	            entitySetSelectedPage();
	        },
	        'json'
	    );
	}

	function search_clearFields(){
		$("#entity-search-time1").val("");
		$("#entity-search-time2").val("");
		$("#entity-search-SIT_NO").val("");
		$("#entity-search-ACCOUNT_NO").val("");
		$("#entity-search-status").val("-1");
		$("#entity-search-account").val("");
	}

	/**************start--增加****************/
	function add_entity(){
		$("#btn-add-entity").attr('disabled', 'disabled');
	    $("#add-entity-hint").html('').fadeOut();
	    
	    var user_id = $("#add-entity-user_id").val();
	    var SIT_NO = $('#add-entity-SIT_NO').val();       // 席位号（允许字母、数字）
	    var CUST_CERT_TYPE = $('#add-entity-CUST_CERT_TYPE').val();       // 客户证件类型
	    var CUST_CERT_NO = $('#add-entity-CUST_CERT_NO').val();         // 客户证件号码
	    var CUST_NAME = $('#add-entity-CUST_NAME').val();            // 客户名称
	    var CUST_ACCT_NAME = $('#add-entity-CUST_ACCT_NAME').val();       // 客户账户名
	    var CUST_SPE_ACCT_NO = $('#add-entity-CUST_SPE_ACCT_NO').val();     // 客户结算账户
	    var CUST_SPE_ACCT_BKTYPE = $('#add-entity-CUST_SPE_ACCT_BKTYPE').val(); // 客户结算账户行别
	    var CUST_SPE_ACCT_BKID = $('#add-entity-CUST_SPE_ACCT_BKID').val();   // 客户结算账户行号
	    var CUST_SPE_ACCT_BKNAME = $('#add-entity-CUST_SPE_ACCT_BKNAME').val(); // 客户结算账户行名
	    var ENABLE_ECDS = $('#add-entity-ENABLE_ECDS').val();          // 是否开通电票
	    var IS_PERSON = $('#add-entity-IS_PERSON').val();            // 是否个人
	    var CUST_PHONE_NUM = $('#add-entity-CUST_PHONE_NUM').val();       // 客户手机号码
	    var CUST_TELE_NUM = $('#add-entity-CUST_TELE_NUM').val();        // 客户电话号码
	    var CUST_ADDR = $('#add-entity-CUST_ADDR').val();            // 客户地址
	    var RMRK = $('#add-entity-RMRK').val();                 // 客户备注
	    var comment = $("#add-entity-comment").val();
	    
	    var hint_html = '';
    	if('-1' == user_id || '' == user_id ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 用户登录账号 ！' ;
        }
    	if('-1' == CUST_CERT_TYPE || '' == CUST_CERT_TYPE ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户证件类型 ！' ;
        }
    	if('' == CUST_CERT_NO ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户证件号码！' ;
        }
    	if('' == CUST_NAME ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户名称！' ;
        }
    	/*if('' == SIT_NO ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 席位号 ！' ;
        }*/
	    
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        $("#btn-add-entity").removeAttr('disabled');
	        return 0;
	    }
	    
	    $("#btn-add-entity").html("添加中...");
	    $.post(BASE_PATH + 'bcsRegister/create', {
	        	'user_id':user_id, 
	        	'SIT_NO':SIT_NO,       				// 席位号
	        	'CUST_CERT_TYPE':CUST_CERT_TYPE,       // 客户证件类型
	        	'CUST_CERT_NO':CUST_CERT_NO,         // 客户证件号码
	        	'CUST_NAME':CUST_NAME,            // 客户名称
	        	'CUST_ACCT_NAME':CUST_ACCT_NAME,       // 客户账户名
	        	'CUST_SPE_ACCT_NO':CUST_SPE_ACCT_NO,     // 客户结算账户
	        	'CUST_SPE_ACCT_BKTYPE':CUST_SPE_ACCT_BKTYPE, // 客户结算账户行别
	        	'CUST_SPE_ACCT_BKID':CUST_SPE_ACCT_BKID,   // 客户结算账户行号
	        	'CUST_SPE_ACCT_BKNAME':CUST_SPE_ACCT_BKNAME, // 客户结算账户行名
	        	'ENABLE_ECDS':ENABLE_ECDS,          // 是否开通电票
	        	'IS_PERSON':IS_PERSON,            // 是否个人
	        	'CUST_PHONE_NUM':CUST_PHONE_NUM,       // 客户手机号码
	        	'CUST_TELE_NUM':CUST_TELE_NUM,        // 客户电话号码
	        	'CUST_ADDR':CUST_ADDR,            // 客户地址
	        	'RMRK':RMRK,                 // 客户备注
		        'comment':comment
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	                $("#btn-add-entity").removeAttr('disabled');
	                $("#btn-add-entity").html("确定");
	            }else {
	                $("#add-entity-hint").html(result.msg + ', 关闭...').fadeIn();
	                setTimeout(function(){
	                    $("#add-entity-modal").modal('hide');
	                    $("#btn-add-entity").removeAttr('disabled');
	                    $("#btn-add-entity").html("确定");
	                    $('#entity-clear-btn').click();
	                }, 1000);
	            }
	        },
	        'json'
	    );
	}

	$(document).on('click', '#add-entity-new', function(event){
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});
			
		$('#btn-add-entity').show();
		$('#btn-add-entity').unbind("click");

		var title = $('#add-entity-new').text();
		$('#info_entity_title').html(title);
		
		$("#add-entity-hint").html('').fadeOut();
		
		clear_entity_field();
		
		getUserAccountList();
		
		$("#btn-add-entity").removeAttr('disabled');
	    $("#btn-add-entity").html("确定");
	    
		$('#btn-add-entity').on('click',function(event){
			add_entity();
		});
		
	});

	function clear_entity_field(){
		$('#info-entity-id').val('');
		
		$('#add-entity-user_id').empty();
		$('#add-entity-comment').val('');
	}

	function getUserAccountList(){
		$.post(BASE_PATH + 'user/searchListAll', {},
		    function(result){
		        if(result.code != 0) {
		            $("#add-entity-hint").html(result.msg + '(' + result.code + ')' + ',请刷新页面').fadeIn();
		        }else {
		        	$('#add-entity-user_id').empty();
		        	$('#add-entity-user_id').append("<option value='-1'>请选择</option>");
		        	var data = result['data']['data'];
		        	for(var i=0;i<data.length;i++){
		        		$("#add-entity-user_id").append("<option value='" + data[i].id + "'>" + data[i].account + ' ' + data[i].nicename + ' ' + data[i].company_name + "</option>");
		        	}
		        }
		    },
		    'json'
		);
	}

	/**************end--增加****************/
	
	
	/**************start--修改****************/
	$(document).on('click', '#entity-changeInfo-btn', function(event){
		var id =  $(this).parent().parent().parent().children().first().text();
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});
		$.post(BASE_PATH + 'bcsRegister/info', {
				'id':id
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	            	fillInentityValue(result.data[0]);
	            }
	        },
	        'json'
	    );

	});

	function fillInentityValue(data){
		$('#info_entity_title').html('开户');
		$("#add-entity-hint").html('').fadeOut();
		
		$('#info-entity-id').val(data.id);

		$('#add-entity-CUST_CERT_TYPE').val(data.CUST_CERT_TYPE);       // 客户证件类型
		$('#add-entity-CUST_CERT_NO').val(data.CUST_CERT_NO);         // 客户证件号码
		$('#add-entity-CUST_NAME').val(data.CUST_NAME);            // 客户名称
		$('#add-entity-CUST_ACCT_NAME').val(data.CUST_ACCT_NAME);       // 客户账户名
		$('#add-entity-CUST_SPE_ACCT_NO').val(data.CUST_SPE_ACCT_NO);     // 客户结算账户
		$('#add-entity-CUST_SPE_ACCT_BKTYPE').val(data.CUST_SPE_ACCT_BKTYPE); // 客户结算账户行别
		$('#add-entity-CUST_SPE_ACCT_BKID').val(data.CUST_SPE_ACCT_BKID);   // 客户结算账户行号
		$('#add-entity-CUST_SPE_ACCT_BKNAME').val(data.CUST_SPE_ACCT_BKNAME); // 客户结算账户行名
		$('#add-entity-ENABLE_ECDS').val(data.ENABLE_ECDS);          // 是否开通电票
		$('#add-entity-IS_PERSON').val(data.IS_PERSON);            // 是否个人
		$('#add-entity-CUST_PHONE_NUM').val(data.CUST_PHONE_NUM);       // 客户手机号码
		$('#add-entity-CUST_TELE_NUM').val(data.CUST_TELE_NUM);        // 客户电话号码
		$('#add-entity-CUST_ADDR').val(data.CUST_ADDR);            // 客户地址
		$('#add-entity-RMRK').val(data.RMRK);                 // 客户备注
		$("#add-entity-comment").val(data.comment);
	    
		
//		$('#add-entity-time').attr('readonly','readonly');
		
//		$('#add-entity-status').removeAttr('disabled');
//		$('#add-entity-remark').removeAttr('readonly');
		
		$('#btn-add-entity').show();
		$('#btn-add-entity').unbind("click");
		$('#btn-add-entity').on('click',function(event){
			updateEntity();
		});
	}

	function updateEntity(){
		var id = $("#info-entity-id").val();
		
	    var CUST_CERT_TYPE = $('#add-entity-CUST_CERT_TYPE').val();       // 客户证件类型
	    var CUST_CERT_NO = $('#add-entity-CUST_CERT_NO').val();         // 客户证件号码
	    var CUST_NAME = $('#add-entity-CUST_NAME').val();            // 客户名称
	    var CUST_ACCT_NAME = $('#add-entity-CUST_ACCT_NAME').val();       // 客户账户名
	    var CUST_SPE_ACCT_NO = $('#add-entity-CUST_SPE_ACCT_NO').val();     // 客户结算账户
	    var CUST_SPE_ACCT_BKTYPE = $('#add-entity-CUST_SPE_ACCT_BKTYPE').val(); // 客户结算账户行别
	    var CUST_SPE_ACCT_BKID = $('#add-entity-CUST_SPE_ACCT_BKID').val();   // 客户结算账户行号
	    var CUST_SPE_ACCT_BKNAME = $('#add-entity-CUST_SPE_ACCT_BKNAME').val(); // 客户结算账户行名
	    var ENABLE_ECDS = $('#add-entity-ENABLE_ECDS').val();          // 是否开通电票
	    var IS_PERSON = $('#add-entity-IS_PERSON').val();            // 是否个人
	    var CUST_PHONE_NUM = $('#add-entity-CUST_PHONE_NUM').val();       // 客户手机号码
	    var CUST_TELE_NUM = $('#add-entity-CUST_TELE_NUM').val();        // 客户电话号码
	    var CUST_ADDR = $('#add-entity-CUST_ADDR').val();            // 客户地址
	    var RMRK = $('#add-entity-RMRK').val();                 // 客户备注
	    var comment = $("#add-entity-comment").val();
	    
	    var hint_html = '';
    	if('-1' == CUST_CERT_TYPE || '' == CUST_CERT_TYPE ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户证件类型 ！' ;
        }
    	if('' == CUST_CERT_NO ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户证件号码！' ;
        }
    	if('' == CUST_NAME ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 客户名称！' ;
        }
    	
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        $("#btn-add-entity").removeAttr('disabled');
	        return 0;
	    }
	    
	    $("#btn-add-selle").html("提交中...");
	    $.post(BASE_PATH + 'bcsRegister/update', {
		    	'id':id,
	        	'CUST_CERT_TYPE':CUST_CERT_TYPE,       // 客户证件类型
	        	'CUST_CERT_NO':CUST_CERT_NO,         // 客户证件号码
	        	'CUST_NAME':CUST_NAME,            // 客户名称
	        	'CUST_ACCT_NAME':CUST_ACCT_NAME,       // 客户账户名
	        	'CUST_SPE_ACCT_NO':CUST_SPE_ACCT_NO,     // 客户结算账户
	        	'CUST_SPE_ACCT_BKTYPE':CUST_SPE_ACCT_BKTYPE, // 客户结算账户行别
	        	'CUST_SPE_ACCT_BKID':CUST_SPE_ACCT_BKID,   // 客户结算账户行号
	        	'CUST_SPE_ACCT_BKNAME':CUST_SPE_ACCT_BKNAME, // 客户结算账户行名
	        	'ENABLE_ECDS':ENABLE_ECDS,          // 是否开通电票
	        	'IS_PERSON':IS_PERSON,            // 是否个人
	        	'CUST_PHONE_NUM':CUST_PHONE_NUM,       // 客户手机号码
	        	'CUST_TELE_NUM':CUST_TELE_NUM,        // 客户电话号码
	        	'CUST_ADDR':CUST_ADDR,            // 客户地址
	        	'RMRK':RMRK,                 // 客户备注
		        'comment':comment,
		        'status':status
	        },
	        function(result){
	        	$("#add-entity-hint").html('');
	            if(result.code != 0) {
	                $("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	                $("#btn-add-entity").removeAttr('disabled');
	                $("#btn-add-entity").html("确定");
	            }else {
	                $("#add-entity-hint").html(result.msg + ', 关闭...').fadeIn();
	                setTimeout(function(){
	                    $("#add-entity-modal").modal('hide');
	                    $("#btn-add-entity").removeAttr('disabled');
	                    $("#btn-add-entity").html("确定");
	                }, 500);
	                setTimeout(function(){
	            		search_entity(parseInt($("#entity-current-page").html()));
	            	},800);
	            }
	        },
	        'json'
	    );
	}
	/**************end--修改****************/

	
    $(document).on('click', '#bcsRegisterSaveBtn', function(event){
    	$(this).text('更新中...');
    	$.post(BASE_PATH + 'bcsRegister/registerAccount', $('#bcsRegister').serialize(),
    	        function(result){
    	            if(result.code != 0) {
    	            	alert(result.msg + '(' + result.code + ')');
    	            }else {
    	            	alert('注册成功,帐号：'+result.data.ACCOUNT_NO);
    	            }
    	            window.location.replace(window.location);
    	        },
    	        'json'
    	    );
    });

 
    
    prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
