{{-- {!! Form::model($model, [
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

{!! Form::close() !!} --}}
<form id="tabel_form" class="form tabel-form1" method="POST" action="{{ $model->exists ? route('menu.update', $model->id) : route('menu.store') }}" enctype="multipart/form-data">
    @csrf {{ method_field($model->exists ? 'PUT' : 'POST') }}
    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="control-label">Nama</label>
            <input id="name" type="text" class="form-control" name="name" value="{{$model->name}}" placeholder="Nama Kategori">
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <textarea rows="4" id="description" class="form-control" name="description" placeholder="Description">{{$model->description}}</textarea>
        </div>
        <div class="form-group">
            <label for="hpp" class="control-label">HPP</label>
            <input id="hpp" type="text" class="form-control" name="hpp" value="{{$model->hpp}}" placeholder="HPP">
        </div>
        <div class="form-group">
            <label for="price" class="control-label">Price</label>
            <input id="price" type="text" class="form-control" name="price" value="{{$model->price}}" placeholder="Price">
        </div>
        <div class="form-group">
            <label for="image" class="control-label">Image</label>
            <br>
            <input id="image" type="file" name="image">
        </div>
        <div class="form-group">
            <label for="discount" class="control-label">Diskon</label>
            <input id="discount" type="text" class="form-control" name="discount" value="{{$model->discount}}" placeholder="Diskon">
        </div>
        <div class="form-group">
            <label for="category_id" class="control-label">Category</label><br>
            <select id="category_id" type="text" class="form-control" name="category_id">
                <option value="0" selected="selected" disabled>Pilih Category</option>
                @foreach($categories as $category)
                <option value="{{$category->id}}" @if($category->id == $model->category_id) selected="selected" @endif>{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>

    <script>
        CKEDITOR.replace('description');
        // var description = document.getElementById("description");
        //   CKEDITOR.replace(desc,{
        //   language:'en-gb'
        // });
        CKEDITOR.config.allowedContent = true;
     </script>
    <script type="text/javascript">
    </script>
</form>
