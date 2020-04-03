$(document).ready(function(){
    let types = [];
    $('body').on('click', '.sort-type', async function(){
        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-outline-dark');
        $.each($('.sort-type'), function(id, item){
            if($(item).hasClass('btn-success')){
                types.push($(item).data('type'));
            }
        });

        await $.get('/my-request', { types })
                .then(res => {
                    $('#result-req').html(res);
                    if(types.length === 0) $('#all-data').addClass('btn-success').removeClass('btn-outline-dark');
                    else $('#all-data').addClass('btn-outline-dark').removeClass('btn-success');
                    types = [];
                });
    }); 
});
