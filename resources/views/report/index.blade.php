@extends('layouts.default')

@section('content')
    <div class="container">
            <div class="card-body">
                <div class="row input-daterange">
                    <div class="col-md-4 form-group">
                        <input type="text" name="date_start" id="date_start" class="form-control" placeholder="from date.." readonly style="width: 300px;" />
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" name="date_end" id="date_end" class="form-control" placeholder="end date.." readonly style="width: 300px;"  />
                    </div>
                    <div class="col-md-4">
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-primary">Refresh</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered text-center" style="width:100%">
                                <thead>
                                    <tr class="text-lite text-center">
                                        <th scope="col">No</th>
                                        <th scope="col">Date Time</th>
                                        <th scope="col">Staff</th>
                                        <th scope="col">Total HPP</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Total VAT</th>
                                        <th scope="col">Total Price VAT</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="details"></div>
                </div>
                <div class="col-md-12 text-center">
                    <div style="font-size: 2.5rem">Sale report</div>
                    <span id="sale" style="font-size: 2rem"></span>
                </div>
                <div class="card-body" id="hacim">
                    <table id="total" class="table table-bordered text-center" style="width:100%">
                        <thead>
                            <tr class="text-lite text-center">
                                <th scope="col">Total HPP</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Total Price VAT</th>
                                <th scope="col">Cash Total</th>
                                <th scope="col">Bank Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="a"></td>
                                <td id="b"></td>
                                <td id="c"></td>
                                <td id="d"></td>
                                <td id="e"></td>
                            </tr>
                        </tbody>
                        @csrf
                    </table>
                </div>
                <div class="col-md-12 text-center">
                    <div style="font-size: 2.5rem">Sale Menu</div>
                    {{-- <span id="menu" style="font-size: 2rem"></span> --}}
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-center" style="width:100%">
                        <thead>
                            <tr class="text-lite text-center">
                                <th scope="col">Menu</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody id="menus">

                        </tbody>
                    </table>
                </div>
            </div>
    </div>
@endsection

@push('after-script')
<script src="code.jquery.com/jquery-3.5.1.js"></script>
<script src="//cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.input-daterange').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });
    load_data();
    function load_data(date_start = '', date_end = '')
    {
        $('#datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: "{{ route('report.index') }}",
                data: {date_start: date_start, date_end:date_end},
                type: "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id', width: '15px'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'user_name', name: 'user_name'},
                {data: 'total_hpp', name: 'total_hpp'},
                {data: 'total_price', name: 'total_price'},
                {data: 'total_vat', name: 'total_vat'},
                {data: 'total_vatprice', name: 'total_vatprice'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }

    fecthData();
    function fecthData(date_start = '', date_end='')
    {
        $.ajax({
            url: "{{route('report.resume')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('#a').html('Rp. ').append(data.hpp);
                $('#b').html('Rp. ').append(data.price);
                $('#c').html('Rp. ').append(data.vatprice);
                $('#d').html('Rp. ').append(data.cash);
                $('#e').html('Rp. ').append(data.bank);
                $('#menus').html(data.menus);
            },
            error: function(data)
            {
                alert('not responding');
            }
        });
    }

    // excel();
    // function excel(date_start)

    $('#filter').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            $('#sale').html(date_start).append(' to ', date_end );
            $('#datatable').DataTable().destroy();
            load_data(date_start, date_end);
            fecthData(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#datatable').DataTable().destroy();
        $('#sale').hide();
        load_data();
        fecthData();
    });

    $('body').on('click', '.view-detail', function(event){
            event.preventDefault();
            var me = $(this),
                url = me.attr('href');

            $.ajax({
                url: url,
                dataType: 'html',
                success: function(response){
                    $('#details').html(response);
                    $('.btn-cobi').click(function(response){
                        $('#details').hide();
                    });
                }
            });
        });

        $('body').on('click', '.view-detail', function(response){
            $('#details').show();
        });
});
</script>
@endpush
