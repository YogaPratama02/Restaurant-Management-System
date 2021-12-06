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
            #restaurant-name,
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
            .change {
                text-align: right;
            }
            .tb-sale-total tr:not(:first-child) {
                background-color: #ccc;
            }
            .tb-sale-total tr:not(:first-child) td:nth-child(2) {
                text-align: right;
                padding-right: 1.5%;
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
                <h2 id="restaurant-name">Rajapala Coffee</h2>
                <p class="me">JL. KH Ahmad Dalan No. 18, Kebayboran Baru, Kramat Peta, Kota Jakarta Selatan, DKI Jakarta,121 30</p>
                <p class="we">Tel: 473-XXXX-XXXX</p>
              </div>
              <div class="date">
                  <p>Date: {{ \Carbon\Carbon::now()->timezone('GMT+7')->format('d-m-Y H:i') }}</p>
              </div>
              <div id="receipt-body">
                <table class="tb-sale-detail">
                    <thead>
                        <tr>
                          <th>Name</th>
                          <th>Phone</th>
                          <th>Table</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td width="30">{{$sale->customer_name}}</td>
                            <td width="30">{{$sale->customer_phone}}</td>
                            <td width="30">{{$sale->table->name}}</td>
                          </tr>
                      </tbody>
                </table>
              </div>
              <div id="receipt-footer">
                <h5 class="thanks">Thank You!!!</h5>
              </div>
        </div>
    </body>
</html>
