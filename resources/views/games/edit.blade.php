@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#game_edit').on('submit', function() {
      
      var game_name = document.getElementById("name").value;
      var game_category = document.getElementById("category").value;
      var game_type = document.getElementById("type").value;
      var game_price = document.getElementById("price").value;
      /*var game_description = document.getElementById("description").value;
      var game_instructions = document.getElementById("instructions").value;
      
      if(game_name == '' || game_category == '' || game_type == '' || game_price == '' || game_description == '' || game_instructions == '')
      {
        alert("You need to complete all the fields.");
        return false;
      }
      
      if(game_name != '' && game_category != '' && game_type != '' && game_price != '' && game_description != '' && game_instructions != '')
      {
        return true;
      }*/

      if(game_name == '' || game_category == '' || game_type == '' || game_price == '')
      {
        alert("You need to complete all the fields.");
        return false;
      }
      
      if(game_name != '' && game_category != '' && game_type != '' && game_price != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>

@endsection

@section('content')
    
  <section class="content-header">
        
    <h1> Games </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($games, ['route' => ['games.update', $games->id], 'method' => 'patch', 'id' => 'game_edit']) !!}

            @include('games.fields')
            
          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
  
  </div>

@endsection