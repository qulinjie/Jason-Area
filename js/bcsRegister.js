$(function(){
    $(document).on('click', '#bcsRegisterSaveBtn', function(event){
    	$(this).text('更新中...');
    	$.post(BASE_PATH + 'bcsRegister/registerAccount', $('#bcsRegister').serialize(),
    	        function(result){
    	            if(result.code != 0) {
    	            	alert(result.msg + '(' + result.code + ')');
    	            }else {
    	            	alert('注册成功,帐号：'+result.data.ACCOUNT_NO);
    	            }
    	            window.location.replace(window.location);
    	        },
    	        'json'
    	    );
    });
    
});