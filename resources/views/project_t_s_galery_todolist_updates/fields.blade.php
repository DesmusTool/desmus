<div class="form-group col-sm-6">
  {!! Form::label('actual_name', 'Actual Name:') !!}
  {!! Form::text('actual_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('past_name', 'Past Name:') !!}
  {!! Form::text('past_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('datetime', 'Datetime:') !!}
  {!! Form::date('datetime', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('user_id', 'User Id:') !!}
  {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('p_t_s_g_t_id', 'P T S G T Id:') !!}
  {!! Form::number('p_t_s_g_t_id', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
  {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
  <a href="{!! route('projectTSGaleryTodolistUpdates.index') !!}" class="btn btn-default">Cancel</a>
</div>