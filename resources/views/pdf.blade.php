<html>
    <body>
        <style>
            #wrapper {
                width: 280px;
                margin: 0 auto;
                color: #000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            #resturant-name,
            #receipt-footer {
                text-align: center;
            }
            .tb-sale-detail,
            .tb-sale-total {
                width: 100%;
                border-spacing: 0;
                margin-top: 10px;
            }
            .tb-sale-detail {
                text-align: center;
            }
            .tb-sale-detail th {
                border-bottom: 1px solid #000;
            }
            .tb-sale-total td {
                padding: 5px 0;
                padding-left: 1.5%;
                border-bottom: 1px solid #000;
            }
            .tb-sale-total tr:first-child td:nth-child(3) {
                border-left: 1px solid #999;
            }
            .tb-sale-total tr:first-child td:nth-child(4) {
                text-align: right;
                padding-left: 1.5%;
            }
            .tb-sale-total tr:not(:first-child) {
                background-color: #ccc;
            }
            .tb-sale-total tr:not(:first-child) td:nth-child(2) {
                text-align: right;
                padding-right: 1.5%;
            }
            .btn {
                width: 100%;
                cursor: pointer;
                text-align: center;
                border-radius: 5px;
                padding: 10px;
                margin: 5px 0;
                border: none;
            }
            .btn-print {
                background-color: #ffa93c;
            }
            .btn-back {
                background-color: #4fa950;
            }
            .me, .we {
                font-size: 14px;
                text-align: center;
            }
            .thanks {
                font-size: 14px;
                text-align: center;
            }
        </style>
        <div id="wrapper">
            <div id="receipt-header">
              <h1 id="resturant-name">The Professor's Caffe</h1>
              <p class="me">JL. KH Ahmad Dalan No. 18, Kebayboran Baru, Kramat Peta, Kota Jakarta Selatan, DKI Jakarta,121 30</p>
              <p class="we">Tel: 473-XXXX-XXXX</p>
            </div>
            <div id="receipt-body">
              <table class="tb-sale-detail">
                <thead>
                  <tr>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Diskon</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($saleDetails as $saleDetail)
                    <tr>
                      <td width="30">{{$saleDetail->menu_name}}</td>
                      <td width="30">{{$saleDetail->quantity}}</td>
                      <td width="30">{{number_format($saleDetail->menu_price * $saleDetail->quantity, 0, ',', '.' )}}</td>
                      <td width="30">{{$saleDetail->menu_discount}} %</td>
                      <td width="30">{{number_format(($saleDetail->menu_price * $saleDetail->quantity) - ($saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.' )}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
              <table class="tb-sale-total">
                <tbody>
                  <tr>
                    <td>VAT</td>
                    <td>{{$total}}%</td>
                    <td>Total</td>
                    <td>Rp{{number_format($sale->total_price + $sale->total_price * $total/ 100, 0, ',', '.' )}}</td>
                  </tr>
                  <tr>
                    <td colspan="3">Type Pembayaran</td>
                    <td colspan="4">{{$sale->payment_type}}</td>
                  </tr>
                  <tr>
                    <td colspan="3">Jumlah Pembayaran</td>
                    <td colspan="4">Rp{{number_format($sale->total_received, 0, ',', '.')}}</td>
                  </tr>
                  <tr>
                    <td colspan="3">Total Kembali</td>
                    <td colspan="4">Rp{{number_format($sale->change, 0, ',', '.')}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="receipt-footer">
              <p class="thanks">Thank You!!!</p>
            </div>
        </div>
    </body>
</html>
