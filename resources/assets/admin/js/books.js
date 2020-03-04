$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var DatatablesDataSourceAjaxServer = {
    init: function() {
        $('table#bookList').DataTable({
            serverSide: true,
            ajax: {
                url: route('admin.book.show'),
            },
            columns: [
                { data: 'title', name: 'title' },
                { data: 'author', name: 'author' },
                { data: 'avg_star', name: 'avg_star' },
                { data: 'count_viewed', name: 'count_viewed' },
                {
                    data: null,
                    name: 'id',
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<a href="#"' + 'class="btn btn-primary btn-sm mr-5 bookDetailbutton" data-bookId="' + data.id + '" data-toggle="modal" data-target="#book_detail_model" title="' + trans('admin.view') + '"><i class="fa fa-eye" aria-hidden="true"></i></a>'+
                        '<a href="#"' + ' class= "btn btn-info m-btn m-btn--custom btn-sm bookEditbutton" data-bookId="' + data.id + '" data-toggle="modal" data-target="#book_update_model" title="' + trans('admin.edit') + '"> <i class="fa fa-edit"></i></a>' +
                        '<button type="submit" class="btn btn-danger m-btn m-btn--custom btn-9 btn-sm mt-5 bookRemovebutton" data-bookId="' + data.id + '" title="' + trans('admin.delete') + '"><i class="fa fa-trash"></i></button>';
                    },
                }
            ],
            order: [[ 6, 'desc' ]],
            language: trans('admin.searchPlaceholder'),
            fnDrawCallback: function(oSettings) {
                if ($('#m_table_1 tr').length < 11) {
                    $('#m_table_1_next').hide();
                    $('#m_table_1_last').hide();
                }
            },
            'fnCreatedRow': function (nRow, book, id) {
                $(nRow).attr('id', 'bookIdRow' + book.id);
            },
        });
    }
};

jQuery(document).ready(function() {
    DatatablesDataSourceAjaxServer.init();

    $('th:last-child .sort').addClass('active');
    $('th').click(function() {
        $('.sort').removeClass('active');
        $(this).children().toggleClass('active');
    });
});

$('#bookList').on('click', '.bookDetailbutton', function () {
    let bookId = $(this).attr('data-bookId');

    $.ajax({
        url: route('admin.books.show', {id: bookId}),
        method: 'GET',
        success: function(data) {
            $('#book_detail_model .modal-header h6').text('');
            $('#book_detail_model #book_img').attr('src', '');
            $('#book_detail_model #book_img').attr('alt', '');
            $('#book_detail_model #listCategoies').html('');
            $('#book_detail_model #author').text('');
            $('#book_detail_model #sku').text('');
            $('#book_detail_model #avg_star').text('');
            $('#book_detail_model #view').text('');
            $('#book_detail_model #description').text('');
            if (data) {
                let image_priority = '';
                data.medias.forEach(function (image) {
                    if (image.priority == 1) {
                        image_priority = image.path;
                    }
                });
                $('#book_detail_model .modal-header h6').text(data.title);
                $('#book_detail_model #book_img').attr('src', (config.image_paths.book + image_priority));
                $('#book_detail_model #book_img').attr('alt', (image_priority));
                data.categories.forEach(function (category) {
                    categoryELement = '<li class="list-group-item">' + category.name + '</li>';
                    $('#book_detail_model #listCategoies').append(categoryELement);
                });
                $('#book_detail_model #author').text(data.author);
                $('#book_detail_model #sku').text(data.sku);
                $('#book_detail_model #avg_star').text(data.avg_star);
                $('#book_detail_model #view').text(data.count_viewed);
                $('#book_detail_model #description').text(data.description);
            } else {
                swal.fire({
                    title: trans('admin.validate.book_notExists'),
                    icon: 'error',
                }).then(function () {
                    $('#book_detail_model .close').click();
                });
            }
        }
    });
});

$('#bookList').on('click', '.bookRemovebutton', function () {
    let bookId = $(this).attr('data-bookId');

    swal.fire({
        title: trans('admin.swal.are_you_sure'),
        type: 'warning',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: trans('admin.swal.no_cancel'),
        confirmButtonText: trans('admin.swal.yes_delete'),
    }).then(function(result) {
        if (result && result.value) {
            $.ajax({
                url: route('admin.books.destroy', {id: bookId}),
                method: 'DELETE',
                success: function(data) {
                    if (data) {
                        $('#bookList tbody #bookIdRow' + bookId).remove();
                        swal.fire({
                            title: trans('admin.swal.title_success'),
                            type: 'success',
                            icon: 'success',
                        });
                    } else {
                        swal.fire({
                            title: trans('admin.validate.book_notExists'),
                            icon: 'error',
                        });
                    }
                }
            });
        }
    });
});

$('#bookList').on('click', '.bookEditbutton', function () {
    let bookId = $(this).attr('data-bookId');

    $.ajax({
        url: route('admin.books.show', {id: bookId}),
        method: 'GET',
        success: function(data) {
            $('#book_update_model form').attr('action', '');
            $('#book_update_model .modal-header h6').text('');
            $('#book_update_model .modal-body #book_name').val('');
            $('#book_update_model .modal-body #author').val('');
            $('#book_update_model .modal-body #description').val('');
            $('#book_update_model #listBookImages').html('');
            $('#book_update_model #listImageBookDiv').css('display', 'none');
            $('#book_update_model select option').prop("selected", false);
            if (data) {
                $('#book_update_model form').attr('action', '/admin/books/' + data.id);
                $('#book_update_model .modal-header h6').text(data.title);
                $('#book_update_model .modal-body #book_name').val(data.title);
                $('#book_update_model .modal-body #author').val(data.author);
                $('#book_update_model .modal-body #description').val(data.description);
                data.categories.forEach(function (category) {
                    $('#book_update_model select option[value="' + category.id + '"').prop("selected", true);
                });
                if (data.medias && data.medias.length > 0) {
                    data.medias.forEach(function (image) {
                        checked = parseInt(image.priority) == 1 ? 'checked' : '';
                        element = '<tr>' +
                            '<td><input type="radio" name="main_image" value="' + image.id + '" ' + checked + '></td>' +
                            '<td><input type="checkbox" name="old_image[]" checked value="' + image.id + '"></td>' +
                            '<td><img class="w-20" src="' + config.image_paths.book + image.path + '" alt="' + image.path + '"></td>' +
                            '</tr>';

                        $('#book_update_model #listBookImages').append(element);
                    });
                    $('#book_update_model #listImageBookDiv').css('display', 'block');
                }
            } else {
                swal.fire({
                    title: trans('admin.validate.book_notExists'),
                    icon: 'error',
                }).then(function () {
                    $('#book_update_model .close').click();
                });
            }
        }
    });
});

$('#book_update_model').on('change', 'input[type=radio]', function () {
    let imageId = $(this).val();
    $('#book_update_model input[value=' + imageId + ']').prop('checked', true);
});

$('#book_update_model').on('change', 'input[type=checkbox]', function () {
    let imageId = $(this).val();
    if (!$(this).is(':checked') && $('#book_update_model input[value=' + imageId + ']:radio').is(':checked')) {
        $(this).prop('checked', true);
    }
});

$.validator.addMethod("regex", function(value, element, regexpr) {
    return regexpr.test(value);
}, trans('admin.validate.regex'));

function validateBook($formElement) {
    $formElement.validate({
        rules: {
            title: {
                required: true,
                minlength: 5,
                maxlength: 191,
            },
            author: {
                required: true,
                minlength: 5,
                maxlength: 191,
            },
            'categories[]': {
                required: true,
            },
            description: {
                required: true,
                minlength: 5,
                maxlength: 2500,
            },
        },
        submitHandler: function(form) {
            form.find(button[type=submit]).attr("disabled", true);
            form.submit();
        }
    });
}

$(document).ready(function () {
    validateBook($('#book_update_model form'));
    validateBook($('#book_create_model form'));

    $('.time-datepicker').datepicker({
        format: config.formatYMD
    });
});

function validateDisabled(form, elementDisabled) {
    if (form.valid()) {
        elementDisabled.prop('disabled', false);
    } else {
        elementDisabled.prop('disabled', true);
    }
}

$('#book_create_model input').keyup(function () {
    validateDisabled($(this), $('#book_create_model form button[type=submit]'));
});

$('#book_create_model textarea').keyup(function () {
    validateDisabled($(this), $('#book_create_model form button[type=submit]'));
});

$('#book_update_model input').keyup(function () {
    validateDisabled($(this), $('#book_update_model form button[type=submit]'));
});

$('#book_update_model textarea').keyup(function () {
    validateDisabled($(this), $('#book_update_model form button[type=submit]'));
});

$('.file-input').fileinput({
    browseLabel: 'Browse',
    browseIcon: '<i class="icon-file-plus"></i>',
    initialCaption: "No file selected",
    maxFileCount: 5,
    allowedFileExtensions: ["jpg", "png"],
});
