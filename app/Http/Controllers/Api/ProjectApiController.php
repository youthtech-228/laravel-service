<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class ProjectApiController extends Controller
{
    //
    private ProjectService $projectService;
    //
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request) {
        $response = $this->projectService->index($request);
        return new JsonResource($response);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }
        $response = $this->projectService->store($request);
        return (new JsonResource($response))->additional(['message' => __('messages.store', ['name' => 'Project'])]);
    }

    public function edit(Request $request, $id) {
        return $this->projectService->edit($id);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }
        $response = $this->projectService->update($request, $id);

        return (new JsonResource(['message' => __('messages.update', ['name' => 'Project'])]));
    }

    public function delete(Request $request, $id) {
        $this->projectService->delete($id);
        return (new JsonResource(['message' =>  __('messages.delete', ['name' => 'Project'])]));
    }
}
