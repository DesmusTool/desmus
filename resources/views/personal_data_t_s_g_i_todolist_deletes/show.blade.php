@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Personal Data T S G I Todolist Delete </h1>
  
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row" style="padding-left: 20px">
                    
          @include('personal_data_t_s_g_i_todolist_deletes.show_fields')
          <a href="{!! route('personalDataTSGITodolistDeletes.index') !!}" class="btn btn-default">Back</a>
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection