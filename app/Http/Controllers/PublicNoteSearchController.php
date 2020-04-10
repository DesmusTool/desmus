<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicNoteSearchController extends Controller
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
			$public_notes = DB::table('public_note')->where('public_note.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_note.deleted_at', '=', null);})->orderBy('public_note.name', 'asc')->limit(50)->get();
 
			if($public_notes)
 			{
				foreach ($public_notes as $key => $public_note)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicNotes.show", [ $public_note -> id ]).'">'.$public_note->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}