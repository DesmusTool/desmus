<div class="form-group col-sm-12">
  {!! Form::label('name', 'Name:') !!}
  {!! Form::text('name', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'name')) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::label('link', 'Link:') !!}
  {!! Form::text('link', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'link')) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::label('email', 'Email:') !!}
  {!! Form::text('email', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'email')) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::label('telephone', 'Telephone:') !!}
  {!! Form::text('telephone', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'telephone')) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::label('address', 'Address:') !!}
  {!! Form::text('address', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'address')) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::label('description', 'Description:') !!}
  {!! Form::textarea('description', 'Add a description ...', ['class' => 'form-control'], array('required' => 'required', 'id' => 'description')) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('image', 'Image File:') !!}
  {!! Form::file('image', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'image')) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('video', 'Video File:') !!}
  {!! Form::file('video', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'video')) !!}
</div>

{!! Form::hidden('file_type', 'File Type:') !!}
{!! Form::hidden('file_type', null, ['class' => 'form-control']) !!}

{!! Form::hidden('views_quantity', 'Views Quantity:') !!}
{!! Form::hidden('views_quantity', 0, ['class' => 'form-control']) !!}

{!! Form::hidden('updates_quantity', 'Updates Quantity:') !!}
{!! Form::hidden('updates_quantity', 0, ['class' => 'form-control']) !!}

{!! Form::hidden('status', 'Status:') !!}
{!! Form::hidden('status', 'on', ['class' => 'form-control']) !!}

{!! Form::hidden('user_id', 'User Id:') !!}
{!! Form::hidden('user_id', $user_id, ['class' => 'form-control']) !!}

<div class="form-group col-sm-12" style="margin-bottom: 0;">
  {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
  <a href="{!! route('publicAdvertisements.index') !!}" class="btn btn-default">Cancel</a>
</div>