<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Total</th>
            <th>Added By</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($supplier as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d M Y', strtotime($item->date)) }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->user->name }}</td>
                @php
                    $total += $item->total;
                @endphp
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <td>{{ $total }}</td>
        </tr>
    </tfoot>
</table>
