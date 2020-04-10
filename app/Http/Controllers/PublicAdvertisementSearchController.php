<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublicAdvertisementSearchController extends Controller
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
			$public_advertisements = DB::table('public_advertisement')->where('public_advertisement.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('public_advertisement.deleted_at', '=', null);})->orderBy('public_advertisement.name', 'asc')->limit(50)->get();
 
			if($public_advertisements)
 			{
				foreach ($public_advertisements as $key => $public_advertisement)
				{
					$output.='<tr>'.'<td> <a href = "'.route("publicAdvertisements.show", [ $public_advertisement -> id ]).'">'.$public_advertisement->name.'</a></td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}