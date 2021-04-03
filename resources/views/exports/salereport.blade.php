<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Month</th>
            <th>Total HPP</th>
            <th>Total Price</th>
            <th>Total Sale</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cards as $cards)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$cards->month}}</td>
                <td>Rp. {{number_format($cards->total_hpp, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($cards->total_price, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($cards->total_vatprice, 2, ',', '.')}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <td>{{number_format($total, 2, ',', '.')}}</td>
        </tr>
    </tfoot>
</table>
<table>
    <tr>
        <th>Total Cash</th>
        <th>Total Transfer Bank</th>
        <th>Total Credit Card</th>
    </tr>
    <tbody>
        <tr>
            @foreach ($saleCash as $saleCash)
                <td>Rp. {{number_format($saleCash->total_vatprice, 2, ',', '.')}}</td>
            @endforeach
            @foreach ($saleBank as $saleBank)
                <td>Rp. {{number_format($saleBank->total_vatprice, 2, ',', '.')}}</td>
            @endforeach
            @foreach ($saleCard as $saleCard)
                <td>Rp. {{number_format($saleCard->total_vatprice, 2, ',', '.')}}</td>
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
    <tbody>
        @foreach ($saleDetail as $saleDetail)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$saleDetail->menu_name}}</td>
                <td>{{$saleDetail->count}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
