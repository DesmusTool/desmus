@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Job T S Tool Delete </h1>
  
  </section>
   
  <div class="content">
       
    @include('adminlte-templates::common.errors')
       
    <div class="box box-primary">
           
      <div class="box-body">
               
        <div class="row">
                   
          {!! Form::model($jobTSToolDelete, ['route' => ['jobTSToolDeletes.update', $jobTSToolDelete->id], 'method' => 'patch']) !!}

            @include('job_t_s_tool_deletes.fields')

          {!! Form::close() !!}
               
        </div>
           
      </div>
       
    </div>
  
  </div>

@endsection