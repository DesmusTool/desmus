<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GameSearchController extends Controller
{
    public function index()
	{
		return view('search.search');
	}
 
	public function search(Request $request)
	{
		if($request->ajax()) 
		{
			$output="";
			$games = DB::table('games')->where('games.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('games.deleted_at', '=', null);})->orderBy('games.name', 'asc')->limit(50)->get();
 
			if($games)
 			{
				foreach ($games as $key => $game)
				{
					$output.='<tr>'.'<td> <a href = "'.route("games.show", [ $game -> id ]).'">'.$game->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}