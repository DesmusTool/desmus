<table class="table table-responsive" id="gameViews-table">
    <thead>
        <tr>
            <th>Datetime</th>
        <th>User Id</th>
        <th>Game Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($gameViews as $gameViews)
        <tr>
            <td>{!! $gameViews->datetime !!}</td>
            <td>{!! $gameViews->user_id !!}</td>
            <td>{!! $gameViews->game_id !!}</td>
            <td>
                {!! Form::open(['route' => ['gameViews.destroy', $gameViews->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('gameViews.show', [$gameViews->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('gameViews.edit', [$gameViews->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>