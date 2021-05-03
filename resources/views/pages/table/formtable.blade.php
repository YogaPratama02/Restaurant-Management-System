{!! Form::model($tables, [
    'route' => $tables->exists ? ['table.update', $tables->id] : 'table.store',
    'method' => $tables->exists ? 'PUT' : 'POST',
    'class' => 'cobatable'
]) !!}

<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Table</label>
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Table.."']) !!}
    </div>
    @if (Route::is('table.edit') )
    <div class="form-group">
        <label for="" class="control-label">Status</label>
        {!! Form::select('status', ['available' => 'available','unvailable'=>'unvailable'],null, ['class' => 'form-control', 'id' => 'status', 'placeholder="Select Status.."']) !!}
    </div>
    @endif
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
