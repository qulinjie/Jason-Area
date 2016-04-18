$(document).ready(function(){
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
	
    var fphm = $("#entity-search-fphm").val();
    var dwmc = $("#entity-search-dwmc").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var dwdm = $('#entity-search-dwdm').val();
	
    //查找
    $.post(BASE_PATH + 'tradeRecord/erp_getSellOrderList', {
	    	'fphm':fphm, 
	    	'dwmc':dwmc,
	    	'dwdm':dwdm,
	    	'time1':time1,
	    	'time2':time2,
	        'page':page
        },
        function(result){
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#add-entity-list").html(result.data.entity_list_html);
            }
            $("#entity-search-btn").removeAttr('disabled');
            renderDatetime();
            entitySetSelectedPage();
        },
        'json'
    );
}

function search_clearFields(){
	$("#entity-search-order_no").val("");
}

/**************start--来往单位搜索分页***********/
$(document).on('click', '#entity-search-btn-contact', function(event){
	search_entity_contact(1);
});

$(document).on('click', '#entity-clear-btn-contact', function(event){
	search_entity_contact(1);
	return;
});

$(document).on('click', '#entity-list-prev-contact', function(event){
	var cur_page = $("#entity-current-page-contact").html();
	var total_page = $("#entity-total-page-contact").html();
	
	if(cur_page == '1') {
		Messenger().post('已经是第一页了！');
		return ;
	}
	var page = cur_page * 1 - 1;
	search_entity_contact(page);
});

$(document).on('click', '#entity-list-next-contact', function(event){
	var cur_page = $("#entity-current-page-contact").html();
	var total_page = $("#entity-total-page-contact").html();
	if(cur_page == total_page) {
		Messenger().post('已经是最后一页了！');
		return ;
	}
	var page = cur_page * 1 + 1;
	search_entity_contact(page);
});

$(document).on('change', '#entity-custom-page-contact', function(event){
	var sel_page = $('#entity-custom-page-contact').val();
	var cur_page = $("#entity-current-page-contact").html();
	var total_page = $("#entity-total-page-contact").html();
	search_entity_contact(sel_page);
});

function entitySetSelectedPage_contact(){
	if($('#entity-custom-page-contact') && $("#entity-current-page-contact") && $("#entity-total-page-contact")){
		var cur_page = parseInt($("#entity-current-page-contact").html());
		var total_page = parseInt($("#entity-total-page-contact").html());;
		var selObj = $('#entity-custom-page-contact');
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
	//entitySetSelectedPage_contact();
});

function search_entity_contact(page){
	var cur_page = $("#entity-current-page-contact").html();
	var total_page = $("#entity-total-page-contact").html();
	if(page < 1 || page > total_page * 1) {
		Messenger().post('页码错误！');
		return ;
	}
	
	$("#entity-search-btn-contact").attr('disabled', 'disabled');
	$("#search-entity-hint-contact").html('').fadeOut();

	var referer = $("#entity-search-referer").val();
    var dwmc = $("#entity-search-dwmc").val(); 
	var dwdm = $('#entity-search-dwdm').val();
	
    //查找
    $.post(BASE_PATH + 'user/erp_getContactCompanyList', {
    		'referer' : referer,
	    	'dwmc':dwmc,
	    	'dwdm':dwdm,
	        'page':page
        },
        function(result){
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#add-entity-list").html(result.data.entity_list_html);
            }
            $("#entity-search-btn-contact").removeAttr('disabled');
            //renderDatetime();
            entitySetSelectedPage_contact();
        },
        'json'
    );
}

/**************end--来往单位搜索分页***********/

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
	
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var fphm = $("#entity-search-fphm").val();
	var dwmc = $("#entity-search-dwmc").val();
	
	if(-1 == order_status) { order_status =""; }
	
    $.download(BASE_PATH + 'tradeRecord/exportData', {
    	'time1':time1,
    	'time2':time2,
    	'fphm':fphm,
    	'dwmc':dwmc,
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

	//普通订单申请付款单提交前验证
	$(document).on('click', '#add-entity-create', function(event){		
			
		//$("#add-entity-create").attr('disabled', 'disabled');
	    $("#add-entity-hint").html('').fadeOut();		
		var hint_html = '';
	    
	    //var apply_no = $('#add-entity-apply_no').val(); // 申请单号
		var comp_name = $('#add-entity-comp_name').val(); // 收款单位
		//var comp_name_code = $('#add-entity-comp_name_code').val(); // 收款单位代码
		var comp_account = $('#add-entity-comp_account').val(); // 收款账号
		var bank_name = $('#add-entity-bank_name').val(); // 开户行
		var amount = $('#add-entity-apply_total_amount').val(); // 申请金额
		var bank_flag = $('#add-entity-bank_flag').val(); //同行、跨行
		//var amount_type = $('#add-entity-amount_type').val(); // 款项类别
		//var comment = $('#add-entity-comment').val(); // 备注
		//var use = $('#add-entity-use').val(); // 用途
		//var comp_name_buyer = $('#add-entity-comp_name_buyer').val(); // 下游买家
		//var comp_name_buyer_code = $('#add-entity-comp_name_buyer_code').val(); // 下游买家代码
		
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
	    if( 0 == inputArr.length ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请引用  订单！' ;
	    }
		
		if( !comp_name || '' == comp_name ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款单位！' ;
	    }
		
	    if( !comp_account || '' == comp_account ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款账号！' ;
	    }else{
	    	var comp_account_reg = /^\d{16,19}$/;
	    	if(!comp_account_reg.test(comp_account)){
	    		hint_html += (hint_html == '' ? '' : '<BR>') + '收款账号 填写有误，请检测！' ;
	    	}
	    }	    
	    
		if( !bank_name || '' == bank_name ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款开户行！' ;
	    } else {
	    	if( '' == $('#add-entity-bank_no').val() || bank_name != $('#add-entity-bank_name_checked').val() ){
		    	hint_html += (hint_html == '' ? '' : '<BR>') + '请验证  收款开户行！' ;
		    	$('#check-entity-bankName').click(); // 验证开户行
		    	/*setTimeout(function(){
		    		$('#add-entity-ref').click();
                }, 2000);*/
		    }
	    }
		
		if( !amount || '' == amount ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 申请金额！' ;
	    }
	    
	    var full_amount = $('#add-entity-full_amount').val();	    
	    //alert(full_amount + '-' + amount + '-' + ( full_amount < amount ));
	    if( parseFloat(amount) > parseFloat(full_amount) ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '总金额 ' + amount +' 不能大于订单采购总金额 ' + full_amount +'！';
	    }	    
	    
	    if( !bank_flag || '-1' == bank_flag ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请选择 同行/跨行！' ;
	    }
	    
	    calculateAmount();
	    
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        //$("#add-entity-create").removeAttr('disabled');
	        return 0;
	    }	    
	    
	    $("#pay-pwd-hint").html('').fadeOut();
	    $("#pay-pwd-modal").modal('show');	    
	    return; 
	});
	
	//申请预付款单提交前验证
	$(document).on('click', '#add-entity-create-advance', function(event){		
			
		//$("#add-entity-advance").attr('disabled', 'disabled');
	    $("#add-entity-hint").html('').fadeOut();		
		var hint_html = '';
	    
		var comp_name = $('#add-entity-comp_name').val(); // 收款单位
		var comp_name_code = $('#add-entity-comp_name_code').val(); // 收款单位代码
		var comp_account = $('#add-entity-comp_account').val(); // 收款账号
		var bank_name = $('#add-entity-bank_name').val(); // 开户行
		var amount = $('#add-entity-apply_total_amount').val(); // 申请金额
		var bank_flag = $('#add-entity-bank_flag').val(); //同行、跨行		
		var comp_name_buyer = $('#add-entity-comp_name_buyer').val(); // 下游买家
		var comp_name_buyer_code = $('#add-entity-comp_name_buyer_code').val(); // 下游买家代码
			
		if( !comp_name || '' == comp_name || !comp_name_code || '' == comp_name_code){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请选择 收款单位！' ;
	    }
		
	    if( !comp_account || '' == comp_account ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款账号！' ;
	    }else{
	    	var comp_account_reg = /^\d{16,19}$/;
	    	if(!comp_account_reg.test(comp_account)){
	    		hint_html += (hint_html == '' ? '' : '<BR>') + '收款账号有误：只能是16-19位数字，且不能包含空格！' ;
	    	}
	    }	    
	    
		if( !bank_name || '' == bank_name ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款开户行！' ;
	    } else {
	    	if( '' == $('#add-entity-bank_no').val() || bank_name != $('#add-entity-bank_name_checked').val() ){
		    	hint_html += (hint_html == '' ? '' : '<BR>') + '请验证  收款开户行！' ;
		    	$('#check-entity-bankName').click(); // 验证开户行
		    	/*setTimeout(function(){
		    		$('#add-entity-ref').click();
                }, 2000);*/
		    }
	    }
		
		if( !amount || '' == amount ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 金额！' ;
	    }else{
	    	var amount_reg = /^\d+?\.?\d*?$/;;
	    	if(!amount_reg.test(amount)){
	    		hint_html += (hint_html == '' ? '' : '<BR>') + ' 金额填写有误：只能输入正数、小数，且不能包含空格!' ;
	    	}
	    }	           
	    
		if( !comp_name_buyer || '' == comp_name_buyer || !comp_name_buyer_code || '' == comp_name_buyer_code ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请选择 下游买家！' ;
	    }	
		
	    if( !bank_flag || '-1' == bank_flag ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请选择 同行/跨行！' ;
	    }
	    	    
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        //$("#add-entity-create").removeAttr('disabled');
	        return 0;
	    }	 
	    
	    
	    $("#build_apply_order").remove();
	    //组装
	    $str = '@;';
	    var str = "@;";
	    var quote_fphm = '';
	    var quote_amount = '';	    
	    var submitInfo = quote_fphm + str + quote_amount + str + comp_name_buyer + str + comp_name_buyer_code + str + amount;
	    var inputObj = "<input id='build_apply_order"  + "' name='input_apply_orders' value='" + submitInfo + "' />";		
		$('#div_submit_info').append(inputObj);
	    
	    $("#pay-pwd-hint").html('').fadeOut();
	    $("#pay-pwd-modal").modal('show');	    
	    return; 
	});
	
	//输入支付密码后提交
	$(document).on('click', '#btn-pay-pwd', function(event){	
		
			var order_apply_type = $('#add-entity-order_apply_type').val(); //申请单类型  0普通订单付款  1预付款单 
			var pay_pwd = $('#pay-pwd').val();//支付密码
			var apply_no = $('#add-entity-apply_no').val(); // 申请单号
		    var comp_name = $('#add-entity-comp_name').val(); // 收款单位
		    var comp_name_code = $('#add-entity-comp_name_code').val(); // 收款单位
		    var comp_account = $('#add-entity-comp_account').val(); // 收款账号
		    var bank_name = $('#add-entity-bank_name').val(); // 开户行
		    var amount_type = $('#add-entity-amount_type').val(); // 款项类别
		    var use = $('#add-entity-use').val(); // 用途
		    var comment = $('#add-entity-comment').val(); // 备注
		    var bank_no = $('#add-entity-bank_no').val(); // 支付号
		    var bank_flag = $('#add-entity-bank_flag').val(); //本行/它行标志
		    var local_flag = $('#add-entity-local_flag').val(); //同城异地标志
		    var erp_fgsdm = $('#add-entity-erp_fgsdm').val(); // erp_分公司代码
		    var erp_bmdm = $('#add-entity-erp_bmdm').val(); // erp_部门代码		
			var erp_fgsmc = $('#add-entity-erp_fgsmc').val();// erp_分公司名称
			var erp_bmmc = $('#add-entity-erp_bmmc').val();// erp_部门名称
			var erp_username = $('#add-entity-erp_username').val();// erp_用户名						
			
			var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
			var order_no_arr = new Array();    
		    $.each(inputArr,function(i, obj){
		    	var order_item = $(obj).val();
		    	//普通订单申请付款单则将申请金额加上
		    	if(parseInt(order_apply_type) == 0){		    		
		    		var quote_fphm_name = obj['id'].replace("quote_fphm_", '');
		    		var apply_amount = $("#apply_amount_"+quote_fphm_name).val(); 
		    		order_item = order_item + "@;" + apply_amount;		    		
		    	}		    	
		    	order_no_arr[i] = order_item; 
	        }); 
		    		    
		    var hint_html2 = '';
		    $("#pay-pwd-hint").html(hint_html2).fadeOut();

		    if( '' == pay_pwd || 0 == pay_pwd.length ){
		    	hint_html2 += (hint_html2 == '' ? '' : '<BR>') + '请填写支付密码！' ;
		    	$("#pay-pwd-hint").html(hint_html2).fadeIn();
		    	return false;
		    }else{
		    	hint_html2 += (hint_html2 == '' ? '' : '<BR>') + '校验支付密码中...' ;
		    	$("#pay-pwd-hint").html(hint_html2).fadeIn();
		    }		    
			
		    $("#btn-pay-pwd").attr('disabled', 'disabled');
		    $.post(BASE_PATH + 'tradeRecord/create_add', {
		    	    'order_apply_type':order_apply_type,
		    		'pay_pwd':pay_pwd, 
		        	'order_no_arr':order_no_arr, 
		        	'apply_no':apply_no,
		        	'comp_name':comp_name,
		        	'comp_name_code':comp_name_code,
		        	'comp_account':comp_account,
		        	'bank_name':bank_name,
		        	'amount_type':amount_type,
		        	'use':use,
			        'comment':comment,
			        'bank_no':bank_no,
			        'bank_flag':bank_flag,
			        'local_flag':local_flag,
			        'erp_fgsdm':erp_fgsdm,
			        'erp_bmdm':erp_bmdm,
			        'erp_fgsmc':erp_fgsmc,
			        'erp_bmmc':erp_bmmc,
			        'erp_username':erp_username	      
		        },
		        function(result){
		        	if(result.code != 6000){
		        		hint_html2 += (hint_html2 == '' ? '' : '<BR>') + '支付密码校验正确！' ;
	    		    	$("#pay-pwd-hint").html(hint_html2).fadeIn();
	    		    	$("#pay-pwd-modal").modal('hide');
		        	}
		            if(result.code != 0) {	
		            	$("#btn-pay-pwd").removeAttr('disabled');	            	
		            	if(result.code == 6000){
		            		hint_html2 += (hint_html2 == '' ? '' : '<BR>') + result.msg ;
		    		    	$("#pay-pwd-hint").html(hint_html2).fadeIn();		    		    	
		            	}else{
		            		$("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();		            		
		            		$("#add-entity-create").html("提交申请");
		            		if(parseInt(order_apply_type) == 0){
		            			//$("#add-entity-create").removeAttr('disabled');
		            			$("#add-entity-create").html("提交申请");
			            	}else if(parseInt(order_apply_type) == 1){	
			            		//$("#add-entity-create-advance").removeAttr('disabled');
			            		$("#add-entity-create-advance").html("提交申请");
			            	}
		            	}		                
		            } else {
		            	if(parseInt(order_apply_type) == 0){
		            		$("#add-entity-create").attr('disabled', 'disabled');
		            		$("#add-entity-create").html("添加中...");
		            	}else if(parseInt(order_apply_type) == 1){
		            		$("#add-entity-create-advance").attr('disabled', 'disabled');
		            		$("#add-entity-create-advance").html("添加中...");
		            	}
		                $("#add-entity-hint").html(result.msg + ', 关闭...').fadeIn();
		                setTimeout(function(){
		                	$('#add-entity-cancel').click();
		                	window.location.href = $('#add-entity-cancel').attr('href');
		                }, 1000);
		            }
		        },
		        'json'
		    );
		
		}
	);
	
	// 验证开户行
	$(document).on('click', '#check-entity-bankName', function(event){
		var bankName = $('#add-entity-bank_name').val(); // 开户行
		checkBankName(bankName);
	});
	
	function checkBankName(bankName){
		if(!bankName || '' == bankName ){
			return ;
		}
		$("#span_check_failed").hide();
		$("#span_check_success").hide();
		
		if( '' != $('#add-entity-bank_no').val() && bankName == $('#add-entity-bank_name_checked').val() ) {
			$("#span_check_success").fadeIn();
    		$("#span_check_failed").fadeOut();
    		return ;
		}
		
		$('#add-entity-bank_no').val('');
		$('#add-entity-bank_name_checked').val('');
		$.post(BASE_PATH + 'tradeRecord/checkBankName', {
		        'bankName':bankName
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#ref-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            } else {
//	            	kk(result);
	            	if(result.data){
	            		$("#span_check_success").fadeIn();
	            		$("#span_check_failed").fadeOut();
	            		$('#add-entity-bank_no').val(result.data['bankNo']);
	            		$('#add-entity-bank_name_checked').val(result.data['bankName']);
	            	} else {
	            		$("#span_check_success").fadeOut();
	            		$("#span_check_failed").fadeIn();
	            		$('#add-entity-bank_no').val('');
	            		$('#add-entity-bank_name_checked').val('');
	            	}
	            }
	        },
	        'json'
	    );
	}
	
	// 验证收款单位 
	$(document).on('click', '#check-entity-comp_name', function(event){
		var dwdm = $('#add-entity-comp_name_code').val(); // 收款单位代码
		checkCompName(dwdm);
	});
	
	function checkCompName(dwdm){
		if(!dwdm || '' == dwdm ){
			return false;
		}
		$("#span_check_comp_failed").hide();
		$("#span_check_comp_success").hide();
	
		if( '' == $('#add-entity-comp_name_code').val()) {
			$("#span_check_comp_success").fadeIn();
    		$("#span_check_comp_failed").fadeOut();
    		return false;
		}				
		//$('#add-entity-comp_name_checked').val('');
		$.post(BASE_PATH + 'user/erp_getContactCompanyInfo', {
		        'dwdm':dwdm
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#ref-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            } else {            	    
	            	if(result.data){
	            		$("#span_check_comp_success").fadeIn();
	            		$("#span_check_comp_failed").fadeOut();
	            		//$('#add-entity-comp_name_code').val(result.data['']);
	            		//$('#add-entity-comp_name_checked').val(result.data['']);
	            		return true;
	            	} else {
	            		$("#span_check_comp_success").fadeOut();
	            		$("#span_check_comp_failed").fadeIn();
	            		$('#add-entity-comp_name_code').val('');
	            		$('#add-entity-comp_name').val('');
	            		//$('#add-entity-comp_name_checked').val('');
	            		return false;
	            	}
	            }
	        },
	        'json'
	    );
	}
	
	// 验证下游买家
	$(document).on('click', '#check-entity-comp_name_buyer', function(event){
		var dwdm = $('#advance-comp_name_buyer_code').val(); // 预付款下游买家
		checkCompNameBuyer(dwdm);
	});
	
	function checkCompNameBuyer(dwdm){
		if(!dwdm || '' == dwdm ){
			return false;
		}
		$("#span_check_buyer_failed").hide();
		$("#span_check_buyer_success").hide();
		
		if( '' == $('#add-entity-comp_name_buyer_code').val()) {
			$("#span_check_buyer_success").fadeIn();
    		$("#span_check_buyer_failed").fadeOut();
    		return false;
		}
				
		//$('#add-entity-comp_name_checked').val('');
		$.post(BASE_PATH + 'user/erp_getContactCompanyInfo', {
		        'dwdm':dwdm
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#ref-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            } else {            	   
	            	if(result.data){
	            		$("#span_check_buyer_success").fadeIn();
	            		$("#span_check_buyer_failed").fadeOut();
	            		//$('#add-entity-comp_name_buyer').val(result.data['']);
	            		//$('#add-entity-comp_name_buyer_code').val(result.data['']);
	            		return true;
	            	} else {
	            		$("#span_check_buyer_success").fadeOut();
	            		$("#span_check_buyer_failed").fadeIn();
	            		$('#add-entity-comp_name_buyer').val('');
	            		$('#add-entity-comp_name_buyer_code').val('');
	            		return false;
	            	}
	            }
	        },
	        'json'
	    );
	}

	//引订单按钮
	$(document).on('click', '#add-entity-quote', function(event){
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});
			
		$('#btn-add-entity').show();
		$('#btn-add-entity').unbind("click");
	
//		var title = $('#add-entity-quote').text();
//		$('#info_entity_title').html(title);
		
		$("#add-entity-hint").html('').fadeOut();
		
		/*clear_entity_field();
		
		$("#btn-add-entity").removeAttr('disabled');
	    $("#btn-add-entity").html("确定");
	    
		$('#btn-add-entity').on('click',function(event){
			add_entity();
		});*/
		
		loadErpSellOrderList();
		//loadErpOrderBuyList();
		
	});
	
	//erp销售订单
	function loadErpSellOrderList(){
		var page = $("#entity-current-page").html();
		var comp_name_code = $("#add-entity-comp_name_code").val();
		var comp_name = $('#add-entity-comp_name').val();
		var time1 = $('#entity-search-time1').val();
		var time2 = $('#entity-search-time2').val();
		
		$('#entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
		
		//查找
	    $.post(BASE_PATH + 'tradeRecord/erp_getSellOrderList', {
	    		'dwmc':comp_name,
	    		'dwdm':comp_name_code,
	    		'time1':time1,
	    		'time2':time2,
		        'page':page
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#add-entity-list").html(result.data.entity_list_html);
	                renderDatetime();
	                entitySetSelectedPage();
	            }
	        },
	        'json'
	    );
	}

	/*//采购订单
	function loadErpOrderBuyList(){
		var page = $("#entity-current-page").html();
		var dwmc = $("#entity-search-dwmc").html();
		var comp_name = $('#info-entity-comp_name').val();
		var time1 = $('#entity-search-time1').val();
		var time2 = $('#entity-search-time2').val();
		
		$('#entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
		
		//查找
	    $.post(BASE_PATH + 'tradeRecord/erp_getOrderBuy', {
	    		'comp_name':comp_name,
	    		'dwmc':dwmc,
	    		'time1':time1,
	    		'time2':time2,
		        'page':page
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#add-entity-list").html(result.data.entity_list_html);
	                renderDatetime();
	                entitySetSelectedPage();
	            }
	        },
	        'json'
	    );
	}*/
	
	function renderDatetime(){
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
	}
	
	$(document).on('click', '.entity-search-select', function(event){

		var fphm = $(this).attr("id").replace('entity-search-select-', '');		
		//查找
	    $.post(BASE_PATH + 'tradeRecord/erp_getSellOrderInfo', {
	    		'fphm':fphm
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	            	$("#add-entity-modal").modal('hide');
	            	filledSellOrderInfo(result.data);	                
	            }
	        },
	        'json'
	    );
	});
	
	//引订单填充数据
	function filledSellOrderInfo(data){
		
		var orderHeader = data.Header;
		var orderDetails = data.Details[0];
		var quote_comp_name_code = orderHeader['dwdm_3']; // 订单的收款单位代码
		var quote_comp_name= orderHeader['dwmc_3']; // 订单收款单位名称
		var comp_name_code = $('#add-entity-comp_name_code').val(); // 收款单位代码
		var comp_name = $('#add-entity-comp_name').val(); // 收款单位名称
		
		//判断单位是否不一致
		if(comp_name_code != '' && quote_comp_name_code != comp_name_code){
			$("#ref-entity-hint").html('收款单位不一致，请引用同一收款单位的单据！').fadeIn();
			return false;
		}
		if(comp_name_code == ''){
			$('#add-entity-comp_name_code').val(quote_comp_name_code);
		}
		if(comp_name == ''){
			$('#add-entity-comp_name').val(quote_comp_name);
		}else{
			getOrgNameInfo($('#add-entity-comp_name').val()); // 根据往来单位，查询付款账户信息。
		}
		
		//填充列表
		filledSellOrderList(orderHeader, orderDetails);
			
	}
		
	/**************str--引订单填充订单列表****************/
	function filledSellOrderList(orderHeader, orderDetails){
		
		//var fphm = $('#add-entity-fphm').val(); // 业务单号-发票号码
		var quote_fphm = orderHeader['fphm_']; // 业务单号-发票号码
		var quote_amount = orderDetails['js_cgje']; // 申请金额
		var quote_comp_name_buyer = orderHeader['string7_']; // 下游买家
		var quote_comp_name_buyer_code = orderHeader['string8_']; // 下游买家代码
		if(quote_comp_name_buyer == 'null' || quote_comp_name_buyer == null ){
			quote_comp_name_buyer = '';
		}
		if(quote_comp_name_buyer_code == 'null'  || quote_comp_name_buyer_code == null){
			quote_comp_name_buyer_code = '';
		}
		
		// 判断重复引用
		var v_fphm = 'quote_fphm_' + quote_fphm;
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");		
		$("#ref-entity-hint").html('').fadeOut();
		var is_repeat = false;	
		if(inputArr){			
			jQuery.each(inputArr, function(i, valObj) {  
			    if( v_fphm == valObj['id']){
			    	$("#ref-entity-hint").html('不能重复引用同一个单据！').fadeIn();
			    	is_repeat = true;			    	
			    }
			});			
		}
		if(is_repeat){
			return false;
		}
		
		var str = "@;";
	    var submitInfo = quote_fphm + str + quote_amount + str + quote_comp_name_buyer + str + quote_comp_name_buyer_code;
	    var inputObj = "<input id='quote_fphm_" + quote_fphm + "' name='input_apply_orders' value='" + submitInfo + "' />";
		var trObj = ""
				+ '<tr>'
				+ '	<td>' + quote_fphm + '</td>'
				+ '	<td>' + quote_amount + '</td>'
				+ '	<td>'
					+ '<input id="quote_amount_' + quote_fphm + '" type="hidden" value=\''+ quote_amount + '\' />' 
					+ '<input id="apply_amount_' + quote_fphm + '" name=\'input_apply_amount\' type="text" class="form-control" value=\''+ quote_amount + '\' style="width:95px;" />' 
					+ '<span id="msg_' + quote_fphm + '" style="color:red;"></span>'
				+ '</td>'
				+ '	<td>' + quote_comp_name_buyer + '</td>'				
				+ '	<td><a id="tr-delete-btn" href="#">删除</a></td>'
				+ '</tr>'
				;
		$('#data-list-table').append(trObj);
		$('#div_submit_info').append(inputObj);
		
		calculateAmount();
	}	
	/**************end--填充订单列表****************/
	
	/**************str--删除****************/
	$(document).on('click', '#tr-delete-btn', function(event){
		var trObj = $(this).parent().parent();
		var fphm = trObj.children().get(0).textContent;
		$('#quote_fphm_' + fphm).remove();
		trObj.remove();
		
		calculateAmount();
		
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
//		alert(inputArr.length);
		if( 0== inputArr.length ){
			$('#add-entity-comp_name').val('');
			$('#add-entity-comp_name_code').val('');			
			$('#add-entity-apply_total_amount').val('');
			
			$("#add-entity-comp_name").removeAttr('disabled'); // 收款单位
			//$("#add-entity-comp_account").removeAttr('disabled'); // 收款账号
			//$('#add-entity-bank_name').removeAttr('disabled'); // 开户行
			//$('#add-entity-amount_type').removeAttr('disabled'); // 款项类别
			//$('#add-entity-comment').removeAttr('disabled'); // 备注
			//$('#add-entity-use').removeAttr('disabled'); // 用途
			//$('#add-entity-bank_flag').removeAttr('disabled'); // 同行/跨行
			//$('#add-entity-local_flag').removeAttr('disabled'); // 同城/异地
		}
		
	});
	/**************end--删除****************/
	
	/*// 判断订单重复引用
	function fphmIsRepeat(quote_fphm){
		
		var quote_fphm = $('#add-entity-fphm').val(); // 业务单号-发票号码
		var v_fphm = 'quote_fphm_' + quote_fphm;
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");		
		$("#ref-entity-hint").html('').fadeOut();
		var is_repeat = false;	
		if(inputArr){			
			jQuery.each(inputArr, function(i, valObj) {  
			    if( v_fphm == valObj['id']){
			    	$("#ref-entity-hint").html('不能重复引用同一个单据！').fadeIn();
			    	is_repeat = true;
			    	return is_repeat;
			    }
			});			
		}
		return is_repeat;
	}*/
	
	//申请金额文本框输入事件
	$(document).on('keyup', "input[name='input_apply_amount']", function(event){
		calculateAmount();
	});
	
	//计算申请总金额及采购总金额
	function calculateAmount(){
		var apply_amount_total = 0;
		var quote_amount_total  = 0;
		var inputArr = $("input[name='input_apply_amount']");
		if(inputArr){	
			var is_true = true;
			jQuery.each(inputArr, function(i, valObj) {	
				var apply_amount_id = valObj['id'];	
				var quote_amount_id = 'quote_amount_' + apply_amount_id.replace('apply_amount_', '');
				$('#'+apply_amount_id).next('span').html('').fadeOut();				
				amount = parseFloat($('#'+apply_amount_id).val());
				js_cgje = parseFloat($('#'+quote_amount_id).val());
				var reg = /^\d+?\.?\d*?$/;
				if(!reg.test(amount)){
					$('#'+apply_amount_id).next('span').html('金额必须输入，且只能输入正数、小数，不能包含空格!').fadeIn();
					$('#'+apply_amount_id).focus();
					is_true = false;
				}else if(amount <= 0){
					$('#'+apply_amount_id).next('span').html('金额不能小于或等于0!').fadeIn();
					$('#'+apply_amount_id).focus();
					is_true = false;
				}else if(amount > js_cgje){					
					$('#'+apply_amount_id).next('span').html('填写金额 ' + amount +' 不能大于订单采购金额 ' + js_cgje +'!').fadeIn();
					$('#'+apply_amount_id).focus();
					is_true = false;
				}				
				if(is_true){ 
					apply_amount_total = apply_amount_total + amount;
					quote_amount_total = quote_amount_total + js_cgje;
				}
			});
			if(!is_true){
				$('#add-entity-apply_total_amount').val('');				
				return false;
			}
			if(apply_amount_total > 0){
				$('#add-entity-apply_total_amount').val((Math.round(apply_amount_total*100)/100));
			}else{
				$('#add-entity-apply_total_amount').val('');
			}
			if(quote_amount_total > 0){
				$('#add-entity-full_amount').val(quote_amount_total);
			}else{
				$('#add-entity-full_amount').val('');
			}
			return true;
		}
		return false;
	}
	
	function getOrgNameInfo(dwmc){
		$.post(BASE_PATH + 'tradeRecord/erp_getOrgNameInfo', {
		        'dwmc':dwmc
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#ref-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            } else {
//	            	kk(result.data[0]);
	            	if(result.data){ // undefined
//	            		$("#ref-entity-hint").html(result.msg + '(' + result.data + ')').fadeIn();
	            		renderBankSelectLi(result.data);
//	            	} else {
	            	}
	            }
	        },
	        'json'
	    );
	}
	
	$('#add-entity-bank_name').bind('focus',function(event){
		var bankObj = $("#div_data_bank li");
		if(0 ==  bankObj.length){
			return false;
		}
		if('readonly' != $('#add-entity-bank_name').attr('readonly') ){
			$("#div_data_bank").fadeIn();
		}
	});
	
	$('#add-entity-bank_name').bind('blur',function(event){
		$('#check-entity-bankName').click(); // 验证开户行
		$("#div_data_bank").fadeOut();
	});
	
	$('#div_data_bank').bind('mouseout',function(event){
		$("#div_data_bank").fadeOut();
	});
	
	var bank_account_list = new Array();
	var city_name_list = new Array();
	var bank_name_list = new Array();
	function renderBankSelectLi(dataList){
		var width = $("#add-entity-bank_name").css('width');
		$("#div_data_bank").css("width",width);
		$("#div_data_bank").fadeOut();
		
		bank_account_list = new Array(); // 清空数据
		var objUl = $("#div_data_bank ul");
		objUl.html('');
		for(var j=0;j<dataList.length;j++){
//			alert(dataList[j]['khh']);
			if( !dataList[j]['khh'] || '' == dataList[j]['khh'] ){
				continue;
			}
			bank_account_list.push(dataList[j]['zh']);
			city_name_list.push(dataList[j]['cityname']);
			bank_name_list.push(dataList[j]['bankname']);
			var li = "<li>" + dataList[j]['khh'] + "</li>";
			objUl.append(li);
		}
		
		var bankObj = $("#div_data_bank li");
		if(0 ==  bankObj.length){
			return false;
		}
		bankObj.each(function(i,e){
			bankObj[i].style.cursor="pointer";
			e.onmouseover = function(e){
				bankObj[i].style.backgroundColor="#3399FF";
			};
			e.onmouseout = function(e){
				bankObj[i].style.backgroundColor="white";
			};
			e.onclick = function(e){
				if('readonly' != $("#add-entity-bank_name").attr('readonly') ){
					var selVal = $(bankObj[i]).text();
					$("#add-entity-bank_name").val(selVal);
					if(bank_account_list[i]){
						$("#add-entity-comp_account").val(bank_account_list[i]);
						//setBankDomain(i);
					}
					$('#check-entity-bankName').click(); // 验证开户行
				}
				$("#div_data_bank").fadeOut();
			};
		});
	}
	
	function setBankDomain(index){
		if('2' == $('#add-entity-record_bank_type').val() ){
			if( '浦发银行' == bank_name_list[index] ){
				$('#add-entity-bank_flag').val('0'); // 0-同行 1-跨行
				$('#add-entity-bank_flag').change();
			} else {
				$('#add-entity-bank_flag').val('1'); // 0-同行 1-跨行
				$('#add-entity-bank_flag').change();
				/*if( '长沙' == city_name_list[index] ){
					$('#add-entity-local_flag').val('0'); // 0-同城 1-异地
				} else {
					$('#add-entity-local_flag').val('1'); // 0-同城 1-异地
				}*/
			}
		}
	}
	
	/*// 同行/跨行
	$('#add-entity-bank_flag').on('change',function(event){
		if('1' == $('#add-entity-bank_flag').val() ){ // 0-同行 1-跨行
			$('#span_local_flag').css("display","block");
		} else {
			$('#span_local_flag').css("display","none");
		}
	});*/
	
	/* ---------查行名列表start----------*/
	$(document).on('click', '#btn-bank_name', function(event){
		
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});
		
		$('#add-entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
		//查找
	    $.post(BASE_PATH + 'spdInternetBank/getApplyIndex', {
	        },
	        function(result){        	
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#add-entity-list").html(result.data.entity_list_html);
	                //renderDatetime();
	                //entitySetSelectedPage();
	            }
	        },
	        'json'
	    );
	});
	/* ---------查行名列表end----------*/
	
	/* ---------查来往单位列表start----------*/
	$(document).on('click', '#btn-comp_name', function(event){
		erp_getContactCompanyList(1); //收款单位		
	});
	
	$(document).on('click', '#btn-comp_name_buyer', function(event){
		erp_getContactCompanyList(2); //下游买家
	});
	
	function erp_getContactCompanyList(referer){
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});		
		
		$('#add-entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
		//查找
	    $.post(BASE_PATH + 'user/erp_getContactCompanyList', {
	    		'referer' : referer
	        },
	        function(result){        	
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#add-entity-list").html(result.data.entity_list_html);
	                //renderDatetime();
	                entitySetSelectedPage_contact();
	            }
	        },
	        'json'
	    );
	}
	
	$(document).on('click', '.entity-select-comp', function(event){
		var dwdm = $(this).attr("id").replace('entity-select-dwdm-', '');		
		var dwmc_id = 'entity-select-dwmc-' + dwdm.toString();		
		if($("#"+dwmc_id).length > 0){			
			$("#add-entity-comp_name_code").val(dwdm);
			$("#add-entity-comp_name").val($("#"+dwmc_id).val());			
			$("#add-entity-modal").modal('hide');
		}
		
	});	
	
	$(document).on('click', '.entity-select-buyer', function(event){
		var dwdm = $(this).attr("id").replace('entity-select-dwdm-', '');		
		var dwmc_id = 'entity-select-dwmc-' + dwdm.toString();		
		if($("#"+dwmc_id).length > 0){
			$("#add-entity-comp_name_buyer_code").val(dwdm);
			$("#add-entity-comp_name_buyer").val($("#"+dwmc_id).val());
			$("#advance-comp_name_buyer").val($("#"+dwmc_id).val());
			$("#add-entity-modal").modal('hide');
		}
		
	});	
	/* ---------查来往单位列表end----------*/
	
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

