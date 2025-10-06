<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{


    // Display all projects
    public function index()
    {
        $projects = Project::all();
        return view('dashboard.projects.index', compact('projects')); // Show all projects
    }

    // Show the create project form
    public function create()
    {
        return view('dashboard.projects.create'); // Show the form to create a new project
    }

    // Store a new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Create a new project
        $project = Project::create($validated);

        try {
            // Add the user to MQTT
            return redirect()->route('dashboard.projects.index')->with('success', 'Project created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.projects.index')->with('error', 'Failed to connect to MQTT: ' . $e->getMessage());
        }
    }

    // Show the edit project form
    public function edit(Project $project)
    {
        return view('dashboard.projects.edit', compact('project')); // Show the form to edit the project
    }

    // Update an existing project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255', // Password is optional
        ]);

        // Update the project
        $project->update($validated);

        // Update the MQTT user password if provided

        return redirect()->route('dashboard.projects.index')->with('success', 'Project updated successfully!');
    }

    // Delete a project
    public function destroy(Project $project)
    {
        // Delete the MQTT user associated with the project
        $project->delete(); // Delete the project

        return redirect()->route('dashboard.projects.index')->with('success', 'Project deleted successfully!');
    }

    // Get all projects in JSON format
    public function getProjectsJson()
    {
        $projects = Project::select('name', 'client_id', 'username', 'password', 'description')->get()->toArray(); // Retrieve all projects
        return $this->writeProjectToJson($projects, 'projects');
    }

    // Get a single project in JSON format
    public function getProjectJson($project_id)
    {
        $project = Project::select('name', 'client_id', 'username', 'password', 'description')->find($project_id)->toArray();
        return $this->writeProjectToJson($project, $project['name']);
    }

    // Write project data to JSON and return as a downloadable response
    protected function writeProjectToJson($project, $name)
    {
        $jsonContent = json_encode($project, JSON_PRETTY_PRINT);
        $fileName = 'project_' . $name . '.json'; // File name for the JSON download

        return response($jsonContent)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Content-Length', strlen($jsonContent));
    }
}
