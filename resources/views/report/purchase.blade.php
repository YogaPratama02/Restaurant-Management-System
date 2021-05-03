@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-6 form-group input-daterange">
                    <input type="text" name="date_start" id="date_start" class="form-control text-center" placeholder="from date.." readonly />
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-6 form-group">
                    <input type="text" name="date_end" id="date_end" class="form-control text-center" placeholder="end date.." readonly />
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-6">
                    <button type="button" name="filter" id="filter" class="btn text-black" style="background-color: #90be6d">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn text-black" style="background-color: #90be6d">Refresh</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12 text-center">
                        <div style="font-size: 26px; font-weight: bold">Monthly Purchase Report</div>
                        <span id="time" style="font-size: 24px"></span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="purchase" class="table hover" style="width:100%">
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align:right">Total:</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
    $('#date_start').datepicker({
        format: 'yyyy-mm-dd',
        minViewMode: 1,
        autoclose: true
    });
    $("#date_end").datepicker({
        format: 'yyyy-mm-dd',
        minViewMode: 1,
        autoclose: true,
    }).on('changeDate',function(e)
        {
            $("#date_end").datepicker('update', new Date(e.date.getFullYear(), e.date.getMonth() + 1, 0));
        });

    load_data();
    function load_data(date_start = '', date_end = '')
    {
        var type = $('#purchase').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url : "{{ route('purchase.data') }}",
                type: "GET",
                data: {date_start: date_start, date_end:date_end}
            },
            columns: [
                {title: 'No', data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, className: 'dt-head-center'},
                {title: 'Date Of Purchase', data: 'month', name: 'month', className: 'dt-head-center'},
                {title: 'Total Expense', data: 'total', name: 'total', className: 'dt-head-center'}
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
                        .column(2)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        var rupiah = '';
                        var angkarev = angka.toString().split('').reverse().join('');
                        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
                        var rupiah1 = 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');

                    $( api.column( 2 ).footer() ).html(
                        rupiah1
                );
            }
        });
    }

    $('#filter').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            $('#time').html(date_start).append(' to ', date_end );
            $('#purchase').DataTable().destroy();
            load_data(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#purchase').DataTable().destroy();
        $('#time').html('');
        load_data();
    });
});
</script>
@endpush
