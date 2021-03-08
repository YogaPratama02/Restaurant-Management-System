{!! Form::model($model, [
    'route' => $model->exists ? ['menu.update', $model->id] : 'menu.store',
    'method' => $model->exists ? 'PUT' : 'POST',
    'files' => true,
    'id' => 'coba',
    'novalidate'
]) !!}

    <div class="modal-body">
        <div class="form-group">
            <label for="" class="control-label">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Menu.."']) !!}
        </div>

        <div class="form-group">
            <label for="hpp" class="control-label">HPP</label>
            <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
                {!! Form::text('hpp', null, ['class' => 'form-control', 'id' => 'hpp']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="menuPrice" class="control-label">Price</label>
            <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
                {!! Form::text('price', null, ['class' => 'form-control', 'id' => 'price']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="control-label">Discount</label>
            <div class="input-group-prepend">
                {!! Form::text('discount', null, ['class' => 'form-control', 'id' => 'discount']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="menuImage" class="control-label">Image</label>
            <div class="input-group-prepend">
                {!! Form::file('image', null, ['class' => 'img-thumbnail', 'id' => 'image']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="Category">Category</label>
            {!! Form::select('category_id' , $model->categories, null, array('class' => 'form-control', 'id' => 'category_id', 'placeholder' => 'Select..')) !!}
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>




{!! Form::close() !!}
