$(function(){
    $('#bcsRegisterSaveBtn').click(function(){
        $.post(BASE_PATH+'bcsRegister/doCreate',$('#bcsRegister').serialize(),function(res){

        },'json');
    });
    
    
    $(document).on('click', '#for-registerAccount-btn', function(event){
    	$(this).text('更新中...');
    	$.post(BASE_PATH + 'bcsRegister/registerAccount', {},
    	        function(result){
    	            if(result.code != 0) {
    	            	alert(result.msg + '(' + result.code + ')');
    	            }else {
    	            	alert(result.msg);
    	            }
    	            location.replace(location);
    	        },
    	        'json'
    	    );
    });
    
});