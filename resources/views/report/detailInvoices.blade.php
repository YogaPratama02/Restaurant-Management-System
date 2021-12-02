@extends('layouts.default')

@section('title','Invoices Report')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/receipt.css')}}" media="all">
<link type="text/css" rel="stylesheet" href="{{asset('/css/no-print.css')}}" media="print">

<div id="wrapper">
    <div class="receipt-header">
        <h3 id="restaurant-name">{{ __("Rajapala Coffee") }}</h3>
    </div>
    <div class="receipt text-center" style="text-align: center;">
        <p>JL. KH Ahmad Dalan No. 18, Kebayboran Baru, Kramat Peta, Kota Jakarta Selatan, DKI Jakarta,121 30</p>
        <p>Tel: 473-XXXX-XXXX</p>
    </div>
    <div class="date">
        <p>Date : {{$time->created_at->format('d-m-Y H:i') }}</p>
    </div>
    <div class="receipt-body">
        <table class="table-sale-detail">
            <thead>
                <tr class="text-left">
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Diskon</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $model)
                <tr class="text-left">
                    <td width="160">{{$model->menu_name}}</td>
                    <td width="60">{{$model->quantity}}</td>
                    <td width="70">{{number_format($model->menu_price * $model->quantity, 0, ',', '.' )}}</td>
                    <td width="60">{{$model->menu_discount}} %</td>
                    <td width="65">{{number_format(($model->menu_price * $model->quantity) - ($model->menu_price * $model->quantity * ($model->menu_discount / 100)), 0, ',', '.' )}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table-sale-total">
            <tbody>
                <tr>
                    <td colspan="2">VAT</td>
                    <td colspan="2" class="text-right">{{$sale->total_vat}} %</td>
                </tr>
                @if ($sale->voucher_id != NULL)
                <tr>
                    <td colspan="2">Discount</td>
                    <td colspan="2">{{$sale->voucher->discount}} %</td>
                </tr>
                @endif
                <tr>
                    <td colspan="2">Total</td>
                    <td colspan="2">Rp{{number_format($sale->total_vatprice, 0, ',', '.')}}</td>
                </tr>
                <tr>
                    <td colspan="2">Tipe Pembayaran</td>
                    <td colspan="2">{{$sale->payment_type}}</td>
                </tr>
                <tr>
                    <td colspan="2">Jumlah Pembayaran</td>
                    <td colspan="2">Rp{{number_format($sale->total_received, 0, ',', '.')}}</td>
                </tr>
                <tr>
                    <td colspan="2">Total Kembali</td>
                    <td colspan="2">Rp{{number_format($sale->change, 0, ',', '.')}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="receipt-footer">
        <p>Thank You!!</p>
    </div>
    <div class="buttons">
        <a href="{{route('invoices.index')}}">
            <button class="btn btn-back">
                Back To Report
            </button>
        </a>
        <a href="{{ url('/invoices/pdf/'. $sale->id) }}" class="btn btn-print" type="button">
            Print
        </a>
    </div>
</div>
@endsection
