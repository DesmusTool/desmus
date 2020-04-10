<!-- Actual Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('actual_name', 'Actual Name:') !!}
    {!! Form::text('actual_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Past Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('past_name', 'Past Name:') !!}
    {!! Form::text('past_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('datetime', 'Datetime:') !!}
    {!! Form::date('datetime', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_id', 'Game Id:') !!}
    {!! Form::number('game_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('gameUpdates.index') !!}" class="btn btn-default">Cancel</a>
</div>
