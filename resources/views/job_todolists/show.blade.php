@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> {!! $jobTodolist -> name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row" style="padding-left: 20px">
                    
          @include('job_todolists.show_fields')
          <a href="{!! route('jobs.show', [$jobTodolist -> job_id]) !!}" class="btn btn-default">Back</a>
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection