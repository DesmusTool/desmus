<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="personalDataTSToolTodolists-table">
      
    <thead>
          
      <tr>
              
        <th>Name</th>
        <th>Description</th>
        <th>Views Quantity</th>
        <th>Updates Quantity</th>
        <th>Status</th>
        <th>Datetime</th>
        <th>Personal Data Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($personalDataTSToolTodolists as $personalDataTSToolTodolist)
          
        <tr>
              
          <td>{!! $personalDataTSToolTodolist->name !!}</td>
          <td>{!! $personalDataTSToolTodolist->description !!}</td>
          <td>{!! $personalDataTSToolTodolist->views_quantity !!}</td>
          <td>{!! $personalDataTSToolTodolist->updates_quantity !!}</td>
          <td>{!! $personalDataTSToolTodolist->status !!}</td>
          <td>{!! $personalDataTSToolTodolist->datetime !!}</td>
          <td>{!! $personalDataTSToolTodolist->personal_data_id !!}</td>
              
          <td>
                  
            {!! Form::open(['route' => ['personalDataTSToolTodolists.destroy', $personalDataTSToolTodolist->id], 'method' => 'delete']) !!}
                  
              <div class='btn-group'>
                      
                <a href="{!! route('personalDataTSToolTodolists.show', [$personalDataTSToolTodolist->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                <a href="{!! route('personalDataTSToolTodolists.edit', [$personalDataTSToolTodolist->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                      
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                  
              </div>
                  
            {!! Form::close() !!}
              
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>