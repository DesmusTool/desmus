<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateUserPersonalDataRequest;
use App\Http\Requests\UpdateUserPersonalDataRequest;
use App\Repositories\UserPersonalDataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;
use Illuminate\Support\Carbon;
use App\Models\PersonalDataTopic;

class UserPersonalDataController extends AppBaseController
{
    private $userPersonalDataRepository;

    public function __construct(UserPersonalDataRepository $userPersonalDataRepo)
    {
        $this->userPersonalDataRepository = $userPersonalDataRepo;
    }

    public function index(Request $request)
    {
        if(Auth::user() != null && Auth::user()->email == 'josemsoberonpenaloza@gmail.com')
        {
            $this->userPersonalDataRepository->pushCriteria(new RequestCriteria($request));
            $userPersonalDatas = $this->userPersonalDataRepository->all();
    
            return view('user_personal_datas.index')
                ->with('userPersonalDatas', $userPersonalDatas);
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
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $users = DB::table('contacts')->join('users', 'users.id', '=', 'contacts.contact_id')->select('name', 'contacts.user_id', 'users.id')->where('contacts.user_id', '=', $user_id)->where(function ($query) {$query->where('contacts.deleted_at', '=', null);})->orderBy('name', 'asc')->get();
            $select = [];
                
            foreach($users as $user)
            {
                $select[$user->id] = $user->name;
            }
            
            $personalDataTopicsList = PersonalDataTopic::where('personal_data_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
            $userPersonalDatasList = DB::table('user_personal_datas')->join('users', 'user_personal_datas.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_datas.description', 'permissions', 'user_personal_datas.datetime', 'user_personal_datas.id', 'personal_data_id', 'users.id as user_id')->where('personal_data_id', $id)->where(function ($query) {$query->where('user_personal_datas.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
            $personalDataViewsList = DB::table('users')->join('personal_data_views', 'users.id', '=', 'personal_data_views.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
            $personalDataUpdatesList = DB::table('users')->join('personal_data_updates', 'users.id', '=', 'personal_data_updates.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();

            return view('user_personal_datas.create', compact('select'))
                ->with('id', $id)
                ->with('now', $now)
                ->with('personalDataTopicsList', $personalDataTopicsList)
                ->with('personalDataViewsList', $personalDataViewsList)
                ->with('personalDataUpdatesList', $personalDataUpdatesList)
                ->with('userPersonalDatasList', $userPersonalDatasList);
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function store(CreateUserPersonalDataRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $input = $request->all();
            $user = DB::table('personal_datas')->where('id', '=', $request -> personal_data_id)->get();
    
            $userPersonalDataCheck = DB::table('user_personal_datas')->where('user_id', '=', $request -> user_id)->where('personal_data_id', '=', $request -> personal_data_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
    
            if($userPersonalDataCheck->isEmpty())
            {
                if($user[0] -> user_id == $user_id)
                {
                    $userPersonalData = $this->userPersonalDataRepository->create($input);
                    $personalDataTopics = DB::table('personal_data_topics')->where('personal_data_id', '=', $userPersonalData -> personal_data_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                    DB::table('user_personal_data_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_id' => $userPersonalData -> id]);
        
                    foreach($personalDataTopics as $personalDataTopic)
                    {
                        $sharedPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                        if($sharedPersonalDataTopic->isEmpty())
                        {
                            DB::table('user_personal_data_topics')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_topic_id' => $personalDataTopic -> id]);
                            
                            $userPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if(isset($userPersonalDataTopic[0]))
                            {
                                DB::table('user_personal_data_topic_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_id' => $userPersonalDataTopic[0] -> id]);
                
                                $personalDataTopicSections = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                            
                                foreach($personalDataTopicSections as $personalDataTopicSection)
                                {
                                    $sharedPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                    if($sharedPersonalDataTopicSection->isEmpty())
                                    {
                                        DB::table('user_personal_data_topic_sections')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_t_s_id' => $personalDataTopicSection -> id]);
                                        
                                        $userPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        
                                        if(isset($userPersonalDataTopicSection[0]))
                                        {
                                            DB::table('user_personal_data_topic_section_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_id' => $userPersonalDataTopicSection[0] -> id]);
                                            
                                            $personalDataTSFiles = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                            
                                            foreach($personalDataTSFiles as $personalDataTSFile)
                                            {
                                                $sharedPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                if($sharedPersonalDataTSFile->isEmpty())
                                                {
                                                    DB::table('user_personal_data_t_s_files')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_t_s_file_id' => $personalDataTSFile -> id]);
                                                    
                                                    $userPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                    
                                                    if(isset($userPersonalDataTSFile[0]))
                                                    {
                                                        DB::table('user_personal_data_t_s_file_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_f_id' => $userPersonalDataTSFile[0] -> id]);
                                                    }
                                                }
                                            }
                            
                                            $personalDataTSNotes = DB::table('personal_data_t_s_notes')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                            
                                            foreach($personalDataTSNotes as $personalDataTSNote)
                                            {
                                                $sharedPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                if($sharedPersonalDataTSNote->isEmpty())
                                                {
                                                    DB::table('user_personal_data_t_s_notes')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_t_s_note_id' => $personalDataTSNote -> id]);
                                                    
                                                    $userPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                    
                                                    if(isset($userPersonalDataTSNote[0]))
                                                    {
                                                        DB::table('user_personal_data_t_s_note_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_n_id' => $userPersonalDataTSNote[0] -> id]);
                                                    }
                                                }
                                            }
                                            
                                            $personalDataTSGaleries = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                            
                                            foreach($personalDataTSGaleries as $personalDataTSGalery)
                                            {
                                                $sharedPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                if($sharedPersonalDataTSGalery->isEmpty())
                                                {
                                                    DB::table('user_personal_data_t_s_galeries')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_t_s_g_id' => $personalDataTSGalery -> id]);
                                                    
                                                    $userPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                    
                                                    if(isset($userPersonalDataTSGalery[0]))
                                                    {
                                                        DB::table('user_personal_data_t_s_galerie_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_id' => $userPersonalDataTSGalery[0] -> id]);
                                
                                                        $personalDataTSGaleryImages = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                                        
                                                        foreach($personalDataTSGaleryImages as $personalDataTSGaleryImage)
                                                        {
                                                            $sharedPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                            if($sharedPersonalDataTSGaleryImage->isEmpty())
                                                            {
                                                                DB::table('user_personal_data_t_s_galery_images')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'p_d_t_s_g_i_id' => $personalDataTSGaleryImage -> id]);
                                                                
                                                                $userPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                                
                                                                if(isset($userPersonalDataTSGaleryImage[0]))
                                                                {
                                                                    DB::table('user_personal_data_t_s_galery_image_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_i_id' => $userPersonalDataTSGaleryImage[0] -> id]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $personalDataTSPlaylists = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                            
                                            foreach($personalDataTSPlaylists as $personalDataTSPlaylist)
                                            {
                                                $sharedPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                if($sharedPersonalDataTSPlaylist->isEmpty())
                                                {
                                                    DB::table('user_personal_data_t_s_p')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'p_d_t_s_p_id' => $personalDataTSPlaylist -> id]);
                                                    
                                                    $userPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                    
                                                    if(isset($userPersonalDataTSPlaylist[0]))
                                                    {
                                                        DB::table('u_p_d_t_s_playlist_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_id' => $userPersonalDataTSPlaylist[0] -> id]);
                                                    
                                                        $personalDataTSPlaylistAudios = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                                        
                                                        foreach($personalDataTSPlaylistAudios as $personalDataTSPlaylistAudio)
                                                        {
                                                            $sharedPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                            if($sharedPersonalDataTSPlaylistAudio->isEmpty())
                                                            {
                                                                DB::table('user_p_d_t_s_p_audios')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'p_d_t_s_p_a_id' => $personalDataTSPlaylistAudio -> id]);
                                                                
                                                                $userPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                                
                                                                if(isset($userPersonalDataTSPlaylistAudio[0]))
                                                                {
                                                                    DB::table('u_p_d_t_s_p_audio_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_a_id' => $userPersonalDataTSPlaylistAudio[0] -> id]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $personalDataTSTools = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                            
                                            foreach($personalDataTSTools as $personalDataTSTool)
                                            {
                                                $sharedPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                if($sharedPersonalDataTSTool->isEmpty())
                                                {
                                                    DB::table('user_personal_data_t_s_tools')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_data_t_s_tool_id' => $personalDataTSTool -> id]);
                                                    
                                                    $userPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                    
                                                    if(isset($userPersonalDataTSTool[0]))
                                                    {
                                                        DB::table('user_personal_data_t_s_tool_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_id' => $userPersonalDataTSTool[0] -> id]);
                                                        
                                                        $personalDataTSToolFiles = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personalDataTSTool -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                                        
                                                        foreach($personalDataTSToolFiles as $personalDataTSToolFile)
                                                        {
                                                            $sharedPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', '=', $personalDataTSToolFile -> id)->where('user_id', '=', $input["user_id"])->where(function ($query) {$query->where('deleted_at', '=', null);})->get();

                                                            if($sharedPersonalDataTSToolFile->isEmpty())
                                                            {
                                                                DB::table('user_personal_data_t_s_tool_files')->insert(['datetime' => $now, 'user_id' => $userPersonalData -> user_id, 'description' => $userPersonalData -> description, 'personal_d_t_s_t_f_id' => $personalDataTSToolFile -> id]);
                                                                
                                                                $userPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('id', '=', $personalDataTSToolFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                                
                                                                if(isset($userPersonalDataTSToolFile[0]))
                                                                {
                                                                    DB::table('user_personal_data_t_s_tool_file_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_f_id' => $userPersonalDataTSToolFile[0] -> id]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $user = DB::table('user_personal_datas')->join('users', 'users.id', '=', 'user_personal_datas.user_id')->where('user_personal_datas.id', '=', $userPersonalData -> id)->select('name')->get();
                    
                    DB::table('recent_activities')->insert(['name' => $user[0] -> name, 'status' => 'active', 'type' => 'u_p_d_c', 'user_id' => $user_id, 'entity_id' => $userPersonalData -> personal_data_id, 'created_at' => $now]);
                
                    Flash::success('User PersonalData saved successfully.');
                    return redirect(route('userPersonalDatas.show', [$userPersonalData -> personal_data_id]));
                }
                
                else
                {
                    return view('deniedAccess');
                }
            }
            
            return redirect(route('userPersonalDatas.show', [$request -> personal_data_id]));
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
            $user_id = Auth::user()->id;
            $userPersonalData = $this->userPersonalDataRepository->findWithoutFail($id);
            $userPersonalDatas = DB::table('user_personal_datas')->join('users', 'user_personal_datas.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_datas.description', 'permissions', 'user_personal_datas.datetime', 'user_personal_datas.id', 'personal_data_id', 'users.id as user_id')->where('personal_data_id', $id)->where(function ($query) {$query->where('user_personal_datas.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
            
            if(empty($userPersonalDatas[0]))
            {
                Flash::error('User PersonalData not found');
                return redirect(route('userPersonalDatas.create', [$id]));
            }
            
            $user = DB::table('personal_datas')->where('id', '=', $id)->get();
            
            if($user[0] -> user_id == $user_id)
            {
                $personalData = DB::table('personal_datas')->where('id', '=', $userPersonalDatas[0] -> personal_data_id)->get();
        
                $personalDataTopicsList = PersonalDataTopic::where('personal_data_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
                $userPersonalDatasList = DB::table('user_personal_datas')->join('users', 'user_personal_datas.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_datas.description', 'permissions', 'user_personal_datas.datetime', 'user_personal_datas.id', 'personal_data_id', 'users.id as user_id')->where('personal_data_id', $id)->where(function ($query) {$query->where('user_personal_datas.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
                $personalDataViewsList = DB::table('users')->join('personal_data_views', 'users.id', '=', 'personal_data_views.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $personalDataUpdatesList = DB::table('users')->join('personal_data_updates', 'users.id', '=', 'personal_data_updates.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
        
                return view('user_personal_datas.show')
                    ->with('userPersonalDatas', $userPersonalDatas)
                    ->with('id', $id)
                    ->with('personalData', $personalData)
                    ->with('personalDataTopicsList', $personalDataTopicsList)
                    ->with('personalDataViewsList', $personalDataViewsList)
                    ->with('personalDataUpdatesList', $personalDataUpdatesList)
                    ->with('userPersonalDatasList', $userPersonalDatasList);
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
            $userPersonalData = DB::table('users')->join('user_personal_datas', 'user_personal_datas.user_id', '=', 'users.id')->where('user_personal_datas.id', $id)->where(function ($query) {$query->where('user_personal_datas.deleted_at', '=', null);})->get();
    
            if(empty($userPersonalData[0]))
            {
                Flash::error('User PersonalData not found');
                return redirect(route('userPersonalDatas.index'));
            }
            
            $user = DB::table('personal_datas')->where('id', '=', $userPersonalData[0] -> personal_data_id)->get();
            
            $personalDataTopicsList = PersonalDataTopic::where('personal_data_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
            $userPersonalDatasList = DB::table('user_personal_datas')->join('users', 'user_personal_datas.user_id', '=', 'users.id')->select('name', 'email', 'user_personal_datas.description', 'permissions', 'user_personal_datas.datetime', 'user_personal_datas.id', 'personal_data_id', 'users.id as user_id')->where('personal_data_id', $id)->where(function ($query) {$query->where('user_personal_datas.deleted_at', '=', null);})->orderBy('datetime', 'desc')->get();
            $personalDataViewsList = DB::table('users')->join('personal_data_views', 'users.id', '=', 'personal_data_views.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
            $personalDataUpdatesList = DB::table('users')->join('personal_data_updates', 'users.id', '=', 'personal_data_updates.user_id')->where('personal_data_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
            
            if($user[0] -> user_id == $user_id)
            {
                return view('user_personal_datas.edit')
                    ->with('userPersonalData', $userPersonalData)
                    ->with('id', $userPersonalData[0] -> personal_data_id)
                    ->with('personalDataTopicsList', $personalDataTopicsList)
                    ->with('personalDataViewsList', $personalDataViewsList)
                    ->with('personalDataUpdatesList', $personalDataUpdatesList)
                    ->with('userPersonalDatasList', $userPersonalDatasList);
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

    public function update($id, UpdateUserPersonalDataRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $userPersonalData = $this->userPersonalDataRepository->findWithoutFail($id);
            $user_id = Auth::user()->id;
    
            if(empty($userPersonalData))
            {
                Flash::error('User PersonalData not found');
                return redirect(route('userPersonalDatas.index'));
            }
            
            $user = DB::table('personal_datas')->where('id', '=', $userPersonalData -> personal_data_id)->get();
    
            if($user[0] -> user_id == $user_id)
            {
                $user_id = $userPersonalData -> user_id;
                $userPersonalData = $this->userPersonalDataRepository->update($request->all(), $id);
                $personalDataTopics = DB::table('personal_data_topics')->where('personal_data_id', '=', $userPersonalData -> personal_data_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                DB::table('user_personal_data_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_id' => $userPersonalData -> id]);
        
                foreach($personalDataTopics as $personalDataTopic)
                {
                    $personalDataTopicSections = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                    
                    $userPersonalDataTopicInserts = DB::table('user_personal_data_topics')->where('personal_data_topic_id', $personalDataTopic -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                    if($userPersonalDataTopicInserts == 0)
                    {
                        DB::table('user_personal_data_topics')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_topic_id' => $personalDataTopic -> id]);
                        $userPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                        DB::table('user_personal_data_topic_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_id' => $userPersonalDataTopic[0] -> id]);
                    }
                    
                    $userPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                    
                    if(isset($userPersonalDataTopic[0]))
                    {
                        DB::table('user_personal_data_topic_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_id' => $userPersonalDataTopic[0] -> id]);
                                    
                        foreach($personalDataTopicSections as $personalDataTopicSection)
                        {
                            $personalDataTSFiles = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                        
                            $userPersonalDataTopicSectionInserts = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', $personalDataTopicSection -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                            if($userPersonalDataTopicSectionInserts == 0)
                            {
                                DB::table('user_personal_data_topic_sections')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_t_s_id' => $personalDataTopicSection -> id]);
                                $userPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                DB::table('user_personal_data_topic_section_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_id' => $userPersonalDataTopicSection[0] -> id]);
                            }
                            
                            $userPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if(isset($userPersonalDataTopicSection[0]))
                            {
                                DB::table('user_personal_data_topic_section_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_id' => $userPersonalDataTopicSection[0] -> id]);
                
                                foreach($personalDataTSFiles as $personalDataTSFile)
                                {
                                    $userPersonalDataTSFileInserts = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', $personalDataTSFile -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                    if($userPersonalDataTSFileInserts == 0)
                                    {
                                        DB::table('user_personal_data_t_s_files')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_t_s_file_id' => $personalDataTSFile -> id]);
                                        $userPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        DB::table('user_personal_data_t_s_file_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_f_id' => $userPersonalDataTSFile[0] -> id]);
                                    }
                                    
                                    $userPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSFile[0]))
                                    {
                                        DB::table('user_personal_data_t_s_file_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_f_id' => $userPersonalDataTSFile[0] -> id]);
                                    }
                                }
                
                                $personalDataTSNotes = DB::table('personal_data_t_s_notes')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSNotes as $personalDataTSNote)
                                {
                                    $userPersonalDataTSNoteInserts = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', $personalDataTSNote -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                    if($userPersonalDataTSNoteInserts == 0)
                                    {
                                        DB::table('user_personal_data_t_s_notes')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_t_s_note_id' => $personalDataTSNote -> id]);
                                        $userPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        DB::table('user_personal_data_t_s_note_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_n_id' => $userPersonalDataTSNote[0] -> id]);
                                    }
                                    
                                    $userPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSNote[0]))
                                    {
                                        DB::table('user_personal_data_t_s_note_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_n_id' => $userPersonalDataTSNote[0] -> id]);
                                    }
                                }
                                
                                $personalDataTSGaleries = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSGaleries as $personalDataTSGalery)
                                {
                                    $personalDataTSGaleryImages = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
                                    $userPersonalDataTSGaleryInserts = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', $personalDataTSGalery -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                    if($userPersonalDataTSGaleryInserts == 0)
                                    {
                                        DB::table('user_personal_data_t_s_galeries')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_t_s_g_id' => $personalDataTSGalery -> id]);
                                        $userPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        DB::table('user_personal_data_t_s_galerie_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_id' => $userPersonalDataTSGalery[0] -> id]);
                                    }
                                    
                                    $userPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSGalery[0]))
                                    {
                                        DB::table('user_personal_data_t_s_galerie_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_id' => $userPersonalDataTSGalery[0] -> id]);
                    
                                        foreach($personalDataTSGaleryImages as $personalDataTSGaleryImage)
                                        {
                                            $userPersonalDataTSGImageInserts = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', $personalDataTSGaleryImage -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                            if($userPersonalDataTSGImageInserts == 0)
                                            {
                                                DB::table('user_personal_data_t_s_galery_images')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'p_d_t_s_g_i_id' => $personalDataTSGaleryImage -> id]);
                                                $userPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                DB::table('user_personal_data_t_s_galery_image_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_i_id' => $userPersonalDataTSGaleryImage[0] -> id]);
                                            }
                                            
                                            $userPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                            
                                            if(isset($userPersonalDataTSGaleryImage[0]))
                                            {
                                                DB::table('user_personal_data_t_s_galery_image_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_i_id' => $userPersonalDataTSGaleryImage[0] -> id]);
                                            }
                                        }
                                    }
                                }
                                
                                $personalDataTSPlaylists = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSPlaylists as $personalDataTSPlaylist)
                                {
                                    $personalDataTSPlaylistAudios = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
                                    $userPersonalDataTSPlaylistInserts = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', $personalDataTSPlaylist -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                    if($userPersonalDataTSPlaylistInserts == 0)
                                    {
                                        DB::table('user_personal_data_t_s_p')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'p_d_t_s_p_id' => $personalDataTSPlaylist -> id]);
                                        $userPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        DB::table('u_p_d_t_s_playlist_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_id' => $userPersonalDataTSPlaylist[0] -> id]);
                                    }
                                    
                                    $userPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSPlaylist[0]))
                                    {
                                        DB::table('u_p_d_t_s_playlist_updates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_id' => $userPersonalDataTSPlaylist[0] -> id]);
                    
                                        foreach($personalDataTSPlaylistAudios as $personalDataTSPlaylistAudio)
                                        {
                                            $userPersonalDataTSPlaylistAudioInserts = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', $personalDataTSPlaylistAudio -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                            if($userPersonalDataTSPlaylistAudioInserts == 0)
                                            {
                                                DB::table('user_p_d_t_s_p_audios')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'p_d_t_s_p_a_id' => $personalDataTSPlaylistAudio -> id]);
                                                $userPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                DB::table('u_p_d_t_s_p_audio_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_a_id' => $userPersonalDataTSPlaylistAudio[0] -> id]);
                                            }
                                            
                                            $userPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                            
                                            if(isset($userPersonalDataTSPlaylistAudio[0]))
                                            {
                                                DB::table('u_p_d_t_s_p_audio_updates')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_a_id' => $userPersonalDataTSPlaylistAudio[0] -> id]);
                                            }
                                        }
                                    }
                                }
                                
                                $personalDataTSTools = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSTools as $personalDataTSTool)
                                {
                                    $personalDataTSToolFiles = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personalDataTSTool -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
                                    $userPersonalDataTSToolInserts = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', $personalDataTSTool -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);
                                    
                                    if($userPersonalDataTSToolInserts == 0)
                                    {
                                        DB::table('user_personal_data_t_s_tools')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_data_t_s_tool_id' => $personalDataTSTool -> id]);
                                        $userPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                        DB::table('user_personal_data_t_s_tool_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_id' => $userPersonalDataTSTool[0] -> id]);
                                    }

                                    $userPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSTool[0]))
                                    {
                                        DB::table('user_personal_data_t_s_tool_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_id' => $userPersonalDataTSTool[0] -> id]);
                                    
                                        foreach($personalDataTSToolFiles as $personalDataTSToolFile)
                                        {
                                            $userPersonalDataTSToolFileInserts = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', $personalDataTSToolFile -> id)->where('user_id', '=', $user_id)->update(['permissions' => $userPersonalData -> permissions]);

                                            if($userPersonalDataTSToolFileInserts == 0)
                                            {
                                                DB::table('user_personal_data_t_s_tool_files')->insert(['datetime' => $now, 'user_id' => $user_id, 'description' => $userPersonalData -> description, 'permissions' => 'advanced', 'personal_d_t_s_t_f_id' => $personalDataTSToolFile -> id]);
                                                $userPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', '=', $personalDataTSToolFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                                DB::table('user_personal_data_t_s_tool_file_c')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_f_id' => $userPersonalDataTSToolFile[0] -> id]);
                                            }
                                           
                                            $userPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', '=', $personalDataTSToolFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                            if(isset($userPersonalDataTSToolFile[0]))
                                            {
                                                DB::table('user_personal_data_t_s_tool_file_u')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_f_id' => $userPersonalDataTSToolFile[0] -> id]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $user_id = Auth::user()->id;
                $user = DB::table('user_personal_datas')->join('users', 'users.id', '=', 'user_personal_datas.user_id')->where('user_personal_datas.id', '=', $userPersonalData -> id)->select('name')->get();
                
                DB::table('recent_activities')->insert(['name' => $user[0] -> name, 'status' => 'active', 'type' => 'u_p_d_u', 'user_id' => $user_id, 'entity_id' => $userPersonalData -> personal_data_id, 'created_at' => $now]);
            
                Flash::success('User PersonalData updated successfully.');
                return redirect(route('userPersonalDatas.show', [$userPersonalData -> personal_data_id]));
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

    public function destroy($id)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $userPersonalData = $this->userPersonalDataRepository->findWithoutFail($id);
            $user_id = Auth::user()->id;
    
            if(empty($userPersonalData))
            {
                Flash::error('User PersonalData not found');
                return redirect(route('userPersonalDatas.index'));
            }
            
            $user = DB::table('personal_datas')->where('id', '=', $userPersonalData -> personal_data_id)->get();
            
            if($user[0] -> user_id == $user_id)
            {
                $user_id = $userPersonalData -> user_id;
                $personalDataTopics = DB::table('personal_data_topics')->where('personal_data_id', '=', $userPersonalData -> personal_data_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                DB::table('user_personal_data_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_id' => $userPersonalData -> id]);
    
                foreach($personalDataTopics as $personalDataTopic)
                {
                    $personalDataTopicSections = DB::table('personal_data_topic_sections')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                    
                    DB::table('user_personal_data_topics')->where('personal_data_topic_id', $personalDataTopic -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                    
                    $userPersonalDataTopic = DB::table('user_personal_data_topics')->where('personal_data_topic_id', '=', $personalDataTopic -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                    
                    if(isset($userPersonalDataTopic[0]))
                    {
                        DB::table('user_personal_data_topic_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_id' => $userPersonalDataTopic[0] -> id]);
                        
                        foreach($personalDataTopicSections as $personalDataTopicSection)
                        {
                            $personalDataTSFiles = DB::table('personal_data_t_s_files')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
        
                            DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', $personalDataTopicSection -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                            
                            $userPersonalDataTopicSection = DB::table('user_personal_data_topic_sections')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                            
                            if(isset($userPersonalDataTopicSection[0]))
                            {
                                DB::table('user_personal_data_topic_section_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_id' => $userPersonalDataTopicSection[0] -> id]);
                                
                                foreach($personalDataTSFiles as $personalDataTSFile)
                                {
                                    DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', $personalDataTSFile -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                    
                                    $userPersonalDataTSFile = DB::table('user_personal_data_t_s_files')->where('personal_data_t_s_file_id', '=', $personalDataTSFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSFile[0]))
                                    {
                                        DB::table('user_personal_data_t_s_file_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_f_id' => $userPersonalDataTSFile[0] -> id]);
                                    }
                                }
                
                                $personalDataTSNotes = DB::table('personal_data_t_s_notes')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSNotes as $personalDataTSNote)
                                {
                                    DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', $personalDataTSNote -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                    
                                    $userPersonalDataTSNote = DB::table('user_personal_data_t_s_notes')->where('personal_data_t_s_note_id', '=', $personalDataTSNote -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSNote[0]))
                                    {
                                        DB::table('user_personal_data_t_s_note_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_n_id' => $userPersonalDataTSNote[0] -> id]);
                                    }
                                }
                                
                                $personalDataTSGaleries = DB::table('personal_data_t_s_galeries')->where('personal_data_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSGaleries as $personalDataTSGalery)
                                {
                                    $personalDataTSGaleryImages = DB::table('personal_data_t_s_galery_images')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
                                    DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', $personalDataTSGalery -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                    
                                    $userPersonalDataTSGalery = DB::table('user_personal_data_t_s_galeries')->where('personal_data_t_s_g_id', '=', $personalDataTSGalery -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSGalery[0]))
                                    {
                                        DB::table('user_personal_data_t_s_galerie_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_id' => $userPersonalDataTSGalery[0] -> id]);
                    
                                        foreach($personalDataTSGaleryImages as $personalDataTSGaleryImage)
                                        {
                                            DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', $personalDataTSGaleryImage -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                            
                                            $userPersonalDataTSGaleryImage = DB::table('user_personal_data_t_s_galery_images')->where('p_d_t_s_g_i_id', '=', $personalDataTSGaleryImage -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                            
                                            if(isset($userPersonalDataTSGaleryImage[0]))
                                            {
                                                DB::table('user_personal_data_t_s_galery_image_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_g_i_id' => $userPersonalDataTSGaleryImage[0] -> id]);
                                            }
                                        }
                                    }
                                }
                                
                                $personalDataTSPlaylists = DB::table('personal_data_t_s_playlists')->where('p_d_t_s_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSPlaylists as $personalDataTSPlaylist)
                                {
                                    $personalDataTSPlaylistAudios = DB::table('personal_data_t_s_p_audios')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                    
                                    DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', $personalDataTSPlaylist -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                    
                                    $userPersonalDataTSPlaylist = DB::table('user_personal_data_t_s_p')->where('p_d_t_s_p_id', '=', $personalDataTSPlaylist -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSPlaylist[0]))
                                    {
                                        DB::table('u_p_d_t_s_playlist_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_id' => $userPersonalDataTSPlaylist[0] -> id]);
                                        
                                        foreach($personalDataTSPlaylistAudios as $personalDataTSPlaylistAudio)
                                        {
                                            DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', $personalDataTSPlaylistAudio -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                            
                                            $userPersonalDataTSPlaylistAudio = DB::table('user_p_d_t_s_p_audios')->where('p_d_t_s_p_a_id', '=', $personalDataTSPlaylistAudio -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                            
                                            if(isset($userPersonalDataTSPlaylistAudio[0]))
                                            {
                                                DB::table('u_p_d_t_s_p_audio_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_p_a_id' => $userPersonalDataTSPlaylistAudio[0] -> id]);
                                            }
                                        }
                                    }
                                }
                                
                                $personalDataTSTools = DB::table('personal_data_t_s_tools')->where('personal_data_topic_section_id', '=', $personalDataTopicSection -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
                                
                                foreach($personalDataTSTools as $personalDataTSTool)
                                {
                                    $personalDataTSToolFiles = DB::table('personal_data_t_s_tool_files')->where('personal_data_t_s_t_id', '=', $personalDataTSTool -> id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
                                    DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', $personalDataTSTool -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                    
                                    $userPersonalDataTSTool = DB::table('user_personal_data_t_s_tools')->where('personal_data_t_s_tool_id', '=', $personalDataTSTool -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                    
                                    if(isset($userPersonalDataTSTool[0]))
                                    {
                                        DB::table('user_personal_data_t_s_tool_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_id' => $userPersonalDataTSTool[0] -> id]);
                                    
                                        foreach($personalDataTSToolFiles as $personalDataTSToolFile)
                                        {
                                            DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', $personalDataTSToolFile -> id)->where('user_id', '=', $user_id)->update(['deleted_at' => $now]);
                                            
                                            $userPersonalDataTSToolFile = DB::table('user_personal_data_t_s_tool_files')->where('personal_d_t_s_t_f_id', '=', $personalDataTSToolFile -> id)->orderBy('datetime', 'desc')->limit(1)->get();
                                            
                                            if(isset($userPersonalDataTSToolFile[0]))
                                            {
                                                DB::table('user_personal_data_t_s_tool_file_d')->insert(['datetime' => $now, 'user_id' => $user_id, 'u_p_d_t_s_t_f_id' => $userPersonalDataTSToolFile[0] -> id]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
        
                $this->userPersonalDataRepository->delete($id);
                $user_id = Auth::user()->id;
                $user = DB::table('user_personal_datas')->join('users', 'users.id', '=', 'user_personal_datas.user_id')->where('user_personal_datas.id', '=', $userPersonalData -> id)->select('name')->get();
                
                DB::table('recent_activities')->insert(['name' => $user[0] -> name, 'status' => 'active', 'type' => 'u_p_d_d', 'user_id' => $user_id, 'entity_id' => $userPersonalData -> personal_data_id, 'created_at' => $now]);
            
                Flash::success('User PersonalData deleted successfully.');
                return redirect(route('userPersonalDatas.show', [$userPersonalData -> personal_data_id]));
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