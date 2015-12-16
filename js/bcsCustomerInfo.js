$(document).ready(function(){

$(document).on('click', '#for-loadCustInfo-btn', function(event){
	$(this).text('更新中...');
	$.post(BASE_PATH + 'bcsCustomer/loadInfo', {},
	        function(result){
	            if(result.code != 0) {
	            	alert(result.msg + '(' + result.code + ')');
	            }else {
	            	alert(result.msg);
	            }
	            $(this).text('更新完成，请刷新页面！');
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
