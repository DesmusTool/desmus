<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="jobTSPTCreates-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>J T S P T Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($jobTSPTCreates as $jobTSPTCreate)
          
        <tr>
              
          <td>{!! $jobTSPTCreate->datetime !!}</td>
          <td>{!! $jobTSPTCreate->user_id !!}</td>
          <td>{!! $jobTSPTCreate->c_t_s_p_t_id !!}</td>
          
          <td>
            
            <div class='btn-group'>
              
              <a href="{!! route('jobTSPTCreates.show', [$jobTSPTCreate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
          
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>