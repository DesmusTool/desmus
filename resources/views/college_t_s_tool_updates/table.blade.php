<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="collegeTSToolUpdates-table">
      
    <thead>
          
      <tr>
              
        <th>Actual Name</th>
        <th>Past Name</th>
        <th>Datetime</th>
        <th>User Id</th>
        <th>College T S Tool Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($collegeTSToolUpdates as $collegeTSToolUpdate)
          
        <tr>
              
          <td>{!! $collegeTSToolUpdate->actual_name !!}</td>
          <td>{!! $collegeTSToolUpdate->past_name !!}</td>
          <td>{!! $collegeTSToolUpdate->datetime !!}</td>
          <td>{!! $collegeTSToolUpdate->user_id !!}</td>
          <td>{!! $collegeTSToolUpdate->college_t_s_tool_id !!}</td>
              
          <td>
            
            <div class='btn-group'>
              
              <a href="{!! route('collegeTSToolUpdates.show', [$collegeTSToolUpdate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
          
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>