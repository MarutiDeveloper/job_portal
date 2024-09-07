<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Error;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AccountController extends Controller
{
    // This method will show user registration Page
    public function registration() {
        return view('front.account.registration');
    }
    //This method will save a user
    public function processRegistration(Request $request){
            $validator = validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required | email | unique:users,email',
                'password' => 'required | min:5 | same:confirm_password',
                'confirm_password' => 'required'

            ]);

            if($validator->passes()){

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->name = $request->name;
                $user->save();

                Session()->flash('success', 'You have Register successfully...! ');
                
                return response()->json([
                    'status' => true,
                    'errors' => []
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors() 
                ]);
            }
    }
     // This method will show user login Page
     public function login() {
        return view('front.account.login');
     }
     public function authenticate(Request $request){
            $validator  =   validator::make($request->all(),[
                'email' =>   'required  | email',
                'password'  =>  'required'

            ]);

            if($validator->passes()){
                if(Auth::attempt(['email'   =>  $request->email,    'password'  =>  $request->password])){
                        return redirect()->route('account.profile');
                }else{
                    return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect....!');
                }

            }else{
                return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
            }
     }
     public function profile(){

        $id = Auth::user()->id;
            
         $user   =   User::where('id', $id)->first();

        return view('front.account.profile',[
            'user'  =>  $user
        ]);
     }
     public function updateProfile(Request $request){
        $id = Auth::user()->id;
                $validator  =   validator::make($request->all(), [
                    'name' => 'required|min:5|max:20',
                    'email' => 'required|email|unique:users,email,' . $id . ',id',
                ]);

                if($validator->passes()){

                    $user   = User::find($id);
                    $user ->name =   $request->name;
                    $user ->email  =   $request->email ;
                    $user->mobile =   $request->mobile;
                    $user ->designation =   $request->designation;
                    $user ->save();

                    session() ->flash('success', 'Profile Updated Successfully..!');

                    return response()->json([
                        'status'    =>  true,
                        'errors'    =>  [ ]
                    ]);

                }else{
                    return response()->json([
                        'status'    =>  false,
                        'errors'    =>  $validator->errors()
                    ]);
                }
     }
     
     public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
     }
     public function updateProfilePic(Request $request){
            //dd($request->all());
            $id = Auth::user()->id;
            $validator = validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ( $validator->passes()){
                $image = $request->image;
                $ext = $image->getClientOriginalExtension();
                $imageName =  $id. '_'.time().'.'.$ext;
                $image->move(public_path('/profile_pic'), $imageName );

                // Create a Small Thumnil

                // $thumbnailPath = public_path('profile_pics/Thumb/' . $imageName);
                // $manager = new ImageManager(Driver::class);
                // $image = $manager->read('$thumbnailPath');

                //crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
                // $image->cover(150, 150);
                // $image->toPng()->save(public_path('profile_pic/Thumb' .$imageName));

                // Delete old profile Pic.
                //File::delete(public_path('profile_pic/' .Auth::user()->image) );
            
                User::where('id',$id)->update(['image' => $imageName]);
               session()->flash('success', 'Profile picture updated Successfully....!');
                return response()->json([
                    'status' => true,
                    'errors' => []
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
     }
     public function createJob(){

        $categories = Category::orderBy('name', 'ASC')->where('status',1)->get();

        $jobTypes = JobType::orderBy('name', 'ASC')->where('status',1)->get();

        return view('front.account.job.create',[
            'categories' =>  $categories,
            'jobTypes' =>  $jobTypes,
        ]);
     }
     public function saveJob(Request $request){

                $rules = [
                    'title' => 'required|min:5|max:200',
                    'category' => 'required',
                    'jobType' => 'required',
                    'vacancy' => 'required|integer',
                    'location' => 'required|max:50',
                    'description' => 'required',
                    'company_name' => 'required|min:3|max:75',
                    
                ];
                $validator = validator::make($request->all(), $rules);

                if($validator->passes()){

                    $job = new Job();
                    $job->title = $request->title;
                    $job->category_id = $request->category;
                    $job->job_type_id = $request->jobType;
                    $job->user_id = Auth::user()->id;
                    $job->vacancy = $request->vacancy;
                    $job->salary = $request->salary;
                    $job->location = $request->location;
                    $job->description = $request->description;
                    $job->benefits = $request->benefits;
                    $job->responsibility = $request->responsibility;
                    $job->qualifications = $request->qualifications;
                    $job->keywords = $request->keywords;
                    $job->experience = $request->experience;
                    $job->company_name = $request->company_name;
                    $job->company_location = $request->company_location;
                    $job->company_website = $request->website;
                    $job->save();

                    session()->flash('success', 'Job added Successfully....!');

                    return response()->json([
                        'status' => true,
                        'errors' => []
                    ]);

                }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ]);
                }
     }
     public function myJobs(){

            $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
            return view('front.account.job.my-jobs',[
                'jobs' => $jobs
            ]);
     }
     public function editJob(Request $request, $id){
        $categories = Category::orderBy('name', 'ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status',1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();
        
        if ($job == null) {
            abort(404);
        }
        return view('front.account.job.edit',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job
        ]);
     }

     public function updateJob(Request $request, $id)
     {
         $validator = Validator::make($request->all(), [
             'title' => 'required|min:3|max:255',
             'category' => 'required|exists:categories,id',
             'jobType' => 'required|exists:job_types,id',
             'vacancy' => 'required|integer|min:1',
             'location' => 'required|string|max:255',
             'description' => 'required|string',
             'company_name' => 'required|string|max:255',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'errors' => $validator->errors(),
             ]);
         }
     
         $job = Job::find($id);
         $job->title = $request->title;
         $job->category_id = $request->category;
         $job->job_type_id = $request->jobType;
         $job->vacancy = $request->vacancy;
         $job->location = $request->location;
         $job->description = $request->description;
         $job->benefits = $request->benefits;
         $job->responsibility = $request->responsibility;
         $job->qualifications = $request->qualifications;
         $job->experience = $request->experience;
         $job->keywords = $request->keywords;
         $job->company_name = $request->company_name;
         $job->company_location = $request->company_location;
         $job->company_website = $request->website;
         $job->save();

         session()->flash('success', 'Job updated successfully!');
     
         return response()->json([
             'status' => true,
             'message' => 'Job updated successfully!',
         ]);
     }
     public function deleteJob(Request $request)
     {
        session()->flash('success', 'Job Deleted successfully....!');
         $jobId = $request->jobId;
         $job = Job::find($jobId);
     
         if ($job) {
             $job->delete();
     
             return response()->json([
                 'status' => true,
                 'message' => 'Job deleted successfully!'
                 
             ]);
         } else {
             return response()->json([
                 'status' => false,
                 'message' => 'Job not found!'
             ]);
         }
     }
     public function myJobApplications(){
            $jobApplications   =   JobApplication::where('user_id', Auth::user()->id)->with('job', 'job.jobType', 'job.applications')->orderBy('created_at', 'DESC')->paginate(10);
            return view('front.account.job.my-job-applications',[
                'jobApplications'  =>  $jobApplications
            ]);
     }
     public function removeJob(Request $request){
           $jobApplication  =  JobApplication::where([
                                           'id' =>  $request->id, 
                                           'user_id' =>  Auth::user()->id]
                                           )->first();
            if ($jobApplication ==  null) {
                session()->flash('error', 'Job Application Not Found...!');
                return  response()->json([
                    'status'    =>  false,
                ]);
            }

            JobApplication::find($request->id)->delete();

            session()->flash('success', 'You have successfully Deleted Job Application...!');
                return  response()->json([
                    'status'    =>  true,
                ]);

     }
     public function savedJobs(){
            // $jobApplications   =   JobApplication::where('user_id', Auth::user()->id)->with('job', 'job.jobType', 'job.applications')->paginate(10);

            $savedJobs  =   SavedJob::where([
                'user_id'   =>  Auth::user()->id

            ])->with('job', 'job.jobType', 'job.applications')->orderBy('created_at', 'DESC')->paginate(10);
            
            return view('front.account.job.saved-Jobs',[
                'savedJobs'  =>  $savedJobs
            ]);
     }

     public function removeSavedJob(Request $request){
        $savedJob  =  SavedJob::where([
                                        'id' =>  $request->id, 
                                        'user_id' =>  Auth::user()->id]
                                        )->first();
         if ($savedJob ==  null) {
             session()->flash('error', 'Job Not Found...!');
             return  response()->json([
                 'status'    =>  false,
             ]);
         }

         SavedJob::find($request->id)->delete();

         session()->flash('success', 'You have successfully Deleted Saved Job...!');
             return  response()->json([
                 'status'    =>  true,
             ]);

  }

  public function updatePassword(Request $request){
            $validator  =   Validator::make($request->all(),[
                'old_password'  =>  'required',
                'new_password'  =>  'required|min:5',
                'confirm_password'  =>  'required|same:new_password',
            ]);

            if ($validator->fails()) {
               return response()->json([
                'status'    =>  false,
                'errors'    =>  $validator->errors(),
               ]);
            }

            if (Hash::check($request->old_password, Auth::user()->password) ==  false) {
                session()->flash('error', 'Your Old Password is incorrect...!');
                return response()->json([
                    'status'    =>  true,
                ]);
            }

            $user   =   User::find(Auth::user()->id);
            $user->password =   Hash::make($request->new_password);
            $user->save();

            session()->flash('success', 'Your Password has been Updated Successfully...!');
            return response()->json([
                'status'    =>  true,
            ]);
  }
  public function forgotPassword(){
    return view('front.account.forgot-password');
  }
 
}
