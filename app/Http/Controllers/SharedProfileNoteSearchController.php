<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SharedProfileNoteSearchController extends Controller
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
			$shared_profile_notes = DB::table('shared_profile_note')->where('shared_profile_note.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('shared_profile_note.deleted_at', '=', null);})->orderBy('shared_profile_note.name', 'asc')->limit(50)->get();
 
			if($shared_profile_notes)
 			{
				foreach ($shared_profile_notes as $key => $shared_profile_note)
				{
					$output.='<tr>'.'<td> <a href = "'.route("sharedProfileNotes.show", [ $shared_profile_note -> id ]).'">'.$shared_profile_note->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}