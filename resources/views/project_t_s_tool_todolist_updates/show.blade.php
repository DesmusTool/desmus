@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Project T S Tool Todolist Update </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row" style="padding-left: 20px">
                    
          @include('project_t_s_tool_todolist_updates.show_fields')
          <a href="{!! route('projectTSToolTodolistUpdates.index') !!}" class="btn btn-default">Back</a>
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection