@extends('layouts.default')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css">
    <div class="container">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group input-daterange">
                    <input type="text" name="date_start" id="date_start" class="form-control text-center" placeholder="from date.." readonly />
                </div>
                <div class="col-md-3 form-group">
                    <input type="text" name="date_end" id="date_end" class="form-control text-center" placeholder="end date.." readonly />
                </div>
                <div class="col-md-3">
                    <button type="button" name="filter" id="filter" class="btn text-white" style="background-color: #295192">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn text-white" style="background-color: #295192">Refresh</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn text-white excel" style="background-color: #295192">Export To Excel</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12 text-center">
                        <div style="font-size: 26px; font-weight: bold">Monthly Sale Report</div>
                        <span id="e" style="font-size: 24px"></span>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead class="text-white" style="background-color:#295192">
                                <tr class="text-lite text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Total HPP</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Total Sale</th>
                                </tr>
                            </thead>
                            <tbody id="d">

                            </tbody>
                            <tfoot id="hi">
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead class="text-white" style="background-color:#295192">
                                <tr class="text-lite text-center">
                                    <th scope="col">Total Cash</th>
                                    <th scope="col">Total Bank Transfer</th>
                                    <th scope="col">Total Credit</th>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead class="text-white" style="background-color:#295192">
                                <tr class="text-lite text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Menu Name</th>
                                    <th scope="col">Total Sold</th>
                                </tr>
                            </thead>
                            <tbody id="k">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" >
                    <canvas id="chart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

    <script type="text/javascript">
        // $(document).ready(function(){
                $('#date_start').datepicker({
                    format: 'yyyy-mm-dd',
                    minViewMode: 1,
                    autoclose: true
                    // viewMode: "months", //this
                    // minViewMode: "months",
                });

                $("#date_end").datepicker({
                    format: 'yyyy-mm-dd',
                    minViewMode: 1,
                    autoclose: true,
                }).on('changeDate',function(e)
                    {
                        $("#date_end").datepicker('update', new Date(e.date.getFullYear(), e.date.getMonth() + 1, 0));
                    });

                month_data();
                function month_data(date_start = '', date_end='')
                {
                    $.ajax({
                        url: "{{route('report.month')}}",
                        type: "GET",
                        dataType:"json",
                        data: {date_start: date_start, date_end:date_end},
                        success: function(data)
                        {
                            $('#d').html(data.menuz);
                            $('#hi').html(data.tal);
                            $('#k').html(data.menu);
                            $('.cash').html(data.cash);
                            $('.transfer').html(data.bank);
                            $('.credit').html(data.card);
                        },
                        error: function(data)
                        {
                            alert('not responding');
                        }
                    });
                }

                $('.excel').click(function(){
                    var date_start = $('#date_start').val();
                    var date_end = $('#date_end').val();
                    // console.log(date_start);
                    if(date_start != '' && date_end != ''){
                        window.location.href = "/report/show/export?date_start="+date_start+"&date_end="+date_end;
                    }else{
                        alert('Both date is required');
                    }
                });

                $('#filter').click(function(){
                    var date_start = $('#date_start').val();
                    var date_end = $('#date_end').val();
                    if(date_start != '' && date_end != ''){
                        $('#e').html(date_start).append(' to ', date_end );
                        month_data(date_start, date_end);
                    }else{
                        alert('Both date is required');
                    }
                });

                $('#refresh').click(function(){
                    $('#date_start').val('');
                    $('#date_end').val('');
                    $('#e').html('');
                    month_data();
                });
            // });
            let myChart = document.getElementById('chart').getContext('2d');
            let label = @php echo $month @endphp;
            let data = @php echo $data @endphp;
            let a = new Chart(myChart, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'Sale Report',
                        data: data,
                        backgroundColor: '#295192',
                        borderWidth: 1,
                        borderColor: '#777',
                    }]
                },
                options: {
                    legend: {
                    display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                            display: true,
                            drawBorder: false,
                            color: '#f2f2f2',

                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return new Intl.NumberFormat('id-ID').format(value)
                                }
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                            display: false,
                            tickMarkLength: 15,
                            }
                        }]
                    }
                }
            });
    </script>
@endpush
