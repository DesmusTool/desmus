<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="projectTopicDeletes-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>Project Topic Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
    
    <tbody>
      
      @foreach($projectTopicDeletes as $projectTopicDelete)
          
        <tr>
              
          <td>{!! $projectTopicDelete->datetime !!}</td>
          <td>{!! $projectTopicDelete->user_id !!}</td>
          <td>{!! $projectTopicDelete->project_topic_id !!}</td>
          
          <td>
            
            <div class='btn-group'>
                      
              <a href="{!! route('projectTopicDeletes.show', [$projectTopicDelete->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
              
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>