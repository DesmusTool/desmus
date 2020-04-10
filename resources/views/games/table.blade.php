<table class="table table-responsive" id="games-table">
    
  <thead>
        
    <tr>
            
      <th>Name</th>
      <th>Category</th>
      <!--<th>Type</th>-->
      <th>Price</th>
      <!--<th>Description</th>
      <th>Instructions</th>
      <th>Views Quantity</th>
      <th>Updates Quantity</th>
      <th>Status</th>
      <th>User Id</th>-->
      <th colspan="3">Action</th>
        
    </tr>
    
  </thead>
    
  <tbody>
    
    @foreach($games as $games)
        
      <tr>
            
        <td>{!! $games->name !!}</td>
        <td>{!! $games->category !!}</td>
        <td>{!! $games->type !!}</td>
        <td>{!! $games->price !!}</td>
            
        <td>
                
          {!! Form::open(['route' => ['games.destroy', $games->id], 'method' => 'delete']) !!}
                
            <div class='btn-group'>
                    
              <a href="{!! route('games.show', [$games->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
              <a href="{!! route('games.edit', [$games->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    
              {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                
            </div>
                
          {!! Form::close() !!}
            
        </td>
        
      </tr>
    
    @endforeach
    
  </tbody>

</table>