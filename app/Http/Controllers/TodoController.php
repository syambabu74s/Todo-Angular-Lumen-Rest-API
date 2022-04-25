<?php

namespace App\Http\Controllers;


use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class TodoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user_id = $user->id;
        return Todo::where('user_id','=',$user_id)->orderBy('id','desc')->get();
    }
    public function getuser()
    {
        $user = auth()->user();
        return $user;
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $todo = new Todo();
            $todo->user_id = $user->id;
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->completion_time = NULL;
            if ($todo->save()) {
                return response()->json(['status' => 'success', 'message' => 'New Todo Note created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $user_id = $user->id;
        try {
            $todo = Todo::where('user_id','=',$user_id)->where('id','=',$id)->first();
            if(!is_null($todo))
            {
                $status=$request->status;
                $completion_time=NULL;
                if($status=='COMPLETE')
                {
                    $completion_time=Carbon::now();
                }
                $todo->completion_time = $completion_time;
                if ($todo->save()) {
                    return response()->json(['status' => 'success', 'message' => 'Status updated successfully']);
                }
                else
                {
                    return response()->json(['status' => 'error', 'message' => 'Some error occured, Please try later']);
                }
            }
            else
            {
                return response()->json(['status' => 'error', 'message' => 'Requested data not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        $user = auth()->user();
        $user_id = $user->id;
        try {
            $todo = Todo::where('user_id','=',$user_id)->where('id','=',$id)->first();
            if(!is_null($todo))
            {
                if ($todo->delete()) {
                    return response()->json(['status' => 'success', 'message' => 'Todo Note deleted successfully']);
                }
                else
                {
                    return response()->json(['status' => 'error', 'message' => 'Some error occured, Please try later']);
                }
            }
            else
            {
                return response()->json(['status' => 'error', 'message' => 'Requested data not found']);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
