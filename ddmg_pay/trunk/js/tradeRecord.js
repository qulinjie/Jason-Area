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
function add_entity(){
	$("#btn-add-entity").attr('disabled', 'disabled');
    $("#add-entity-hint").html('').fadeOut();
    
    var code = $("#add-entity-code").val();
    var type = $("input[name='add-entity-type-rdo'][type='radio']:checked").val();
    var active_count = $("#add-entity-active_count").val();
    var time_start = $("#add-entity-time_start").val();
	var time_end = $("#add-entity-time_end").val();
	var comment = $("#add-entity-comment").val();
    
    var hint_html = '';
    if( 1 == type ){ // 按次数
    	if('' == active_count || 0 >= active_count){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 正确的可用次数！' ;
        }
    } else if( 2 == type ){ // 按时间
    	if('' == time_start ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 有效开始时间 ！' ;
        }
    	if('' == time_end ){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 有效结束时间！' ;
        }
    } else {
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 正确的使用方式！' ;
    }
    
    if(hint_html != ''){
        $("#add-entity-hint").html(hint_html).fadeIn();
        $("#btn-add-entity").removeAttr('disabled');
        return 0;
    }
    
    $("#btn-add-entity").html("添加中...");
    $.post(BASE_PATH + 'authorizationCode/create', {
        	'code':code, 
        	'type':type,
        	'active_count':active_count,
	        'time_start':time_start,
	        'time_end':time_end,
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
	
	getAuthorizationCode();
	
	$("#btn-add-entity").removeAttr('disabled');
    $("#btn-add-entity").html("确定");
    
	$('#btn-add-entity').on('click',function(event){
		add_entity();
	});
	
	$('#add-entity-type-count').on('click',function(event){
		change_type()
	});
	$('#add-entity-type-time').on('click',function(event){
		change_type()
	});
});

function clear_entity_field(){
	$('#info-entity-id').val('');
	
	$('#info-entity-code').html('');
	$('#add-entity-code').val('');
	$('#add-entity-active_count').val('');
	$('#add-entity-time_start').val('');
	$('#add-entity-time_end').val('');
	$('#add-entity-comment').val('');
}

function getAuthorizationCode(){
	$.post(BASE_PATH + 'authorizationCode/getCode', {},
	    function(result){
	        if(result.code != 0) {
	            $("#add-entity-hint").html(result.msg + '(' + result.code + ')' + ',请刷新页面').fadeIn();
	        }else {
	        	var code = result.data;
	        	$('#info-entity-code').html(code);
	        	$('#add-entity-code').val(code);
	        }
	    },
	    'json'
	);
}

function change_type(){
	var type = $("input[name='add-entity-type-rdo'][type='radio']:checked").val();
	if( 1== type){
		$('#div-active-count').css('display', 'block');
		
		$("#div-time-start").css('display', 'none');
		$("#div-time-end").css('display', 'none');
	} else {
		$('#div-active-count').css('display', 'none');
		
		$("#div-time-start").css('display', 'block');
		$("#div-time-end").css('display', 'block');
	}
}

/**************end--付款****************/

/**************start--查看****************/
$(document).on('click', '#entity-infoDisplay-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	$('#add-entity-modal').modal('show');
	$.post(BASE_PATH + 'tradeRecord/info', {
			'id':id
        },
        function(result){
            if(result.code != 0) {
                $("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
            	//$("#entity-list").html(result.data.entity_list_html);
            }
            $("#entity-search-btn").removeAttr('disabled');
        },
        'json'
    );

});

/**************end--查看****************/



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

