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
		var FMS_TRANS_NO = $("#entity-search-FMS_TRANS_NO").val();
		var status = $("#entity-search-status").val();
		
		if(-1 == status) { status =""; }
		
	    //查找
	    $.post(BASE_PATH + 'bcsTransfer/searchList', {
		    	'time1':time1,
		    	'time2':time2,
		    	'SIT_NO':SIT_NO,
		    	'FMS_TRANS_NO':FMS_TRANS_NO,
		    	'status':status,
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
		$("#entity-search-FMS_TRANS_NO").val("");
		$("#entity-search-status").val("-1");
	}
	
    prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
