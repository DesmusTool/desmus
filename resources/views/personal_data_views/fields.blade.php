<div class="form-group col-sm-6">
  {!! Form::label('datetime', 'Datetime:') !!}
  {!! Form::date('datetime', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('user_id', 'User Id:') !!}
  {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('personal_data_id', 'Personal Data Id:') !!}
  {!! Form::number('personal_data_id', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
  <a href="{!! route('personalDataViews.index') !!}" class="btn btn-default">Cancel</a>
</div>