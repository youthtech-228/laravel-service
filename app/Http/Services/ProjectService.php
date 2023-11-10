<?php

namespace App\Http\Services;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectService
{
    public function index(Request $request) {
        return Project::all();
    }

    public function store(Request $request) {
        $input = $request->all();
        $project = new Project($input);
        $project->save();

        return $project;
    }

    public function edit($id) {
        return Project::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $input = $request->all();
        $project = Project::findOrFail($id)->update($input);
        return $project;
    }

    public function delete($id) {
        $project = Project::findOrFail($id);
        $project->delete();
    }
}