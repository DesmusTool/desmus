@extends('layouts.app')

@section('scripts')

  @foreach($sharedProfileNoteComments as $sharedProfileNoteComment)
              
    @if($sharedProfileNote -> id == $sharedProfileNoteComment -> s_p_n_id)
        
      <script>
                                              
        $('#shared_profile_note_comment_response_{!! $sharedProfileNoteComment -> id !!}').on('submit', function() {
                                                  
          var shared_profile_note_comment_response = document.getElementById("shared_profile_note_comment_response_content_{!! $sharedProfileNoteComment -> id !!}").value;
                                                  
          if(shared_profile_note_comment_response.length >= 1000)
          {
            alert("Invalid character number for the note comment response.");
            return false;
          }
                                                  
          if(shared_profile_note_comment_response == '')
          {
            alert("You need to complete the field to post a comment response.");
            return false;
          }
                                                  
          if(shared_profile_note_comment_response != '')
          {
            return true;
          }
                                                  
          return false;
                                                  
        });
                                                
      </script>
          
    @endif
        
  @endforeach

  <script>
    
    $('#shared_profile_note_comment').on('submit', function() {
      
      var shared_profile_note_comment = document.getElementById("shared_profile_note_comment_content").value;
      
      if(shared_profile_note_comment.length >= 1000)
      {
        alert("Invalid character number for the file comment.");
        return false;
      }
      
      if(shared_profile_note_comment == '')
      {
        alert("You need to complete the field to post a comment.");
        return false;
      }
      
      if(shared_profile_note_comment != '')
      {
        return true;
      }
      
      return false;
      
    });
    
  </script>
  
  <script>

    DecoupledEditor
            
      .create(document.querySelector('#editor'))
            
      .then( editor => {
                
        const toolbarContainer = document.querySelector('#toolbar-container');
        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
            
      })
            
      .catch( error => {
                
        console.error(error);
        
      });

  </script>

  <script>

    var jq=jQuery.noConflict();
    
    jq(document).ready( function(){
      
      jq(document).keydown(function(event){

        var content = document.getElementById("editor").children;
        var contentCount = document.getElementById("editor").childElementCount;

        jq(document).ready(function($){

          $("#text").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text');

          text.val(allText);

        });
        
      });
      
      jq(document).mousedown(function(event){

        var content = document.getElementById("editor").children;
        var contentCount = document.getElementById("editor").childElementCount;

        jq(document).ready(function($){

          $("#text").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text');

          text.val(allText);

        });
        
      });
    
    });

  </script>
    
@endsection

@section('content')
    
  <section class="content-header">
        
    <h1> {!! $sharedProfileNote->name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row">
                    
          @include('shared_profile_notes.show_fields')
          <a href="{!! route('sharedProfile.index') !!}" class="btn btn-default" style="margin-left: 20px">Back</a>
                
        </div>
            
      </div>
        
    </div>
  
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class = "active">
        
        <a href="#shared_profile_note_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#shared_profile_note_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="shared_profile_note_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Note Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileNoteViewsList as $sharedProfileNoteViewList)
            
             <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileNoteViewList -> name !!} </h4>
                  <p> {!! $sharedProfileNoteViewList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="shared_profile_note_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Note Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($sharedProfileNoteUpdatesList as $sharedProfileNoteUpdateList)
            
            <li>
                
              <a href="">
                  
                <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                <div class="menu-info">
                    
                  <h4 class="control-sidebar-subheading"> {!! $sharedProfileNoteUpdateList -> name !!} </h4>
                  <p> {!! $sharedProfileNoteUpdateList -> datetime !!} </p>
                  
                </div>
                
              </a>
              
            </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>

@endsection