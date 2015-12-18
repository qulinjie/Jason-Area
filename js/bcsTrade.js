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
	
    var FMS_TRANS_NO = $("#entity-search-FMS_TRANS_NO").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var order_no = $("#entity-search-order_no").val();
	var seller_name = $("#entity-search-seller_name").val();
	var status = $("#entity-search-status").val();
	var amount1 = $("#entity-search-amount1").val();
	var amount2 = $("#entity-search-amount2").val();
	
	if(-1 == status) { status =""; }
	
    //查找
    $.post(BASE_PATH + 'bcsTrade/searchList', {
	    	'FMS_TRANS_NO':FMS_TRANS_NO, 
	    	'time1':time1,
	    	'time2':time2,
	    	'order_no':order_no,
	    	'seller_name':seller_name,
	    	'status':status,
	    	'amount1':amount1,
	    	'amount2':amount2,
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
	$("#entity-search-FMS_TRANS_NO").val("");
	$("#entity-search-time1").val("");
	$("#entity-search-time2").val("");
	$("#entity-search-order_no").val("");
	$("#entity-search-seller_name").val("");
	$("#entity-search-status").val("-1");
	$("#entity-search-amount1").val("");
	$("#entity-search-amount2").val("");
}

/**************start--查看****************/
$(document).on('click', '#entity-infoDisplay-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var code =  $(this).parent().parent().parent().children().first().next().text();
	
	$("#info-entity-hint").html('').hide();
	$('#info-entity-modal').modal('show');
	$('#info-entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
	
	$.post(BASE_PATH + 'authorizationCode/getInfo', {
			'id':id,
			'code':code
        },
        function(result){
            if(result.code != 0) {
                $("#info-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
            	fillInEntityValue(result.data);
            }
        },
        'json'
    );

});

function fillInEntityValue(data){
	$("#info-entity-list").html(data.entity_list_html);
	$("#search-head-div").html('').hide();
	$("#entity-pager-ul").html('').hide();
	$("#th-operation-id").html('').hide();
	$("td[name='td-operation-name']").each(function(i,e){
		$(e).html('').hide();
	});
	var height_px = $("#info-entity-list").css("height");
	height = height_px.substring(0,height_px.length-2);
	if( 500 < Number(height) ){
		$("#info-entity-list").css("height","500px").css("overflow-y","scroll");
	} else {
		$("#info-entity-list").css("height","")
	}
}
/**************end--查看****************/

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

prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
