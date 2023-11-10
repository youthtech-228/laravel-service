<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class TaskApiController extends Controller
{
     //
     private TaskService $taskService;
     //
     public function __construct(TaskService $taskService)
     {
         $this->taskService = $taskService;
     }
 
     public function index(Request $request, $projectId) {
         $response = $this->taskService->index($request, $projectId);
         return new JsonResource($response);
     }
 
     public function store(Request $request) {
         $validator = Validator::make($request->all(), [
             'name' => 'required',
             'project_id' => 'required',
         ]);
 
         if ($validator->fails()) {
             return response()->json([
                 'message' => 'The given data was invalid.',
                 'errors' => $validator->errors()
             ], 422);
         }
         $response = $this->taskService->store($request);
         return (new JsonResource($response))->additional(['message' => __('messages.store', ['name' => 'Task'])]);
     }
 
     public function edit(Request $request, $id) {
        $response = $this->taskService->edit($id);
        return  new JsonResource($response);
     }
 
     public function update(Request $request, $id) {
         $response = $this->taskService->update($request, $id);
 
         return (new JsonResource(['message' => __('messages.update', ['name' => 'Task'])]));
     }
 
     public function delete($id) {
         $this->taskService->delete($id);
         return (new JsonResource(['message' =>  __('messages.delete', ['name' => 'Task'])]));
     }

     public function sort(Request $request) {
        $this->taskService->sort($request->get('id'), $request->get('order_index'));
        return new JsonResource(["id"=>$request->get('id'), 'order_index' => $request->get('order_index')]);
     }
}
