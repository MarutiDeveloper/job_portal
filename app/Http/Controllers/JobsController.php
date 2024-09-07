<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // This Method will show Jobs page.
    public function showJobForm(Request $request)
    {
        $categories = Category::all(); // Retrieve all categories from the database
        $jobTypes = JobType::all(); // Retrieve all job types from the database
        $jobs = Job::where('status', 1);

        // Search using Keywords
        if (!empty($request->keywords)) {
            $jobs = $jobs->where(function($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keywords . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keywords . '%');
            });
        }

        // Search using Location.
        if(!empty($request->location)){
            $jobs = $jobs->where('location',$request->location);
        }

        // Search using Category.
        if(!empty($request->category)){
            $jobs = $jobs->where('category_id',$request->category);
        }

        $jobTypeArray   =   []; 
        // Search using jobType.
         if(!empty($request->jobType)){

            $jobTypeArray = explode(',',$request->jobType);
            $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
        }

           // Search using Experience.
           if(!empty($request->experience)){
            $jobs = $jobs->where('experience',$request->experience);
        }

        $jobs = $jobs->with(['jobType', 'category']);

        if ($request->sort == '0') {
            $jobs = $jobs->orderBy('created_at', 'ASC');
        }else{
            $jobs = $jobs->orderBy('created_at', 'DESC');

        }

        $jobs = $jobs->paginate(9);
        
        
        return view('front.jobs',[
            'categories' => $categories,
            'jobTypes' =>   $jobTypes,
            'jobs'  =>  $jobs,
            'jobTypeArray'  =>  $jobTypeArray
        ]);
    }
    // This method show job detail page.
    public function detail($id){
        
        $job = Job::where([
                                            'id' => $id, 
                                            'status'    => 1
                                        ])->with(['jobType','category'])->first();

         if ($job   ==  null) {
           abort(404);
         }
         $count  =  0;
         if (Auth::user()) {
            $count  =   SavedJob::where([
                'user_id'   =>  Auth::user()->id,
                'job_id'    =>  $id
            ])->count();
         }

         // fatch applicants.
         $applications  =   JobApplication::where('job_id', $id)->with('user')->get();
        
           return view('front.jobDetail',['job' =>  $job, 'count'   =>  $count, 'applications'  =>  $applications]);
    }

    public function applyJob(Request $request){
        $id = $request->id;
    
        $job = Job::find($id);
        
        // If Job Not found in db
        if (!$job) {
            $message = 'Job does not exist!';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
    
        // You cannot apply to your own job.
        if ($job->user_id == Auth::id()) {
            $message = 'You cannot apply to your own job!';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
    
        // You cannot apply to the same job more than once.
        $applicationExists = JobApplication::where([
            'user_id' => Auth::id(),
            'job_id' => $id
        ])->exists();
    
        if ($applicationExists) {
            $message = 'You cannot apply to the same job more than once!';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
    
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::id();
        $application->employer_id = $job->user_id;
        $application->applied_date = now();
        $application->save();
    
        // Send Notification to Employer Email.
        $employer = User::find($job->user_id);
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];
        //Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
    
        $message = 'You have successfully applied!';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    public function saveJob(Request $request){

        $id =   $request->id;

        $job    =   Job::find( $id);

        if ($job    ==  null) {
            $message    =   'Job Not found... !';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message '  =>  $message
            ]);
        }

        // Check User Already Saved Job.
        $count  =   SavedJob::where([
            'user_id'   =>  Auth::user()->id,
            'job_id'    =>  $id
        ])->count();

        if ($count > 0) {
            $message    =   'You have already Saved this Job... !';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message '  =>  $message
            ]);
        }

        $savedJob   =   new SavedJob;
        $savedJob->job_id   =   $id;
        $savedJob->user_id   =  Auth::user()->id;
        $savedJob->save();

        $message    =   'You have Successfully Saved this Job... !';

            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message '  =>  $message
            ]);
    }
}
