$(document).ready(function() {
    const { settings } = window.translations;
    var upVote = $('.up-vote').data('upvote');

    $('.btn-vote').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        if($this.hasClass('not-allowed')) return;
        const review = $(this).data('review');
        const vote = $(this).data('value');
        const thisVote = $(`#count-${review}`);
        var up_index = $('.up-vote').index(this);
        var down_index = $('.down-vote').index(this);
        var index;
        if (up_index < 0) {
            index = down_index;
        }
        if (down_index < 0) {
            index = up_index;
        }
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
                console.log(data);
                if (data.status) {
                    $('.satisfied').eq(index).html(data.satisfied);
                } else if (data == 2) {
                    swal({
                        text: settings.book.loginToVote,
                    });
                } else if (data == 'error') {
                    swal({
                        icon: 'warning',
                        title: settings.book.voted,
                    });
                }
            }
        });
    });
});


$('.people-voted').click(function() {
    var id = $(this).data('id');
    //$('.show_people').empty();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/books/review/' + id,
        data: {
            id
        },
        success: function(data) {
            $.each(data, function(k, v) {
                $.each(data[k], function(key, value) {
                    $('.show_people').append(
                        '<li>' +
                        '<a href="#">' +
                        '<img src="' + value.avatar + '" alt="image" class="show_people_img"/>' +
                        '</a> ' + value.name +
                        '</li>'
                    );
                });
            });
            $('#people_voted').modal('show');
        },
    });
});
