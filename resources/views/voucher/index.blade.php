@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#90be6d">
                    <h5 class="card-title mt-1 text-white"><i class="fas fa-tag"></i> Voucher
                    </h5>
                    <a href="{{ route('voucher.create') }}" class="btn btn-sm float-right modal-show text-white" style="background-color:#577590" title="Create Voucher"><i class="fas fa-plus"></i> Create Voucher</a>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Name</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @include('layouts.modal')
    </div>
</div>
@endsection

@push('after-script')
    <script type="text/javascript">
        $('#datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('voucher.data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'discount', name: 'discount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: '30%'}
        ]
    });

    $('body').on('click', '.modal-show', function(event) {
        event.preventDefault();

        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title');

        $('.modal-title').text(title);

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(response) {
                $('#modal-body').html(response);
                $('#modal-btn-save').text(me.hasClass('edit') ? 'Update' : 'Save');
            },
        });
        $('#modal-form').modal('show');
    });

    $('body').on('submit', '.voucher', function (event) {
        event.preventDefault();

        var form = $('#modal-form form'),
            url = form.attr('action'),
            method = form.attr('method');

        form.find('.help-block').remove();
        form.find('.form-group').removeClass('has-error');

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function(response) {
                form.trigger('reset');
                $('#modal-form').modal('hide');
                $('#datatable').DataTable().ajax.reload();

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Purchase has been saved!',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function (xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function (key, value) {
                        $('#' + key)
                            .closest('.form-group')
                            .addClass('has-error')
                            .append('<span class="help-block text-danger">' + value + '</span>');
                    });
                }
            }
        });
    });

    $('body').on('click', '.btn-delete', function(event) {
        event.preventDefault();
        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title'),
            csrf_token = $('meta[name="csrf-token"]').attr('content');

        swal.fire({
        title: 'Are you sure want to delete ' + title + ' ?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        '_method': "DELETE",
                        '_token' :'{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#datatable').DataTable().ajax.reload();

                        swal.fire({
                            icon: 'success',
                            title: 'Your file has been deleted!',
                            position: 'center',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xhr) {
                        swal.fire({
                            icon: 'error',
                            title: 'Opppss..',
                            text: 'Something Wrong :('
                        });
                    }
                });
            }
        });
    });
    </script>
@endpush
