@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1 class="pull-left">Job Topic Todolists</h1>
    <h1 class="pull-right"></h1>
    
  </section>
    
  <div class="content" style = 'margin-top: 20px'>
        
    <div class="clearfix"></div>
    
    @include('flash::message')

    <div class="clearfix"></div>
        
    <div class="box box-primary">
            
      <div class="box-body">
                    
        @include('job_topic_todolists.table')
            
      </div>
        
    </div>
        
    <div class="text-center">
        
    </div>
    
  </div>

@endsection