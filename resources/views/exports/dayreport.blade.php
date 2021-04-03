<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Total HPP</th>
            <th>Total Price</th>
            <th>Total Sale</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sale as $sale)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$sale->date}}</td>
                <td>Rp. {{number_format($sale->total_hpp, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($sale->total_price, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($sale->total_vatprice, 2, ',', '.')}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <td>Rp. {{number_format($total, 2, ',', '.')}}</td>
        </tr>
    </tfoot>
</table>

<table>
    <thead>
        <tr>
            <th>Total Cash</th>
            <th>Total Transfer Bank</th>
            <th>Total Credit Card</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach ($saleCash as $saleCash)
                <td>Rp. {{number_format($saleCash->total_vatprice, 2, ',', '.')}}</td>
            @endforeach
            @foreach ($saleBank as $saleBank)
                <td>Rp. {{number_format($saleBank->total_vatprice, 2, ',', '.')}}</td>
            @endforeach
            @foreach ($saleCredit as $saleCredit)
                <td>Rp. {{number_format($saleCredit->total_vatprice, 2, ',', '.')}}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Menu Name</th>
            <th>Total Sold</th>
        </tr>
    </thead>
    @foreach ($saleDetail as $saleDetail)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$saleDetail->menu_name}}</td>
            <td>{{$saleDetail->count}}</td>
        </tr>
    @endforeach
</table>
