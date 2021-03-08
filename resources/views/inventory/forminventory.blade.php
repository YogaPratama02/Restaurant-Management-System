{!! Form::model($inventories, [
    'route' => $inventories->exists ? ['inventory.update', $inventories->id] : 'inventory.store',
    'method' => $inventories->exists ? 'PUT' : 'POST',
    'class' => 'cobainventory'
]) !!}

<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Ingredients</label>
        {!! Form::text('ingredients', null, ['class' => 'form-control', 'id' => 'ingredients', 'placeholder="Ingredients.."']) !!}
    </div>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Stock Quantity</label>
        {!! Form::text('stock_quantity', null, ['class' => 'form-control','id' => 'stock_quantity', 'placeholder="Stock Quantity.."']) !!}
    </div>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Alert Quantity</label>
        {!! Form::text('alert_quantity', null, ['class' => 'form-control','id' => 'alert_quantity', 'placeholder="Alert Quantity.."']) !!}
    </div>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Unit</label>
        {!! Form::text('unit', null, ['class' => 'form-control','id' => 'unit', 'placeholder="Unit.."']) !!}
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
