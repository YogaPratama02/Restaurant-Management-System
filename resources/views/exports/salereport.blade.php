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
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('M Y', strtotime($cards->month)) }}</td>
                <td>{{ $cards->total_hpp }}</td>
                <td>{{ $cards->total_price }}</td>
                <td>{{ $cards->total_vatprice }}</td>
                {{-- <td>Rp. {{number_format($cards->total_hpp, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($cards->total_price, 2, ',', '.')}}</td>
                <td>Rp. {{number_format($cards->total_vatprice, 2, ',', '.')}}</td> --}}
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <td>{{ $total }}</td>
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
            <td>{{ $total_cash }}</td>
            <td>{{ $total_bank }}</td>
            <td>{{ $total_card }}</td>
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
                <td>{{ $loop->iteration }}</td>
                <td>{{ $saleDetail->menu_name }}</td>
                <td>{{ $saleDetail->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
