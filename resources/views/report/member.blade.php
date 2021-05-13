@extends('layouts.default')

@section('title','Members')

@section('content')
<div class="contaier">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="member" class="table hover" style="width:100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
    <script type="text/javascript">
    var member = $('#member').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('member.index') }}",
            columns: [
                {title: 'No', data: 'DT_RowIndex', name: 'id', width: '10%'},
                {title: 'Name', data: 'name', name: 'name'},
                {title: 'Email', data: 'email', name: 'email'}
            ]
        });
    </script>
@endpush
