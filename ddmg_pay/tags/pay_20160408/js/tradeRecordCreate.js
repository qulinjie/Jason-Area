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
	
    var fphm = $("#entity-search-fphm").val();
    var dwmc = $("#entity-search-dwmc").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var comp_name = $('#info-entity-comp_name').val();
	
    //查找
    $.post(BASE_PATH + 'tradeRecord/erp_getOrderBuy', {
	    	'fphm':fphm, 
	    	'dwmc':dwmc,
	    	'comp_name':comp_name,
	    	'time1':time1,
	    	'time2':time2,
	        'page':page
        },
        function(result){
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#entity-list").html(result.data.entity_list_html);
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

	$(document).on('click', '#add-entity-create', function(event){		
				
		//$("#add-entity-create").attr('disabled', 'disabled');
	    $("#add-entity-hint").html('').fadeOut();
	    
	    var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
//		alert(inputArr.length);
		
		var hint_html = '';
	    if( 0 == inputArr.length ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 单据！' ;
	    }
		
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        //$("#add-entity-create").removeAttr('disabled');
	        return 0;
	    }
	    
	    $("#pay-pwd-modal").modal('show');	    
	    return; 
	});
	
	$(document).on('click', '#btn-pay-pwd', function(event){			
		
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
		    var erp_fgsdm = $('#add-entity-erp_fgsdm').val();
		    var erp_bmdm = $('#add-entity-erp_bmdm').val(); // erp_部门代码		
			var erp_fgsmc = $('#add-entity-erp_fgsmc').val();// erp_分公司名称
			var erp_bmmc = $('#add-entity-erp_bmmc').val();// erp_部门名称
			var erp_username = $('#add-entity-erp_username').val();// erp_用户名
			
			var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
			var order_no_arr = new Array();    
		    $.each(inputArr,function(i,obj){
		    	order_no_arr[i] =   $(obj).val();
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
		            		//$("#add-entity-create").removeAttr('disabled');
		            		$("#add-entity-create").html("提交申请");
		            	}		                
		            } else {		            	 
		            	$("#add-entity-create").attr('disabled', 'disabled');
		            	$("#add-entity-create").html("添加中...");
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

	$(document).on('click', '#add-entity-new', function(event){
		$('#add-entity-modal').modal('show');
		$('#add-entity-modal').modal({keyboard: false});
			
		$('#btn-add-entity').show();
		$('#btn-add-entity').unbind("click");
	
		var title = $('#add-entity-new').text();
		$('#info_entity_title').html(title);
		
		$("#add-entity-hint").html('').fadeOut();
		
		/*clear_entity_field();
		
		$("#btn-add-entity").removeAttr('disabled');
	    $("#btn-add-entity").html("确定");
	    
		$('#btn-add-entity').on('click',function(event){
			add_entity();
		});*/
	
		loadErpOrderBuyList();
	});

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
	                $("#entity-list").html(result.data.entity_list_html);
	                renderDatetime();
	                entitySetSelectedPage();
	            }
	        },
	        'json'
	    );
	}
	
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
//		var fphm =  $(this).text();
//		alert(fphm);
//		return false;
		var fphm = $(this).attr("id").replace('entity-search-select-', '');
		
		//查找
	    $.post(BASE_PATH + 'tradeRecord/erp_getOrderBuyInfo', {
	    		'fphm':fphm
	        },
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	            	filledOrderBuyInfo(result.data);
	                $("#add-entity-modal").modal('hide');
	            }
	        },
	        'json'
	    );
	});
	
	function filledOrderBuyInfo(data){
		var orderHeader = data.OrderHeader;
		var orderDetails = data.OrderDetails[0];
		
		$('#add-entity-amount').val(''); // 单据原始金额
		$('#add-entity-full_amount').val(''); // 单据原始金额
		
		$('#add-entity-fphm').val(orderHeader['fphm_']); // 业务单号-发票号码 
		$('#add-entity-comp_name').val(orderHeader['dwmc_']); // 收款单位
		$('#add-entity-comp_name_code').val(orderHeader['dwdm_']); // 收款单位代码
		$('#add-entity-amount').val(orderHeader['_cgddje']); // 金额
		$('#add-entity-full_amount').val(orderHeader['_cgddje']); // 金额
		$('#add-entity-comp_name_buyer').val(orderDetails['string7_']); // 下游买家
		$('#add-entity-comp_name_buyer_code').val(orderDetails['string8_']); //下游买家代码
		
		getOrgNameInfo($('#add-entity-comp_name').val()); // 根据往来单位，查询付款账户信息。
	}
	
	
	
	/**************str--删除****************/
	$(document).on('click', '#tr-delete-btn', function(event){
		var trObj = $(this).parent().parent();
		var fphm = trObj.children().get(0).textContent;
		$('#V_' + fphm).remove();
		trObj.remove();
		
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
//		alert(inputArr.length);
		if( 0== inputArr.length ){
			$('#info-entity-comp_name').val('');
			
			$("#add-entity-comp_name").removeAttr('disabled'); // 收款单位
			$("#add-entity-comp_account").removeAttr('disabled'); // 收款账号
			$('#add-entity-bank_name').removeAttr('disabled'); // 开户行
			$('#add-entity-amount_type').removeAttr('disabled'); // 款项类别
			$('#add-entity-comment').removeAttr('disabled'); // 备注
			$('#add-entity-use').removeAttr('disabled'); // 用途
			$('#add-entity-bank_flag').removeAttr('disabled'); // 同行/跨行
			$('#add-entity-local_flag').removeAttr('disabled'); // 同城/异地
		}
		
	});
	/**************end--删除****************/

	/**************str--引用****************/
	$(document).on('click', '#add-entity-ref', function(event){
		$("#ref-entity-hint").html('').fadeOut();
		
		var fphm = $('#add-entity-fphm').val(); // 业务单号-发票号码
		
		// 判断重复引用
		var v_fphm = 'V_' + fphm;
		var inputArr = $('#div_submit_info').children("input[name='input_apply_orders']");
		$("#ref-entity-hint").html(hint_html).fadeOut();
		var is_repeat = false;
		jQuery.each(inputArr, function(i, valObj) {  
		    if( v_fphm == valObj['id']){
		    	$("#ref-entity-hint").html('不能重复引用同一个单据！').fadeIn();
		    	is_repeat = true;
		    }
		});
		if(is_repeat) { return false; }
		
		var apply_no = $('#add-entity-apply_no').val(); // 申请单号
		var comp_name = $('#add-entity-comp_name').val(); // 收款单位
		var comp_name_code = $('#add-entity-comp_name_code').val(); // 收款单位代码
		var comp_account = $('#add-entity-comp_account').val(); // 收款账号
		var bank_name = $('#add-entity-bank_name').val(); // 开户行
		var amount = $('#add-entity-amount').val(); // 申请金额
		var amount_type = $('#add-entity-amount_type').val(); // 款项类别
		var comment = $('#add-entity-comment').val(); // 备注
		var use = $('#add-entity-use').val(); // 用途
		var comp_name_buyer = $('#add-entity-comp_name_buyer').val(); // 下游买家
		var comp_name_buyer_code = $('#add-entity-comp_name_buyer_code').val(); // 下游买家代码
		
		var hint_html = '';
	    if( !amount || '' == amount ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 金额！' ;
	    }
	    
	    var full_amount = $('#add-entity-full_amount').val();
//	    alert(full_amount + '-' + amount + '-' + ( full_amount < amount ));
	    if( parseFloat(amount) > parseFloat(full_amount) ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '填写金额 ' + amount +' 不能大于单据金额 ' + full_amount +'！';
	    }
	    
	    if( !comp_account || '' == comp_account ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 收款账号！' ;
	    }
	    if( !bank_name || '' == bank_name ){
	    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 开户行！' ;
	    } else {
	    	if( '' == $('#add-entity-bank_no').val() || bank_name != $('#add-entity-bank_name_checked').val() ){
		    	hint_html += (hint_html == '' ? '' : '<BR>') + '请验证 开户行！' ;
		    	$('#check-entity-bankName').click(); // 验证开户行
		    	/*setTimeout(function(){
		    		$('#add-entity-ref').click();
                }, 2000);*/
		    }
	    }
	    
	    if(hint_html != ''){
	        $("#ref-entity-hint").html(hint_html).fadeIn();
	        return false;
	    }
	    
	    var str = "@;"
	    var submitInfo = fphm + str + amount + str + comp_name_buyer + str + comp_name_buyer_code + str + comment;
	    var inputObj = "<input id='V_" + fphm + "' name='input_apply_orders' value='" + submitInfo + "' />";
	    
		var trObj = ""
				+ '<tr>'
				+ '	<td>' + fphm + '</td>'
				+ '	<td>' + amount + '</td>'
				+ '	<td>' + comp_name_buyer + '</td>'
				+ '	<td>' + comment + '</td>'
				+ '	<td><a id="tr-delete-btn" href="#">删除</a></td>'
				+ '</tr>'
				;
	    
		$('#data-list-table').append(trObj);
		$('#div_submit_info').append(inputObj);
		
		if('' == $('#info-entity-comp_name').val()){
			$('#info-entity-comp_name').val(comp_name);
		}
		$('#add-entity-amount').val(''); // 申请金额
		
		$("#add-entity-comp_name").attr('disabled', 'disabled'); // 收款单位
		$("#add-entity-comp_account").attr('disabled', 'disabled'); // 收款账号
		$('#add-entity-bank_name').attr('disabled', 'disabled'); // 开户行
		$('#add-entity-amount_type').attr('disabled', 'disabled'); // 款项类别
		$('#add-entity-comment').attr('disabled', 'disabled'); // 备注
		$('#add-entity-use').attr('disabled', 'disabled'); // 用途
		$('#add-entity-bank_flag').attr('disabled', 'disabled'); // 同行/跨行
		$('#add-entity-local_flag').attr('disabled', 'disabled'); // 同城/异地
		
	});
	/**************end--引用****************/
	
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
						setBankDomain(i);
					}
					$('#check-entity-bankName').click(); // 验证开户行
				}
				$("#div_data_bank").fadeOut();
			};
		});
	}
	
	function setBankDomain(index){
//		alert(index);
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
//		alert(index);
	}
	
	/*// 同行/跨行
	$('#add-entity-bank_flag').on('change',function(event){
		if('1' == $('#add-entity-bank_flag').val() ){ // 0-同行 1-跨行
			$('#span_local_flag').css("display","block");
		} else {
			$('#span_local_flag').css("display","none");
		}
	});*/
	
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
