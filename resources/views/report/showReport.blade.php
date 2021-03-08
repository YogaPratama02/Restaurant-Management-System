{{-- @extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Main Functions</a></li>
                        <li class="breadcrumb-item"><a href="/report">Report</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Result</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if($sales->count() > 0)
                    <div class="alert alert-success" role="alert">
                        <p>Jumlah Total Penjualan dari {{$dateStart}} sampai {{$dateEnd}} adalah Rp{{number_format($totalSale)}}</p>
                        <p>Jumlah Transaksi: {{$sales->total()}}</p>
                    </div>
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

                    <form action="/report/show/export" method="GET">
                        <input type="hidden" name="dateStart" value="{{$dateStart}}">
                        <input type="hidden" name="dateEnd" value="{{$dateEnd}}">
                        <input type="submit" class="btn mb-2" style="background-color: salmon" value="Export To Excel">
                    </form>
                @else
                    <div class="alert alert-danger" role="alert">
                        There is no sale report
                    </div>
                @endif
                <div class="col-md-12">
                    <div id="details"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script type="text/javascript">
        $('#datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('report.dataTable') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'id', width: '15px'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'user_name', name: 'user_name'},
                {data: 'total_hpp', name: 'total_hpp'},
                {data: 'total_price', name: 'total_price'},
                {data: 'total_vat', name: 'total_vat'},
                {data: 'total_vatprice', name: 'total_vatprice'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
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
    </script>

@endpush

<!-- {{-- @section('charts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        Highcharts.chart('charts', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Grafik Penjualan'
    },
    xAxis: {
        categories: {!! json_encode($sale->updated_at) !!},
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Tokyo',
        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
    }]
});
    </script>
@endsection --}} --> --}}
