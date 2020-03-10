$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var DatatablesDataSourceAjaxServer = {
    init: function() {
        $('table#officeList').DataTable({
            serverSide: true,
            ajax: {
                url: route('admin.office.ajax-index'),
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'address', name: 'address' },
                { data: 'description', name: 'description' },
                {
                    data: null,
                    name: 'id',
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<a href="#"' + 'class="btn btn-primary btn-sm mr-5 officeDetailbutton" data-officeId="' + data.id
                            + '" data-toggle="modal" data-target="#office_detail_model" title="' + trans('admin.view') + '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    },
                }
            ],
            order: [[ 0, 'desc' ]],
            language: trans('admin.searchPlaceholder'),
            fnDrawCallback: function(oSettings) {
                if ($('#m_table_1 tr').length < 11) {
                    $('#m_table_1_next').hide();
                    $('#m_table_1_last').hide();
                }
            },
            'fnCreatedRow': function (nRow, office, id) {
                $(nRow).attr('id', 'officeIdRow' + office.id);
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

$('#officeList').on('click', '.officeDetailbutton', function () {
    let officeId = $(this).attr('data-officeId');

    $.ajax({
        url: route('admin.offices.show', {id: officeId}),
        method: 'GET',
        success: function(data) {
            $('#office_detail_model .modal-header h6').text('');
            $('#office_detail_model #name').html('');
            $('#office_detail_model #address').text('');
            $('#office_detail_model #description').text('');
            $('#office_detail_model #wsm_workspace_id').text('');
            if (data) {
                $('#office_detail_model .modal-header h6').text(data.name);
                $('#office_detail_model #name').text(data.name);
                $('#office_detail_model #address').text(data.address);
                $('#office_detail_model #description').text(data.description);
                $('#office_detail_model #wsm_workspace_id').text(data.wsm_workspace_id);
            } else {
                swal.fire({
                    title: trans('admin.validate.office_notExists'),
                    icon: 'error',
                }).then(function () {
                    $('#office_detail_model .close').click();
                });
            }
        }
    });
});