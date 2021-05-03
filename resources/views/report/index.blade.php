@extends('layouts.default')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/day.css')}}">
    <div class="container">
            <div class="card-body">
                <div class="row input-daterange">
                    <div class="col-md-3 col-sm-4 col-lg-3 col-5 form-group">
                        <input type="text" name="date_start" id="date_start" class="form-control" placeholder="from date.." readonly />
                    </div>
                    <div class="col-md-3 col-sm-4 col-lg-3 col-5 form-group">
                        <input type="text" name="date_end" id="date_end" class="form-control" placeholder="end date.." readonly />
                    </div>
                    <div class="col-md-3 col-sm-4 col-lg-3 col-5">
                        <button type="button" name="filter" id="filter" class="btn text-black" style="background-color: #90be6d">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn text-black" style="background-color: #90be6d">Refresh</button>
                    </div>
                    <div class="col-md-3 col-sm-4 col-lg-3 col-5">
                        <button type="button" class="btn text-black excel" style="background-color: #90be6d">Export To Excel</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 text-center">
                            <div style="font-size: 26px; font-weight: 510">Daily Sale And Menu report</div>
                            <span id="time" style="font-size: 24px"></span>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                            <div class="table-reponsive">
                                <table id="sale_daily" class="table hover display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date Time</th>
                                            <th>Total HPP</th>
                                            <th>Total Price</th>
                                            <th>Total Sale</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" style="width:100%">
                                    <thead class="text-black" style="font-size:16px;">
                                        <tr class="text-lite text-center">
                                            <th scope="col">Total Cash</th>
                                            <th scope="col">Total Bank Transfer</th>
                                            <th scope="col">Total Payment Card</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="cash"></td>
                                            <td class="transfer"></td>
                                            <td class="credit"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body table-responsive">
                            <table id="menu_daily" class="table hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Menu</th>
                                        <th>Total Sold</th>
                                    </tr>
                                </thead>
                            </table>
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
    function fecthData(date_start = '', date_end='')
    {
        var sale = $('#sale_daily').DataTable({
        responsive: true,
        ajax: {
            url : "{{ route('report.dataDaily') }}",
            type: "GET",
            data: {date_start: date_start, date_end:date_end}
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, className: 'dt-center'},
        {data: 'date', name: 'date', className: 'dt-center'},
        {data: 'total_hpp', name: 'total_hpp', className: 'dt-center'},
        {data: 'total_price', name: 'total_price', className: 'dt-center'},
        {data: 'total_vatprice', name: 'total_vatprice', className: 'dt-center'},
        ],
        "footerCallback": function ( row, data, date_start, date_end, display ) {
            var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\Rp. ,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            angka = api
                .column(4)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                var rupiah = '';
                var angkarev = angka.toString().split('').reverse().join('');
                for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
                var rupiah1 = 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');

                $( api.column( 4 ).footer() ).html(
                    rupiah1
            );
        },
    });
    }

    menu_data()
    function menu_data(date_start = '', date_end='')
    {
        var type = $('#menu_daily').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url : "{{ route('report.resumeDaily') }}",
                type: "GET",
                data: {date_start: date_start, date_end:date_end}
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, className: 'dt-head-center'},
                {data: 'menu_name', name: 'menu_name', className: 'dt-head-center'},
                {data: 'count', name: 'count', className: 'dt-head-center'}
            ],
        });
    }

    type_data();
    function type_data(date_start = '', date_end='')
    {
        $.ajax({
            url: "{{route('report.index')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('.cash').html(data.cash);
                $('.transfer').html(data.transfer);
                $('.credit').html(data.credit);
            }
        });
    }

    $('.excel').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            window.location.href = "/report/day/export?date_start="+date_start+"&date_end="+date_end;
        }else{
            alert('Both date is required');
        }
    });

    $('#filter').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            $('#time').html(date_start).append(' to ', date_end );
            $('#sale_daily').DataTable().destroy();
            $('#menu_daily').DataTable().destroy();
            fecthData(date_start, date_end);
            menu_data(date_start, date_end);
            type_data(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#time').html('');
        $('#sale_daily').DataTable().destroy();
        $('#menu_daily').DataTable().destroy();
        fecthData();
        type_data();
        menu_data();
    });
});
</script>
@endpush
