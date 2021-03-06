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
	
    var MCH_TRANS_NO = $("#entity-search-MCH_TRANS_NO").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var oppositeAcctName = $("#entity-search-oppositeAcctName").val();
	var inout = $("#entity-search-inout").val();
	var paybankname      = $('#entity-search-s_paybankname').val();
	var txamt1           = $('#entity-search-txamt1').val();
	var txamt2           = $('#entity-search-txamt2').val();
	var erpfgsdm        = $('#entity-search-user_fgsdm').val();
	if(-1 == status) { status =""; }
	
    //查找
    $.post(BASE_PATH + 'bcsTrade/searchList', {
	    	'MCH_TRANS_NO':MCH_TRANS_NO, 
	    	'time1':time1,
	    	'time2':time2,
	    	'oppositeAcctName':oppositeAcctName,
	    	'inout':inout,
			'paybankname':paybankname,
			'erpfgsdm':erpfgsdm,
			'txamt1':txamt1,
			'txamt2':txamt2,
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
	$("#entity-search-MCH_TRANS_NO").val("");
	$("#entity-search-time1").val("");
	$("#entity-search-time2").val("");
	$("#entity-search-oppositeAcctName").val("");
	$('#entity-search-s_paybankname').val("");
	$('#entity-search-user_fgsdm').val("");
	$('#entity-search-txamt1').val("");
	$('#entity-search-txamt2').val("");

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


/**************str--更新****************/
$(document).on('click', '#entity-loadInfo-btn', function(event){
	var id =  $(this).parent().parent().parent().children().get(0).textContent;
	
	var objBtn = $(this);
	objBtn.html('更新中...');
	
	$("#operation-entity-hint").html('').fadeOut();
	$.post(BASE_PATH + 'bcsTrade/loadInfo', {'id':id},
	        function(result){
				if(result.code != 0) {
		    		$("#operation-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();	
		        } else {
		        	if(0==result.code){
		        		$("#operation-entity-hint").html('更新完成！').fadeIn();
			        	setTimeout(function(){
			        		search_entity($("#entity-current-page").html());
			            }, 1000);
		        	} else {
		        		$("#operation-entity-hint").html('更新失败！').fadeIn();
		        	}
		        }
	            objBtn.html('更新');
	        },
	        'json'
	    );
});
/**************end--更新****************/

/**************str--更新浦发虚拟子账户流水****************/
$(document).on('click', '#entity-loadList-btn', function(event){
	var objBtn = $(this);
	objBtn.html('更新中...');
	
	$("#operation-entity-hint").html('').fadeOut();
	$.post(BASE_PATH + 'bcsTrade/spd_loadAccountTradeList', {},
	        function(result){
	            if(result.code != 0) {
            		$("#operation-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();	
	            } else {
	            	if(0==result.code){
	            		$("#operation-entity-hint").html('更新完成！').fadeIn();
		            	setTimeout(function(){
		            		search_entity($("#entity-current-page").html());
		                }, 1000);
	            	} else {
	            		$("#operation-entity-hint").html('更新失败！').fadeIn();
	            	}
	            }
	            objBtn.html('更新流水');
	        },
	        'json'
	    );
});
/**************end--更新浦发虚拟子账户流水****************/

/**************begin--收款单手动发送erp****************/

$(document).on('click', ".erp-sync-entity", function(evnent){
	var obj = $(this);
	var mch_trans_no = obj.attr("id").replace('erp-sync-entity-', '');
	obj.html('发送中...');
	
	$.post(BASE_PATH + 'bcsTrade/erp_syncBillsOfCollection', {    		
        	'mch_trans_no':mch_trans_no,
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

/**************end--收款单手动发送erp****************/

prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
