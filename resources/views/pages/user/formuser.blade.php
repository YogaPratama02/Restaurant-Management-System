{!! Form::model($users, [
    'route' => $users->exists ? ['user.update', $users->id] : 'user.store',
    'method' => $users->exists ? 'PUT' : 'POST',
    'class' => 'cobauser'
]) !!}

<div class="modal-body">
    <div class="form-group">
        <label for="name" class="control-label">Nama</label>
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder="Nama.."']) !!}
    </div>
    <div class="form-group">
        <label for="phone_number" class="control-label">Phone Number</label>
        {!! Form::text('phone_number', null, ['class' => 'form-control', 'id' => 'phone_number', 'placeholder="Phone Number"']) !!}
    </div>
    <div class="form-group">
        <label for="role" class="control-label">Role</label>
        {!! Form::select('role', ['admin' => 'admin', 'cashier' => 'cashier'], null,['class'=> 'form-control', 'id' => 'role', 'placeholder'=>'Select Role..']) !!}
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Email</label>
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder="Email.."']) !!}
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Password</label>
        {!! Form::password('password', array('placeholder'=>'Password', 'class'=>'form-control', 'id' => 'password..')) !!}
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modal-btn-save"></button>
    </div>


{!! Form::close() !!}
