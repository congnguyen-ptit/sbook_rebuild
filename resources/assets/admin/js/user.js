const textDatatable = trans('admin.textDatatable');
var DatatablesBasicPaginations = {
    init: function() {
        $('table#list-user').DataTable({
            serverSide: true,
            ajax: {
                url: route('admin.users.index'),
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'position', name: 'position' },
                {
                    data: 'roles',
                    name: 'roles',
                    defaultValue: 'N/A',
                    orderable: false,
                    render: function(data, type, row) {
                        if (data.length > 0) {
                            var html = '';
                            html += '<select class="form-control m-bootstrap-select opac-1">';
                            $.each(data, function(index, role) {
                                html += '<option>' + role.name + '</option>';
                            });
                            html += '</select>';
                            return html;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'office',
                    name: 'office_id',
                    render: function (data, type, row) {
                        return data == null ? 'N/A' : data.name;
                    }
                },
                { data: 'reputation_point', name: 'reputation_point' },
                { data: 'employee_code', name: 'employee_code' },
                {
                    data: 'action',
                    name: 'action',
                    render: function (data, type, row) {
                        return data;
                    },
                }
            ],
            order: [[ 0, 'asc' ]],
            language: textDatatable,
            fnDrawCallback: function(oSettings) {
                if ($('#m_table_1 tr').length < 11) {
                    $('#m_table_1_next').hide();
                    $('#m_table_1_last').hide();
                }
            },
            'fnCreatedRow': function (nRow, book, id) {
                $(nRow).attr('id', 'bookIdRow' + book.id);
            },
        })
    }
};
jQuery(document).ready(function() {
    DatatablesBasicPaginations.init();

    $('th:first-child .sort').addClass('active');
    $('th').click(function() {
        $('.sort').removeClass('active');
        $(this).children().toggleClass('active');
    });
});

$(document).ready( function () {
    $('body').on('click', '.trigger-modal', function () {
        const id = $(this).attr('data-id');
        $.ajax({
            url: route('admin.users.show', id),
            method: 'GET',
            success: function (res) {
                $('#detail-user').html(res);
                $('#btn-modal').trigger('click');
            },
            error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText);
                if (xhr.status === config.STATUS.CLIENT.NOT_FOUND) {
                    swal.fire({
                        text: response.message,
                        type: 'warning',
                        icon: 'warning',
                    });
                }
            }
        });
    });
});
