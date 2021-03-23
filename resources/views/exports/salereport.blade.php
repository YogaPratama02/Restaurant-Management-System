<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cards as $cards)
            <tr>
                <td>{{$cards->month}}</td>
                <td>{{$cards->total_vatprice}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5">Total Amount from {{$date_start}} to {{$date_end}}</td>
        </tr>
    </tbody>
</table>
