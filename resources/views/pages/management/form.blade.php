{!! Form::model($categories, [
    'route' => $categories->exists ? ['category.update', $categories->id] : 'category.store',
    'method' => $categories->exists ? 'PUT' : 'POST',
    'class' => 'cobaaja'
]) !!}

    {{-- <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
<div class="modal-body">
    <div class="form-group">
        <label for="" class="control-label">Name</label>
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Category.."']) !!}
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
