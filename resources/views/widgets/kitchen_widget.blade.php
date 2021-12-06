<div class="container">
    <div class="row">
    @if($saleDetails->first() != null)
    @php
        $lama = $saleDetails->first()->sale_id-1;
    @endphp
        <div class="card ml-3 amazing" style="width: 18rem; background-color: #97cf6e">
            <h1 class="text-center">{{$saleDetails->first()->sale->table->name}}</h1>
    @foreach ($saleDetails as $index=>$saleDetail)
        @if($saleDetails->first()->sale_id != $saleDetail->sale_id)
        @if($saleDetail->sale_id != $lama)
            </div>
        @endif
        @if($saleDetail->sale_id != $lama)
            <div class="card ml-3 amazing" style="width: 18rem; background-color: #97cf6e">
                <h1 class="text-center">{{$saleDetail->sale->table->name}}</h1>
        @endif
                    <div class="card ml-3 mr-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{$saleDetail->menu_name}} {{$saleDetail->quantity}}</li>
                            <li class="list-group-item">{{$saleDetail->note}}</li>
                            @if ($saleDetail->status == 'confirm')
                                <div class="btn" style="background-color: #f3b949" id="confirm" data-id='{!! $saleDetail->id !!}'>Confirm</div>
                                <a href="{{route('cashier.print-menu-name', $saleDetail->sale_id)}}" class="btn btn-primary mt-1" type="button">
                                    Print
                                </a>
                            @endif
                            @if ($saleDetail->status == 'waiting')
                                <div class="btn" style="background-color: #ff7171" id="waiting" data-id='{!! $saleDetail->id !!}'>Done!!</div>
                                <a href="{{route('cashier.print-menu-name', $saleDetail->sale_id)}}" class="btn btn-primary mt-1" type="button">
                                    Print
                                </a>
                            @endif
                        </ul>
                    </div>
        @if($saleDetail->sale_id != $lama)
            @php
                $lama = $saleDetail->sale_id;
            @endphp
        @endif
        @endif
        @if($saleDetails->first()->sale_id == $saleDetail->sale_id)
            <div class="card ml-3 mr-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">{{$saleDetail->menu_name}} {{$saleDetail->quantity}}</li>
                    <li class="list-group-item">{{$saleDetail->note}}</li>
                    @if ($saleDetail->status == 'confirm')
                        <div class="btn" style="background-color: #f3b949" id="confirm" data-id='{!! $saleDetail->id !!}'>Confirm</div>
                        <a href="{{route('cashier.print-menu-name', $saleDetail->sale_id)}}" class="btn btn-primary mt-1" type="button">
                            Print
                        </a>
                    @endif
                    @if ($saleDetail->status == 'waiting')
                        <div class="btn" style="background-color: #ff7171" id="waiting" data-id='{!! $saleDetail->id !!}'>Done!!</div>
                        <a href="{{route('cashier.print-menu-name', $saleDetail->sale_id)}}" class="btn btn-primary mt-1" type="button">
                            Print
                        </a>
                    @endif
                </ul>
            </div>
        @endif
    @endforeach
    </div>
    @endif
</div>
</div>
