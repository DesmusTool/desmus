@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> Personal Data Topic Section View </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row" style="padding-left: 20px">
                    
          @include('personal_data_topic_section_views.show_fields')
          <a href="{!! route('personalDataTopicSectionViews.index') !!}" class="btn btn-default">Back</a>
        
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection