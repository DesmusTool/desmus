@extends('layouts.app')

@section('content')
    
  <section class="content-header">
        
    <h1> {!! $personalDataTSToolTodolist -> name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row" style="padding-left: 20px">
                    
          @include('personal_data_t_s_tool_todolists.show_fields')
          <a href="{!! route('personalDataTSTools.show', [$personalDataTSToolTodolist -> p_d_t_s_t_id]) !!}" class="btn btn-default">Back</a>
                
        </div>
            
      </div>
        
    </div>
    
  </div>

@endsection