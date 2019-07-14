$(document).ready(function () {
    $('a.comment-delete').click(function () {
        var params = {
            'id': $(this).attr('data-id')
        };
        var comment = $("#comment-"+params['id']);
        $.post('/post/default/delete-comment', params, function (data) {
            console.log(data);
            if(data.success) {
                comment.hide();
            }
        });
        return false;
    });
});



