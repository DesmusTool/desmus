<div class="table-responsive">
  
  <table class="table table-bordered table-striped dataTable" id="userPersonalDataTSPs-filtered_table">
    
    <thead>
          
      <tr>
              
        <th>Username</th>
        <th>Email</th>
        <th>Description</th>
        <th>Permissions</th>
        <th>Datetime</th>
        <th>Action</th>
          
      </tr>
      
    </thead>
      
    <tbody>
      
      @foreach($userPersonalDataTSPs as $userPersonalDataTSP)
      
        <tr>
              
          <td> {!! $userPersonalDataTSP->name !!} </td>
          <td> {!! $userPersonalDataTSP->email !!} </td>
          <td> {!! $userPersonalDataTSP->description !!} </td>
          <td> {!! $userPersonalDataTSP->permissions !!}</td>
          <td> {!! $userPersonalDataTSP->datetime !!} </td>
          
          <td>
                  
            {!! Form::open(['route' => ['userPersonalDataTSPs.destroy', $userPersonalDataTSP->id], 'method' => 'delete']) !!}
                  
              <div class='btn-group'>
                      
                <a href="{!! route('userPersonalDataTSPs.edit', [$userPersonalDataTSP->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                  
              </div>
                  
            {!! Form::close() !!}
              
          </td>
          
        </tr>
      
      @endforeach
      
    </tbody>
  
  </table>
  
</div>