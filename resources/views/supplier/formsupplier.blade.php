{!! Form::model($suppliers, [
    'route' => $suppliers->exists ? ['supplier.update', $suppliers->id] : 'supplier.store',
    'method' => $suppliers->exists ? 'PUT' : 'POST',
    'class' => 'cobasupplier'
]) !!}

    <div class="form-group">
        <label for="" class="control-label">Date</label>
        {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'date', 'placeholder="Date.."']) !!}
        {{-- {!! Form::date('start', null, ['class' => 'form-control', 'id' => 'start', 'placeholder="Start.."']) !!} --}}
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="" class="control-label">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Name.."']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="" class="control-label">Total</label>
        <div class="input-group-prepend">
            <span class="input-group-text">Rp</span>
            {!! Form::text('total', null, ['class' => 'form-control', 'id' => 'total']) !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
