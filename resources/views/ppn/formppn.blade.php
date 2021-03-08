{!! Form::model($ppn, [
    'route' => $ppn->exists ? ['ppn.update', $ppn->id] : 'ppn.store',
    'method' => $ppn->exists ? 'PUT' : 'POST',
    'class' => 'cobappn'
]) !!}

    <div class="modal-body">
        <div class="form-group">
            <label for="" class="control-label">PPN</label>
            {!! Form::text('ppn', null, ['class' => 'form-control', 'id' => 'ppn', 'placeholder="PPN.."']) !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
