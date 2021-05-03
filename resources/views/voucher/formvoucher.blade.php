{!! Form::model($voucher, [
    'route' => $voucher->exists ? ['voucher.update', $voucher->id] : 'voucher.store',
    'method' => $voucher->exists ? 'PUT' : 'POST',
    'class' => 'voucher'
]) !!}

    <div class="modal-body">
        <div class="form-group">
            <label for="" class="control-label">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Name.."']) !!}
        </div>
        <div class="form-group">
            <label for="" class="control-label">Discount</label>
            {!! Form::text('discount', null, ['class' => 'form-control', 'id' => 'discount', 'placeholder="Discount.."']) !!}
        </div>
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            {!! Form::select('status', ['Active' => 'Active','Non-Active'=>'Non-Active'],null, ['class' => 'form-control', 'id' => 'status', 'placeholder="Select Status.."']) !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
