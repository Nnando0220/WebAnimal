$(document).ready(function (){
    $('.btn-like').on('click', function (){
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
        }else {
            $(this).toggleClass('active');
        }
        const postId = $(this).data('post-id');
        const likeCountElement = $(this).find('.like-count');

        const url = '/like/' + postId;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: url,
            data: {
                postId: postId,
            },
            dataType: 'json',
            success: function (response) {
                likeCountElement.text(response.likes);
            },
            error: function (xhr, status, error) {
                console.error('Erro ao processar like:', error);
            }
        });
   });
});
