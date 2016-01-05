$(document).ready(function(){

$(document).on('click', '#entity-search-btn', function(event){
	$("#search-list-hint").html('').hide();
	var FMS_TRANS_NO = $("#entity-search-FMS_TRANS_NO").val();
	var FUNC_CODE = $("#entity-search-FUNC_CODE").val();
	
	var hint_html = '';
    if( !FMS_TRANS_NO || '' == FMS_TRANS_NO ){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写交易流水号！' ;
    }
    if( !FUNC_CODE || '' == FUNC_CODE ){
    	hint_html += (hint_html == '' ? '' : '<BR>') + '请填写功能号！' ;
    }
    
    if(hint_html != ''){
        $("#search-list-hint").html(hint_html).fadeIn();
        $("#entity-search-btn").removeAttr('disabled');
        return 0;
    }
    
    $('#entity-list').html("<div style='width:100%;text-align:center;'><img alt='正在加载数据...' src='" + BASE_PATH + "view/images/tips_loading.gif'/></div>");
    
	$.post(BASE_PATH + 'bcsTrade/tradeStatusQuery', {'FMS_TRANS_NO':FMS_TRANS_NO,'FUNC_CODE':FUNC_CODE},
	    function(result){
	        if(result.code != 0) {
	            $("#search-list-hint").html(result.msg + '(' + result.code + ')' + ',请刷新页面').fadeIn();
	        } else {
	        	$('#entity-list').html(result.data.entity_list_html);
	        }
	    },
	    'json'
	);
	
});


prettyPrint();
});

function kk(obj){
	var tt = '';
	jQuery.each(obj, function(i, val) {  
	    tt = tt + " | " + i + "=" + val;  
	});
	alert(tt);
}

