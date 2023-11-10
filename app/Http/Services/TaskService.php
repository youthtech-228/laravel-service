<?php

namespace App\Http\Services;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TaskService
{
    public function index(Request $request, $projectId) {
        return Task::where('project_id', $projectId)->orderBy(DB::raw('-`priority`'), 'desc')->orderBy('order_index', 'ASC')->get();
    }

    public function store(Request $request) {
        $input = $request->all();
        $task = new Task($input);
        $task->save();
        $this->updateSort($task->project_id, $task->id);
        return $task;
    }

    public function edit($id) {
        return Task::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $input = $request->all();

        $task = Task::findOrFail($id)->update($input);

        return $task;
    }

    public function delete($id) {
        $task = Task::findOrFail($id);
        $task->delete();
        return $task;
    }

    public function sort($ids, $order_indexs) {
        foreach($ids as  $key => $id) {
            $task = Task::find($id);
            if ($task) {
                $task->order_index = $order_indexs[$key];
                $task->save();
            }
        }
        
    }

    private function updateSort($projectId, $taskId) {
        $tasks = Task::where("project_id", $projectId)->where('id', '!=', $taskId)->orderBy(DB::raw('-`priority`'), 'desc')->orderBy('order_index', 'ASC')->get();

        foreach($tasks as $key => $task) {
            $task = Task::find($task->id);
            if ($task) {
                $task->order_index = $key + 1;
                $task->save();
            }
        }
    }
}