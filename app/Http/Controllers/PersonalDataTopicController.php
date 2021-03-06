<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreatePersonalDataTopicRequest;
use App\Http\Requests\UpdatePersonalDataTopicRequest;
use App\Repositories\PersonalDataTopicRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;
use App\Models\PersonalDataTopic;
use App\Models\PersonalDataTopicSection;
use App\Models\PersonalDataTopicUpdate;
use Illuminate\Support\Carbon;

class PersonalDataTopicController extends AppBaseController
{
    private $personalDataTopicRepository;

    public function __construct(PersonalDataTopicRepository $personalDataTopicRepo)
    {
        $this->personalDataTopicRepository = $personalDataTopicRepo;
    }

    public function index(Request $request)
    {
        if(Auth::user() != null && Auth::user()->email == 'josemsoberonpenaloza@gmail.com')
        {
            $this->personalDataTopicRepository->pushCriteria(new RequestCriteria($request));
            $personalDataTopics = $this->personalDataTopicRepository->all();
    
            return view('personal_data_topics.index')
                ->with('personalDataTopics', $personalDataTopics);
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
            $personalDataTopicsList = PersonalDataTopic::where('personal_data_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();

            return view('personal_data_topics.create')
                ->with('id', $id)
                ->with('personalDataTopicsList', $personalDataTopicsList);
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function store(CreatePersonalDataTopicRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $input = $request->all();
            $personalDataTopic = $this->personalDataTopicRepository->create($input);
    
            DB::table('personal_data_topic_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_topic_id' => $personalDataTopic -> id]);
            DB::table('recent_activities')->insert(['name' => $personalDataTopic -> name, 'status' => 'active', 'type' => 'p_d_t_c', 'user_id' => $user_id, 'entity_id' => $personalDataTopic -> id, 'created_at' => $now]);
    
            Flash::success('PersonalData Topic saved successfully.');
            return redirect(route('personalDatas.show', [$personalDataTopic -> personal_data_id]));
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function show($id, Request $request)
    {
        if(Auth::user() != null)
        {
            $personal_data_section_p = $request -> personal_data_section_p;
            
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $personalDataTopic = $this->personalDataTopicRepository->findWithoutFail($id);
            
            if(empty($personalDataTopic))
            {
                Flash::error('PersonalData Topic not found');
                return redirect(route('personalDataTopics.index'));
            }
            
            $userPersonalDataTopics = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userPersonalDataTopics as $userPersonalDataTopic)
            {
                if($userPersonalDataTopic -> user_id == $user_id)
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('personal_data_topics')->join('personal_datas', 'personal_data_topics.personal_data_id', '=', 'personal_datas.id')->join('users', 'users.id', '=', 'personal_datas.user_id')->where('personal_data_topics.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id || $isShared)
            {
                DB::table('personal_data_topic_views')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_topic_id' => $id]);
                DB::table('personal_data_topics')->where('id', $id)->update(['views_quantity' => DB::raw('views_quantity + 1')]);
    
                $personalDataTopic = $this->personalDataTopicRepository->findWithoutFail($id);
                $personalDataTopicSections = PersonalDataTopicSection::where('personal_data_topic_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->paginate(50, ['*'], 'personal_data_section_p');
                $personalDataTopicSectionCount = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', $id)->where('personal_data_topic_id' , '=', $id)->where(function ($query) {$query->where('personal_data_topic_sections.deleted_at', '=', null);})->count();
                $personalDataTopicSectionList = DB::table('personal_data_topics')->join('personal_data_topic_sections', 'personal_data_topics.id', '=', 'personal_data_topic_sections.personal_data_topic_id')->where('personal_data_topic_id' , '=', $id)->where(function ($query) {$query->where('personal_data_topic_sections.deleted_at', '=', null);})->orderBy('personal_data_topic_sections.views_quantity', 'desc')->limit(5)->get();
                $personalDataTopicViews = DB::table('users')->join('personal_data_topic_views', 'users.id', '=', 'personal_data_topic_views.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();
                $personalDataTopicUpdates = DB::table('users')->join('personal_data_topic_updates', 'users.id', '=', 'personal_data_topic_updates.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();
                $personalDataTopicTodolist = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'active');})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(50)->get();
                $personalDataTopicTodolistCompleted = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'finalized');})->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(50)->get();

                $personalDataTopicSectionsList = PersonalDataTopicSection::where('personal_data_topic_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
                $userPersonalDataTopicsList = DB::table('user_personal_data_topics')->join('users', 'user_personal_data_topics.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_data_topics.description', 'permissions', 'user_personal_data_topics.datetime', 'user_personal_data_topics.id', 'personal_data_topic_id')->where('personal_data_topic_id', $id)->where(function ($query) {$query->where('user_personal_data_topics.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
                $personalDataTopicViewsList = DB::table('users')->join('personal_data_topic_views', 'users.id', '=', 'personal_data_topic_views.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $personalDataTopicUpdatesList = DB::table('users')->join('personal_data_topic_updates', 'users.id', '=', 'personal_data_topic_updates.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $personalDataTopicTodolistsList = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'active');})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(5)->get();
                $personalDataTopicTodolistsCompletedList = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'finalized');})->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(5)->get();
    
                return view('personal_data_topics.show')
                    ->with('personalDataTopic', $personalDataTopic)
                    ->with('personalDataTopicSections', $personalDataTopicSections)
                    ->with('personalDataTopicSectionCount', $personalDataTopicSectionCount)
                    ->with('personalDataTopicSectionList', $personalDataTopicSectionList)
                    ->with('personalDataTopicViews', $personalDataTopicViews)
                    ->with('personalDataTopicUpdates', $personalDataTopicUpdates)
                    ->with('personalDataTopicTodolist', $personalDataTopicTodolist)
                    ->with('personalDataTopicTodolistCompleted', $personalDataTopicTodolistCompleted)
                    ->with('personal_data_section_p', $personal_data_section_p)
                    ->with('user_id', $user_id)
                    ->with('user', $user)
                    ->with('now', $now)
                    ->with('id', $id)
                    ->with('personalDataTopicSectionsList', $personalDataTopicSectionsList)
                    ->with('userPersonalDataTopicsList', $userPersonalDataTopicsList)
                    ->with('personalDataTopicViewsList', $personalDataTopicViewsList)
                    ->with('personalDataTopicUpdatesList', $personalDataTopicUpdatesList)
                    ->with('personalDataTopicTodolistsList', $personalDataTopicTodolistsList)
                    ->with('personalDataTopicTodolistsCompletedList', $personalDataTopicTodolistsCompletedList);
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
            $personalDataTopic = $this->personalDataTopicRepository->findWithoutFail($id);
    
            if(empty($personalDataTopic))
            {
                Flash::error('PersonalData Topic not found');
                return redirect(route('personalDataTopics.index'));
            }
            
            $userPersonalDataTopics = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userPersonalDataTopics as $userPersonalDataTopic)
            {
                if($userPersonalDataTopic -> user_id == $user_id && $userPersonalDataTopic -> permissions == 'advanced')
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('personal_data_topics')->join('personal_datas', 'personal_data_topics.personal_data_id', '=', 'personal_datas.id')->join('users', 'users.id', '=', 'personal_datas.user_id')->where('personal_data_topics.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id || $isShared)
            {
                $personalDataTopicSectionsList = PersonalDataTopicSection::where('personal_data_topic_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
                $personalDataTopicViewsList = DB::table('users')->join('personal_data_topic_views', 'users.id', '=', 'personal_data_topic_views.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $personalDataTopicUpdatesList = DB::table('users')->join('personal_data_topic_updates', 'users.id', '=', 'personal_data_topic_updates.user_id')->where('personal_data_topic_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $userPersonalDataTopicsList = DB::table('user_personal_data_topics')->join('users', 'user_personal_data_topics.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_data_topics.description', 'permissions', 'user_personal_data_topics.datetime', 'user_personal_data_topics.id', 'personal_data_topic_id')->where('personal_data_topic_id', $id)->where(function ($query) {$query->where('user_personal_data_topics.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
                $personalDataTopicTodolistsList = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'active');})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(5)->get();
                $personalDataTopicTodolistsCompletedList = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_todolists', 'personal_data_topics.id', '=', 'personal_data_topic_todolists.personal_data_topic_id')->where('personal_data_topic_todolists.personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('personal_data_topic_todolists.status', '=', 'finalized');})->where(function ($query) {$query->where('personal_data_topic_todolists.deleted_at', '=', null);})->orderBy('personal_data_topic_todolists.datetime', 'desc')->limit(5)->get();

                return view('personal_data_topics.edit')
                    ->with('personalDataTopic', $personalDataTopic)
                    ->with('personalDataTopicSectionsList', $personalDataTopicSectionsList)
                    ->with('personalDataTopicViewsList', $personalDataTopicViewsList)
                    ->with('personalDataTopicUpdatesList', $personalDataTopicUpdatesList)
                    ->with('userPersonalDataTopicsList', $userPersonalDataTopicsList)
                    ->with('personalDataTopicTodolistsList', $personalDataTopicTodolistsList)
                    ->with('personalDataTopicTodolistsCompletedList', $personalDataTopicTodolistsCompletedList);
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

    public function update($id, UpdatePersonalDataTopicRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $personalDataTopic = $this->personalDataTopicRepository->findWithoutFail($id);
    
            if(empty($personalDataTopic))
            {
                Flash::error('PersonalData Topic not found');
                return redirect(route('personalDataTopics.index'));
            }
    
            $userPersonalDataTopics = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $id)->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userPersonalDataTopics as $userPersonalDataTopic)
            {
                if($userPersonalDataTopic -> user_id == $user_id && $userPersonalDataTopic -> permissions == 'advanced')
                {
                    $isShared = true;
                    break;
                }
            }
            
            $user = DB::table('personal_data_topics')->join('personal_datas', 'personal_data_topics.personal_data_id', '=', 'personal_datas.id')->join('users', 'users.id', '=', 'personal_datas.user_id')->where('personal_data_topics.id', '=', $id)->get();
            
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
                $newPersonalDataTopic = $this->personalDataTopicRepository->update($request->all(), $id);
                $specific_info_size = strlen($request -> specific_info);
                
                DB::table('personal_data_topics')->where('id', $id)->update(['updates_quantity' => DB::raw('updates_quantity + 1'), 'specific_info_size' => $specific_info_size]);
                DB::table('personal_data_topic_updates')->insert(['actual_name' => $newPersonalDataTopic -> name, 'past_name' => $personalDataTopic -> name, 'datetime' => $now, 'personal_data_topic_id' => $id, 'user_id' => $user_id]);
                DB::table('recent_activities')->insert(['name' => $personalDataTopic -> name, 'status' => 'active', 'type' => 'p_d_t_u', 'user_id' => $user_id, 'entity_id' => $personalDataTopic -> id, 'created_at' => $now]);
                
                Flash::success('PersonalData Topic updated successfully.');
                return redirect(route('personalDataTopics.show', [$id]));
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
            $personalDataTopic = $this->personalDataTopicRepository->findWithoutFail($id);
            
            if (empty($personalDataTopic))
            {
                Flash::error('PersonalData Topic not found');
                return redirect(route('personalDataTopics.index'));
            }
            
            $user = DB::table('personal_data_topics')->join('personal_datas', 'personal_data_topics.personal_data_id', '=', 'personal_datas.id')->join('users', 'users.id', '=', 'personal_datas.user_id')->where('personal_data_topics.id', '=', $id)->get();
            
            if($user_id == $user[0] -> id)
            {
                $personalDataTopicSections = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                    
                DB::table('user_personal_data_topics')->where('personal_data_topic_id', $personalDataTopic -> id)->update(['deleted_at' => $now]);
                
                $userPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                
                if($userPersonalDataTopic == null)
                {
                    DB::table('user_personal_data_topic_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_id' => $userPersonalDataTopic[0] -> id]);
                }
                
                foreach($personalDataTopicSections as $personalDataTopicSection)
                {
                    $personalDataTSFiles = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                    DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', $personalDataTopicSection -> id)->update(['deleted_at' => $now]);
                    
                    $userPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                    
                    if($userPersonalDataTopicSection == null)
                    {
                        DB::table('user_personal_data_topic_section_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_id' => $userPersonalDataTopicSection[0] -> id]);
                    }
                        
                    foreach($personalDataTSFiles as $personalDataTSFile)
                    {
                        DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', $personalDataTSFile -> id)->update(['deleted_at' => $now]);
                        
                        $userPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        
                        if($userPersonalDataTSFile == null)
                        {
                            DB::table('user_personal_data_t_s_file_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_f_id' => $userPersonalDataTSFile[0] -> id]);
                        }
                    }
        
                    $personalDataTSNotes = DB::table('personal_data_t_s_notes')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSNotes as $personalDataTSNote)
                    {
                        DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', $personalDataTSNote -> id)->update(['deleted_at' => $now]);
                        
                        $userPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        
                        if($userPersonalDataTSNote == null)
                        {
                            DB::table('user_personal_data_t_s_note_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_n_id' => $userPersonalDataTSNote[0] -> id]);
                        }
                    }
                        
                    $personalDataTSGaleries = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSGaleries as $personalDataTSGalery)
                    {
                        $personalDataTSGaleryImages = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                        DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', $personalDataTSGalery -> id)->update(['deleted_at' => $now]);
                        
                        $userPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        
                        if($userPersonalDataTSGalery == null)
                        {
                            DB::table('user_personal_data_t_s_galerie_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_id' => $userPersonalDataTSGalery[0] -> id]);
                        }
                        
                        foreach($personalDataTSGaleryImages as $personalDataTSGaleryImage)
                        {
                            DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', $personalDataTSGaleryImage -> id)->update(['deleted_at' => $now]);
                            
                            $userPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if($userPersonalDataTSGaleryImage == null)
                            {
                                DB::table('user_personal_data_t_s_galery_image_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_i_id' => $userPersonalDataTSGaleryImage[0] -> id]);
                            }
                        }
                    }
                        
                    $personalDataTSPlaylists = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSPlaylists as $personalDataTSPlaylist)
                    {
                        $personalDataTSPlaylistAudios = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                            
                        DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', $personalDataTSPlaylist -> id)->update(['deleted_at' => $now]);
                        
                        $userPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        
                        if($userPersonalDataTSPlaylist == null)
                        {
                            DB::table('u_p_d_t_s_playlist_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_id' => $userPersonalDataTSPlaylist[0] -> id]);
                        }
                        
                        foreach($personalDataTSPlaylistAudios as $personalDataTSPlaylistAudio)
                        {
                            DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', $personalDataTSPlaylistAudio -> id)->update(['deleted_at' => $now]);
                            
                            $userPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if($userPersonalDataTSPlaylistAudio == null)
                            {
                                DB::table('u_p_d_t_s_p_audio_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_a_id' => $userPersonalDataTSPlaylistAudio[0] -> id]);
                            }
                        }
                    }
                        
                    $personalDataTSTools = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSTools as $personalDataTSTool)
                    {
                        $personalDataTSToolFiles = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personalDataTSTool -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                        DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', $personalDataTSTool -> id)->update(['deleted_at' => $now]);
                        
                        $userPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        
                        if($userPersonalDataTSTool == null)
                        {
                            DB::table('user_personal_data_t_s_tool_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_id' => $userPersonalDataTSTool[0] -> id]);
                        }
                        
                        foreach($personalDataTSToolFiles as $personalDataTSToolFile)
                        {
                            DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', $personalDataTSToolFile -> id)->update(['deleted_at' => $now]);
                            
                            $userPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', '=', $personalDataTSToolFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if($userPersonalDataTSToolFile == null)
                            {
                                DB::table('user_personal_data_t_s_tool_file_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_f_id' => $userPersonalDataTSToolFile[0] -> id]);
                            }
                        }
                    }
                }
                
                $this->personalDataTopicRepository->delete($id);
                $personalDataTopicSections = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                    
                foreach($personalDataTopicSections as $personalDataTopicSection)
                {
                    $personalDataTSFiles = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                    DB::table('personal_data_topic_sections')->where('id', $personalDataTopicSection -> id)->update(['deleted_at' => $now]);
                    DB::table('personal_data_topic_section_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_t_s_id' => $personalDataTopicSection -> id]);
                        
                    foreach($personalDataTSFiles as $personalDataTSFile)
                    {
                        DB::table('personal_data_t_s_files')->where('id', $personalDataTSFile -> id)->update(['deleted_at' => $now]);
                        DB::table('personal_data_t_s_file_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_t_s_file_id' => $personalDataTSFile -> id]);
                    }
        
                    $personalDataTSNotes = DB::table('personal_data_t_s_notes')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSNotes as $personalDataTSNote)
                    {
                        DB::table('personal_data_t_s_notes')->where('id', $personalDataTSNote -> id)->update(['deleted_at' => $now]);
                        DB::table('personal_data_t_s_note_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_t_s_note_id' => $personalDataTSNote -> id]);
                    }
                        
                    $personalDataTSGaleries = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSGaleries as $personalDataTSGalery)
                    {
                        $personalDataTSGaleryImages = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                        DB::table('personal_data_t_s_galeries')->where('id', $personalDataTSGalery -> id)->update(['deleted_at' => $now]);
                        DB::table('personal_data_t_s_galery_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_d_t_s_g_id' => $personalDataTSGalery -> id]);
        
                        foreach($personalDataTSGaleryImages as $personalDataTSGaleryImage)
                        {
                            DB::table('personal_data_t_s_galery_images')->where('id', $personalDataTSGaleryImage -> id)->update(['deleted_at' => $now]);
                            DB::table('personal_data_t_s_galery_image_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'p_d_t_s_g_i_id' => $personalDataTSGaleryImage -> id]);
                        }
                    }
                        
                    $personalDataTSPlaylists = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSPlaylists as $personalDataTSPlaylist)
                    {
                        $personalDataTSPlaylistAudios = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                            
                        DB::table('personal_data_t_s_playlists')->where('id', $personalDataTSPlaylist -> id)->update(['deleted_at' => $now]);
                        DB::table('personal_data_t_s_p_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'p_d_t_s_p_id' => $personalDataTSPlaylist -> id]);
                            
                        foreach($personalDataTSPlaylistAudios as $personalDataTSPlaylistAudio)
                        {
                            DB::table('personal_data_t_s_p_audios')->where('id', $personalDataTSPlaylistAudio -> id)->update(['deleted_at' => $now]);
                            DB::table('p_d_t_s_p_audio_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'p_d_t_s_p_a_id' => $personalDataTSPlaylistAudio -> id]);
                        }
                    }
                        
                    $personalDataTSTools = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                    foreach($personalDataTSTools as $personalDataTSTool)
                    {
                        $personalDataTSToolFiles = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personalDataTSTool -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
                        DB::table('personal_data_t_s_tools')->where('id', $personalDataTSTool -> id)->update(['deleted_at' => $now]);
                        DB::table('personal_data_t_s_tool_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_t_s_tool_id' => $personalDataTSTool -> id]);
                        
                        foreach($personalDataTSToolFiles as $personalDataTSToolFile)
                        {
                            DB::table('personal_data_t_s_tool_files')->where('id', $personalDataTSToolFile -> id)->update(['deleted_at' => $now]);
                            DB::table('personal_data_t_s_tool_file_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'p_d_t_s_t_f_id' => $personalDataTSToolFile -> id]);
                        }
                    }
                }
                
                DB::table('personal_data_topic_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'personal_data_topic_id' => $personalDataTopic -> id]);
                DB::table('recent_activities')->insert(['name' => $personalDataTopic -> name, 'status' => 'active', 'type' => 'p_d_t_d', 'user_id' => $user_id, 'entity_id' => $personalDataTopic -> id, 'created_at' => $now]);
            
                Flash::success('PersonalData Topic deleted successfully.');
                return redirect(route('personalDatas.show', [$personalDataTopic -> personal_data_id]));
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