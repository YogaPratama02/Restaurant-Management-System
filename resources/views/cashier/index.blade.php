@extends('layouts.default')

@section('content')
    <link type="text/css" rel="stylesheet" href="{{asset('/css/form.css')}}">
    <div class="container">
        <div class="row" id="table-detail"></div>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <button class="btn btn-primary btn-block" id="btn-show-tables">View All Table</button>
                <div class="select-table text-center"></div>
            </div>
        </div>
        <div class="col md-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach ($categories as $category)
                        <button class="nav-item nav-link" data-id="{{$category->id}}" data-toggle="tab">
                            {{$category->name}}
                        </button>
                    @endforeach
                </div>
            </nav>
            <div id="list-menu" class="row mt-3"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="order"></div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#table-detail').hide();

            $('#btn-show-tables').click(function(){
                if($('#table-detail').is(':hidden')){
                    $.get('/cashier/getTable', function(data){
                        $('#table-detail').html(data);
                        $('#table-detail').slideDown('fast');
                        $('#btn-show-tables').html('Hide Tables').removeClass('btn-primary').addClass('btn-success');
                    })
                }else{
                    $('#table-detail').slideUp('slow');
                    $('#btn-show-tables').html('View All Table').removeClass('btn-success').addClass('btn-primary');
                }
            });
            $('.nav-link').click(function(){
                $.get('/cashier/getMenu/'+$(this).data('id'), function(data){
                    $('#list-menu').html(data);
                });
            });
            var table_id = "";
            var table_name = "";
            var sale_id = "";
            var menu_discount = "";
            var quantity = "";
            $('#table-detail').on('click', '.btn-table', function(){
                table_id = $(this).data('id');
                table_name = $(this).data('name');
                $('.select-table').html('<br><h3>Table: '+table_name+'</h3><hr>');
                $.get('/cashier/getSaleDetailsByTable/'+table_id, function(data){
                    $('#order').html(data);
                });
            });
            $('#list-menu').on('click', '.btn-menu', function(){
                if(table_id == ""){
                    alert('pilih table terlebih dahulu');
                }else{
                    var menu_id = $(this).data('id');
                    $.ajax({
                        type: "POST",
                        data: {
                            '_token' :'{{ csrf_token() }}',
                            'menu_id': menu_id,
                            'table_id': table_id,
                            'table_name': table_name,
                            'quantity': 1,
                            'menu_discount': menu_discount
                        },
                        url: '/cashier/order',
                        success: function(data){
                            $('#order').html(data);
                        }
                    });
                }
            });
            $('#order').on('click', '.btn-confirm-order', function(data){
                var SaleID = $(this).data('id');
                var customer_name = $('#customer_name').val();
                var customer_phone = $('#customer_phone').val();
                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'sale_id': SaleID,
                        'customer_name': customer_name,
                        'customer_phone': customer_phone
                    },
                    url: '/cashier/confirmOrder',
                    success: function(data){
                        $('#order').html(data);
                    }
                });
            });

            $('#order').on('click', '.btn-order-again', function(data){
                var SaleID = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'sale_id': SaleID
                    },
                    url: '/cashier/confirmAgain',
                    success: function(data){
                        $('#order').html(data);
                    }
                });
            });

            // increase quantity
            // $('#order').on('click', '.btn-increase-quantity', function(){
            //     var saleDetailID = $(this).data('id');
            //     // var invent = $(this).data('menu_id');
            //     $.ajax({
            //         type: 'POST',
            //         data: {
            //             '_token' :'{{ csrf_token() }}',
            //             'saleDetail_id': saleDetailID
            //         },
            //         url: '/cashier/increase-quantity',
            //         success: function(data){
            //             $('#order').html(data);
            //         }
            //     });
            // });

            // decrease quantity
            $('#order').on('click', '.btn-decrease-quantity', function(){
                var saleDetailID = $(this).data('id')
                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'saleDetail_id': saleDetailID,
                    },
                    url: '/cashier/decrease-quantity',
                    success: function(data){
                        $('#order').html(data);
                    }
                });
            });

            // click payment
            // $('#order').on('click', '.huhu', function(){
                // var totalAmout = $(this).attr('data-totalAmount');
            //     $('#paid_amount').keyup(function(){
            //     var totalAmount = $('.btn-payment').attr('data-total');
            //     var balance = $(this).val();
            //     var changeAmount = balance - totalAmount;
            //     $('#balance').html(changeAmount);

            //     // if(changeAmount >= 0){
            //     //     $('.btn-payment').prop('disabled', false);
            //     // }else{
            //     //     $('.btn-payment').prop('disabled', true);
            //     // }
            // });
            // });

            // $('#order').on('click', '#paid_amount'.keyup(function(){
            //     var totalAmout = $(this).attr('data-totalAmount');
            // }));

            $('#order').on('keyup', '#paid_amount', function(){
                // var a = $(".btn-payment").attr('data-total');
                var totalAmount = $(".try").attr('data-all');
                // console.log(b);
                var paid_amount = $(this).val();
                var balance = (paid_amount - totalAmount);
                // console.log(balance);
                $('#balance').val(balance);
                if(paid_amount <= 1){
                    $('#balance').val(null);
                }else{
                    $('#balance').val(balance);
                }

                if(balance >= 0){
                    $('.btn-payment').prop('disabled', false);
                }else{
                    $('.btn-payment').prop('disabled', true);
                }

            });

            // calculate
            // save payment

            $('#order').on('click', '.btn-payment', function(data){
                sale_id = $(this).data('id');
                var paid_amount = $('#paid_amount').val();
                var paymentType = $(".true:checked").val();
                var saleId = sale_id;
                // console.log(saleId);

                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'saleID': saleId,
                        'receiveTotal': paid_amount,
                        'paymentType': paymentType
                    },
                    url: '/cashier/savePayment',
                    success: function(data){
                        window.location.href=data
                    }
                })
            });

            // $('.btn-save-payment').click(function(data){

            //     $.ajax({
            //         type: 'POST',
            //         data: {
            //             '_token' :'{{ csrf_token() }}',
            //             'saleID': saleId,
            //             'receiveTotal': receivedToal,
            //             'paymentType': paymentType
            //         },
            //         url: '/cashier/savePayment',
            //         success: function(data){
            //             window.location.href=data
            //         }
            //     })
            // });
        });
    </script>
@endpush

