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

	$(document).on('click', '#entity-search-btn-bank', function(event){
		search_entity(1);
	});

	$(document).on('click', '#entity-clear-btn-bank', function(event){
		search_clearFields();
	    search_entity(1);
		return;
	});

	$(document).on('click', '#entity-list-prev-bank', function(event){
		var cur_page = $("#entity-current-page-bank").html();
		var total_page = $("#entity-total-page-bank").html();
		
		if(cur_page == '1') {
			Messenger().post('已经是第一页了！');
			return ;
		}
		var page = cur_page * 1 - 1;
		search_entity(page);
	});

	$(document).on('click', '#entity-list-next-bank', function(event){
		var cur_page = $("#entity-current-page-bank").html();
		var total_page = $("#entity-total-page-bank").html();
		if(cur_page == total_page) {
			Messenger().post('已经是最后一页了！');
			return ;
		}
		var page = cur_page * 1 + 1;
	    search_entity(page);
	});

	$(document).on('change', '#entity-custom-page-bank', function(event){
		var sel_page = $('#entity-custom-page-bank').val();
		var cur_page = $("#entity-current-page-bank").html();
		var total_page = $("#entity-total-page-bank").html();
		search_entity(sel_page);
	});

	
	$(function(){
		entitySetSelectedPage();
	});

	function search_entity(page){
		var cur_page = $("#entity-current-page-bank").html();
		var total_page = $("#entity-total-page-bank").html();
		if(page < 1 || page > total_page * 1) {
			Messenger().post('页码错误！');
			return ;
		}
		
		$("#entity-search-btn-bank").attr('disabled', 'disabled');
		$("#search-entity-hint").html('').fadeOut();
		
	    var bankNo = $("#entity-search-bankNo").val();
		var bankName = $("#entity-search-bankName").val();
		var super_bank_id = $("#entity-search-super_bank_id").val();
		var city_id = $("#entity-search-city_id").val();
		var isApplyIndex = $("#entity-search-is_apply_index").val();
		if(isApplyIndex.toString() == '1'){
			isApplyIndex = '2';
		}else{
			isApplyIndex = '0';
		}
		
	    //查找
	    $.post(BASE_PATH + 'spdInternetBank/searchList', {
		    	'bankNo':bankNo,
		    	'bankName':bankName,
		    	'super_bank_id':super_bank_id,
		    	'city_id':city_id,
		    	'isApplyIndex':isApplyIndex,
		        'page':page
	        },
	        function(result){	        	
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#entity-list").html(result.data.entity_list_html);
	            }	            
	            $("#entity-search-btn-bank").removeAttr('disabled');	                        
	            setTimeout(function(){
	            	entitySetSelectedPage();
                }, 1000);	           
	        },
	        'json'
	    );
	}

	function search_clearFields(){
		$("#entity-search-bankNo").val("");
		$("#entity-search-bankName").val("");
		$("#entity-search-super_bank_id option[value=-1]").attr("selected",true);
		//alert($("#entity-search-provice_id option[value=-1]").val());
		$("#entity-search-provice_id option[value=-1]").attr("selected",true);		
		$("#entity-search-city_id").empty().append('<option value="-1">-请选择-</option>');
	}


	$(document).on('click', '#add-entity-new', function(event){
		$("#search-entity-hint").html('').fadeOut();
		
		$("#add-entity-new").attr('disabled', 'disabled');
		$("#add-entity-new").html("更新中...");
//		alert(356);return false;
		
	    $.post(BASE_PATH + 'spdInternetBank/create', {},
	        function(result){
	            if(result.code != 0) {
	                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	                $("#search-entity-hint").html(result.msg + ', 关闭...').fadeIn();
	                setTimeout(function(){
	                    $('#entity-clear-btn-bank').click();
	                }, 1000);
	            }
	            $("#add-entity-new").removeAttr('disabled');
                $("#add-entity-new").html("更新");
	        },
	        'json'
	    );
		
	});

	
	$(document).on('change', '#entity-search-provice_id', function(event){
		var province_id = ($(this).val());
		$.post(BASE_PATH + 'city/getCityByProviceId', {
		    	'province_id':province_id,	    	
	        },
	        function(result){
	            if(result.code != 0) {
	                //$("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
	            }else {
	               var html = '<option value="-1">-请选择-</option>';	
	               $("#entity-search-city_id").empty();	               
	               if(result.hasOwnProperty('data') == false){
	            	   $("#entity-search-city_id").append(html);
	               }	               
	               for(var i=0; i<result.data.length; i++){
	            	   html = "<option value='" + result.data[i]['id'] + "'>" + result.data[i]['city_name'] + "</option>";
	            	   //alert(html);
	            	   $("#entity-search-city_id").append(html);
	               }
	               
	            }            
	        },
	        'json'
		);
	});
    
	$(document).on('click', '.entity-select-bank', function(event){

		var id = $(this).attr("id").replace('entity-select-', '');		
		var bankname_id = 'entity-select-bankname-' + id.toString();		
		if($("#"+bankname_id).length > 0){
			$("#add-entity-bank_name").val($("#"+bankname_id).val());
			$("#add-entity-modal").modal('hide');
		}
		
	});	
	
    prettyPrint();
});

function entitySetSelectedPage(){
	if($('#entity-custom-page-bank') && $("#entity-current-page-bank") && $("#entity-total-page-bank")){
		var cur_page = $("#entity-current-page-bank").html();
		var total_page = $("#entity-total-page-bank").html();
		var selObj = $('#entity-custom-page-bank');
		selObj.empty();
		var html = '';
		for(var i=1; i<=total_page; i++){
			html += "<option value='" + i +"'>" + i +"</option>";
		}
		selObj.append(html);
		selObj.val(cur_page);
	}
}

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
