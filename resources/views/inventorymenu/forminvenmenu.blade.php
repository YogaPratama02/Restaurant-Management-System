{!! Form::model($model, [
    'route' => $model->exists ? ['inventmenu.update', $model->id] : 'inventmenu.store',
    'method' => $model->exists ? 'PUT' : 'POST',
    'class' => 'cobainvent',
    'novalidate'
]) !!}

    <div class="modal-body">
        <div class="form-group">
            <label for="inventory">Inventory</label>
            {!! Form::select('inventory_id' , $model->inventories, null, array('class' => 'form-control', 'placeholder' => 'Select..')) !!}
        </div>
        <div class="form-group">
            <label for="">Menu</label>
            {!! Form::select('menu_id' , $model->menus, null, array('class' => 'form-control', 'placeholder' => 'Select..')) !!}
        </div>
        <div class="form-group">
            <label for="" class="control-label">Consumption</label>
            {!! Form::text('consumption', null, ['class' => 'form-control','id' => 'consumption', 'placeholder="Consumption.."']) !!}
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>




{!! Form::close() !!}
