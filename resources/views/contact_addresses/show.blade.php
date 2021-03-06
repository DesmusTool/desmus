@extends('layouts.app')

@section('content')

  <section class="content-header">
    
    <h1> Contact Address </h1>
    
  </section>
  
  <div class="content">
    
    <div class="box box-primary">
      
      <div class="box-body">
        
        <div class="row" style="padding: 0">
          
          @include('contact_addresses.show_fields')
          
          <a href="{!! route('contacts.show', [$id]) !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
          
        </div>
        
      </div>
      
    </div>
    
  </div>

@endsection