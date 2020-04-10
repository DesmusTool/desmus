<table class="table table-responsive" id="gameUpdates-table">
    <thead>
        <tr>
            <th>Actual Name</th>
        <th>Past Name</th>
        <th>Datetime</th>
        <th>User Id</th>
        <th>Game Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($gameUpdates as $gameUpdates)
        <tr>
            <td>{!! $gameUpdates->actual_name !!}</td>
            <td>{!! $gameUpdates->past_name !!}</td>
            <td>{!! $gameUpdates->datetime !!}</td>
            <td>{!! $gameUpdates->user_id !!}</td>
            <td>{!! $gameUpdates->game_id !!}</td>
            <td>
                {!! Form::open(['route' => ['gameUpdates.destroy', $gameUpdates->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('gameUpdates.show', [$gameUpdates->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('gameUpdates.edit', [$gameUpdates->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>