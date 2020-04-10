<table class="table table-responsive" id="gameCreates-table">
    <thead>
        <tr>
            <th>Datetime</th>
        <th>User Id</th>
        <th>Game Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($gameCreates as $gameCreates)
        <tr>
            <td>{!! $gameCreates->datetime !!}</td>
            <td>{!! $gameCreates->user_id !!}</td>
            <td>{!! $gameCreates->game_id !!}</td>
            <td>
                {!! Form::open(['route' => ['gameCreates.destroy', $gameCreates->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('gameCreates.show', [$gameCreates->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('gameCreates.edit', [$gameCreates->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>