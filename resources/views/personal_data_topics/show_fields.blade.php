{!! Form::open(['route' => 'personalDataTopicTodolists.store', 'id' => 'personal_data_topic_todolist_create']) !!}

  {{ csrf_field() }}

  <div id="add_task" class="modal fade" role="dialog">
      
    <div class="modal-dialog modal-lg">
        
      <div class="modal-content">
          
        <div class="modal-header">
            
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              
            <span aria-hidden="true">×</span>
            
          </button>
            
          <h4 class="modal-title"> Add Task </h4>
            
        </div>
          
        <div class="modal-body">
            
          <div class = "row">
              
            <div class="form-group col-sm-12">
              {!! Form::label('name', 'Name:') !!}
              {!! Form::text('name', null, ['class' => 'form-control'], array('required' => 'required', 'id' => 'name')) !!}
            </div>
              
            <div class="form-group col-sm-12 col-lg-12">
              {!! Form::label('description', 'Description:') !!}
              {!! Form::textarea('description', 'Add a description ...', ['class' => 'form-control'], array('required' => 'required', 'id' => 'description')) !!}
            </div>
              
            <div class="form-group col-sm-12">
              {!! Form::label('datetime', 'Datetime:') !!}
              {!! Form::datetimelocal('datetime', $now, ['class' => 'form-control'], array('required' => 'required', 'id' => 'datetime')) !!}
            </div>
              
            <div class="form-group col-sm-6">
              {!! Form::hidden('views_quantity', 'Views Quantity:') !!}
              {!! Form::hidden('views_quantity', 0, ['class' => 'form-control']) !!}
            </div>
              
            <div class="form-group col-sm-6">
              {!! Form::hidden('updates_quantity', 'Updates Quantity:') !!}
              {!! Form::hidden('updates_quantity', 0, ['class' => 'form-control']) !!}
            </div>
              
            <div class="form-group col-sm-6">
              {!! Form::hidden('status', 'Status:') !!}
              {!! Form::hidden('status', 'active', ['class' => 'form-control']) !!}
            </div>
              
            <div class="form-group col-sm-6">
              {!! Form::hidden('personal_data_topic_id', 'PersonalData Id:') !!}
              {!! Form::hidden('personal_data_topic_id', $id, ['class' => 'form-control']) !!}
            </div>
              
          </div>
            
        </div>
          
        <div class="modal-footer">
            
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Task</button>
            
        </div>
          
      </div>
        
    </div>
      
  </div>
    
{!! Form::close() !!}

<section class="content" style="min-height: 30px;">

  <div class="row">
 
    <div class="col-md-12">
          
      <div class="nav-tabs-custom" style="margin-bottom: 0;">
            
        <ul class="nav nav-tabs">

          <li class = "active"><a href="#personal_data_topic_section" data-toggle="tab"> Sections </a></li>
          <li><a href="#personal_data_topic_info" data-toggle="tab"> Information </a></li>
          <li><a href="#personal_data_topic_specific_info" data-toggle="tab"> Specific Information </a></li>
          <li><a href="#personal_data_topic_tasks" data-toggle="tab"> Tasks </a></li>
          <li><a href="#personal_data_topic_statistics" data-toggle="tab"> Statistics </a></li>
          <li><a href="#personal_data_topic_most_viewed" data-toggle="tab"> Most Viewed </a></li>
          <li><a href="#personal_data_topic_views_history" data-toggle="tab"> Views History </a></li>
          <li><a href="#personal_data_topic_updates_history" data-toggle="tab"> Updates History </a></li>

        </ul>

        <div class="tab-content clearfix">

          <div class = "tab-pane active" id = "personal_data_topic_section">

            @include('personal_data_topic_sections.filtered_table')
            
            <div class="mailbox-controls" style="margin-top: 10px;">
                              
              <div class="btn-group">
                                  
              </div>
                                
              <div class="pull-right">
                                
                1-50
                                  
                <div class="btn-group">
                                    
                  @if($personal_data_section_p < 1)
                        
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p=1" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p=2" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                          
                  @endif
                          
                  @if($personal_data_section_p == 1)
                                      
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p=1" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p={!! $personal_data_section_p + 1 !!}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
  
                  @endif
                          
                  @if($personal_data_section_p > 1)
                          
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p={!! $personal_data_section_p - 1 !!}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a href = "http://desmus-jmsp.c9users.io/personalDataTopics/{!! $id !!}?personal_data_section_p={!! $personal_data_section_p + 1 !!}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                            
                  @endif
                                    
                </div>
                                
              </div>
                                
            </div>

          </div>
          
          <div class = "tab-pane" id = "personal_data_topic_info">
            
            <div class="form-group">
              {!! Form::label('name', 'Name:') !!}
              <p>{!! $personalDataTopic->name !!}</p>
            </div>
            
            <div class="form-group">
              {!! Form::label('user_name', 'User Name:') !!}
              <p>{!! $user[0]->name !!}</p>
            </div>
            
            <div class="form-group">
              {!! Form::label('user_email', 'User Email:') !!}
              <p>{!! $user[0]->email !!}</p>
            </div>
            
            <div class="form-group">
              {!! Form::label('created_at', 'Created At:') !!}
              <p>{!! $personalDataTopic->created_at !!}</p>
            </div>
            
            <div class="form-group">
              {!! Form::label('updated_at', 'Updated At:') !!}
              <p>{!! $personalDataTopic->updated_at !!}</p>
            </div>
            
          </div>

          <div class = "tab-pane" id = "personal_data_topic_specific_info">

            <div id="toolbar-container"></div>

            {!! Form::model($personalDataTopic, ['route' => ['personalDataTopics.update', $personalDataTopic->id], 'method' => 'patch']) !!}

              <div class = "form-group" id="editor"> {!! $personalDataTopic->specific_info !!} </div>

              <textarea id = "text" name = "specific_info" hidden> </textarea>

              <input type = "datetime" name = "updated_at" value = "{!! $now !!}" hidden>

              <div class="form-group col-sm-12" style="margin-bottom: 0;">
                {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}
              </div>

            {!! Form::close() !!}

          </div>
          
          <div class = "tab-pane" id = "personal_data_topic_tasks">
          
            <div class="box box-primary" style="position: relative; left: 0px; top: 0px;">
          
              <div class="box-header ui-sortable-handle" style="cursor: move;">
              
                <i class="ion ion-clipboard"></i>
                <h3 class="box-title">To Do List</h3>
                
              </div>
              
              <div class="box-body">
                
                <ul class="todo-list ui-sortable">
                  
                  @foreach($personalDataTopicTodolist as $task)
                  
                    <a href = "{!! route('personalDataTopicTodolists.show', [$task->id]) !!}">
                  
                      <li>
                        
                        <span class="handle ui-sortable-handle">
                          
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                          
                        </span>
                        
                        <span class="text">{{ $task -> name }}   ---   {{ $task -> datetime }}</span>
                        
                        <div class="tools">
                          
                          {!! Form::open(['route' => ['personalDataTopicTodolists.destroy', $task->id], 'method' => 'delete']) !!}
                          
                            <a class='btn btn-default btn-xs' href = "{!! route('personalDataTopicTodolists.edit', [$task->id]) !!}"> <i class="fa fa-edit"></i> </a>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            
                          {!! Form::close() !!}
                    
                        </div>
                  
                      </li>
                  
                    </a>
                  
                  @endforeach
              
                </ul>
            
              </div>
            
              <div class="box-footer clearfix no-border">
              
                <a href = "" class="btn btn-default pull-right" data-toggle="modal" data-target="#add_task"><i class="fa fa-plus"></i> Add task </a>
            
              </div>
          
            </div>
            
            <div class="box box-primary" style="position: relative; left: 0px; top: 0px; margin-bottom: 10px;">
          
              <div class="box-header ui-sortable-handle" style="cursor: move;">
              
                <i class="ion ion-clipboard"></i>
                <h3 class="box-title">Finished Tasks</h3>
                
              </div>
              
              <div class="box-body">
                
                <ul class="todo-list ui-sortable">
                  
                  @foreach($personalDataTopicTodolistCompleted as $task)
                  
                    <a href = "{!! route('personalDataTopicTodolists.show', [$task->id]) !!}">
                  
                      <li>
                        
                        <span class="handle ui-sortable-handle">
                          
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                          
                        </span>
                        
                        <span class="text">{{ $task -> name }}   ---   {{ $task -> datetime }}</span>
                        
                        <div class="tools">
                          
                          {!! Form::open(['route' => ['personalDataTopicTodolists.destroy', $task->id], 'method' => 'delete']) !!}
                          
                            <a class='btn btn-default btn-xs' href = "{!! route('personalDataTopicTodolists.edit', [$task->id]) !!}"> <i class="fa fa-edit"></i> </a>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            
                          {!! Form::close() !!}
                    
                        </div>
                  
                      </li>
                  
                    </a>
                  
                  @endforeach
              
                </ul>
            
              </div>
          
            </div>
            
          </div>

          <div class = "tab-pane" id = "personal_data_topic_statistics">

            <div class="col-lg-4 col-xs-4">
          
              <div class="small-box bg-yellow" style="margin-bottom: 0;">
            
                <div class="inner">
              
                  <h3><sup style="font-size: 20px">{!! $personalDataTopicSectionCount !!}</sup></h3>

                  <p>Sections Quantity</p>
            
                </div>
            
                <div class="icon">
              
                  <i class="ion ion-stats-bars"></i>
            
                </div>
            
                <a href = "#personal_data_topic_section" data-toggle="tab" class="small-box-footer">
            
                  More Information
            
                </a>
          
              </div>
        
            </div>

            <div class="col-lg-4 col-xs-4">
          
              <div class="small-box bg-green" style="margin-bottom: 0;">
            
                <div class="inner">
              
                  <h3><sup style="font-size: 20px">{!! $personalDataTopic -> views_quantity !!}</sup></h3>

                  <p>Views Quantity</p>
            
                </div>
            
                <div class="icon">
              
                  <i class="ion ion-stats-bars"></i>
            
                </div>
            
                <a href = "#personal_data_topic_views_history" data-toggle="tab" class="small-box-footer">
            
                  More Information
            
                </a>
          
              </div>
        
            </div>

            <div class="col-lg-4 col-xs-4">
          
              <div class="small-box bg-red" style="margin-bottom: 0;">
            
                <div class="inner">
              
                  <h3><sup style="font-size: 20px">{!! $personalDataTopic -> updates_quantity !!}</sup></h3>

                  <p>Updates Quantity</p>
            
                </div>
            
                <div class="icon">
              
                  <i class="ion ion-stats-bars"></i>
            
                </div>
            
                <a href = "#personal_data_topic_updates_history" data-toggle="tab" class="small-box-footer">
            
                  More Information
            
                </a>
          
              </div>
        
            </div>

          </div>

          <div class = "tab-pane" id = "personal_data_topic_most_viewed">

            <div class="box box-primary col-sm-6" style="margin-bottom: 0">
            
              <div class="box-header with-border">
              
                <h3 class="box-title">Most Viewed Sections</h3>
            
              </div>

              <div class="box-body">
              
                <ul class="products-list product-list-in-box">

                  @foreach($personalDataTopicSectionList as $section)
                
                    <li class="item">
                  
                      <div class="product-info">
                    
                        <a href="{!! route('personalDataTopicSections.show', [$section -> id]) !!}" class="product-title">
                          <span class="label label-warning pull-right">{!! $section -> views_quantity !!}</span>
                        </a>
                
                        <a href = "{!! route('personalDataTopicSections.show', [$section -> id]) !!}">
                          <span class="product-description">
                            {!! $section->name !!}
                          </span>
                        </a>
                  
                      </div>
                
                    </li>

                  @endforeach
        
                </ul>
            
              </div>
          
            </div>

          </div>
          
          <div class = "tab-pane" id = "personal_data_topic_views_history">

            <div class="box box-primary" style="margin-bottom: 0;">

              <div class="box-body">
              
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                  <div class="row">

                    <div class="col-sm-12">
                      
                      <div class="table-responsive">

                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="margin-bottom: 0;">
                  
                          <thead>
                  
                            <tr role="row">
  
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Username </th>
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Email </th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 182px;"> Datetime </th>
                              
                            </tr>
                  
                          </thead>
                  
                          <tbody>
                    
                            @foreach($personalDataTopicViews as $personalDataTopicView)
  
                              <tr role="row" class="odd">
                              
                                <td class=""> {!! $personalDataTopicView->name !!} </td>
                                <td class=""> {!! $personalDataTopicView->email !!} </td>
                                <td class=""> {!! $personalDataTopicView->datetime !!} </td>
                  
                              </tr>
      
                            @endforeach
  
                          </tbody>
                
                        </table>
                        
                      </div>

                    </div>

                  </div>

                </div>
            
              </div>

            </div>

          </div>

          <div class = "tab-pane" id = "personal_data_topic_updates_history">

            <div class="box box-primary" style="margin-bottom: 0;">

              <div class="box-body">
              
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                  <div class="row">

                    <div class="col-sm-12">
                      
                      <div class="table-responsive">

                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="margin-bottom: 0;">
                  
                          <thead>
                  
                            <tr role="row">
  
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Actual Name </th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 182px;"> Past Name </th>
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Username </th>
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Email </th>
                              <th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 224px;" aria-sort="descending"> Datetime Update </th>
  
                            </tr>
                  
                          </thead>
                  
                          <tbody>
                    
                            @foreach($personalDataTopicUpdates as $personalDataTopicUpdate)
  
                              <tr role="row" class="odd">
                              
                                <td class=""> {!! $personalDataTopicUpdate->actual_name !!} </td>
                                <td class=""> {!! $personalDataTopicUpdate->past_name !!} </td>
                                <td class=""> {!! $personalDataTopicUpdate->name !!} </td>
                                <td class=""> {!! $personalDataTopicUpdate->email !!} </td>
                                <td class="sorting_1"> {!! $personalDataTopicUpdate->datetime !!} </td>
                  
                              </tr>
      
                            @endforeach
  
                          </tbody>
                
                        </table>
                        
                      </div>

                    </div>

                  </div>

                </div>
            
              </div>

            </div>

          </div>

        </div>

      </div>
              
    </div>
            
  </div>

</section>