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
		var USER_NAME = $("#entity-search-user_name").val();
		var SIT_NO = $("#entity-search-SIT_NO").val();
		var ACCOUNT_NO = $("#entity-search-ACCOUNT_NO").val();
		var status = $("#entity-search-status").val();
		
		if(-1 == status) { status =""; }
		
	    //查找
	    $.post(BASE_PATH + 'bcsCustomer/searchList', {
		    	'time1':time1,
		    	'time2':time2,
		    	'SIT_NO':SIT_NO,
		    	'ACCOUNT_NO':ACCOUNT_NO,
				'USER_NAME':USER_NAME,
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
		$("#entity-search-ACCOUNT_NO").val("");
		$("#entity-search-status").val("-1");
		$("#entity-search-user_name").val("");
	}
	
	
	// 更新金额
	//$.post(BASE_PATH + 'bcsCustomer/loadInfo', {},function(result){},'json');
	
	/**************str--更新****************/
	$(document).on('click', '#entity-loadInfo-btn', function(event){
		var user_id =  $(this).parent().parent().parent().children().get(1).textContent;
		
		var objBtn = $(this);
		objBtn.html('更新中...');
		
		$("#operation-entity-hint").html('').fadeOut();
		$.post(BASE_PATH + 'bcsCustomer/loadInfo', {'user_id':user_id},
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
	
	$(document).on('click', '#entity-loadInfo-btn2', function(event){
		var user_id =  $(this).parent().parent().parent().children().get(1).textContent;
		var ACCOUNT_NO =  $(this).parent().parent().parent().children().get(3).textContent;
		
		var objBtn = $(this);
		objBtn.html('更新中...');
		
		$("#operation-entity-hint").html('').fadeOut();
		$.post(BASE_PATH + 'bcsTrade/spd_loadAccountTradeList', {'virtualAcctNo':ACCOUNT_NO},
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
		
		spdLoadAccountInfo(ACCOUNT_NO); // 更新余额
	});
	/**************end--更新****************/
    
	/**************str--更新浦发虚拟子账户****************/
	
	function spdLoadAccountInfo(virtualAcctNo){
		$.post(BASE_PATH + 'bcsCustomer/spd_loadAccountList', {"virtualAcctNo":virtualAcctNo},function(result){},'json');
	}
	
	$(document).on('click', '#entity-loadList-btn', function(event){
		var objBtn = $(this);
		objBtn.html('更新中...');
		
		$("#operation-entity-hint").html('').fadeOut();
		$.post(BASE_PATH + 'bcsCustomer/spd_loadAccountList', {},
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
		            objBtn.html('更新浦发虚拟子账户');
		        },
		        'json'
		    );
	});
	/**************end--更新浦发虚拟子账户****************/
    
	/**************start--增加****************/
	function add_entity(){
		$("#btn-add-entity").attr('disabled', 'disabled');
	    $("#add-entity-hint").html('').fadeOut();
	    
	    var account  = $("#add-entity-account").val();
	    var ACCOUNT_NO = $("#add-entity-ACCOUNT_NO").val();
	    var comment = '';
	    
	    var hint_html = '';
    	if( '' == account || '-1' == account){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写用户登录账号 ！' ;
        }
    	
    	if( '' == ACCOUNT_NO || '-1' == ACCOUNT_NO){
        	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写虚拟账户 ！' ;
        }
    	
	    if(hint_html != ''){
	        $("#add-entity-hint").html(hint_html).fadeIn();
	        $("#btn-add-entity").removeAttr('disabled');
	        return 0;
	    }
	    
	    $("#btn-add-entity").html("添加中...");
	    $.post(BASE_PATH + 'bcsCustomer/updateBind', {
	        	'account':account, 
	        	'ACCOUNT_NO':ACCOUNT_NO,
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
		
		$("#btn-add-entity").removeAttr('disabled');
	    $("#btn-add-entity").html("确定");
	    
		$('#btn-add-entity').on('click',function(event){
			add_entity();
		});

		renderErpUserSelect();
		renderSpdCardSelect();
	});

	/*编辑账户*/
	$(document).on('click','.entity-edit-btn',function() {
		$('#customer-edit-modal').modal('show');
		$('#edit-entity-hint').css('display','none').html('');
		//$('#customer-edit-modal').modal({keyboard: false});

		var valId = $(this).attr("id").replace('entity-edit-', '');
		var accountId = $(this).attr('cid');
		$('#oldid').val(valId);
		$('#oldaccountid').val(accountId);
		renderSpdCardEdit(accountId);
	});

	/*编辑更新*/
	$(document).on('click','#btn-edit-entity',function() {
		var accountVal  = $('#edit-entity-ACCOUNT_NO').val();
		var accountInfo = accountVal.split('-');
		var accountNo   = accountInfo[0];
		var oldId 		= $('#oldid').val();
		var accountId   = $('#oldaccountid').val();
		//var currentPage = $('#entity-current-page').val();
		var currentPage  = $('#entity-custom-page').val();
		//判断虚拟账号是否变动
		if(parseInt(oldId) != parseInt(accountNo)) {
			$.post(BASE_PATH + 'bcsCustomer/updateCustomerList',{"ACCOUNT_NO":accountNo,"ACCOUNT_OLD_NO":oldId,'ACCOUNT_ID':accountId},
				  function(data) {
					  if(data) {
						  $('#edit-entity-hint').css('display','block').html('更新成功');
						  setTimeout(function () {
							  $('#customer-edit-modal').modal('hide');
						  },1000);
						  search_entity(currentPage);
					  }
				  },
				  'json'
			);
		} else {
			$('#customer-edit-modal').modal('hide');
		}
	});

	/*清空*/
	function clear_entity_field(){
		$('#info-entity-id').val('');
		//$('#add-entity-account').empty();
		$('#add-entity-comment').val('');
		$('#add-entity-account').val('');
		$('#add-entity-account_str').val('');
	}

	//编辑时,对获取的银行信息的处理
	function renderSpdCardEdit(id){
		$("#edit-entity-ACCOUNT_NO").empty();
		$.post(BASE_PATH + 'bcsCustomer/getAllList', {"record_bank_type":2},
				function(result){
					if(result.code != 0) {
						Messenger().post(result.msg + '(' + result.code + ')');
					} else {
						var data     = result['data'];
						var selected = '';
						for(var i=0;i<data.length;i++){
							selected = data[i]['id'] == id?'selected':'';
							$("#edit-entity-ACCOUNT_NO").append("<option value='" + data[i].ACCOUNT_NO+'-'+data[i].id + "'"+selected+">" + data[i].ACCOUNT_NO + " " + data[i].SIT_NO + "</option>");
						}
					}
				},
				'json'
		);
	}

	function renderSpdCardSelect(id){
		$("#add-entity-ACCOUNT_NO").empty();
		$("#add-entity-ACCOUNT_NO").append("<option value='-1'>请选择</option>");
		$.post(BASE_PATH + 'bcsCustomer/getAllList', {"record_bank_type":2},
				function(result){
			        if(result.code != 0) {
			        	Messenger().post(result.msg + '(' + result.code + ')');
			        } else {
			        	var data = result['data'];
						var accountNoArr = [];
			        	for(var i=0;i<data.length;i++){
			        		$("#add-entity-ACCOUNT_NO").append("<option value='" + data[i].ACCOUNT_NO + "'>" + data[i].ACCOUNT_NO + " " + data[i].SIT_NO + "</option>");
			        	}
			        }
			    },
			    'json'
		);
	}
	
	function renderErpUserSelect2(id){
		$("#add-entity-account").empty();
		
		$("#add-entity-account").append("<option value='-1'>请选择</option>");
		$.post(BASE_PATH + 'user/erp_getList', {},
				function(result){
			        if(result.code != 0) {
			        	Messenger().post(result.msg + '(' + result.code + ')');
			        } else {
			        	var data = result['data'];
			        	for(var i=0;i<data.length;i++){
			        		$("#add-entity-account").append("<option value='" + data[i].usercode + "|" + data[i].username + "'>" + data[i].username + " " + data[i].usercode + "</option>");
			        	}
			        }
			    },
			    'json'
		);
	}
	
	var user_info_list_k = [];
	var user_info_list_v = [];
	function renderErpUserSelect(id){
		$.post(BASE_PATH + 'user/erp_getList', {},
				function(result){
			        if(result.code != 0) {
			        	Messenger().post(result.msg + '(' + result.code + ')');
			        } else {
			        	user_info_list_k = [];
		        		user_info_list_v = [];
			        	var data = result['data'];
			        	for(var i=0;i<data.length;i++){
			        		var str_k = data[i].username + " " + data[i].usercode;
			        		var str_v = data[i].usercode + "|" + data[i].username;
			        		user_info_list_k.push(str_k);
			        		user_info_list_v.push(str_v);
			        	}
			        	renderUserListSelect(user_info_list_k,user_info_list_v);
			        }
			    },
			    'json'
		); // end post
	} // end renderErpUserSelect
	
	$('#add-entity-account_str').bind('focus',function(event){
		var objUl = $("#div_data_user ul");
		objUl.html('');
		
		var str = $("#add-entity-account_str").val();
		filterUserInfo(str)
		
		var list_li_Obj = $("#div_data_user li");
		if(0 ==  list_li_Obj.length){
			return false;
		}
		if('readonly' != $('#add-entity-account_str').attr('readonly') ){
			$("#div_data_user").fadeIn();
		}
	}).bind('blur',function(event){
		$("#div_data_user").fadeOut();
	}).bind('keyup',function(event){
		$("#add-entity-account").val('');
		var str = $("#add-entity-account_str").val();
		filterUserInfo(str)
		$("#div_data_user").fadeIn();
	});
	
	function filterUserInfo(str){
		if(!str || '' == str){
			renderUserListSelect(user_info_list_k,user_info_list_v);
			return ;
		}
		var list_key = [];
		var list_val = [];
		if(0 ==  user_info_list_k.length){
			return false;
		}
		for(var i=0; i<user_info_list_k.length; i++){
			var key = user_info_list_k[i];
			if( -1 != key.indexOf(str)){
				list_key.push(user_info_list_k[i]);
				list_val.push(user_info_list_v[i]);
			}
		}
		renderUserListSelect(list_key,list_val);
	}
	
	function renderUserListSelect(list_key,list_val){
		var objUl = $("#div_data_user ul");
		objUl.html('');
		if(0 ==  list_key.length){
			return false;
		}
		for(var i=0; i<list_key.length; i++){
			var li = "<li><span>" + list_key[i] + "</span><input type='hidden' value='" + list_val[i] + "' /></li>";
			objUl.append(li);
		}
		$("#add-entity-account_str").attr("placeholder","");
		
		var list_li_Obj = $("#div_data_user li");
		if(0 ==  list_li_Obj.length){
			return false;
		}
		list_li_Obj.each(function(i,e){
			list_li_Obj[i].style.cursor="pointer";
			e.onmouseover = function(e){
				list_li_Obj[i].style.backgroundColor="#3399FF";
			};
			e.onmouseout = function(e){
				list_li_Obj[i].style.backgroundColor="white";
			};
			e.onclick = function(e){
				if('readonly' != $("#add-entity-account_str").attr('readonly') ){
					var selKey = $(list_li_Obj[i]).find("span").text();
					var selVal = $(list_li_Obj[i]).find("input").val();
					//alert(selKey + '--' +selVal);
					$("#add-entity-account_str").val(selKey);
					$("#add-entity-account").val(selVal);
				}
				$("#div_data_user").fadeOut();
			};
		});
	}
	

	/**************end--增加****************/
	
    prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " \r\n " + i + "=" + val;  
	});
	alert(tt);
}
