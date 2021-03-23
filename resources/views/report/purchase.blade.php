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
                        <table id="purchase" class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr class="text-lite text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Date Of Purchase</th>
                                    <th scope="col">Input Date</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Total Items</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <h3>Total Expenditure : <span id="z"></span></h3>
                </div>
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
        $('#purchase').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [
            'excel', 'pdf'
            ],
            ajax: {
                url: "{{ route('purchase.index') }}",
                data: {date_start: date_start, date_end:date_end},
                type: "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id', width: '15px'},
                {data: 'date', name: 'date'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'name', name: 'name'},
                {data: 'total', name: 'total'}
            ]
        });
    }

    total_purchase();
    function total_purchase(date_start = '', date_end = '')
    {
        $.ajax({
            url: "{{route('purchase.total')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('#z').html('Rp. ').append(data.total);
            },
            error: function(data)
            {
                alert('not responding');
            }
        });
    }

    $('#filter').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            $('#purchase').DataTable().destroy();
            load_data(date_start, date_end);
            total_purchase(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#purchase').DataTable().destroy();
        load_data();
        total_purchase();
    });
});
</script>
@endpush
