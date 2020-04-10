@extends('layouts.app')

@section('scripts')

  <script>
    
    $('#shared_profile_image_comment').on('submit', function() {
      
      var shared_profile_image_comment = document.getElementById("shared_profile_image_comment_content").value;
      
      if(shared_profile_image_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(shared_profile_image_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(shared_profile_image_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  @foreach($sharedProfileImageComments as $sharedProfileImageComment)
              
    @if($sharedProfileImage -> id == $sharedProfileImageComment -> s_p_i_id)
        
      <script>
                                              
        $('#shared_profile_image_comment_response_{!! $sharedProfileImageComment -> id !!}').on('submit', function() {
                                                  
          var shared_profile_image_comment_response = document.getElementById("shared_profile_image_comment_response_content_{!! $sharedProfileImageComment -> id !!}").value;
                                                  
          if(shared_profile_image_comment_response.length >= 1000)
          {
            alert("Invalid character number for the image comment response.");
            return false;
          }
                                                  
          if(shared_profile_image_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(shared_profile_image_comment_response != '')
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
        
    <h1> {!! $sharedProfileImage->name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row">
                    
          @include('shared_profile_images.show_fields')
          <a href="{!! route('sharedProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
                
        </div>
            
      </div>
        
    </div>
    
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#shared_profile_image_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#shared_profile_image_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="shared_profile_image_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Image Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileImageViewsList as $sharedProfileImageViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileImageViewList -> name !!} </h4>
                  <p> {!! $sharedProfileImageViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="shared_profile_image_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Image Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileImageUpdatesList as $sharedProfileImageUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileImageUpdateList -> name !!} </h4>
                  <p> {!! $sharedProfileImageUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>

@endsection