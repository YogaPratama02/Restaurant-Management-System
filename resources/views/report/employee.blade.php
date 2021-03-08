@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card-body">
            <div class="row input-daterange">
                <div class="col-md-5 form-group">
                    <input type="text" name="date_start" id="date_start" class="form-control" placeholder="from date.." readonly style="width: 300px;" />
                </div>
                <div class="col-md-5 form-group">
                    <input type="text" name="date_end" id="date_end" class="form-control" placeholder="end date.." readonly style="width: 300px;"  />
                </div>
                <div class="col-md-2">
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-primary">Refresh</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="employee" class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr class="text-lite text-center">
                                    <th scope="col" style="width: -0.5rem;">Employee</th>
                                    <th scope="col" style="width: 2rem;">Transactions Total</th>
                                </tr>
                            </thead>
                            <tbody id="w">

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
    $('.input-daterange').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
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
                $('#w').html(data);
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
