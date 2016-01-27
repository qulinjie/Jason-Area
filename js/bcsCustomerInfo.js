$(document).ready(function(){

// 更新金额
$.post(BASE_PATH + 'bcsCustomer/loadInfo', {},function(result){},'json');

$(document).on('click', '#for-loadCustInfo-btn', function(event){
	$(this).text('更新中...');
	$.post(BASE_PATH + 'bcsCustomer/loadInfo', {},
	        function(result){
	            if(result.code != 0) {
	            	alert(result.msg + '(' + result.code + ')');
	            }else {
	            	alert(result.msg);
	            	window.location.reload();
	            }
	        },
	        'json'
	    );
});

/**************start--出金/入金****************/
function add_entity(inOut){
	$("#btn-add-entity").attr('disabled', 'disabled');
    $("#add-entity-hint").html('').fadeOut();
    
    var amount = $("#add-entity-amount").val();
    var pwd = $("#add-entity-pwd").val();
    
    var hint_html = '';
	if('' == amount || isNaN(amount) || 0 >= amount){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 正确的金额！' ;
    }
	if('' == pwd ){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写 密码！' ;
    }
    
    if(hint_html != ''){
        $("#add-entity-hint").html(hint_html).fadeIn();
        $("#btn-add-entity").removeAttr('disabled');
        return 0;
    }
    
    $("#btn-add-entity").html("转账中...");
    $.post(BASE_PATH + 'bcsCustomer/transfer', {
        	'amount':amount, 
        	'pwd':pwd,
        	'inOut':inOut
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
                    window.location.reload(); // 刷新页面
                }, 1000);
            }
        },
        'json'
    );
}

$(document).on('click', '#add-transferIn-new', function(event){
	transfer(2,$(this).text() );
});

$(document).on('click', '#add-transferOut-new', function(event){
	transfer(1,$(this).text() );
});

function transfer(inOut,title){
	$('#add-entity-modal').modal('show');
	$('#add-entity-modal').modal({keyboard: false});
		
	$('#btn-add-entity').show();
	$('#btn-add-entity').unbind("click");

	$('#info_entity_title').html(title);
	
	$("#add-entity-hint").html('').fadeOut();
	
	clear_entity_field();
	
	$("#btn-add-entity").removeAttr('disabled');
    $("#btn-add-entity").html("确定");
    
	$('#btn-add-entity').on('click',function(event){
		add_entity(inOut);
	});
}

function clear_entity_field(){
	$('#info-entity-id').val('');
	
	$('#add-entity-amount').val('');
	$('#add-entity-pwd').val('');
}

/**************end--出金/入金****************/

prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " | " + i + "=" + val;  
	});
	alert(tt);
}
