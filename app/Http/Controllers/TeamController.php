<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Yajra\DataTables\DataTables;
class TeamController extends Controller
{
    public function showTeam(Request $request)
    {
        $teams = Team::leftJoin('users',function($join){
            $join->on('users.team_id','=','teams.id')
            ->where('users.role_id',2);
            })
            ->select('teams.id','teams.team_name','teams.description','users.full_name as manager')->get();
        // dd($teams->toArray());
        if ($request->ajax()) {
            return DataTables::of($teams)->toJson();
        }
        return view('admin.teams.teams-list');
    }
    public function createTeam()
    {
        return view('admin.teams.create-team');
    }
    public function teamSubmit(Request $request)
    {
        $validate = $request->validate([
            'team_name' => 'required|unique:teams',
            'description' => 'nullable|string',
        ]);
        $team = Team::create($validate);
        return redirect()->route('admin.team.list');
    }
    public function teamList(Request $request)
    {
        $teams = User::with('creator')
            ->where('team_id', auth()->user()->team_id)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.role_name as role_name');
        // dd($teams->toArray());
        if ($request->ajax()) {
            return DataTables::of($teams)
                ->editColumn('gender', function ($row) {
                    if ($row->gender == 'M') {
                        return 'Male';
                    }
                    if ($row->gender == 'F') {
                        return 'Female';
                    }
                    return '<i>Others</i>';
                })
                ->addColumn('creator_name', function ($row) {
                    return $row->creator?->full_name ?? 'N/A';
                })
                ->filterColumn('creator_name', function ($query, $keyword) {
                    $query->whereHas('creator', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('gender', function ($query, $keyword) {
                    $keyword = strtolower(trim($keyword));

                    if ($keyword == 'male') {
                        $query->where('users.gender', 'M');
                    } elseif ($keyword == 'female') {
                        $query->where('users.gender', 'F');
                    } elseif ($keyword == 'others') {
                        $query->whereNotIn('users.gender', ['M', 'F']);
                    }
                })
                ->addColumn('Action', function ($row) {
                    if(auth()->user()->role_id == 1) {
                    return "<a href = '" .
                        route('admin.view.employee', $row->id) .
                        "'><i class='bi bi-eye'></i></a>
                    <a href = '" .
                        route('admin.edit.employee', $row->id) .
                        "'><i class='bi bi-pencil'></i></a>
                    <a href='" .
                        route('admin.delete.employee', $row->id) .
                        "' onclick=\"return confirm('Do you want to delete?')\">
                        <i class='bi bi-trash'></i></a>";
                    }
                    elseif(auth()->user()->role_id == 2) {
                        return "<a href = '" .
                        route('admin.view.employee', $row->id) .
                        "'><i class='bi bi-eye'></i></a>";
                    }
                })
                ->rawColumns(['gender', 'Action'])
                ->toJson();
        }
        return view('manager.team-list', compact('teams'));
    }
}
