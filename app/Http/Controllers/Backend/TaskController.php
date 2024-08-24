<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getAlltask(): JsonResponse
    {
        try {
            $task = Task::orderBy('id', 'desc')->get();

        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Source List found!!', '200', $task);

    }


    public function gettask(Task $task): JsonResponse
    {
        try {
            return sendSuccessResponse('Task Found!!', '200', $task);
        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }

    }

    public function storetask(RequestTask $request): JsonResponse
    {
        try {
            Task::create([
                'user_id'       => $request->user_id,         
                'title'         => $request->title,
                'description'   => $request->description,
                'deadline'      => $request->deadline,
                'priority'      => $request->priority,
                'status'         => $request->status,
            ]);

        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Task created Successfully!!', '200');

    }

    public function updatetask(RequestTask $request, Task $task): JsonResponse
    {
        try {
            $task->update([
                'user_id'       => $request->user_id,         
                'title'         => $request->title,
                'description'   => $request->description,
                'deadline'      => $request->deadline,
                'priority'      => $request->priority,
                'status'         => $request->status,
            ]);
        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Task updated Successfully!!', '200');


    }

    public function deletetask(Task $task): JsonResponse
    {
        try {
            $task->delete();

        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Task deleted Successfully!!', '200');

    }
}
