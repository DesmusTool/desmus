<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateProjectTSFileRequest;
use App\Http\Requests\UpdateProjectTSFileRequest;
use App\Repositories\ProjectTSFileRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;
use App\Models\ProjectTSFile;
use Illuminate\Support\Carbon;

class ProjectTSFileController extends AppBaseController
{
    private $projectTSFileRepository;

    public function __construct(ProjectTSFileRepository $projectTSFileRepo)
    {
        $this->projectTSFileRepository = $projectTSFileRepo;
    }

    public function index(Request $request)
    {
        if(Auth::user() != null && Auth::user()->email == 'josemsoberonpenaloza@gmail.com')
        {
            $this->projectTSFileRepository->pushCriteria(new RequestCriteria($request));
            $projectTSFiles = $this->projectTSFileRepository->all();
    
            return view('project_t_s_files.index')
                ->with('projectTSFiles', $projectTSFiles);
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function create($id)
    {
        if(Auth::user() != null)
        {
            $projectTSFilesList = ProjectTSFile::where('project_topic_section_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(5)->get();

            return view('project_t_s_files.create')
                ->with('id', $id)
                ->with('projectTSFilesList', $projectTSFilesList);
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function store(CreateProjectTSFileRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $input = $request->all();
            $projectTSFile = $this->projectTSFileRepository->create($input);
    
            $file = $request->file('file');
            $new_file = 'file_' . $projectTSFile -> id . '.' . $file -> getClientOriginalExtension();
            $file->move(public_path("files/projects/"), $new_file);
            $fileType = $file -> getClientOriginalExtension();
            $file_path = "files/projects/file_" . $projectTSFile -> id . '.' . $file -> getClientOriginalExtension();
            $size = filesize($file_path);
    
            DB::table('project_t_s_files')->where('id', $projectTSFile->id)->update(['file_type' => $fileType, 'file_size' => $size]);
            DB::table('project_t_s_file_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'project_t_s_file_id' => $projectTSFile -> id]);
            DB::table('recent_activities')->insert(['name' => $projectTSFile -> name, 'status' => 'active', 'type' => 'p_t_s_f_c', 'user_id' => $user_id, 'entity_id' => $projectTSFile -> id, 'created_at' => $now]);
    
            Flash::success('Project T S File saved successfully.');
            return redirect(route('projectTopicSections.show', [$projectTSFile -> project_topic_section_id]));
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function show($id)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $projectTSFile = $this->projectTSFileRepository->findWithoutFail($id);
            
            if(empty($projectTSFile))
            {
                Flash::error('Project T S File not found');
                return redirect(route('projectTSFiles.index'));
            }
            
            $userProjectTSFiles = DB::table('user_project_t_s_files')->where('project_t_s_file_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userProjectTSFiles as $userProjectTSFile)
            {
                if($userProjectTSFile -> user_id == $user_id)
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('project_t_s_files')->join('project_topic_sections', 'project_t_s_files.project_topic_section_id', '=', 'project_topic_sections.id')->join('project_topics', 'project_topic_sections.project_topic_id', '=', 'project_topics.id')->join('projects', 'project_topics.project_id', '=', 'projects.id')->join('users', 'users.id', '=', 'projects.user_id')->where('project_t_s_files.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id || $isShared)
            {
                DB::table('project_t_s_file_views')->insert(['datetime' => $now, 'user_id' => $user_id, 'project_t_s_file_id' => $id]);
                DB::table('project_t_s_files')->where('id', $id)->update(['views_quantity' => DB::raw('views_quantity + 1')]);
                
                $projectTSFile = $this->projectTSFileRepository->findWithoutFail($id);
                $projectTopicSectionFileViews = DB::table('users')->join('project_t_s_file_views', 'users.id', '=', 'project_t_s_file_views.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();
                $projectTopicSectionFileUpdates = DB::table('users')->join('project_t_s_file_updates', 'users.id', '=', 'project_t_s_file_updates.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();

                $userProjectTSFilesList = DB::table('user_project_t_s_files')->join('users', 'user_project_t_s_files.user_id', '=', 'users.id')->select('name', 'email', 'user_project_t_s_files.description', 'permissions', 'user_project_t_s_files.datetime', 'user_project_t_s_files.id', 'project_t_s_file_id')->where('project_t_s_file_id', $id)->where(function ($query) {$query->where('user_project_t_s_files.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
                $projectTopicSectionFileViewsList = DB::table('users')->join('project_t_s_file_views', 'users.id', '=', 'project_t_s_file_views.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $projectTopicSectionFileUpdatesList = DB::table('users')->join('project_t_s_file_updates', 'users.id', '=', 'project_t_s_file_updates.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
    
                return view('project_t_s_files.show')
                    ->with('projectTSFile', $projectTSFile)
                    ->with('projectTSFileViews', $projectTopicSectionFileViews)
                    ->with('projectTSFileUpdates', $projectTopicSectionFileUpdates)
                    ->with('userProjectTSFilesList', $userProjectTSFilesList)
                    ->with('projectTSFileViewsList', $projectTopicSectionFileViewsList)
                    ->with('projectTSFileUpdatesList', $projectTopicSectionFileUpdatesList)
                    ->with('user', $user);
            }
            
            else
            {
                return view('deniedAccess');
            }
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function edit($id)
    {
        if(Auth::user() != null)
        {
            $user_id = Auth::user()->id;
            $projectTSFile = $this->projectTSFileRepository->findWithoutFail($id);
    
            if(empty($projectTSFile))
            {
                Flash::error('Project T S File not found');
                return redirect(route('projectTSFiles.index'));
            }
            
            $userProjectTSFiles = DB::table('user_project_t_s_files')->where('project_t_s_file_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userProjectTSFiles as $userProjectTSFile)
            {
                if($userProjectTSFile -> user_id == $user_id && $userProjectTSFile -> permissions == 'advanced')
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('project_t_s_files')->join('project_topic_sections', 'project_t_s_files.project_topic_section_id', '=', 'project_topic_sections.id')->join('project_topics', 'project_topic_sections.project_topic_id', '=', 'project_topics.id')->join('projects', 'project_topics.project_id', '=', 'projects.id')->join('users', 'users.id', '=', 'projects.user_id')->where('project_t_s_files.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id || $isShared)
            {
                $userProjectTSFilesList = DB::table('user_project_t_s_files')->join('users', 'user_project_t_s_files.user_id', '=', 'users.id')->select('name', 'email', 'user_project_t_s_files.description', 'permissions', 'user_project_t_s_files.datetime', 'user_project_t_s_files.id', 'project_t_s_file_id')->where('project_t_s_file_id', $id)->where(function ($query) {$query->where('user_project_t_s_files.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
                $projectTopicSectionFileViewsList = DB::table('users')->join('project_t_s_file_views', 'users.id', '=', 'project_t_s_file_views.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $projectTopicSectionFileUpdatesList = DB::table('users')->join('project_t_s_file_updates', 'users.id', '=', 'project_t_s_file_updates.user_id')->where('project_t_s_file_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();

                return view('project_t_s_files.edit')
                    ->with('projectTSFile', $projectTSFile)
                    ->with('id', $id)
                    ->with('userProjectTSFilesList', $userProjectTSFilesList)
                    ->with('projectTSFileViewsList', $projectTopicSectionFileViewsList)
                    ->with('projectTSFileUpdatesList', $projectTopicSectionFileUpdatesList);
            }
            
            else
            {
                return view('deniedAccess');
            }
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function update($id, UpdateProjectTSFileRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $projectTSFile = $this->projectTSFileRepository->findWithoutFail($id);
    
            if(empty($projectTSFile))
            {
                Flash::error('Project T S File not found');
                return redirect(route('projectTSFiles.index'));
            }
            
            $userProjectTSFiles = DB::table('user_project_t_s_files')->where('project_t_s_file_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userProjectTSFiles as $userProjectTSFile)
            {
                if($userProjectTSFile -> user_id == $user_id && $userProjectTSFile -> permissions == 'advanced')
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('project_t_s_files')->join('project_topic_sections', 'project_t_s_files.project_topic_section_id', '=', 'project_topic_sections.id')->join('project_topics', 'project_topic_sections.project_topic_id', '=', 'project_topics.id')->join('projects', 'project_topics.project_id', '=', 'projects.id')->join('users', 'users.id', '=', 'projects.user_id')->where('project_t_s_files.id', '=', $id)->get();
            
            $size = 0;
            $college_data_sizes = DB::table('colleges')->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
            foreach($college_data_sizes as $college_data_size)
            {
                $size += $college_data_size -> specific_info_size;
                $college_topic_data_sizes = DB::table('college_topics')->where('college_id', '=', $college_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                foreach($college_topic_data_sizes as $college_topic_data_size)
                {
                    $size += $college_topic_data_size -> specific_info_size;
                    $college_section_data_sizes = DB::table('college_topic_sections')->where('college_topic_id', '=', $college_topic_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($college_section_data_sizes as $college_section_data_size)
                    {
                        $size += $college_section_data_size -> specific_info_size;
                        $college_file_data_sizes = DB::table('college_t_s_files')->where('college_topic_section_id', '=', $college_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                
                        foreach($college_file_data_sizes as $college_file_data_size)
                        {
                            $size += $college_file_data_size -> file_size;
                        }
                
                        $college_note_data_sizes = DB::table('college_t_s_notes')->where('college_topic_section_id', '=', $college_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($college_note_data_sizes as $college_note_data_size)
                        {
                            $size += $college_note_data_size -> specific_info_size;
                        }
                                
                        $college_galery_data_sizes = DB::table('college_t_s_galeries')->where('college_topic_section_id', '=', $college_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($college_galery_data_sizes as $college_galery_data_size)
                        {
                            //$size += $college_galery_data_size -> specific_info_size;
                            $college_image_data_sizes = DB::table('college_t_s_galery_images')->where('college_t_s_g_id', '=', $college_galery_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($college_image_data_sizes as $college_image_data_size)
                            {
                                $size += $college_image_data_size -> file_size;
                            }
                        }
                                
                        $college_playlist_data_sizes = DB::table('college_t_s_playlists')->where('c_t_s_id', '=', $college_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($college_playlist_data_sizes as $college_playlist_data_size)
                        {
                            //$size += $college_playlist_data_size -> specific_info_size;
                            $college_audio_data_sizes = DB::table('college_t_s_p_audios')->where('c_t_s_p_id', '=', $college_playlist_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($college_audio_data_sizes as $college_audio_data_size)
                            {
                                $size += $college_audio_data_size -> file_size;
                            }
                        }
                                
                        $college_tool_data_sizes = DB::table('college_t_s_tools')->where('college_topic_section_id', '=', $college_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($college_tool_data_sizes as $college_tool_data_size)
                        {
                            //$size += $college_tool_data_size -> specific_info_size;
                            $college_tool_file_data_sizes = DB::table('college_t_s_tool_files')->where('college_t_s_t_id', '=', $college_tool_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($college_tool_file_data_sizes as $college_tool_file_data_size)
                            {
                                $size += $college_tool_file_data_size -> file_size;
                            }
                        }
                    }
                }
            }
            
            $job_data_sizes = DB::table('jobs')->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

            foreach($job_data_sizes as $job_data_size)
            {
                $size += $job_data_size -> specific_info_size;
                $job_topic_data_sizes = DB::table('job_topics')->where('job_id', '=', $job_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                foreach($job_topic_data_sizes as $job_topic_data_size)
                {
                    $size += $job_topic_data_size -> specific_info_size;
                    $job_section_data_sizes = DB::table('job_topic_sections')->where('job_topic_id', '=', $job_topic_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($job_section_data_sizes as $job_section_data_size)
                    {
                        $size += $job_section_data_size -> specific_info_size;
                        $job_file_data_sizes = DB::table('job_t_s_files')->where('job_topic_section_id', '=', $job_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                
                        foreach($job_file_data_sizes as $job_file_data_size)
                        {
                            $size += $job_file_data_size -> file_size;
                        }
                
                        $job_note_data_sizes = DB::table('job_t_s_notes')->where('job_topic_section_id', '=', $job_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($job_note_data_sizes as $job_note_data_size)
                        {
                            $size += $job_note_data_size -> specific_info_size;
                        }
                                
                        $job_galery_data_sizes = DB::table('job_t_s_galeries')->where('job_topic_section_id', '=', $job_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($job_galery_data_sizes as $job_galery_data_size)
                        {
                            //$size += $job_galery_data_size -> specific_info_size;
                            $job_image_data_sizes = DB::table('job_t_s_galery_images')->where('job_t_s_g_id', '=', $job_galery_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($job_image_data_sizes as $job_image_data_size)
                            {
                                $size += $job_image_data_size -> file_size;
                            }
                        }
                                
                        $job_playlist_data_sizes = DB::table('job_t_s_playlists')->where('j_t_s_id', '=', $job_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($job_playlist_data_sizes as $job_playlist_data_size)
                        {
                            //$size += $job_playlist_data_size -> specific_info_size;
                            $job_audio_data_sizes = DB::table('job_t_s_p_audios')->where('j_t_s_p_id', '=', $job_playlist_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($job_audio_data_sizes as $job_audio_data_size)
                            {
                                $size += $job_audio_data_size -> file_size;
                            }
                        }
                                
                        $job_tool_data_sizes = DB::table('job_t_s_tools')->where('job_topic_section_id', '=', $job_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($job_tool_data_sizes as $job_tool_data_size)
                        {
                            //$size += $job_tool_data_size -> specific_info_size;
                            $job_tool_file_data_sizes = DB::table('job_t_s_tool_files')->where('job_t_s_t_id', '=', $job_tool_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($job_tool_file_data_sizes as $job_tool_file_data_size)
                            {
                                $size += $job_tool_file_data_size -> file_size;
                            }
                        }
                    }
                }
            }
            
            $project_data_sizes = DB::table('projects')->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
            foreach($project_data_sizes as $project_data_size)
            {
                $size += $project_data_size -> specific_info_size;
                $project_topic_data_sizes = DB::table('project_topics')->where('project_id', '=', $project_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                foreach($project_topic_data_sizes as $project_topic_data_size)
                {
                    $size += $project_topic_data_size -> specific_info_size;
                    $project_section_data_sizes = DB::table('project_topic_sections')->where('project_topic_id', '=', $project_topic_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($project_section_data_sizes as $project_section_data_size)
                    {
                        $size += $project_section_data_size -> specific_info_size;
                        $project_file_data_sizes = DB::table('project_t_s_files')->where('project_topic_section_id', '=', $project_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                
                        foreach($project_file_data_sizes as $project_file_data_size)
                        {
                            $size += $project_file_data_size -> file_size;
                        }
                
                        $project_note_data_sizes = DB::table('project_t_s_notes')->where('project_topic_section_id', '=', $project_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($project_note_data_sizes as $project_note_data_size)
                        {
                            $size += $project_note_data_size -> specific_info_size;
                        }
                                
                        $project_galery_data_sizes = DB::table('project_t_s_galeries')->where('project_topic_section_id', '=', $project_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($project_galery_data_sizes as $project_galery_data_size)
                        {
                            //$size += $project_galery_data_size -> specific_info_size;
                            $project_image_data_sizes = DB::table('project_t_s_galery_images')->where('project_t_s_g_id', '=', $project_galery_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($project_image_data_sizes as $project_image_data_size)
                            {
                                $size += $project_image_data_size -> file_size;
                            }
                        }
                                
                        $project_playlist_data_sizes = DB::table('project_t_s_playlists')->where('p_t_s_id', '=', $project_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($project_playlist_data_sizes as $project_playlist_data_size)
                        {
                            //$size += $project_playlist_data_size -> specific_info_size;
                            $project_audio_data_sizes = DB::table('project_t_s_p_audios')->where('p_t_s_p_id', '=', $project_playlist_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($project_audio_data_sizes as $project_audio_data_size)
                            {
                                $size += $project_audio_data_size -> file_size;
                            }
                        }
                                
                        $project_tool_data_sizes = DB::table('project_t_s_tools')->where('project_topic_section_id', '=', $project_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($project_tool_data_sizes as $project_tool_data_size)
                        {
                            //$size += $project_tool_data_size -> specific_info_size;
                            $project_tool_file_data_sizes = DB::table('project_t_s_tool_files')->where('project_t_s_t_id', '=', $project_tool_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($project_tool_file_data_sizes as $project_tool_file_data_size)
                            {
                                $size += $project_tool_file_data_size -> file_size;
                            }
                        }
                    }
                }
            }
            
            $personal_data_data_sizes = DB::table('personal_datas')->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
            foreach($personal_data_data_sizes as $personal_data_data_size)
            {
                $size += $personal_data_data_size -> specific_info_size;
                $personal_data_topic_data_sizes = DB::table('personal_data_topics')->where('personal_data_id', '=', $personal_data_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                foreach($personal_data_topic_data_sizes as $personal_data_topic_data_size)
                {
                    $size += $personal_data_topic_data_size -> specific_info_size;
                    $personal_data_section_data_sizes = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personal_data_topic_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personal_data_section_data_sizes as $personal_data_section_data_size)
                    {
                        $size += $personal_data_section_data_size -> specific_info_size;
                        $personal_data_file_data_sizes = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personal_data_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                
                        foreach($personal_data_file_data_sizes as $personal_data_file_data_size)
                        {
                            $size += $personal_data_file_data_size -> file_size;
                        }
                
                        $personal_data_note_data_sizes = DB::table('personal_data_t_s_notes')->where('personal_data_t_s_id', '=', $personal_data_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($personal_data_note_data_sizes as $personal_data_note_data_size)
                        {
                            $size += $personal_data_note_data_size -> specific_info_size;
                        }
                                
                        $personal_data_galery_data_sizes = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personal_data_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($personal_data_galery_data_sizes as $personal_data_galery_data_size)
                        {
                            //$size += $personal_data_galery_data_size -> specific_info_size;
                            $personal_data_image_data_sizes = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personal_data_galery_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($personal_data_image_data_sizes as $personal_data_image_data_size)
                            {
                                $size += $personal_data_image_data_size -> file_size;
                            }
                        }
                                
                        $personal_data_playlist_data_sizes = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personal_data_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($personal_data_playlist_data_sizes as $personal_data_playlist_data_size)
                        {
                            //$size += $personal_data_playlist_data_size -> specific_info_size;
                            $personal_data_audio_data_sizes = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personal_data_playlist_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($personal_data_audio_data_sizes as $personal_data_audio_data_size)
                            {
                                $size += $personal_data_audio_data_size -> file_size;
                            }
                        }
                                
                        $personal_data_tool_data_sizes = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personal_data_section_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                        foreach($personal_data_tool_data_sizes as $personal_data_tool_data_size)
                        {
                            //$size += $personal_data_tool_data_size -> specific_info_size;
                            $personal_data_tool_file_data_sizes = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personal_data_tool_data_size -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                            foreach($personal_data_tool_file_data_sizes as $personal_data_tool_file_data_size)
                            {
                                $size += $personal_data_tool_file_data_size -> file_size;
                            }
                        }
                    }
                }
            }
            
            if(($user_id == $user[0] -> id || $isShared) && $size <= 1073741824)
            {
                $newProjectTSFile = $this->projectTSFileRepository->update($request->all(), $id);
                
                DB::table('project_t_s_files')->where('id', $id)->update(['updates_quantity' => DB::raw('updates_quantity + 1')]);
                DB::table('project_t_s_file_updates')->insert(['actual_name' => $newProjectTSFile -> name, 'past_name' => $projectTSFile -> name, 'datetime' => $now, 'project_t_s_file_id' => $id, 'user_id' => $user_id]);
                DB::table('recent_activities')->insert(['name' => $projectTSFile -> name, 'status' => 'active', 'type' => 'p_t_s_f_u', 'user_id' => $user_id, 'entity_id' => $projectTSFile -> id, 'created_at' => $now]);
            
                Flash::success('Project T S File updated successfully.');
                return redirect(route('projectTSFiles.show', [$id]));
            }
            
            else
            {
                if($size > 1073741824)
                {
                    Flash::error('Your storage space is exhausted, you can get more space at only 15 dollars per month.');
                    return redirect(route('colleges.index'));
                }
                
                return view('deniedAccess');
            }
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function destroy($id)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $projectTSFile = $this->projectTSFileRepository->findWithoutFail($id);
    
            if(empty($projectTSFile))
            {
                Flash::error('Project T S File not found');
                return redirect(route('projectTSFiles.index'));
            }
            
            $user = DB::table('project_t_s_files')->join('project_topic_sections', 'project_t_s_files.project_topic_section_id', '=', 'project_topic_sections.id')->join('project_topics', 'project_topic_sections.project_topic_id', '=', 'project_topics.id')->join('projects', 'project_topics.project_id', '=', 'projects.id')->join('users', 'users.id', '=', 'projects.user_id')->where('project_t_s_files.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id)
            {
                DB::table('user_project_t_s_files')->where('project_t_s_file_id', $projectTSFile -> id)->update(['deleted_at' => $now]);
                
                $userProjectTSFile = DB::table('user_project_t_s_files')->where('project_t_s_file_id', '=', $projectTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                
                if($userProjectTSFile == null)
                {
                    DB::table('user_project_t_s_file_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'user_p_t_s_f_id' => $userProjectTSFile[0] -> id]);
                }
                
                $this->projectTSFileRepository->delete($id);
                
                DB::table('project_t_s_file_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'project_t_s_file_id' => $projectTSFile -> id]);
                DB::table('recent_activities')->insert(['name' => $projectTSFile -> name, 'status' => 'active', 'type' => 'p_t_s_f_d', 'user_id' => $user_id, 'entity_id' => $projectTSFile -> id, 'created_at' => $now]);
            
                Flash::success('Project T S File deleted successfully.');
                return redirect(route('projectTopicSections.show', [$projectTSFile -> project_topic_section_id]));
            }
            
            else
            {
                return view('deniedAccess');
            }
        }
        
        else
        {
            return view('deniedAccess');
        }
    }
}