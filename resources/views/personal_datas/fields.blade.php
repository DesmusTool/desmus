<div class="form-group col-sm-6">
  {!! Form::label('name', 'Name:') !!}
  {!! Form::text('name', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'name')) !!}
</div>

{!! Form::hidden('specific_info', 'Specific Info:') !!}
{!! Form::hidden('specific_info', $personalData -> specific_info, ['class' => 'form-control']) !!}

{!! Form::hidden('views_quantity', 'Views Quantity:') !!}
{!! Form::hidden('views_quantity', $personalData -> views_quantity, ['class' => 'form-control']) !!}

{!! Form::hidden('updates_quantity', 'Updates Quantity:') !!}
{!! Form::hidden('updates_quantity', $personalData -> updates_quantity, ['class' => 'form-control']) !!}

{!! Form::hidden('status', 'Status:') !!}
{!! Form::hidden('status', 'on', ['class' => 'form-control']) !!}

{!! Form::hidden('user_id', 'User Id:') !!}
{!! Form::hidden('user_id', $personalData -> user_id, ['class' => 'form-control']) !!}

<div class="form-group col-sm-12">
  {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
  <a href="{!! route('personalDatas.index') !!}" class="btn btn-default">Cancel</a>
</div>