@extends('layouts.default')

@section('title','Receipt')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('css/receipt.css')}}" media="all">
<link type="text/css" rel="stylesheet" href="{{asset('css/no-print.css')}}" media="print">

<div id="wrapper">
    <div class="receipt-header">
        <h3 id="restaurant-name">{{ __("Tukad Jangga Coffee") }}</h3>
    </div>
    <div class="receipt text-center" style="text-align: center;">
        <p>Jl Untung Surapati, Belakang komplek Perumahan Pandawa</p>
        <p>Tel: 473-XXXX-XXXX</p>
    </div>
    <div class="date">
        <p>Date: {{ \Carbon\Carbon::now()->timezone('GMT+7')->format('d-m-Y H:i') }}</p>
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
                @foreach ($saleDetails as $saleDetail)
                    <tr class="text-left">
                        <td width="160">{{$saleDetail->menu_name}}</td>
                        <td width="50">{{$saleDetail->quantity}}</td>
                        <td width="60">{{number_format($saleDetail->menu_price * $saleDetail->quantity, 0, ',', '.' )}}</td>
                        <td width="60">{{$saleDetail->menu_discount}} %</td>
                        <td width="65">{{number_format(($saleDetail->menu_price * $saleDetail->quantity) - ($saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.' )}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table-sale-total">
            <tbody>
                <tr>
                    <td colspan="2">VAT</td>
                    <td colspan="2" class="text-right">{{$total}} %</td>
                </tr>
                @if ($sale->voucher_id != NULL)
                <tr>
                    <td colspan="2">Discount</td>
                    <td colspan="2">{{$sale->voucher->discount}} %</td>
                </tr>
                @endif

                @if ($sale->voucher_id != NULL)
                <tr>
                    <td colspan="2">Total</td>
                    <td colspan="2">Rp{{number_format($sale->total_price + ($sale->total_price * $total/ 100) - ($sale->total_price * $sale->voucher->discount / 100), 0, ',', '.' )}}</td>
                </tr>
                @else
                <tr>
                    <td colspan="2">Total</td>
                    <td colspan="2">Rp{{number_format($sale->total_price + ($sale->total_price * $total/ 100), 0, ',', '.' )}}</td>
                </tr>
                @endif
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
        <a href="{{route('cashier.index')}}">
            <button class="btn btn-back">
                Back To Cashier
            </button>
        </a>
        <a href="{{ url('/cashier/pdf/'. $sale->id) }}" class="btn btn-print" type="button">
            Print
        </a>
    </div>
</div>
@endsection

@push('after-script')
@endpush
