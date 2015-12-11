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
	
    //查找
    $.post(BASE_PATH + 'tradeRecord/searchList', {
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
                }, 1000);
            }
        },
        'json'
    );
    
}

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
	
	$('#info-pay-trade').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	
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


/**************end--付款****************/

$("td[name='td-operation-name']").each(function(i,e){
	$(e).unbind("click");
});

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

