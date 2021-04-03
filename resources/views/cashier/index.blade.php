@extends('layouts.default')

@section('content')
    <link type="text/css" rel="stylesheet" href="{{asset('/css/form.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" />
    <div class="container">
        <div class="row" id="table-detail"></div>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <button class="btn btn-block" id="btn-show-tables" style="background-color: #ffd384">View All Table</button>
                <div class="select-table text-center"></div>
                <div class="l text-center"></div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-5">
                <div class="flex-container text-white justify-content-center">
                    @foreach ($categories as $category)
                    <a class="nav-link" data-id="{{$category->id}}">{{$category->name}}</a>
                    @endforeach
                </div>
                <div id="list-menu" class="row mt-1"></div>
            </div>
            <div class="col-md-7" id="order"></div>
            <div class="col-md-7" id="wrwr"></div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div id="order"></div>
            </div>
        </div> --}}
    </div>
@endsection

@push('after-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // $('.nav li').click(function(){
            //     $('.nav li').removeClass('active');
            //     $(this).addClass('active');
            // })

            // $('.carousel-container').load('.imgs', () => {
            //     var slideImg = $('.imgs').length;
            //     var sliderWidth = $('.imgs').width();
            //     let min, max;
            //     if (slideImg > 3) {
            //         min = 0;
            //         max = -(slideImg  * sliderWidth * 3);
            //     } else if (slideImg < 3) {
            //         min = 0;
            //         max = 0;
            //     }

            //     $('.carousel-slide').width(slideImg * sliderWidth).draggable({
            //         axis: "x",
            //         drag: function (event, ui) {
            //             if (ui.position.left > min) {
            //                 ui.position.left = min;
            //             } else if (ui.position.left < max) {
            //                 ui.position.left = max;
            //             }
            //         }
            //     })
            // })

            $('#table-detail').hide();

            $('#btn-show-tables').click(function(){
                if($('#table-detail').is(':hidden')){
                    $.get('/cashier/getTable', function(data){
                        $('#table-detail').html(data);
                        $('#table-detail').slideDown('fast');
                        $('#btn-show-tables').html('Hide Tables').css('background-color', '#a6a9b6');
                    })
                }else{
                    $('#table-detail').slideUp('slow');
                    $('#btn-show-tables').html('View All Table').css('background-color', '#ffd384');
                }
            });
            $(".nav-link").click(function(){
                $.get("/cashier/getMenu/"+$(this).data("id"),function(data){
                    $('#list-menu').html(data);
                });
            });
            $(".nav-link").dblclick(function(){
                $.get("/cashier/getMenu/"+$(this).data("id"),function(data){
                    $('#list-menu').toggle()
                    $('#list-menu').hide()
                });
            });
            $(".nav-link").click(function(){
                $.get("/cashier/getMenu/"+$(this).data("id"),function(data){
                    $('#list-menu').show();
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
                id = $(this).data('id');
                $('.select-table').html('<br><h3>Table: '+table_name+'</h3>');
                $.get('/cashier/getSaleDetailsByTable/'+table_id, function(data){
                    $('#order').html(data.a);
                    $('.l').html(data.b);
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
            $('body').on('click', '.cape', function(event){
                event.preventDefault();
                var me = $(this),
                    url = me.attr('href');
                $.ajax({
                    url: url,
                    dataType: 'html',
                    success: function(response){
                        $('.note').html(response).toggle(500);
                    },
                    error: function(response){
                        alert('not response');
                    }
                });
            });
            $('body').on('click', '.mejaUpdate', function(event){
                event.preventDefault();
                var table_id = $(this).data('id');
                $.ajax({
                    method: 'POST',
                    data : {
                        '_token' :'{{ csrf_token() }}',
                        'table_id' : table_id,
                    },
                    url : '/cashier/updateTable',
                    success : function(response){
                        // $('#order').html(data);
                        // alert('berhasil')
                        console.log(table_id);
                    },
                    error : function(response){
                        alert('gagal');

                    }
                });
            });

            $('.l').on('click', '.table_update', function(){
                var sale_id = $(this).data('id');
                var table_id = $('.riri').val();
                $.ajax({
                    method: 'POST',
                    data : {
                        '_token' :'{{ csrf_token() }}',
                        'table_id' : table_id,
                        'sale_id' : sale_id
                    },
                    url : '/cashier/mejaPindah',
                    success : function(response){
                        // $('#order').html(data);
                        console.log(table_name);
                    },
                    error : function(response){
                        alert('gagal');
                    }
                });
            });

            $('#order').on('click', '.update_note', function(){
                var SaleDe = $(this).data('id');
                var note = $('.text-area').val();
                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'saleDetail_id': SaleDe,
                        'note' : note
                    },
                    url: '/cashier/update/',
                    success: function(data){
                        $('#order').html(data);
                        // console.log(saleDetail_id);
                    },
                    error: function(data){
                        alert('gagal');
                    }
                })
            });

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
        });
    </script>
@endpush

