<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Total Expense</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($purchase as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('M Y', strtotime($item->month)) }}</td>
                <td>{{ $item->total }}</td>
                @php
                    $total += $item->total;
                @endphp
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <td>{{ $total }}</td>
        </tr>
    </tfoot>
</table>
