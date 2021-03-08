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
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
