$(document).ready(function () {
    $('a.button-like').click(function () {
        var button = $(this);
        var params = {
            'id': $(this).attr('data-id')
        };
        button.hide();
        button.siblings('.button-unlike').show();
        $.post('/post/default/like', params, function (data) {
            if (data.success) {
                button.siblings('.button-unlike').children('.likes-count').text(data.likesCount);
            } else {
                button.show();
                button.siblings('.button-like').show();
            }
        });
        return false;
    });

    $('a.button-unlike').click(function () {
        var button = $(this);
        var params = {
            'id': $(this).attr('data-id')
        };
        button.hide();
        button.siblings('.button-like').show();
        $.post('/post/default/unlike', params, function (data) {
            if (data.success) {
                button.siblings('.button-like').children('.likes-count').text(data.likesCount);
            } else {
                button.show();
                button.siblings('.button-unlike').show();
            }
        });
        return false;
    });
});

