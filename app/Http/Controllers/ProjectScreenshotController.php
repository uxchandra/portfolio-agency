<?php

namespace App\Http\Controllers;

use App\Models\ProjectScreenshot;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectScreenshotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        //
        return view('admin.project_screenshots.create', [
            'project' => $project
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        //
        $validated = $request->validate([
            'screenshot' => 'required|image|mimes:png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('screenshot')) {
                $path = $request->file('screenshot')->store('project_screenshot', 'public');
                $validated['screenshot'] = $path;
            }
            $validated['project_id'] = $project->id;

            $newScreenshot = ProjectScreenshot::create($validated);


            DB::commit();

            return redirect()->back()->with('success', 'Project Screenshot Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectScreenshot $projectScreenshot)
    {
        //
        try {
            $projectScreenshot->delete();
            return redirect()->back()->with('success', 'Screenshot Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error' . $e->getMessage());
        }
    }
}
