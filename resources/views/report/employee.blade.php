@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group input-daterange">
                    <input type="text" name="date_start" id="date_start" class="form-control text-center" placeholder="from date.." readonly />
                </div>
                <div class="col-md-4 form-group">
                    <input type="text" name="date_end" id="date_end" class="form-control text-center" placeholder="end date.." readonly />
                </div>
                <div class="col-md-4">
                    <button type="button" name="filter" id="filter" class="btn text-white" style="background-color: #295192">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn text-white" style="background-color: #295192">Refresh</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="employee" class="table table-bordered text-center" style="width:100%">
                            <thead class="text-white" style="background-color:#295192">
                                <tr class="text-lite text-center">
                                    <th scope="col">Employee</th>
                                    <th scope="col">Total Transactions </th>
                                </tr>
                            </thead>
                            <tbody id="result">

                            </tbody>
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
        $.ajax({
            url: "{{route('report.employee')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('#result').html(data);
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
            load_data(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        load_data();
    });
});
</script>
@endpush
