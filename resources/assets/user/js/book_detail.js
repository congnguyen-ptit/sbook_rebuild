
let bar_chart;
$('.product-social-links').on('click', '#chartUserEvaluationBt', function () {
    let nowYear = $('#chartUserEvaluationModel #yearFilterSelect').val();
    let book_id = $('#chartUserEvaluationModel #yearFilterSelect').attr('data-bookId');
    getUserEvaluationByYear(book_id, nowYear).then(data => {
        bar_chart = c3.generate({
            bindto: '#userEvaluationchart',
            size: {
                width: 528,
                height: 350
            },
            data: {
                columns: [
                    data.reviews
                ],
                type: 'line'
            },
            color: {
                pattern: ['#2196F3']
            },
            bar: {
                width: {
                    ratio: 0.5
                }
            },
            axis: {
                x: {
                    label: data.label_x,
                    type: 'category',
                    categories: data.months,
                    tick: {
                        rotate: -45
                    },
                },
                y: {
                    label: data.label_y
                },
            },
            grid: {
                y: {
                    show: true
                }
            }
        });
    });
});

$('#chartUserEvaluationModel').on('change', '#yearFilterSelect', function () {
    let nowYear = $(this).val();
    let book_id = $(this).attr('data-bookId');
    getUserEvaluationByYear(book_id, nowYear).then(data => {
        bar_chart.load({
            columns: [
                data.reviews
            ]
        });
    });
});

async function getUserEvaluationByYear(book_id, year) {
    var data;
    await $.ajax({
        url: '/books/' + book_id + '/statistic',
        beforeSend: function() {
            $('.loader').show();
        },
        type: 'GET',
        dataType: 'json',
        data: {
            year: year
        },
    }).done(function (rs) {
        $('.loader').hide();
        data = rs;
    });

    return data;
}

$(document).ready(function(){
    $('#list-owner').click(function (e) {
        e.preventDefault();
        $('#modal-list-owner').modal('show');
    });
});
