<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Receipt ID</th>
            <th>Date Time</th>
            <th>Table</th>
            <th>Staff</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $countSale = 1;
        @endphp
        @foreach ($sales as $sale)
            <tr>
                <td>{{$countSale++}}</td>
                <td>{{$sale->id}}</td>
                <td>{{date("d/m/Y H:i:s", strtotime($sale->updated_at))}}</td>
                <td>{{$sale->table_name}}</td>
                <td>{{$sale->user_name}}</td>
                <td>{{$sale->total_price}}</td>
            </tr>
            <tr>
                <th></th>
                <th>Menu ID</th>
                <th>Menu</th>
                <th>Quantiy</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td></td>
                    <td>{{$saleDetail->menu_id}}</td>
                    <td>{{$saleDetail->menu_name}}</td>
                    <td>{{$saleDetail->quantity}}</td>
                    <td>{{$saleDetail->menu_price}}</td>
                    <td>{{$saleDetail->menu_price * $saleDetail->quantity}}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="5">Total Penjualan dari {{date("d/m/Y H:i:s", strtotime($date_start))}} sampai {{date("d/m/Y H:i:s", strtotime($date_end))}}</td>
            <td>Rp{{number_format($totalSale)}}</td>
        </tr>
    </tbody>
</table>
