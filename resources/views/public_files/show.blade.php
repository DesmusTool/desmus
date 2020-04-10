@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#public_file_comment').on('submit', function() {
      
      var public_file_comment = document.getElementById("public_file_comment_content").value;
      
      if(public_file_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(public_file_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(public_file_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($publicFileComments as $publicFileComment)
              
    @if($publicFile -> id == $publicFileComment -> public_file_id)
        
      <script>
                                              
        $('#public_file_comment_response_{!! $publicFileComment -> id !!}').on('submit', function() {
                                                  
          var public_file_comment_response = document.getElementById("public_file_comment_response_content_{!! $publicFileComment -> id !!}").value;
                                                  
          if(public_file_comment_response.length >= 1000)
          {
            alert("Invalid character number for the file comment response.");
            return false;
          }
                                                  
          if(public_file_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(public_file_comment_response != '')
          {
            return true;
          }
                                                  
          return false;
                                                  
        });
                                                
      </script>
          
    @endif
        
  @endforeach
    
@endsection

@section('content')
    
  <section class="content-header">
        
    <h1> {!! $publicFile->name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row">
                    
          @include('public_files.show_fields')
          <a href="{!! route('publicProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
                
        </div>
            
      </div>
        
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#public_file_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#public_file_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">

      <div class="tab-pane active" id="public_file_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> File Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicFileViewsList as $publicFileViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicFileViewList -> name !!} </h4>
                  <p> {!! $publicFileViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="public_file_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> File Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($publicFileUpdatesList as $publicFileUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $publicFileUpdateList -> name !!} </h4>
                  <p> {!! $publicFileUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>

@endsection