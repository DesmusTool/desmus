@extends('layouts.app')

@section('content')
    
  <section class="content-header">
    
    <h1> Job T S File Todolist Delete </h1>
    
  </section>
    
  <div class="content">
        
    @include('adminlte-templates::common.errors')
        
    <div class="box box-primary">

      <div class="box-body">
                
        <div class="row">
                    
          {!! Form::open(['route' => 'jobTSFileTodolistDeletes.store']) !!}

            @include('job_t_s_file_todolist_deletes.fields')

          {!! Form::close() !!}
                
        </div>
            
      </div>
        
    </div>
  
  </div>

@endsection