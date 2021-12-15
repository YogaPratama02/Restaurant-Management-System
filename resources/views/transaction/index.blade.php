@extends('layouts.default')

@section('title','All Transaction')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/day.css')}}">
<div class="container mt-3">
    <div class="row input-daterange">
        <div class="col-md-3 col-sm-4 col-lg-3 col-5 form-group">
            <input type="text" name="date_start" id="date_start" class="form-control" placeholder="from date.." readonly />
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3 col-5 form-group">
            <input type="text" name="date_end" id="date_end" class="form-control" placeholder="end date.." readonly />
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3 col-5">
            <button type="button" name="filter" id="filter" class="btn text-black" style="background-color: #97cf6e">Filter</button>
            <button type="button" name="refresh" id="refresh" class="btn text-black" style="background-color: #97cf6e">Refresh</button>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3 col-5">
            {{-- <button type="button" class="btn text-black excel" style="background-color: #97cf6e">Export To Excel</button> --}}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-center">
                <div style="font-size: 26px; font-weight: 510">All Transaction</div>
                <span id="time" style="font-size: 24px"></span>
            </div>
            <div class="card-body">
                <div class="tab-content">
                <div class="table-reponsive">
                    <table id="transaction" class="table hover display" style="width:100%">
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script type="text/javascript">
    $(document).ready(function(){
        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        fecthData();
        function fecthData(date_start = '', date_end=''){
            var table = $('#transaction').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url : "{{ route('transaction.data') }}",
                type: "GET",
                data: {date_start: date_start, date_end:date_end}
            },
            scrollX: true,
            columns: [
                    {title: 'No', data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false, width: '7.5%', className: 'dt-center'},
                    {title: 'Date', data: 'created_at', name: 'created_at', width: '17.5%', className: 'dt-center'},
                    {title: 'Customer Name', data: 'customer_name', name: 'customer_name', width: '17.5%', className: 'dt-center'},
                    {title: 'Total HPP', data: 'total_hpp', name: 'total_hpp', width: '17.5%', className: 'dt-center'},
                    {title: 'Total Price', data: 'total_price', name: 'total_price', width: '17.5%', className: 'dt-center'},
                    {title: 'Total Price (VAT)', data: 'total_vatprice', name: 'total_vatprice', width: '17.5%', className: 'dt-center'},
                    {title: 'Payment Type', data: 'payment_type', name: 'payment_type', width: '17.5%', className: 'dt-center'},
                    {title: 'Action', data: 'action', name: 'action', orderable:false, width: '17.5%', className: 'dt-center'}
                ],
            });
        }

        $('#filter').click(function(){
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();
            if(date_start != '' && date_end != ''){
                $('#time').html(date_start).append(' to ', date_end );
                $('#transaction').DataTable().destroy();
                fecthData(date_start, date_end);
            }else{
                alert('Both date is required');
            }
        });

        $('#refresh').click(function(){
            $('#date_start').val('');
            $('#date_end').val('');
            $('#time').html('');
            $('#transaction').DataTable().destroy();
            fecthData();
        });

        $('body').on('click', '.btn-delete', function(event) {
            event.preventDefault();
            var me = $(this),
                url = me.attr('href'),
                title = me.attr('title'),
                csrf_token = $('meta[name="csrf-token"]').attr('content');

            swal.fire({
            title: 'Are you sure want to delete Transaction ?',
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
                            $('#transaction').DataTable().ajax.reload();

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
    });
</script>
@endpush
