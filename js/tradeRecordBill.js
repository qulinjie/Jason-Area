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
	setLiTabs(2);
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
	
	if(-1 == order_status) { order_status =""; }
	
	var spanLoading = $('#span-trade-order-list');
	spanLoading.html("<div style='width:100%;text-align:center;height:" + spanLoading.height() + "px;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	
    //查找
    $.post(BASE_PATH + 'tradeRecord/searchListBill', {
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

/**************start--确认发货****************/
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
/**************end--确认发货****************/


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

/**************start--登记实发****************/

$(document).on('click', '#add-check-new', function(event){
	stopPropagation = true;
	
	$("#info-check-hint").html('').hide();
	$('#info-check-modal').modal('show');
	$('#info-check-modal').modal({keyboard: false});
	
	$('#btn-add-check').show();
	$('#btn-add-check').unbind("click");

	$("#btn-add-check").removeAttr('disabled');
    $("#btn-add-check").html("确定");
    
	$('#btn-add-check').on('click',function(event){
		add_check();
	});
	
	$('#info-check-trade').html("<div style='width:100%;text-align:center;'><img id='img-loging-data' alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	
	var id =  $(this).parent().parent().parent().children().first().text();
	$.post(BASE_PATH + 'tradeRecord/getInfoCheck', {'id':id},
	    function(result){
	        if(result.code != 0) {
	            $("#add-check-hint").html(result.msg + '(' + result.code + ')' + ',请刷新页面').fadeIn();
	        } else {
	        	$('#info-check-trade').html(result.data.tradeRecord_check);
	        }
	    },
	    'json'
	);
	
});

function add_check(){
	$("#btn-add-check").attr('disabled', 'disabled');
    $("#info-check-hint").html('').fadeOut();
    
    var id = $("#info-check-id").val();
    
    var hint_html = '';
    var data = '';
    
    $('#registerNet tr').each(function(){
    	var number = $(this).find('input:first').val();
    	var weight = $(this).find('input:last').val();
    	if(number === undefined || weight === undefined){
    		return true;
    	}else if(number == '' || weight == ''){
    		hint_html =  hint_html ? hint_html : '请填写实发信息！' ;
    	}else{
//    		if(!isNaN(number)){
//    			hint_html =  hint_html ? hint_html : '请填写正确的实发数量！' ;
//    		}else{
    			data += $(this).find('td:first').text() + '_' + number+'_'+weight+';';  
    		//}    		
    	}
    });
    
    if(hint_html != ''){
        $("#info-check-hint").html(hint_html).fadeIn();
        $("#btn-add-check").removeAttr('disabled');
        return 0;
    }
    
    $("#btn-add-check").html("正在提交...");
    $.post(BASE_PATH + 'tradeRecord/registerNet', {
        	'data':data
        },
        function(result){
            if(result.code != 0) {
                $("#info-check-hint").html(result.msg + '(' + result.code + ')').fadeIn();
                $("#btn-add-check").removeAttr('disabled');
                //$("#btn-add-check").html("确定支付");
            }else {
                $("#info-pay-hint").html(result.msg + ', 关闭...').fadeIn();
                setTimeout(function(){
                    $("#info-check-modal").modal('hide');
                    $("#btn-add-check").removeAttr('disabled');
                    //$("#btn-add-check").html("确定支付");
                }, 500);
            }
        },
        'json'
    );
    
}

/**************end--登记实发****************/

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

