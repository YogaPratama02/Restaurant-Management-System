@extends('layouts.default');

@section('content')
    <div class="container">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr class="text-lite text-center">
                                    <th scope="col" id="huhu">No</th>
                                    <th scope="col">Ingredients</th>
                                </tr>
                            </thead>
                            <tbody id="inventory">

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
    inventory();
    function inventory(){
        $.ajax({
            url: "{{route('report.inventory')}}",
            type: "GET",
            dataType:"json",
            success: function(data)
            {
                $('#inventory').html(data);
            },
            error: function(data)
            {
                alert('not responding');
            }
        });
    }
</script>
@endpush
