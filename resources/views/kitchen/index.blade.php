@extends('layouts.default')

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
    // $(function() {
    //     startRefresh();
    // });

    // function startRefresh() {
    //     setTimeout(startRefresh,10000);
    //     $.get('/kitchen', function(data) {
    //         $('.lama').html(data);
    //     });
    // }
    // sessionStorage.setItem('occupation', 'anjing lah susah');
    // console.log(sessionStorage.getItem('occupation'));
    // $('body').on('click', '#cepet', function(){
    //        alert('haha')
    // });
    $('body').on('click', '#ayo', function(data){
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
    $('body').on('click', '#cepet', function(data){
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