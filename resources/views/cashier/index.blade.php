@extends('layouts.default')

@section('content')
    <link type="text/css" rel="stylesheet" href="{{asset('/css/form.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="container">
        <div class="row">
            <div class="row col-md-5 scroll justify-content-start" id="table-detail"></div>
            <div class="col-md-7 ml-2 nav-wrap col-lg-7">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @foreach ($categories as $categories)
                            <a class="nav-item nav-link show_menu ml-1 mt-1" data-id="{{$categories->id}}" data-toggle="tab">
                                {{$categories->name}}
                            </a>
                        @endforeach
                    </div>
                </nav>
                <div id="list-menu" class="row mt-1 get_menu"></div>
            </div>
        </div>
        <div class="row ml-1">
            <div class="btn refresh ml-1 mt-1">Refresh Table</div>
            <div class="move_table ml-1 mt-1"></div>
        </div>
        <div class="table_name text-center"></div>
        <div class="col-md-12" id="order"></div>
    </div>
@endsection

@push('after-script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $.get('/cashier/getTable', function(data){
                $('#table-detail').html(data);
            })
            $('.refresh').click(function(){
                $.get('/cashier/getTable', function(data){
                    $('#table-detail').html(data);
                })
            });
            $(".show_menu").click(function(){
                $.get("/cashier/getMenu/"+$(this).data("id"),function(data){
                    $('#list-menu').html(data);
                });
            });
            $(".show_menu").dblclick(function(){
                $.get("/cashier/getMenu/"+$(this).data("id"),function(data){
                    $('#list-menu').toggle()
                    $('#list-menu').hide()
                });
            });
            $(".show_menu").click(function(){
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
                $('.table_name').html('<br><h3>Table: '+table_name+'</h3>');
                $.get('/cashier/getSaleDetailsByTable/'+table_id, function(data){
                    $('#order').html(data.sale);
                    $('.move_table').html(data.modal);
                });
            });

            $('#list-menu').on('click', '.btn-menu', function(){
                if(table_id == ""){
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        background: '#a6a9b6',
                        })
                        Toast.fire({
                            html: '<p class="select-table">Pilih Table terlebih dahulu!</p>',
                        });
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
                let SaleID = $(this).data('id');
                let customer_name = $('#customer_name').val();
                let customer_phone = $('#customer_phone').val();
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
                let SaleID = $(this).data('id');
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
            $('body').on('click', '.modal_note', function(event){
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

                    },
                    error : function(response){
                        alert('not responding');
                    }
                });
            });

            $('.move_table').on('click', '.table_update', function(){
                let sale_id = $(this).data('id');
                let table_id = $('.list_table').val();
                $.ajax({
                    method: 'POST',
                    data : {
                        '_token' :'{{ csrf_token() }}',
                        'table_id' : table_id,
                        'sale_id' : sale_id
                    },
                    url : '/cashier/mejaPindah',
                    success : function(response){
                        $('#modal-move').modal('hide');
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 2000,
                        background: '#ffd56b'
                        });
                        Toast.fire({
                            title : "berhasil pindah Table, silahkan click table yang sudah dipilih!"
                        });
                        setTimeout(function() {
                            window.location.replace('/cashier');
                        },3000);
                    },
                    error : function(response){
                        alert('not responding');
                    }
                });
            });

            $('#order').on('click', '.voucher', function(){
                event.preventDefault();
                let sale_id = $(this).data('id');
                let voucher_id = $('.area').val();
                $.ajax({
                    type : 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'voucher_id' : voucher_id,
                        'sale_id' : sale_id,
                    },
                    url : '/cashier/voucher',
                    success: function(data){
                        $('#order').html(data.sale);
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#ffd56b',
                        })
                        Toast.fire({
                            title: `Selamat, kamu mendapatkan voucher diskon sebesar ${data.sale_voucher} %!`,
                        });

                    }
                })
            });

            $('#order').on('click', '.update_note', function(){
                let SaleDe = $(this).data('id');
                let note = $('.text-area').val();
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
                    },
                    error: function(data){
                        alert('gagal');
                    }
                })
            });

            // decrease quantity
            $('#order').on('click', '.btn-decrease-quantity', function(){
                let saleDetailID = $(this).data('id')
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

            $('#order').on('keyup', '#paid_amount', function(){
                let totalAmount = $(".try").attr('data-all');
                let paid_amount = $(this).val();
                let newTotalAmount = (totalAmount/1000).toFixed(3).split('.').join("");
                let balance = (paid_amount - newTotalAmount);
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
                let paid_amount = $('#paid_amount').val();
                let payment_type = $(".true:checked").val();
                let saleId = sale_id;

                $.ajax({
                    type: 'POST',
                    data: {
                        '_token' :'{{ csrf_token() }}',
                        'saleID': saleId,
                        'receiveTotal': paid_amount,
                        'payment_type': payment_type
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

