$('.tab-type').click(function (e) {
    e.preventDefault();
    let type = $(this).attr('data-type');
    let listPage = $('a.page-link');
    $.each(listPage, (index, item) => {
        if (type === 'list') {
            let _href = $(item).attr('href');
            $(item).attr('href', _href + `&type=${type}`);
        }else{
            let _href = $(item).attr('href').replace('type=list', '').replace(/&/g,'');
            $(item).attr('href', _href);
        }
    });
});
