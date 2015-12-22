$(document).ready(function() {
    $(document).on('click', '#message-list-prev', function (event) {
        var cur_page = $("#message-current-page").html();
        var total_page = $("#message-total-page").html();

        if (cur_page == '1') {
            Messenger().post('已经是第一页了！');
            return;
        }
        var page = cur_page * 1 - 1;
        search_message(page);
    });

    $(document).on('click', '#message-list-next', function (event) {
        var cur_page = $("#message-current-page").html();
        var total_page = $("#message-total-page").html();
        if (cur_page == total_page) {
            Messenger().post('已经是最后一页了！');
            return;
        }
        var page = cur_page * 1 + 1;
        search_message(page);
    });

    $(document).on('change', '#message-custom-page', function (event) {
        var sel_page = $('#message-custom-page').val();
        var cur_page = $("#message-current-page").html();
        var total_page = $("#message-total-page").html();
        search_message(sel_page);
    });

    function messageSetSelectedPage() {
        if ($('#message-custom-page') && $("#message-current-page") && $("#message-total-page")) {
            var cur_page = $("#message-current-page").html();
            var total_page = $("#message-total-page").html();
            var selObj = $('#message-custom-page');
            selObj.empty();
            for (var i = 1; i <= total_page; i++) {
                selObj.append("<option value='" + i + "'>" + i + "</option>");
            }
            selObj.val(cur_page);
        }
    }

    $(function () {
        messageSetSelectedPage();
    });

    function search_message(page) {
        var cur_page = $("#message-current-page").html();
        var total_page = $("#message-total-page").html();
        if (page < 1 || page > total_page * 1) {
            Messenger().post('页码错误！');
            return;
        }

        $("#message-search-btn").attr('disabled', 'disabled');
        $("#search-message-hint").html('').fadeOut();

        //查找
        $.post(BASE_PATH + 'message/searchList', {
                'page': page
            },
            function (result) {
                if (result.code != 0) {
                    $("#search-message-hint").html(result.msg + '(' + result.code + ')').fadeIn();
                } else {
                    $("#message-list").html(result.data.message_list_html);
                }
                messageSetSelectedPage();
            },
            'json'
        );
    }

});

