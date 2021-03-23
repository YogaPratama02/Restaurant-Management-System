@extends('layouts.default')

@section('content')
    <div class="container">
            <div class="card-body">
                <div class="row input-daterange">
                    <div class="col-md-4 form-group">
                        <input type="text" name="date_start" id="date_start" class="form-control" placeholder="from date.." readonly />
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" name="date_end" id="date_end" class="form-control" placeholder="end date.." readonly />
                    </div>
                    <div class="col-md-4">
                        <button type="button" name="filter" id="filter" class="btn text-white" style="background-color: #295192">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn text-white" style="background-color: #295192">Refresh</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 text-center">
                            <div style="font-size: 26px; font-weight: 510">Daily Sale And Menu report</div>
                            <span id="m" style="font-size: 24px"></span>
                        </div>
                        <div class="card-body">
                            <table id="" class="table table-bordered text-center" style="width:100%">
                                <thead>
                                    <tr class="text-lite text-center">
                                        <th scope="col">No</th>
                                        <th scope="col">Date Time</th>
                                        <th scope="col">Total HPP</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Total Sale</th>
                                    </tr>
                                </thead>
                                <tbody id="z">

                                </tbody>
                                <tfoot id="o">
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="f" class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr class="text-lite text-center">
                                    <th scope="col">Menu</th>
                                    <th scope="col">Total Sold</th>
                                </tr>
                            </thead>
                            <tbody id="menus">

                            </tbody>
                        </table>
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

    fecthData();
    function fecthData(date_start = '', date_end='')
    {
        $.ajax({
            url: "{{route('report.index')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('#z').html(data.a);
                $('#o').html(data.day);
            },
            error: function(data)
            {
                alert('not responding');
            }
        });
    }

    load_data();
    function load_data(date_start = '', date_end='')
    {
        $.ajax({
            url: "{{route('report.resume')}}",
            type: "GET",
            dataType:"json",
            data: {date_start: date_start, date_end:date_end},
            success: function(data)
            {
                $('#menus').html(data);
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
            $('#m').html(date_start).append(' to ', date_end );
            fecthData(date_start, date_end);
            load_data(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#m').html('');
        fecthData();
        load_data();
    });
});
</script>
@endpush
