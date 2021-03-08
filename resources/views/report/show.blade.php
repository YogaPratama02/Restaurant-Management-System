<div class="card-header">
    <table class="table table-bordered" style="width:100%">
        <thead style="background-color:#295192">
            <tr class="text-lite text-center text-white">
                <th>No</th>
                <th>Menu</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Diskon</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saleDetail as $saleDetail)
                <tr class="text-center">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$saleDetail->menu_name}}</td>
                    <td>{{$saleDetail->quantity}}</td>
                    <td>{{$saleDetail->menu_price}}</td>
                    <td>{{$saleDetail->menu_discount}} %</td>
                    <td>{{($saleDetail->menu_price * $saleDetail->quantity) - (($saleDetail->menu_price * $saleDetail->quantity) * ($saleDetail->menu_discount/100))}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-rounded mb-3 btn-cobi" style="background-color: salmon">close</button>
</div>
