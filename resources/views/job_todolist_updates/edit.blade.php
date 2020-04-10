@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Job Todolist Update </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($jobTodolistUpdate, ['route' => ['jobTodolistUpdates.update', $jobTodolistUpdate->id], 'method' => 'patch']) !!}

            @include('job_todolist_updates.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
   
  </div>

@endsection