$(function(){
    $('#bcsRegisterSaveBtn').click(function(){
        $.post(BASE_PATH+'bcsRegister/doCreate',$('#bcsRegister').serialize(),function(res){

        },'json');
    });
});