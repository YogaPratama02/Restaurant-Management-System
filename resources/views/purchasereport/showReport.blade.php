@extends('layouts.default')

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
                @if($supplier->count() > 0)
                    <div class="alert alert-success" role="alert">
                        <p>Jumlah Total Pembelanjaan dari {{$dateStart}} sampai {{$dateEnd}} adalah Rp{{number_format($total, 0, ',', '.')}}</p>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr class="text-lite text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Date Time</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Total Purchase</th>
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

    </script>

@endpush
