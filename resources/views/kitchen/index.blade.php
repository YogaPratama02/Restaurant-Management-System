@extends('layouts.default')

@section('title','Kitchen')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- {{-- @livewire('counter') --}}
                        {{-- <livewire:counter></livewire:counter> --}}
                        @asyncWidget('kitchen_widget')
                        {{-- @widget('_widget') --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script type="text/javascript">
    $('body').on('click', '#confirm', function(data){
        $.ajax({
            url: '/kitchen/update/'+$(this).data('id'),
            method: 'GET',
            success: function(response){

            },
            error: function(response){
                alert('gagal');
            }
        })
    });
    $('body').on('click', '#waiting', function(data){
        $.ajax({
            url: '/kitchen/again/'+$(this).data('id'),
            method: 'GET',
            success: function(response){

            },
            error: function(response){
                alert('gagal');
            }
        })
    });
</script>
@endpush
