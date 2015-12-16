$(document).ready(function(){

$(document).on('click', '#for-loadMarketInfo-btn', function(event){
	$(this).text('更新中...');
	$.post(BASE_PATH + 'bcsMarket/loadInfo', {},
	        function(result){
	            if(result.code != 0) {
	            	alert(result.msg + '(' + result.code + ')');
	            }else {
	            	alert(result.msg);
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
