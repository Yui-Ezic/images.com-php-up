$(document).ready(function () {
    $('a.comment-delete').click(function () {
        var params = {
            'id': $(this).attr('data-id')
        };
        var textArea = $("#textArea-"+params['id']);
        $.post('/post/default/delete-comment', params, function (data) {
            console.log(data);
            if(data.success) {
                textArea.html("Comment was deleted.");
                $('#comment-'+params['id']+' a.comment-refresh').show();
                $('#comment-'+params['id']+' a.comment-delete').hide();
            }
        });
        return false;
    });
    
    $('a.comment-refresh').click(function() {
        console.log("clicked");
        var params = {
            'id': $(this).attr('data-id')
        };
        var textArea = $("#textArea-"+params['id']);
        $.post('/post/default/refresh-comment', params, function (data) {
            console.log(data);
            if(data.success) {
                textArea.html(data.text);
                $('#comment-'+params['id']+' a.comment-refresh').hide();
                $('#comment-'+params['id']+' a.comment-delete').show();
            }
        });
        return false;
    });
});



