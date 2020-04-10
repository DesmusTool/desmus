@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Job T S G Image Todolist Update </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
    
    <div class="box box-primary">
      
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($jobTSGImageTodolistUpdate, ['route' => ['jobTSGImageTodolistUpdates.update', $jobTSGImageTodolistUpdate->id], 'method' => 'patch']) !!}

            @include('job_t_s_g_image_todolist_updates.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
      
    </div>
   
  </div>

@endsection