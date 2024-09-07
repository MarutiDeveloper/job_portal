<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(){
        $applications    =   JobApplication::orderBy('created_at', 'DESC')
                                                                                        ->with('job', 'user', 'employer')
                                                                                        ->paginate(10);
        return view('admin.job-applications.list', [
            'applications'   =>  $applications
        ]);
    }
    public function destroy(Request $request){
            $id = $request->id;

            $jobApplication =   JobApplication::find( $id);

            if ($jobApplication ==  null) {
                $message    =   "Either job Application deleted or not found.!";
                session()->flash('error', $message);
               return response()->json([
                'status'    =>  false

               ]);
            }

            $jobApplication->delete();
            $message    =   "You have successfully Deleted Job Application.!";
                session()->flash('success', $message);
               return response()->json([
                'status'    =>  true

               ]);
    }
}
