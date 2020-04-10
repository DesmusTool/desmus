<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="userPersonalDataTSNoteUs-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>U P D T S N Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
     
    <tbody>
      
      @foreach($userPersonalDataTSNoteUs as $userPersonalDataTSNoteU)
          
        <tr>
              
          <td>{!! $userPersonalDataTSNoteU->datetime !!}</td>
          <td>{!! $userPersonalDataTSNoteU->user_id !!}</td>
          <td>{!! $userPersonalDataTSNoteU->u_p_d_t_s_n_id !!}</td>
              
          <td>
            
            <div class='btn-group'>
              
              <a href="{!! route('userPersonalDataTSNoteUs.show', [$userPersonalDataTSNoteU->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
          
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>