<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="jobTodolistCreates-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>J T Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($jobTodolistCreates as $jobTodolistCreate)
          
        <tr>
              
          <td>{!! $jobTodolistCreate->datetime !!}</td>
          <td>{!! $jobTodolistCreate->user_id !!}</td>
          <td>{!! $jobTodolistCreate->c_t_id !!}</td>
              
          <td>
                  
            <div class='btn-group'>
                      
              <a href="{!! route('jobTodolistCreates.show', [$jobTodolistCreate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
            
          </td>
          
        </tr>
      
      @endforeach
    
    </tbody>
  
  </table>

</div>