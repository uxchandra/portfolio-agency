<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\ProjectOrder;

class FrontController extends Controller
{
    //
    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->take(6)->get();
        return view('front.index', [
            'projects' => $projects
        ]);
    }

    public function details(Project $project)
    {
        return view('front.details', [
            'project' => $project
        ]);
    }

    public function book()
    {
        return view('front.book');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'budget' => 'required|integer',
            'category' => 'required|string',
            'brief' => 'required|string|max:65535'
        ]);

        DB::beginTransaction();

        try {

            $newProject = ProjectOrder::create($validated);


            DB::commit();

            return redirect()->route('front.index')->with('success', 'Project Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }
}
