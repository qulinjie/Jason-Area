$(document).ready(function(){

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
		var cur_page = parseInt($("#entity-current-page").html());
		var total_page = parseInt($("#entity-total-page").html());
		var selObj = $('#entity-custom-page');
		selObj.empty();		
	    var start = cur_page > 20 ? cur_page - 20 : 1;
	    var end = (total_page - cur_page) > 20 ? cur_page + 19 : total_page;
	    var html = '';
		for(var i=start; i<=end; i++){
			html += "<option value='" + i +"'>" + i +"</option>";				
		}
		selObj.append(html);
		selObj.val(cur_page);
	}
}
$(function(){
	entitySetSelectedPage();
	setLiTabs(0);
	renderTableEvent();
	
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
	
    var order_no = $("#entity-search-order_no").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var order_status = $("#entity-search-order_status").val();
	var order_time1 = $("#entity-search-order_time1").val();
	var order_time2 = $("#entity-search-order_time2").val();
	var seller_name = $("#entity-search-seller_name").val();
	var seller_conn_name = $("#entity-search-seller_conn_name").val();
	var order_sum_amount1 = $("#entity-search-order_sum_amount1").val();
	var order_sum_amount2 = $("#entity-search-order_sum_amount2").val();	
	var apply_status = $("#entity-search-apply_status").val();
	var backhost_status = $("#entity-search-backhost_status").val();
	//新增加金额、机构名称以及款项类别筛选
	var amount1     = $("#entity-search-order_amount1").val();
	var amount2     = $("#entity-search-order_amount2").val();
	var amount_type = $("#entity-search-amount_type").val();
	var erp_fgsmc   = $("#entity-search-erp_fgsmc").val();
	
	if(-1 == order_status) { order_status =""; }
	if(-1 == apply_status) { apply_status =""; }
	if(-1 == backhost_status) { backhost_status =""; }
	
	var spanLoading = $('#span-trade-order-list');
	spanLoading.html("<div style='width:100%;text-align:center;height:" + spanLoading.height() + "px;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
		
	var pathname = window.location.pathname;
	var audit_level = '0';
	if(pathname.indexOf("searchListFrist") > 0){
		audit_level = '1';		
	}
	if(pathname.indexOf("searchListSecond") > 0){
		audit_level = '2';		
	}
    //查找
    $.post(BASE_PATH + 'tradeRecord/searchList', {
	    	'order_no':order_no, 
	    	'time1':time1,
	    	'time2':time2,
			'amount1':amount1,
			'amount2':amount2,
			'amount_type':amount_type,
			'erp_fgsmc':erp_fgsmc,
	    	'order_status':order_status,
	    	'order_time1':order_time1,
	    	'order_time2':order_time2,
	    	'seller_name':seller_name,
	    	'seller_conn_name':seller_conn_name,
	    	'order_sum_amount1':order_sum_amount1,
	    	'order_sum_amount2':order_sum_amount2,
	    	'apply_status':apply_status,
	    	'backhost_status':backhost_status,
	        'page':page,
	        'audit_level':audit_level
        },
        function(result){        	
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#entity-list").html(result.data.entity_list_html);
            }
            $("#entity-search-btn").removeAttr('disabled');
            entitySetSelectedPage();
            renderTableEvent();
        },
        'json'
    );
}

function search_clearFields(){
	$("#entity-search-order_no").val("");
	$("#entity-search-time1").val("");
	$("#entity-search-time2").val("");
	$("#entity-search-order_status").val("-1");
	$("#entity-search-order_time1").val("");
	$("#entity-search-order_time2").val("");
	$("#entity-search-seller_name").val("");
	$("#entity-search-seller_conn_name").val("");
	$("#entity-search-order_sum_amount1").val("");
	$("#entity-search-order_sum_amount2").val("");
	$("#entity-search-apply_status").val("-1");
	$("#entity-search-backhost_status").val("-1");

	$("#entity-search-order_amount1").val("");
	$("#entity-search-order_amount2").val("");
	$("#entity-search-amount_type").val("");
	$("#entity-search-erp_fgsmc").val("");
}

/**************start--导出数据****************/

$(document).on('click', '#entity-export-page-btn', function(event){
	export_data(1);
});

$(document).on('click', '#entity-export-all-btn', function(event){
	export_data(2);
});

function export_data(export_type){
	var page = $("#entity-current-page").html();
	
	$("#search-entity-hint").html('').fadeOut();
	
    var order_no = $("#entity-search-order_no").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var order_status = $("#entity-search-order_status").val();
	var order_time1 = $("#entity-search-order_time1").val();
	var order_time2 = $("#entity-search-order_time2").val();
	var seller_name = $("#entity-search-seller_name").val();
	var seller_conn_name = $("#entity-search-seller_conn_name").val();
	var order_sum_amount1 = $("#entity-search-order_sum_amount1").val();
	var order_sum_amount2 = $("#entity-search-order_sum_amount2").val();
	
	if(-1 == order_status) { order_status =""; }
	
    $.download(BASE_PATH + 'tradeRecord/exportData', {
    	'order_no':order_no, 
    	'time1':time1,
    	'time2':time2,
    	'order_status':order_status,
    	'order_time1':order_time1,
    	'order_time2':order_time2,
    	'seller_name':seller_name,
    	'seller_conn_name':seller_conn_name,
    	'order_sum_amount1':order_sum_amount1,
    	'order_sum_amount2':order_sum_amount2,
        'page':page,
        'export_type':export_type
    },'post');
    
}

jQuery.download = function(url, data, method){
    if( url && data ){ 
        data = typeof data == 'string' ? data : jQuery.param(data);
        var inputs = '';
        jQuery.each(data.split('&'), function(){ 
            var pair = this.split('=');
            inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />'; 
        });        
        jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>').appendTo('body').submit().remove();
    };
};

/**************end--导出数据****************/

/**************start--拒付****************/
$(document).on('click', '#entity-changeStatus-btn', function(event){
	stopPropagation = true;
	var id =  $(this).parent().parent().parent().children().first().text();
	var status = $(this).parent().parent().parent().children().children().first().val(); // "status" value
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id,'status':status},function(event){
		$.post(BASE_PATH + 'tradeRecord/changeStatus', {'id':event.data.id, 'status':event.data.status },
		        function(result){
		            if(result.code != 0) {
		                $("#confirm-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
		            }else {
		            	$("#confirm-entity-hint").html(result.msg + ', 关闭...').fadeIn();
		            	setTimeout(function(){
		            		$('#confirm-entity-modal').modal('hide');
		            	},500);
		            	setTimeout(function(){
		            		search_entity(parseInt($("#entity-current-page").html()));
		            	},800);
		            }
		        },
		        'json'
		    );
	});
});
/**************end--拒付****************/


/**************start--删除****************/
$(document).on('click', '#entity-delete-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id},function(event){
		$.post(BASE_PATH + 'tradeRecord/delete', {'id':event.data.id},
		        function(result){
		            if(result.code != 0) {
		                $("#confirm-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
		            }else {
		            	$("#confirm-entity-hint").html(result.msg + ', 关闭...').fadeIn();
		            	setTimeout(function(){
		            		$('#confirm-entity-modal').modal('hide');
		            	},500);
		            	setTimeout(function(){
		            		search_entity(parseInt($("#entity-current-page").html()));
		            	},800);
		            }
		        },
		        'json'
		    );
	});
	
});
/**************end--删除****************/

/**************start--付款****************/

$(document).on('click', '#add-pay-new', function(event){
	stopPropagation = true;
	
	$("#add-pay-pwd").val('');
	$("#info-pay-hint").html('').hide();
	$('#info-pay-modal').modal('show');
	$('#info-pay-modal').modal({keyboard: false});
	
	$('#btn-add-pay').show();
	$('#btn-add-pay').unbind("click");

	$("#btn-add-pay").removeAttr('disabled');
    $("#btn-add-pay").html("确定支付");
    
	$('#btn-add-pay').on('click',function(event){
		add_pay();
	});
	
	$('#info-pay-trade').html("<div style='width:100%;text-align:center;'><img id='img-loging-data' alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	
	var id =  $(this).parent().parent().parent().children().first().text();
	$.post(BASE_PATH + 'tradeRecord/getInfo', {'id':id},
	    function(result){
	        if(result.code != 0) {
	            $("#add-pay-hint").html(result.msg + '(' + result.code + ')' + ',请刷新页面').fadeIn();
	        } else {
	        	$('#info-pay-trade').html(result.data.tradeRecord_pay);
	        }
	    },
	    'json'
	);
	
});

function add_pay(){
	$("#btn-add-pay").attr('disabled', 'disabled');
    $("#info-pay-hint").html('').fadeOut();
    
    var id = $("#info-pay-id").val();
    var pwd = $("#add-pay-pwd").val();
    
    var hint_html = '';
    if( !pwd || '' == pwd ){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写支付密码！' ;
    }
    
    if(hint_html != ''){
        $("#info-pay-hint").html(hint_html).fadeIn();
        $("#btn-add-pay").removeAttr('disabled');
        return 0;
    }
    
    pwd = hex2b64(do_encrypt(pwd));
    
    $("#btn-add-pay").html("正在付款...");
    $.post(BASE_PATH + 'tradeRecord/pay', {
        	'id':id, 
        	'pwd':pwd
        },
        function(result){
            if(result.code != 0) {
                $("#info-pay-hint").html(result.msg + '(' + result.code + ')').fadeIn();
                $("#btn-add-pay").removeAttr('disabled');
                $("#btn-add-pay").html("确定支付");
            }else {
                $("#info-pay-hint").html(result.msg + ', 关闭...').fadeIn();
                setTimeout(function(){
                    $("#info-pay-modal").modal('hide');
                    $("#btn-add-pay").removeAttr('disabled');
                    $("#btn-add-pay").html("确定支付");
                    $('#entity-clear-btn').click();
                    // 更新金额
                    $.post(BASE_PATH + 'bcsCustomer/loadInfo', {},function(result){},'json');
                }, 500);
            }
        },
        'json'
    );
    
}

/**************end--付款****************/

$("td[name='td-operation-name']").each(function(i,e){
	$(e).unbind("click");
});



$(document).on('click', '#for-test-btn', function(event){
	$.post(BASE_PATH + 'bcsCustomer/loadInfo', {},
	        function(result){
	            if(result.code != 0) {
	                $("#search-list-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	            	$("#search-entity-hint").html(result.msg + ', 关闭...').fadeIn();
	            }
	        },
	        'json'
	    );
});


function setLiTabs(num) {
	$("#tabs").find("li").eq(num).addClass("thistab").show(); 

	$("#tabs").find("li").bind("click",function(){
		$(this).addClass("thistab").siblings("li").removeClass("thistab");
		resetOrderStatusSelect();
		var chrId = $(this).children("a:first").attr("id");
		if("order-waiting-list" == chrId){ // 待付款
			$("#entity-search-order_status").val('1');
			$("#order-status-show").html("操作");
			$('#entity-search-btn').click();
		} else if("order-details-list" == chrId){ // 付款明细
			$("#entity-search-order_status").append("<option value='9'>已付/拒付</option>");
			$("#entity-search-order_status").val('9');
			$("#order-status-show").html("付款类别");
			$('#entity-search-btn').click();
		} else if("trade-details-list" == chrId){ // 资金查询
			
		}
	});
	$("#tabs").find("a").css("text-decoration","none");
};

function resetOrderStatusSelect(){
	var sel = $("#entity-search-order_status");
	sel.empty();
	sel.append("<option value='-1'>全部</option>");
	sel.append("<option value='1'>待付</option>");
	sel.append("<option value='2'>已付</option>");
	sel.append("<option value='3'>拒付</option>");
}

function renderTableEvent() {
	var index = $("#tabs").find("li").filter(".thistab").index();
	if(0== index){
		$("#order-status-show").html("操作");
	} else if(1== index){
		$("#order-status-show").html("付款类别");
	} 
	$(".panel-body").find("div[class='content']:odd").css("background","#f3f3f3");
	
	$(".panel-body").find(".odd").each(function(i,e){
		$(this).bind("click",function(){
			$(this).parent().next().toggle();
		})
	});
}

$(document).on('click', '#test_send_pay', function(event){
	$.post(BASE_PATH + 'tradeRecord/test_sendTransferTrade', {},
	        function(result){
	            if(result.code != 0) {
	                $("#search-list-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	            	$("#search-entity-hint").html(result.msg + ', 关闭...').fadeIn();
	            }
	        },
	        'json'
	    );
});


/**************start--付款审批****************/

$(".form_datetime").datetimepicker({
	language:  'zh-CN',
	format: 'yyyy-mm-dd',
	minView: "month",
	weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
    showMeridian: 0
});

$(document).on('click', '.audit-entity', function(event){	
	
	$('#audit-entity-modal').modal('show');
	$('#audit-entity-modal').modal({keyboard: false});
		
	$('#btn-audit-entity').show();
	$('#btn-audit-entity').unbind("click");
	
	var title = $('#audit-entity-new').text();
	$('#info_entity_title').html(title);
	
	$("#audit-entity-hint").html('').fadeOut();
	
	var v = $(this).attr("id").replace('audit-entity-', '');
	var arr = v.split('-');
	loadOneAuditTradRecord(arr[0], arr[1]);
});

function loadOneAuditTradRecord(id, audit_level){	
	$('#audit-entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	//查找
    $.post(BASE_PATH + 'tradeRecord/getOneTrandRecord', {    		
	        'id':id,
	        'audit_level':audit_level
        },
        function(result){        	
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#audit-entity-list").html(result.data.entity_list_html);
                //renderDatetime();
                //entitySetSelectedPage();
            }
        },
        'json'
    );
}

$(document).on('click', '#sendCode', function(event){	
	sends.send();		
	if(0 == sends.checked){
		//var id = $("#info-entity-id").val();
		var mobile = $('#mobile').val();
		$.post(BASE_PATH + 'sms/sendSmsVerificationCode', { 
		        'mobile':mobile,
		        'codetype':11
		    },
		    function(result){
		    	if(result.code == 5000){		    		
	                $('#sendCode').removeAttr('disabled').val('重新获取验证码');
	                $("#info-sms-hint").html('发送短信失败：' + result.msg + '(' + result.code + ')').fadeIn();
	                sends.clearItv();
		    	}
		    },
		    'json'
	   );
	}
});
var sends = {
    checked:1,
    time:60,
    timer:new Object(),
    send:function(){
        var numbers = /^1\d{10}$/;
        var mobile = $('#mobile').val().replace(/\s+/g,""); //获取输入手机号码        
        if(!numbers.test(mobile) || mobile.length ==0){
            $('#info-sms-hint').append('<span class="error">手机格式错误</span>');
            return false;
        }            
        if(numbers.test(mobile)){
        	function timeCountDown(){
                if(sends.time==0){
                	sends.clearItv();
                    $('#sendCode').removeAttr('disabled').val("重新获取验证码");
                    sends.checked = 1;
                    return true;
                }
                $('#sendCode').attr('disabled', 'disabled').val(sends.time+"秒后再次发送");
                sends.time--;
                sends.checked = 0;
                return false;                    
            }                
            timeCountDown();
            sends.timer = setInterval(timeCountDown,1000);            
        }
    },
	clearItv:function(){
		clearInterval(sends.timer);
	}
};

$(document).on('click', '#btn-sms-pay', function(event){
	$('#info-sms-hint').html('').fadeOut();
	var vcode = $('#vcode').val();    
	if(vcode == '' || vcode.length ==0){    	 
	$('#info-sms-hint').html('请输入验证码！').fadeIn();
	    return false;
	}
	
	//$("#sms-entity-modal").modal('hide');
	$("#btn-sms-pay").attr('disabled', 'disabled'); 
    $("#add-entity-audit1").html("提交审批中...");
	$("#add-entity-audit1").attr('disabled', 'disabled');
	$("#add-entity-audit2").attr('disabled', 'disabled').hide();
	auditOneTradRecord1(2);
          
});

$(document).on('click', '#sms-entity-close', function(event){
	$("#sms-entity-modal").modal('hide');
});

//apply_status 申请状态 1一级待审核 2一级审核通过 3一级审核驳回 4二级待审核 5二级审核通过 6二级审核驳回
$(document).on('click', '#add-entity-audit1', function(event){	
	$('#sms-entity-modal').modal('show');	
	return;
	/*if(confirm("您确定通过审批吗？")){
		$("#add-entity-audit1").html("提交审批中...");
		$("#add-entity-audit1").attr('disabled', 'disabled');
		$("#add-entity-audit2").attr('disabled', 'disabled').hide();
		auditOneTradRecord1(2);
	}*/	
});

$(document).on('click', '#add-entity-audit2', function(event){	
	if(confirm("您确定审批驳回吗？")){
		$("#add-entity-audit2").html("提交审批中...");
		$("#add-entity-audit1").attr('disabled', 'disabled').hide();
		$("#add-entity-audit2").attr('disabled', 'disabled');
		auditOneTradRecord1(3);
	}	
});

function auditOneTradRecord1(apply_status){	
	var id = $("#info-entity-id").val();	
	var vcode = $('#vcode').val();
	$.post(BASE_PATH + 'tradeRecord/auditOneTradRecord', {    		
	        'id':id,
	        'apply_status':apply_status,
	        'vcode':vcode
	    },
	    function(result){
	    	var hint_html = ''; 
	        if(result.code != 0) {	        	
	        	if(result.code == 5000){
	        		//$('#sms-entity-modal').modal('show');
	        		hint_html = $("#info-sms-hint").html();	            
		            hint_html += (hint_html == '' ? '' : '<BR>') + result.msg;
	        		$('#info-sms-hint').html(hint_html).fadeIn();	        		
	        	}else{
	        		hint_html = $("#add-entity-hint").html();	            
	        		hint_html += (hint_html == '' ? '' : '<BR>') + '审批操作失败：'+ result.msg + '(' + result.code + ')' ;
	        		$("#add-entity-hint").html(hint_html).fadeIn();	        		        	
	        	}	
	            if(apply_status == 2){
	            	$("#btn-sms-pay").removeAttr('disabled');
	            	$("#add-entity-audit1").html("审批通过");
	            }else{
	            	$("#add-entity-audit2").html("审批驳回");	            		            	
	            }
	            $("#add-entity-audit2").removeAttr('disabled');
	            $("#add-entity-audit1").removeAttr('disabled');	            
	        }else {
	        	$("#sms-entity-modal").modal('hide');
	        	$("#add-entity-hint").html('提交审批中...').fadeIn();
	        	hint_html = $("#add-entity-hint").html();
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '审批操作：' + result.msg ;
	        	$("#add-entity-hint").html(hint_html).fadeIn();
	        	if(apply_status == 2){
	        		$("#add-entity-audit1").html("已审批");
	        	}else{
	        		$("#add-entity-audit2").html("已驳回");
	        	}
	        	hint_html = $("#add-entity-hint").html();
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '关闭...' ;
	        	$("#add-entity-hint").html(hint_html).fadeIn();
                setTimeout(function(){
                	$('#add-entity-cancel').click();                	
                	search_entity($("#entity-current-page").html());
                }, 1500);
	        }
	    },
	    'json'
	);
}

$(document).on('click', '#add-entity-audit21', function(event){
	if(confirm("审批通过后会进行付款，您确定通过审批吗？")){
		$("#add-entity-audit21").html("提交审批中...");
		$("#add-entity-audit21").attr('disabled', 'disabled');
		$("#add-entity-audit22").attr('disabled', 'disabled').hide();
		auditOneTradRecord2(5);
	}	
});

$(document).on('click', '#add-entity-audit22', function(event){	
	if(confirm("您确定审批驳回吗？")){
		$("#add-entity-audit22").html("提交审批中...");
		$("#add-entity-audit21").attr('disabled', 'disabled').hide();
		$("#add-entity-audit22").attr('disabled', 'disabled');
		auditOneTradRecord2(6);
	}	
});

function auditOneTradRecord2(apply_status){	
	var id = $("#info-entity-id").val();	
	$.post(BASE_PATH + 'tradeRecord/auditOneTradRecord', {    		
	        'id':id,
	        'apply_status':apply_status
	    },
	    function(result){
	    	var hint_html = '';	 
	    	$("#add-entity-hint").html('提交审批中...').fadeIn();	
	        if(result.code != 0) {
	        	hint_html = $("#add-entity-hint").html();	            
	            hint_html += (hint_html == '' ? '' : '<BR>') + '审批操作失败：'+ result.msg + '(' + result.code + ')' ;
	        	$("#add-entity-hint").html(hint_html).fadeIn();	
	            if(apply_status == 5){
	            	$("#add-entity-audit21").html("审批通过");
	            }else{
	            	$("#add-entity-audit22").html("审批驳回");	            		            	
	            }
	            $("#add-entity-audit22").removeAttr('disabled');
	            $("#add-entity-audit21").removeAttr('disabled');	            
	        }else {	       
	        	hint_html = $("#add-entity-hint").html();
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '审批操作：' + result.msg ;
	        	$("#add-entity-hint").html(hint_html).fadeIn();	
	        	$("#add-entity-audit21").html("已审批");
	        	if(apply_status == 5){
	        		sendTransferTrade1();
	        		return;
	        	}else{
	        		$("#add-entity-audit22").html("已驳回");
	        	}
	        	hint_html = $("#add-entity-hint").html();
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '关闭...' ;
	        	$("#add-entity-hint").html(hint_html).fadeIn();
                setTimeout(function(){
                	$('#add-entity-cancel').click();                	
                	search_entity($("#entity-current-page").html());
                }, 1500);
	        }
	    },
	    'json'
	);
}

$(document).on('click', "#add-entity-pay", function(evnent){
	sendTransferTrade1();
});

function sendTransferTrade1(){
	var id = $("#info-entity-id").val();
	hint_html = $("#add-entity-hint").html();
	hint_html += (hint_html == '' ? '' : '<BR>') + '正在付款...' ;
	$("#add-entity-hint").html(hint_html).fadeIn();
	$("#add-entity-pay").removeClass("hidden").html("正在付款...").attr('disabled', 'disabled');
	var jqxhr = $.post(BASE_PATH + 'tradeRecord/sendTransferTrade', {    		
	        'id':id,	        	        
	    },
	    function(result2) {
	    	var is_success = true;
	    	//kk(result2.data);
	    	hint_html = $("#add-entity-hint").html();	    	
	    	if(result2.code != 0) {
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '付款操作失败：'+ result2.msg + '(' + result2.code + ')';
	        	$("#add-entity-hint").html(hint_html).fadeIn();	        	
	        	is_success = false;
	        }else {
	        	jnlSeqNo = result2.data['jnlSeqNo'];
	        	backhostStatus = result2.data['backhostStatus'];
	        	backhostDesc = result2.data['backhostDesc'];
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '付款操作结果：' + backhostDesc + '(流水号[' + jnlSeqNo + '])';
	        	$("#add-entity-hint").html(hint_html).fadeIn(); 
	        	if(backhostStatus == 8 || backhostStatus == 9){
	        		is_success = false;
	        	}                
	        }
	        
	        if(is_success){
	        	$("#add-entity-pay").html("已付款");	
	        	hint_html = $("#add-entity-hint").html();
	        	hint_html += (hint_html == '' ? '' : '<BR>') + '关闭...';
	            setTimeout(function(){
                	$('#add-entity-cancel').click();                	
                	search_entity($("#entity-current-page").html());
                }, 2000);
	        }else{
	        	$("#add-entity-pay").html("付款").removeAttr('disabled');
	        }
	        
	    },
	    'json'
	);	
	jqxhr.error(function(data, status, e) { 
		hint_html = $("#add-entity-hint").html();
    	hint_html += (hint_html == '' ? '' : '<BR>') + '付款操作失败：(' + e + ')';
        $("#add-entity-hint").html(hint_html).fadeIn();
		$("#add-entity-pay").html("付款").removeAttr('disabled');
	});
}
/**************end--付款审批****************/

/**************begin--付款单手动发送erp****************/

$(document).on('click', ".erp-sync-entity", function(evnent){
	var obj = $(this);
	var id = obj.attr("id").replace('erp-sync-entity-', '');
	obj.html('发送中...');
	
	$.post(BASE_PATH + 'tradeRecord/erp_syncBillsOfPayment', {    		
        	'id':id,
        	'is_ec':1
    	},
    	function(result){
    		if(result.code != 0){
    			$('#search-list-hint').html('发送失败：' + result.msg).fadeIn();
    			obj.html('手动发送');
    		}else{
    			$('#search-list-hint').html('发送成功！').fadeIn();
    			obj.fadeOut();
    			obj.parent().html('成功');
    		}
    	},
    	'json'
    );
});

/**************end--付款单手动发送erp****************/

prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " | " + i + "=" + val;  
	});
	alert(tt);
}

function showDetailInfo(o,id){
	var info = $('#info_tr_'+id);
	if( 'none' == info.css('display')){
		info.fadeIn();
	} else {
		info.hide();
	}
}

var stopPropagation = false;
function showDetailInfo_delay(o,id){
	setTimeout(function(){ 
		if(stopPropagation){
			stopPropagation = false;
		} else {
			showDetailInfo(o,id);
		}
	}, 100);
}




