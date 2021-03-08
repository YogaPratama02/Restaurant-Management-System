  <div>
  <div class="container">
        <div class="row">
        @if($saleDetails->first() != null)
        @php
            $lama = $saleDetails->first()->sale_id-1;
        @endphp
            <div class="card ml-3" style="width: 18rem; background-color: rgb(50, 212, 72)">
                <h1 class="text-center">{{$saleDetails->first()->sale->table_name}}</h1>
        @foreach ($saleDetails as $index=>$saleDetail)
            @if($saleDetails->first()->sale_id != $saleDetail->sale_id)
            @if($saleDetail->sale_id != $lama)
                </div>
            @endif
            @if($saleDetail->sale_id != $lama)
                <div class="card ml-3" style="width: 18rem; background-color: rgb(50, 212, 72)">
                    <h1 class="text-center">{{$saleDetail->sale->table_name}}</h1>
            @endif
                        <div class="card ml-3 mr-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">{{$saleDetail->menu_name}} {{$saleDetail->quantity}}</li>
                                <div class="btn btn-primary" wire:model.debounce.500ms=refresComponent()>Fast Food</div>
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
                        @livewire('counter')
                    </ul>
                </div>
            @endif
        @endforeach
        </div>
        @endif
    </div>
    </div>
</div>
