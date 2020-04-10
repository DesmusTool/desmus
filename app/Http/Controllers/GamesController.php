<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateGamesRequest;
use App\Http\Requests\UpdateGamesRequest;
use App\Repositories\GamesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;
use App\Models\Games;
use Illuminate\Support\Carbon;

class GamesController extends AppBaseController
{
    private $gamesRepository;

    public function __construct(GamesRepository $gamesRepo)
    {
        $this->gamesRepository = $gamesRepo;
    }

    public function index(Request $request)
    {
        $this->gamesRepository->pushCriteria(new RequestCriteria($request));
        $games = $this->gamesRepository->all();

        return view('games.index')
            ->with('games', $games);
    }

    public function create()
    {
        $user_id = Auth::user()->id;
        
        if(Auth::user() != null)
        {
            $game_list = Games::where('user_id', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
            
            return view('games.create')
                ->with('user_id', $user_id)
                ->with('game_list', $game_list);
        }
        
        else
        {
            return view('games.create');
        }
    }

    public function store(CreateGamesRequest $request)
    {
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $input = $request->all();
            $games = $this->gamesRepository->create($input);
            
            if($games -> user_id == $user_id)
            {
                DB::table('game_creates')->insert(['datetime' => $now, 'user_id' => $user_id, 'game_id' => $games -> id]);
                DB::table('recent_activities')->insert(['name' => $games -> name, 'status' => 'active', 'type' => 'g_c', 'user_id' => $user_id, 'entity_id' => $games -> id, 'created_at' => $now]);
                
                Flash::success('Game saved successfully.');
                return redirect(route('games.index'));
            }
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
            $game_p = $request -> game_p;
            
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $game = $this->gamesRepository->findWithoutFail($id);
            
            if(empty($game))
            {
                Flash::error('Game not found');
                return redirect(route('games.index'));
            }
            
            DB::table('game_views')->insert(['datetime' => $now, 'user_id' => $user_id, 'game_id' => $id]);
            DB::table('games')->where('id', $id)->update(['views_quantity' => DB::raw('views_quantity + 1')]);
            
            $games = Games::where('id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->paginate(50, ['*'], 'game_p');
            $gameCount = DB::table('games')->where('id', '=', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->count();
            $gameList = DB::table('games')->where('id' , '=', $id)->where(function ($query) {$query->where('games.deleted_at', '=', null);})->orderBy('games.views_quantity', 'desc')->limit(5)->get();
            $gameViews = DB::table('users')->join('game_views', 'users.id', '=', 'game_views.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();
            $gameUpdates = DB::table('users')->join('game_updates', 'users.id', '=', 'game_updates.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(50)->get();
            $user = DB::table('games')->join('users', 'games.user_id', '=', 'users.id')->where('games.id', '=', $id)->get();
                
            $gameViewsList = DB::table('users')->join('game_views', 'users.id', '=', 'game_views.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
            $gameUpdatesList = DB::table('users')->join('game_updates', 'users.id', '=', 'game_updates.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                
            return view('games.show')
                ->with('game', $game)
                ->with('games', $games)
                ->with('gameCount', $gameCount)
                ->with('gameList', $gameList)
                ->with('gameViews', $gameViews)
                ->with('gameUpdates', $gameUpdates)
                ->with('game_p', $game_p)
                ->with('user_id', $user_id)
                ->with('user', $user)
                ->with('now', $now)
                ->with('id', $id)
                ->with('gameViewsList', $gameViewsList)
                ->with('gameUpdatesList', $gameUpdatesList);
        }
        
        else
        {
            return view('deniedAccess');
        }
    }

    public function edit($id)
    {
        $games = $this->gamesRepository->findWithoutFail($id);

        if(empty($games))
        {
            Flash::error('Games not found');
            return redirect(route('games.index'));
        }

        return view('games.edit')->with('games', $games);
        
        if(Auth::user() != null)
        {
            $user_id = Auth::user()->id;
            $games = $this->gamesRepository->findWithoutFail($id);
    
            if(empty($games))
            {
                Flash::error('Game not found');
                return redirect(route('games.index'));
            }
            
            $userGames = DB::table('user_games')->where('game_id', '=', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            $isShared = false;
            
            foreach($userGames as $userGame)
            {
                if($userGame -> user_id == $user_id && $userGame -> permissions == 'advanced')
                {
                    $isShared = true;
                    break;
                }
            }
            
            if($user_id == $games -> user_id || $isShared)
            {
                $gamesList = GameTopic::where('game_id', $id)->where(function ($query) {$query->where('deleted_at', '=', null);})->orderBy('id', 'desc')->limit(10)->get();
                $gameViewsList = DB::table('users')->join('game_views', 'users.id', '=', 'game_views.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                $gameUpdatesList = DB::table('users')->join('game_updates', 'users.id', '=', 'game_updates.user_id')->where('game_id', $id)->orderBy('datetime', 'desc')->limit(10)->get();
                
                return view('games.edit')
                    ->with('games', $games)
                    ->with('user_id', $user_id)
                    ->with('gamesList', $gamesList)
                    ->with('gameViewsList', $gameViewsList)
                    ->with('gameUpdatesList', $gameUpdatesList);
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

    public function update($id, UpdateGamesRequest $request)
    {
        /*$games = $this->gamesRepository->findWithoutFail($id);

        if(empty($games))
        {
            Flash::error('Games not found');
            return redirect(route('games.index'));
        }

        $games = $this->gamesRepository->update($request->all(), $id);

        Flash::success('Games updated successfully.');
        return redirect(route('games.index'));*/
        
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $game = $this->gamesRepository->findWithoutFail($id);
    
            if(empty($game))
            {
                Flash::error('Game not found');
                return redirect(route('games.index'));
            }
            
            $size = 0;
            $game_data_sizes = DB::table('games')->where('user_id', '=', $user_id)->where(function ($query) {$query->where('deleted_at', '=', null);})->get();
            
            if(($user_id == $game -> user_id) && $size <= 1073741824)
            {
                $newGame = $this->gamesRepository->update($request->all(), $id);
                $specific_info_size = strlen($request -> description);
    
                DB::table('games')->where('id', $id)->update(['updates_quantity' => DB::raw('updates_quantity + 1')]);
                DB::table('game_updates')->insert(['actual_name' => $newGame -> name, 'past_name' => $game -> name, 'datetime' => $now, 'game_id' => $id, 'user_id' => $user_id]);
                DB::table('recent_activities')->insert(['name' => $game -> name, 'status' => 'active', 'type' => 'g_u', 'user_id' => $user_id, 'entity_id' => $game -> id, 'created_at' => $now]);
        
                Flash::success('Game updated successfully.');
                return redirect(route('games.show', [$id]));
            }
            
            else
            {
                if($size > 1073741824)
                {
                    Flash::error('Your storage space is exhausted, you can get more space at only 15 dollars per month.');
                    return redirect(route('games.index'));
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
        /*$games = $this->gamesRepository->findWithoutFail($id);

        if(empty($games))
        {
            Flash::error('Games not found');
            return redirect(route('games.index'));
        }

        $this->gamesRepository->delete($id);

        Flash::success('Games deleted successfully.');
        return redirect(route('games.index'));*/
        
        if(Auth::user() != null)
        {
            $now = Carbon::now();
            $user_id = Auth::user()->id;
            $game = $this->gamesRepository->findWithoutFail($id);
            
            if(empty($game))
            {
                Flash::error('Game not found');
                return redirect(route('games.index'));
            }
            
            if($user_id == $game -> user_id)
            {
                $this->gamesRepository->delete($id);
                
                DB::table('game_deletes')->insert(['datetime' => $now, 'user_id' => $user_id, 'game_id' => $game -> id]);
                DB::table('recent_activities')->insert(['name' => $game -> name, 'status' => 'active', 'type' => 'g_d', 'user_id' => $user_id, 'entity_id' => $game -> id, 'created_at' => $now]);
        
                Flash::success('Game deleted successfully.');
                return redirect(route('games.index'));
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