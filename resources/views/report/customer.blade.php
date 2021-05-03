@extends('layouts.default')

@section('content')
    <div class="contaier">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customer" class="table hover" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script type="text/javascript">
    var customer = $('#customer').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('customer.index') }}",
            columns: [
                {title: 'No', data: 'DT_RowIndex', name: 'id', width: '10%'},
                {title: 'Name', data: 'customer_name', name: 'customer_name'},
                {title: 'Number Phone', data: 'customer_phone', name: 'customer_phone'}
            ]
        });
    </script>
@endpush
