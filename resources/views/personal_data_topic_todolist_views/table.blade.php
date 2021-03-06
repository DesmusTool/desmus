<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="personalDataTopicTodolistViews-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>P D T T Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($personalDataTopicTodolistViews as $personalDataTopicTodolistView)
          
        <tr>
              
          <td>{!! $personalDataTopicTodolistView->datetime !!}</td>
          <td>{!! $personalDataTopicTodolistView->user_id !!}</td>
          <td>{!! $personalDataTopicTodolistView->p_d_t_t_id !!}</td>
              
          <td>
            
            <div class='btn-group'>
                      
              <a href="{!! route('personalDataTopicTodolistViews.show', [$personalDataTopicTodolistView->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
          
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>