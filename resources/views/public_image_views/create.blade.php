@extends('layouts.app')

@section('content')

  <section class="content-header">
        
    <h1> Public Image View </h1>
    
  </section>
    
  <div class="content">
        
    @include('adminlte-templates::common.errors')
        
    <div class="box box-primary">

      <div class="box-body">
                
        <div class="row">
                    
          {!! Form::open(['route' => 'publicImageViews.store']) !!}

            @include('public_image_views.fields')

          {!! Form::close() !!}
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection