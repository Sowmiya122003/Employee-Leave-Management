<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;

class TeamController extends Controller
{
    public function showteam(){
        $teams = Team::with('manager')->get();
        return view('admin.teams.teams-list',['teams'=> $teams]);
    }
    public function createteam(){
        return view('admin.teams.create-team');
    }
    public function teamsubmit(Request $request){
        $validate = $request->validate([
            'team_name' => 'required|unique:teams',
            'description'=>'nullable|string'
        ]);
        $team = Team::create($validate);
        return redirect()->route('team.list');
    }
}
