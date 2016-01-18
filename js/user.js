$(document).ready(
		function() {
			$(".form_datetime").datetimepicker({
				language : 'zh-CN',
				format : 'yyyy-mm-dd hh:ii',
				weekStart : 1,
				todayBtn : 1,
				autoclose : 1,
				todayHighlight : 1,
				startView : 2,
				forceParse : 0,
				showMeridian : 0
			});
			$(document).on('click', '#btn-confirm-user-update', function(event) {
				$('#user-hint').text('正在保存...').show();	
				$.post(BASE_PATH + 'user/update',$('form').serialize(),function(res){
					if(res.code == 0){
						$('#user-hint').text('保存成功').hide(3000);
						window.location.reload();
					}else{
						$('#user-hint').text(res.msg+'('+res.code+')');
					}
				},'json');
			});
			$(document).on('click', '#entity-update-btn', function(event) {
				$('#user-hint').hide();				
				$('#id').val('');
				$('#user-account').val('');
				$('#user-name').val('');
				$('#user-remark').val('');
				$('#user-legal-name').val('');
				$('#user-company-name').val('');
				$('#user-business-license').val('');				
				$('input[type=radio]').removeAttr('checked');
				
				var id = Number($(this).parent().parent().parent().find(':first').text());	
				$.post(BASE_PATH + 'user/getInfo',{'id':id},function(res){
					if(res.code == 0){
						$('#id').val(res.data.id);
						$('#user-account').val(res.data.account);
						$('#user-name').val(res.data.real_name);
						$('#user-legal-name').val(res.data.legal_name);
						$('#user-company-name').val(res.data.company_name);
						$('#user-business-license').val(res.data.business_license);
						$('#user-remark').val(res.data.comment);
						$('input[name=user-status][value='+res.data.status+']').prop('checked','checked');
						$('input[name=user-person-cert][value='+res.data.personal_authentication_status+']').prop('checked','checked');
						$('input[name=user-company-cert][value='+res.data.company_authentication_status+']').prop('checked','checked');
						$('#person-cert').attr('href',BASE_PATH+'user/getCert?id='+res.data.id+'&flag=10000');
						$('#enterprise-cert').attr('href',BASE_PATH+'user/getCert?id='+res.data.id+'&flag=20000');
					}				
				},'json');
				$('#audit-personal-modal').modal();
			});

			$(document).on('click', '#entity-search-btn', function(event) {
				search_entity(1);
			});

			$(document).on('click', '#entity-clear-btn', function(event) {
				search_clearFields();
				search_entity(1);
				return;
			});

			$(document).on('click', '#entity-list-prev', function(event) {
				var cur_page = $("#entity-current-page").html();
				var total_page = $("#entity-total-page").html();

				if (cur_page == '1') {
					Messenger().post('已经是第一页了！');
					return;
				}
				var page = cur_page * 1 - 1;
				search_entity(page);
			});

			$(document).on('click', '#entity-list-next', function(event) {
				var cur_page = $("#entity-current-page").html();
				var total_page = $("#entity-total-page").html();
				if (cur_page == total_page) {
					Messenger().post('已经是最后一页了！');
					return;
				}
				var page = cur_page * 1 + 1;
				search_entity(page);
			});

			$(document).on('change', '#entity-custom-page', function(event) {
				var sel_page = $('#entity-custom-page').val();
				var cur_page = $("#entity-current-page").html();
				var total_page = $("#entity-total-page").html();
				search_entity(sel_page);
			});

			function entitySetSelectedPage() {
				if ($('#entity-custom-page') && $("#entity-current-page")
						&& $("#entity-total-page")) {
					var cur_page = $("#entity-current-page").html();
					var total_page = $("#entity-total-page").html();
					var selObj = $('#entity-custom-page');
					selObj.empty();
					for (var i = 1; i <= total_page; i++) {
						selObj.append("<option value='" + i + "'>" + i
								+ "</option>");
					}
					selObj.val(cur_page);
				}
			}
			$(function() {
				entitySetSelectedPage();
			});

			function search_entity(page) {
				var cur_page = $("#entity-current-page").html();
				var total_page = $("#entity-total-page").html();
				if (page < 1 || page > total_page * 1) {
					Messenger().post('页码错误！');
					return;
				}

				$("#entity-search-btn").attr('disabled', 'disabled');
				$("#search-entity-hint").html('').fadeOut();

				var time1 = $("#entity-search-time1").val();
				var time2 = $("#entity-search-time2").val();
				var account = $("#entity-search-account").val();
				var nicename = $("#entity-search-nicename").val();
				var status = $("#entity-search-status").val();

				if (-1 == status) {
					status = "";
				}

				// 查找
				$.post(BASE_PATH + 'user/searchList', {
					'time1' : time1,
					'time2' : time2,
					'account' : account,
					'nicename' : nicename,
					'status' : status,
					'page' : page
				}, function(result) {
					if (result.code != 0) {
						$("#search-entity-hint").html(
								result.msg + '(' + result.code + ')').fadeIn();
					} else {
						$("#entity-list").html(result.data.entity_list_html);
					}
					$("#entity-search-btn").removeAttr('disabled');
					entitySetSelectedPage();
				}, 'json');
			}

			function search_clearFields() {
				$("#entity-search-time1").val("");
				$("#entity-search-time2").val("");
				$("#entity-search-account").val("");
				$("#entity-search-nicename").val("");
				$("#entity-search-status").val("-1");
			}

			prettyPrint();
		});

function kk(obj) {
	var tt = '';
	jQuery.each(obj, function(i, val) {
		tt = tt + " \r\n " + i + "=" + val;
	});
	alert(tt);
}
