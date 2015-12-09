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
	
    var code = $("#entity-search-code").val();
    var time1 = $("#entity-search-time1").val();
	var time2 = $("#entity-search-time2").val();
	var type = $("#entity-search-type").val();
	var status = $("#entity-search-status").val();
	
	if(-1 == type) { type =""; }
	if(-1 == status) { status =""; }
	
    //查找
    $.post(BASE_PATH + 'authorizationCode/searchList', {
	    	'code':code, 
	    	'time1':time1,
	    	'time2':time2,
	    	'type':type,
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
	$("#entity-search-code").val("");
	$("#entity-search-time1").val("");
	$("#entity-search-time2").val("");
	$("#entity-search-type").val("-1");
	$("#entity-search-status").val("-1");
}


/**************start--停用/启用****************/
$(document).on('click', '#entity-changeStatus-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var status = $(this).parent().parent().parent().children().children().first().val(); // "status" value
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id,'status':status},function(event){
		$.post(BASE_PATH + 'authorizationCode/changeStatus', {'id':event.data.id, 'status':event.data.status },
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
/**************end--停用/启用****************/


/**************start--删除****************/
$(document).on('click', '#entity-delete-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id},function(event){
		$.post(BASE_PATH + 'authorizationCode/delete', {'id':event.data.id},
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

/**************start--增加****************/
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

/**************end--增加****************/


/**************start--查看****************/
$(document).on('click', '#entity-infoDisplay-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	$('#add-entity-modal').modal('show');
	$.post(BASE_PATH + 'user/info', {
			'id':id
        },
        function(result){
            if(result.code != 0) {
                $("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
            	displayentityValue(result.data[0]);
            }
            $("#entity-search-btn").removeAttr('disabled');
        },
        'json'
    );

});

function displayentityValue(data){
	$('#info_entity_title').html('查看');
	$("#add-entity-hint").html('').fadeOut();
	
	$('#info-entity-id').val(data.id);
	
	$('#add-entity-tel').val(data.tel);
	$('#add-entity-name').val(data.name);
	$("#add-entity-gender").val(data.gender);
	$('#add-entity-cardId').val(data.card_id);
//	$('#add-entity-email').val(data.email);
	$('#add-entity-company').val(data.company);
	$('#add-entity-bankName').val(data.bank_name);
	$('#add-entity-bankNum').val(data.bank_num);
	$('#add-entity-info').val(data.info);
	$('#add-entity-city').val(data.city);
	$('#add-entity-bankNumBranch').val(data.bank_name_branch);
	$('#add-entity-advantageProduct').val(data.advantage_product);
	$('#add-entity-inviteCode').val(data.invite_code);
	
	entity_renderSelect(data.manager_id);
	entity_renderBmdmSelect(data.bmdm);
	entity_renderFgsSelect(data.fgs);
	
	if('' != data.attachment_name){
		$("#entity-attachment-name").html(data.attachment_name);
		$("#entity-attachment-name").attr('title',data.attachment_name);
		
		$("#entity-attachment-download").css('display', 'block');
		
		$('#entity-attachment-name').unbind("click");
		$('#entity-attachment-name').on('click',function(event){
			downloadFile($('#info-entity-id').val());
		});
		
		$('#add-entity-download-txt').html('协议附件').removeClass('col-sm-1').addClass('col-sm-2');
	}
	
	$('#entity-attachment-clear').css('display', 'none');
	$('#entity-attachment-clearServer').css('display', 'none');
	$("#entity-attachment-file").css('display', 'none');
	
	$("#entity-attachment-code").css('display', 'block');
	
	$("#add-entity-manager").attr('disabled','disabled');
	$("#add-entity-fgs").attr('disabled','disabled');
	$("#add-entity-bmdm").attr('disabled','disabled');
	
	$('#add-entity-tel').attr('readonly','readonly');
	$('#add-entity-name').attr('readonly','readonly');
	$("#add-entity-gender").attr('disabled','disabled');
	$('#add-entity-cardId').attr('readonly','readonly');
	//$('#add-entity-email').attr('readonly','readonly');
	$('#add-entity-company').attr('readonly','readonly');
	$('#add-entity-bankName').attr('readonly','readonly');
	$('#add-entity-bankNum').attr('readonly','readonly');
	$('#add-entity-info').attr('readonly','readonly');
	$('#add-entity-city').attr('readonly','readonly');
	$('#add-entity-bankNumBranch').attr('readonly','readonly');
	$('#add-entity-advantageProduct').attr('readonly','readonly');
	$('#add-entity-inviteCode').attr('readonly','readonly');
	
	$(".citydatadiv").hide();
	$('#btn-add-entity').hide();
}
/**************end--查看****************/




prettyPrint();
});
