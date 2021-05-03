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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employee" class="table hover" style="width:100%"></table>
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
        var employee = $('#employee').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url : "{{ route('report.employeeData') }}",
                type: "GET",
                data: {date_start: date_start, date_end:date_end}
            },
            columns: [
                {title: 'No', data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, className: 'dt-center'},
                {title: 'Employee Name', data: 'employee_name', name: 'employee_name', className: 'dt-center'},
                {title: 'Total transaction', data: 'count', name: 'count', className: 'dt-center'}
            ],
        });
    }

    $('#filter').click(function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != '' && date_end != ''){
            $('#employee').DataTable().destroy();
            load_data(date_start, date_end);
        }else{
            alert('Both date is required');
        }
    });

    $('#refresh').click(function(){
        $('#date_start').val('');
        $('#date_end').val('');
        $('#employee').DataTable().destroy();
        load_data();
    });
});
</script>
@endpush
