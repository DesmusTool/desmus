@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> User Project Topic Section D </h1>
    
  </section>
    
  <div class="content">
    
    @include('adminlte-templates::common.errors')
        
    <div class="box box-primary">

      <div class="box-body">
                
        <div class="row">
                    
          {!! Form::open(['route' => 'userProjectTopicSectionDs.store']) !!}

            @include('user_project_topic_section_ds.fields')

          {!! Form::close() !!}
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection