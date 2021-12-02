@extends('layouts.default')

@section('title','Invoices Report')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#97cf6e">
                    <h5 class="card-title mt-1 text-white">Invoices Report</h5>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Customers Name</th>
                                <th>Cashiers Name</th>
                                <th>Date</th>
                                <th>Action</th>
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
<script>
    $('#datatable').DataTable({
        responsive: true,
        serverSide: true,
        ajax: "{{ route('invoices.data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'id', width : '5%'},
            {data: 'customer_name', name: 'customer_name', width : '30%'},
            {data: 'user.name', name: 'user.name'},
            {data: 'created_at', name: 'created_at',width : '20%'},
            {data: 'action', name: 'action'},
        ]
    });
</script>
@endpush
