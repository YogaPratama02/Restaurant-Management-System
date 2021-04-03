{!! Form::model($model, [
    'route' => $model->exists ? ['roombooking.update', $model->id] : 'roombooking.store',
    'method' => $model->exists ? 'PUT' : 'POST',
    'class' => 'booking',
    'novalidate'
]) !!}

<div class="modal-body">
    <div class="form-group">
        <label for="">Room</label>
        {!! Form::select('table_id' , $model->tables, null, array('class' => 'form-control', 'placeholder' => 'Room..')) !!}
    </div>
    <div class="form-group">
        <label for="" class="control-label">Date</label>
        {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'date', 'placeholder="Date.."']) !!}
    </div>
    <div class="form-group">
        <label for="" class="control-label">Start</label>
        {!! Form::time('start', null, ['class' => 'form-control', 'id' => 'start', 'placeholder="Start.."']) !!}
        {{-- {!! Form::date('start', null, ['class' => 'form-control', 'id' => 'start', 'placeholder="Start.."']) !!} --}}
    </div>
    <div class="form-group">
        <label for="" class="control-label">End</label>
        {!! Form::time('end', null, ['class' => 'form-control', 'id' => 'end', 'placeholder="End.."']) !!}
    </div>
    <div class="form-group">
        <label for="" class="control-label">Price</label>
        <div class="input-group-prepend">
            <span class="input-group-text">Rp</span>
            {!! Form::text('price', null, ['class' => 'form-control', 'id' => 'price']) !!}
        </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
