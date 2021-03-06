<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersonalDataTopicSectionSearchController extends Controller
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
			$sections = DB::table('personal_datas')->join('personal_data_topics', 'personal_datas.id', '=', 'personal_data_topics.personal_data_id')->join('personal_data_topic_sections', 'personal_data_topics.id', '=', 'personal_data_topic_sections.personal_data_topic_id')->where('personal_data_topic_sections.name','LIKE','%'.$request->search."%")->where(function ($query) {$query->where('user_id', '=', Auth::user()->id);})->where(function ($query) {$query->where('personal_data_topic_sections.deleted_at', '=', null);})->orderBy('personal_data_topic_sections.name', 'asc')->paginate(50);

			if($sections)
 			{
				foreach ($sections as $key => $section)
				{
					$output.='<tr>'.'<td> <a href = "'.route("personalDataTopicSections.show", [ $section -> id ]).'">'.$section->name.'</a> </td>'.'</tr>';
				}
 
				return Response($output);
 			}
 		}
 	}
}