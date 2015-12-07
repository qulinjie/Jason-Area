$(document).ready(function(){


/*************************************************************** entity  start ************************************************************************/
/**************start--增加****************/


function add_entity(){
	$("#btn-add-entity").attr('disabled', 'disabled');
    $("#add-entity-hint").html('').fadeOut();
    
    var tel = $("#add-entity-tel").val();
    var name = $("#add-entity-name").val();
    var gender = $('#add-entity-gender').val();
    var cardId = $('#add-entity-cardId').val();
    var email = '';//$('#add-entity-email').val();
    var company = $('#add-entity-company').val();
    var bankName = $('#add-entity-bankName').val();
    var bankNum = $('#add-entity-bankNum').val();
    var info = $('#add-entity-info').val();
    var city = $('#add-entity-city').val();
    var bankNumBranch = $('#add-entity-bankNumBranch').val();
    var advantageProduct = $('#add-entity-advantageProduct').val();
    var manager = $("#add-entity-manager").val();
    var attachmentName = $('#add-entity-attachmentName').val();
    var attachmentPath = $('#add-entity-attachmentPath').val();
    var fgs = $('#add-entity-fgs').val();
    var bmdm = $('#add-entity-bmdm').val();
    
    var hint_html = '';
    if(tel == '') {
        hint_html += (hint_html == '' ? '请填写手机号！' : '<BR>请填写手机号！' );
    }
    if(11 != tel.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '手机号必须为11位数字！' ;
    }
    if(name == ''){
        hint_html += (hint_html == '' ? '请填写姓名！' : '<BR>请填写姓名！' );
    }
    if(10 < name.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '姓名必须为10位以内，限汉字、字母！' ;
    }
    if(manager == '' || manager == -1){
    	hint_html += (hint_html == '' ? '请填写客户经理！' : '<BR>请填写客户经理！' );
    }
    if(cardId == ''){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写身份证号！' ;
    }
    
    if(fgs == '' || fgs == -1){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写分公司！' ;
    }
    if(bmdm == '' || bmdm == -1){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写部门！' ;
    }
    
    if(''!=info && 20 < info.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '签名为20位以内，限汉字、字母、数字、符号！' ;
    }
    if(''!=company && 30 < company.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '公司名称为30位以内，限汉字、字母、数字！' ;
    }
    if(''!=bankName && 20 < bankName.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '银行名称为20位以内，限汉字！' ;
    }
    if(''!=bankNum && 19 < bankNum.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '银行账号为19位以内，限数字！' ;
    }
    if(''!=bankNumBranch && 10 < bankNumBranch.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '开户支行名称为10位以内，限汉字、字母！' ;
    }
    if(''!=advantageProduct && 20 < advantageProduct.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '擅长品种为20位以内，限汉字、字母！' ;
    }
    
    if(hint_html != ''){
        $("#add-entity-hint").html(hint_html).fadeIn();
        $("#btn-add-entity").removeAttr('disabled');
        return 0;
    }
    
    $("#btn-add-entity").html("添加中...");
    $.post(BASE_PATH + 'user/entity_add', {
        	'tel':tel, 
	        'name':name,
	        'gender':gender,
	        'card_id':cardId,
	        'email':email,
	        'company':company,
	        'bank_name':bankName,
	        'bank_num':bankNum,
	        'info':info,
	        'city':city,
	        'fgs':fgs,
	        'bmdm':bmdm,
	        'bank_name_branch':bankNumBranch,
	        'advantage_product':advantageProduct,
	        'manager_id':manager,
	        'attachment_name':attachmentName,
	        'attachment_path':attachmentPath
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
                    location.href = BASE_PATH + 'user/entity_getIndex';
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
	
	$("#btn-add-entity").removeAttr('disabled');
    $("#btn-add-entity").html("确定");
    
	$('#btn-add-entity').on('click',function(event){
		add_entity();
	});
	
	$('#entity-attachment-clear').unbind("click");
	$(document).on('click', '#entity-attachment-clear', function(event){
		clearInputVal('file');
	});
	entity_renderCitySelect();
	entity_renderSelect();
	entity_renderBmdmSelect();
	entity_renderFgsSelect();
});

/**************end--增加****************/

function entity_renderCitySelect(id){
	//Messenger().post('==>>length=' + $("#add-entity-city").length);
	if(15 < $("#add-entity-city").length){
		$("#add-entity-city").val('');
		return ;
	}
	
	var upd = false;
	if(typeof id !== "undefined" && '' != id) {
//		Messenger().post('==>>id=' + id);
		upd = true;
	}
	if(!upd){
		$("#add-entity-city").append("<option value=''>请选择</option>");
		id= '';
	}
	$.post(BASE_PATH + 'city/getCityNameList', {},
			function(result){
		        if(result.code != 0) {
		        	Messenger().post(result.msg + '(' + result.code + ')');
		        } else {
		        	var data = result['data'];
		        	for(var i=0;i<data.length;i++){
		        		$("#add-entity-city").append("<option value='" + data[i].name + "'>" + data[i].name + "</option>");
		        	}
		        	$("#add-entity-city").val(id);
		        }
		    },
		    'json'
	);
}

function entity_renderSelect(id){
	var upd = false;
	if(typeof id !== "undefined" && '' != id) {
//		Messenger().post('==>>id=' + id);
		upd = true;
	}
	if(!upd){
		$("#add-entity-manager").append("<option value='-1'>请选择</option>");
	}
	$.post(BASE_PATH + 'user/manager_getAllList', {},
			function(result){
		        if(result.code != 0) {
		        	Messenger().post(result.msg + '(' + result.code + ')');
		        } else {
		        	var data = result['data'];
		        	for(var i=0;i<data.length;i++){
		        		//$("#add-entity-manager").append("<option value='" + data[i].id + "'>" + data[i].name + "(" + data[i].city + ")</option>");
		        		$("#add-entity-manager").append("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
		        	}
		        	if(upd) {
		        		$("#add-entity-manager").val(id);
		        	}
		        }
		    },
		    'json'
	);
}

function entity_renderBmdmSelect(id){
	$("#add-entity-bmdm").empty();
	var upd = false;
	if(typeof id !== "undefined" && '' != id) {
		upd = true;
	} else {
		$("#add-entity-bmdm").append("<option value='-1'>请选择</option>");
	} 
	$.post(BASE_PATH + 'user/erp_getDepList', {},
			function(result){
		        if(result.code != 0) {
		        	Messenger().post(result.msg + '(' + result.code + ')');
		        } else {
		        	var data = result['data']['data'];
		        	for(var i=0;i<data.length;i++){
		        		$("#add-entity-bmdm").append("<option value='" + data[i].dm + "'>" + data[i].mc + "</option>");
		        	}
		        	if(upd) {
		        		$("#add-entity-bmdm").val(id);
		        	}
		        }
		    },
		    'json'
	);
}

function entity_renderFgsSelect(id){
	$("#add-entity-fgs").empty();
	var upd = false;
	if(typeof id !== "undefined" && '' != id) {
		upd = true;
	} else {
		$("#add-entity-fgs").append("<option value='-1'>请选择</option>");
	}
	$.post(BASE_PATH + 'user/erp_getSubcompanyList', {},
			function(result){
		        if(result.code != 0) {
		        	Messenger().post(result.msg + '(' + result.code + ')');
		        } else {
		        	var data = result['data']['data'];
		        	for(var i=0;i<data.length;i++){
		        		$("#add-entity-fgs").append("<option value='" + data[i].dm + "'>" + data[i].mc + "</option>");
		        	}
		        	if(upd) {
		        		$("#add-entity-fgs").val(id);
		        	}
		        }
		    },
		    'json'
	);
}

function clear_entity_field(){
	$('#info-entity-id').val('');
	
	$('#add-entity-tel').val('');
	$('#add-entity-name').val('');
	$("#add-entity-gender").val('0');
	$('#add-entity-cardId').val('');
//	$('#add-entity-email').val('');
	$('#add-entity-company').val('');
	$('#add-entity-bankName').val('');
	$('#add-entity-bankNum').val('');
	$('#add-entity-info').val('');
	$('#add-entity-city').val('');
	$("#add-entity-manager").empty();
	$('#add-entity-bankNumBranch').val('');
	$('#add-entity-advantageProduct').val('');
	$('#add-entity-inviteCode').val('');
	$('#add-entity-attachmentName').val('');
	$('#add-entity-attachmentPath').val('');
	$('#add-entity-attachmentDelete').val('');
	
	$('#add-entity-download-txt').html('下载').removeClass('col-sm-2').addClass('col-sm-1');
	
	$("#entity-attachment-download").css('display', 'none');
	$("#entity-attachment-code").css('display', 'none');
	
	$('#entity-attachment-clear').css('display', 'block');
	$('#entity-attachment-clearServer').css('display', 'block');
	$("#entity-attachment-file").css('display', 'block');
	
	clearInputVal('file');
	
	$('#add-entity-tel').removeAttr('readonly');
	$('#add-entity-name').removeAttr('readonly');
	$("#add-entity-gender").removeAttr('disabled');
	$('#add-entity-cardId').removeAttr('readonly');
//	$('#add-entity-email').removeAttr('readonly');
	$('#add-entity-company').removeAttr('readonly');
	$('#add-entity-bankName').removeAttr('readonly');
	$('#add-entity-bankNum').removeAttr('readonly');
	$('#add-entity-info').removeAttr('readonly');
	$('#add-entity-city').removeAttr('disabled');
	$("#add-entity-manager").removeAttr('disabled');
	$("#add-entity-fgs").removeAttr('disabled');
	$("#add-entity-bmdm").removeAttr('disabled');
	$('#add-entity-bankNumBranch').removeAttr('readonly');
	$('#add-entity-advantageProduct').removeAttr('readonly');
	
	$(".citydatadiv").hide();
}




function get_entity(page){
	var cur_page = $("#entity-current-page").html();
	var total_page = $("#entity-total-page").html();
	if(page < 1 || page > total_page * 1) {
		Messenger().post('页码错误！');
		return ;
	}
	//获取
    $.post(BASE_PATH + 'user/entity_getList', {'page':page},
        function(result){
            if(result.code != 0) {
            	Messenger().post(result.msg + '(' + result.code + ')');
            }else {
                $("#entity-list").html(result.data.entity_list_html);
            }
            entitySetSelectedPage();
        },
        'json'
    );
}

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
    
    //查找
    $.post(BASE_PATH + 'authorizationCode/searchList', {
	    	'code':code, 
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

$(document).on('click', '#entity-search-btn', function(event){
	search_entity(1);
});

$(document).on('click', '#entity-clear-btn', function(event){
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
//	Messenger().post('==>>' + cur_page + ' , ' +total_page + ' , sel_page=' + sel_page);
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
//		alert(cur_page + ' , ' +total_page);
//		Messenger().post('==>>' + cur_page + ' , ' +total_page);
	}
}
$(function(){
	entitySetSelectedPage();
});


/**************start--修改****************/
$(document).on('click', '#entity-infoUpd-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	$('#add-entity-modal').modal('show');
	$('#add-entity-modal').modal({keyboard: false});
	$.post(BASE_PATH + 'user/info', {
			'id':id
        },
        function(result){
            if(result.code != 0) {
                $("#search-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
            	clear_entity_field();
            	fillInentityValue(result.data[0]);
            }
            $("#entity-search-btn").removeAttr('disabled');
        },
        'json'
    );

});

function fillInentityValue(data){
	$('#info_entity_title').html('修改');
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
//	$('#add-entity-city').val(data.city);
	$('#add-entity-bankNumBranch').val(data.bank_name_branch);
	$('#add-entity-advantageProduct').val(data.advantage_product);
	$('#add-entity-inviteCode').val(data.invite_code);
	
	entity_renderCitySelect(data.city);
	entity_renderSelect(data.manager_id);
	entity_renderBmdmSelect(data.bmdm);
	entity_renderFgsSelect(data.fgs);
	
	$('#info-entity-dm').val(data.dm);
	
	if('' != data.attachment_name){
		$("#entity-attachment-name").html(data.attachment_name);
		$("#entity-attachment-name").attr('title',data.attachment_name);
		
		$("#entity-attachment-download").css('display', 'block');
		
		$('#entity-attachment-name').unbind("click");
		$('#entity-attachment-name').on('click',function(event){
			downloadFile($('#info-entity-id').val());
		});
		
		$('#entity-attachment-clearServer').unbind("click");
		$('#entity-attachment-clearServer').on('click',function(event){
			$('#add-entity-attachmentDelete').val('1');
			$("#entity-attachment-download").css('display', 'none');
		});
	}
	
	$("#entity-attachment-code").css('display', 'block');
	$("#add-entity-inviteCode").attr('readonly', 'readonly');
	
	$('#add-entity-tel').removeAttr('readonly');
	$('#add-entity-name').removeAttr('readonly');
	$("#add-entity-gender").removeAttr('disabled');
	$('#add-entity-cardId').removeAttr('readonly');
	$('#add-entity-email').removeAttr('readonly');
	$('#add-entity-company').removeAttr('readonly');
	$('#add-entity-bankName').removeAttr('readonly');
	$('#add-entity-bankNum').removeAttr('readonly');
	$('#add-entity-info').removeAttr('readonly');
	$('#add-entity-city').removeAttr('readonly');
	$('#add-entity-bankNumBranch').removeAttr('readonly');
	$('#add-entity-advantageProduct').removeAttr('readonly');
	
//	Messenger().post('==>>');
	$('#btn-add-entity').show();
	$("#btn-add-entity").removeAttr('disabled');
    $("#btn-add-entity").html("确定");
	$('#btn-add-entity').unbind("click");
	$('#btn-add-entity').on('click',function(event){
		updateentity();
	});
	
	$('#entity-attachment-clear').unbind("click");
	$(document).on('click', '#entity-attachment-clear', function(event){
		clearInputVal('file');
	});
	
	$(".citydatadiv").hide();
}

function downloadFile(id){
	var url = BASE_PATH + 'user/download?id='+id;
//	Messenger().post('==>>1');
	$.ajax({
        url: url,
        type:'post',
        async:false,
        success:function(){
            window.open(url);
        }
    });
//    Messenger().post('==>>2');
}


function updateentity(){
	var fileName = $('#file').val();
	if('' == fileName){
		updateentity2();
		return ;
	}
	$('#add-entity-attachmentName').val(getFileName(fileName));
	$.ajaxFileUpload({
        url:BASE_PATH + 'user/upload',
        secureuri :false,
        fileElementId :'file',//file控件id
        dataType : 'json',
        success : function (result, status){
        	if(result.code != 0) {
                $("#add-entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
                $("#btn-add-entity").removeAttr('disabled');
                $("#btn-add-entity").html("确定");
            } else {
//            	alert('result=' + result + ' ,status='+status);
//        		Messenger().post('attachmentPath=' + result['data']);
        		$('#add-entity-attachmentPath').val(result['data']);
        		updateentity2();
            }
        },
        error: function(result, status, e){
        	$("#add-entity-hint").html(result.msg + '(' + e + ')').fadeIn();
        	$("#btn-add-entity").removeAttr('disabled');
            $("#btn-add-entity").html("确定");
        }
    });
}

function updateentity2(){
	var id = $("#info-entity-id").val();
	var tel = $("#add-entity-tel").val();
    var name = $("#add-entity-name").val();
    var gender = $('#add-entity-gender').val();
    var cardId = $('#add-entity-cardId').val();
    var company = $('#add-entity-company').val();
    var bankName = $('#add-entity-bankName').val();
    var bankNum = $('#add-entity-bankNum').val();
    var info = $('#add-entity-info').val();
    var city = $('#add-entity-city').val();
    var bankNumBranch = $('#add-entity-bankNumBranch').val();
    var advantageProduct = $('#add-entity-advantageProduct').val();
    var manager = $("#add-entity-manager").val();
    var attachmentName = $('#add-entity-attachmentName').val();
    var attachmentPath = $('#add-entity-attachmentPath').val();
    var attachmentDelete = $('#add-entity-attachmentDelete').val();
    
    var fgs = $('#add-entity-fgs').val();
    var bmdm = $('#add-entity-bmdm').val();
    var dm = $('#info-entity-dm').val();
    
    var hint_html = '';
    if(tel == '') {
        hint_html += (hint_html == '' ? '请填写手机号！' : '<BR>请填写手机号！' );
    }
    if(11 != tel.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '手机号必须为11位数字！' ;
    }
    if(name == ''){
        hint_html += (hint_html == '' ? '请填写姓名！' : '<BR>请填写姓名！' );
    }
    if(10 < name.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '姓名必须为10位以内，限汉字、字母！' ;
    }
    if(manager == '' || manager == -1 || null==manager){
    	hint_html += (hint_html == '' ? '请填写客户经理！' : '<BR>请填写客户经理！' );
    }
    
    if(fgs == '' || fgs == -1){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写分公司！' ;
    }
    if(bmdm == '' || bmdm == -1){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写部门！' ;
    }
    
    if(''!=info && 20 < info.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '签名为20位以内，限汉字、字母、数字、符号！' ;
    }
    if(''!=company && 30 < company.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '公司名称为30位以内，限汉字、字母、数字！' ;
    }
    if(''!=bankName && 20 < bankName.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '银行名称为20位以内，限汉字！' ;
    }
    if(''!=bankNum && 19 < bankNum.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '银行账号为19位以内，限数字！' ;
    }
    if(''!=bankNumBranch && 10 < bankNumBranch.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '开户支行名称为10位以内，限汉字、字母！' ;
    }
    if(''!=advantageProduct && 20 < advantageProduct.length){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '擅长品种为20位以内，限汉字、字母！' ;
    }
    
    if(hint_html != ''){
        $("#add-entity-hint").html(hint_html).fadeIn();
        $("#btn-add-entity").removeAttr('disabled');
        return 0;
    }
    
    $("#btn-add-selle").html("提交中...");
    $.post(BASE_PATH + 'user/entity_update', {
	    	'id':id,
	    	'tel':tel, 
	        'name':name,
	        'gender':gender,
	        'card_id':cardId,
	        'company':company,
	        'bank_name':bankName,
	        'bank_num':bankNum,
	        'info':info,
	        'city':city,
	        'fgs':fgs,
	        'bmdm':bmdm,
	        'dm':dm,
	        'bank_name_branch':bankNumBranch,
	        'advantage_product':advantageProduct,
	        'manager_id':manager,
	        'attachment_name':attachmentName,
	        'attachment_path':attachmentPath,
	        'attachment_delete':attachmentDelete
        },
        function(result){
        	$("#add-entity-hint").html('');
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
                }, 500);
                setTimeout(function(){
            		search_entity(parseInt($("#entity-current-page").html()));
            	},800);
            }
        },
        'json'
    );
}

/**************end--修改****************/

/**************start--停用/启用****************/
$(document).on('click', '#entity-enabledStatus-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var enabled = $(this).parent().parent().parent().children().children().first().val();
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id,'enabled':enabled},function(event){
		$.post(BASE_PATH + 'user/changeStatus', {'id':event.data.id, 'enabled_status':event.data.enabled },
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
	var enabled = $(this).parent().parent().parent().children().children().first().val();
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	
	$('#confirm-modal-body').html('是否'+txt+'!');
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id,'enabled':enabled},function(event){
		$.post(BASE_PATH + 'user/delete', {'id':event.data.id, 'enabled_status':event.data.enabled },
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

/**************start--更多信息****************/
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
/**************end--更多信息****************/

/**************end--重置密码****************/
$(document).on('click', '#entity-resetPwd-btn', function(event){
	var id =  $(this).parent().parent().parent().children().first().text();
	var txt = $(this).text();
	
	$("#confirm-entity-hint").html('').fadeOut();
	$('#confirm-entity-modal').modal('show');
	$('#confirm-modal-body').html('是否'+txt+'!');
	
	$('#btn-confirm-entity').unbind("click");
	$('#btn-confirm-entity').on('click', {'id':id},function(event){
		$.post(BASE_PATH + 'user/entity_resetPwd', {'id':event.data.id},
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
/**************end--重置密码****************/

/*************************************************************** entity  end ************************************************************************/


prettyPrint();
});
