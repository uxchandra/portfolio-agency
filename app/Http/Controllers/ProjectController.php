<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->get();
        return view('admin.projects.index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'cover' => 'required|image|mimes:png,jpg|max:2048',
            'about' => 'required|string|max:65535'
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('cover')) {
                $path = $request->file('cover')->store('projects', 'public');
                $validated['cover'] = $path;
            }
            $validated['slug'] = Str::slug($request->name);

            $newProject = Project::create($validated);


            DB::commit();

            return redirect()->route('admin.projects.index')->with('success', 'Project Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
        return view('admin.projects.edit', [
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'cover' => 'sometimes|image|mimes:png,jpg|max:2048',
            'about' => 'required|string|max:65535'
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('cover')) {
                $path = $request->file('cover')->store('projects', 'public');
                $validated['cover'] = $path;
            }
            $validated['slug'] = Str::slug($request->name);

            $project->update($validated);

            DB::commit();

            return redirect()->route('admin.projects.index')->with('success', 'Project Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        try {
            $project->delete();
            return redirect()->back()->with('success', 'Project Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }
}
