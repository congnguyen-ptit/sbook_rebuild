$(document).ready(function() {
    const { settings } = window.translations;
    var upVote = $('.up-vote').data('upvote');

    $('.btn-vote').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        if($this.hasClass('noy-allowed')) return;
        const review = $(this).data('review');
        const vote = $(this).data('value');
        const thisVote = $(`#count-${review}`);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/review/' + review + '/vote',
            dataType: 'JSON',
            data: {
                vote
            },
            success: function(data) {
                if (data.status) {
                    $('#satisfied').html(data.satisfied);
                    $('#unsatisfied').html(data.unsatisfied);
                } else if (data == 2) {
                    swal({
                        text: settings.book.loginToVote,
                    });
                } else if (data == 'error') {
                    swal({
                        text: settings.book.voted,
                    });
                }
            }
        });
    });
});
