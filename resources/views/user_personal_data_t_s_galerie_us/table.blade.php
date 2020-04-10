<div class="table-responsive">

  <table class="table table-bordered table-striped dataTable" id="userPersonalDataTSGalerieUs-table">
      
    <thead>
          
      <tr>
              
        <th>Datetime</th>
        <th>User Id</th>
        <th>U P D T S G Id</th>
        <th colspan="3">Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($userPersonalDataTSGalerieUs as $userPersonalDataTSGalerieU)
          
        <tr>
              
          <td>{!! $userPersonalDataTSGalerieU->datetime !!}</td>
          <td>{!! $userPersonalDataTSGalerieU->user_id !!}</td>
          <td>{!! $userPersonalDataTSGalerieU->u_p_d_t_s_g_id !!}</td>
              
          <td>
            
            <div class='btn-group'>
              
              <a href="{!! route('userPersonalDataTSGalerieUs.show', [$userPersonalDataTSGalerieU->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
            
            </div>
          
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>