<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:06 PM
 */

namespace App\Http\Controllers;


use App\Blog;
use App\LogEvent;
use App\OperationMessage;
use App\Tag;
use App\Tour;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\jDateTime;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function home()
    {

//        \App\Utilities\Utility::getContactList();

        $tours = DB::table('tours')
            ->where('tours.deleted_at','=',null)
            ->where('tours.status','=',Tour::StatusActive)
            ->where('tours.deadline_date_time','>',\Carbon\Carbon::now("Asia/Tehran"))
            ->orderBy('deadline_date_time', 'desc')
            ->take(3)
            ->get();

        if(count($tours) < 3)
        {
            $added_tours = DB::table('tours')
                ->where('tours.deleted_at','=',null)
                ->where('tours.status','=',Tour::StatusActive)
                ->where('tours.deadline_date_time','<=',\Carbon\Carbon::now("Asia/Tehran"))
                ->orderBy('deadline_date_time', 'desc')
                ->take(3 - count($tours))
                ->get();

            $tours = $tours->merge($added_tours);
        }



        foreach($tours as $tour)
        {
            $tour->info = json_decode($tour->info);
            $tour->site_share = json_decode($tour->info->site_share);
            $tour->tour_address = json_decode($tour->tour_address);

            $fileNameWithoutExtention = Tour::TOUR_AVATAR_FILE_NAME;
            if(\App\Utilities\Utility::isFileExist(Tag::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour->tour_id))
            {
                $tour->hasAvatarPicture = 1;
            }
            else{
                $tour->hasAvatarPicture = 0;
            }

            $tour->owner = User::findById($tour->user_id);


            $tour->miladi_start_date_time = $tour->start_date_time;
            $tour->miladi_end_date_time = $tour->end_date_time;
            $tour->miladi_deadline_date_time = $tour->deadline_date_time;

            $tour->start_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->start_date_time));
            $tour->end_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->end_date_time));
            $tour->deadline_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->deadline_date_time));

        }



        $blogs = DB::table('blogs')
            ->where('blogs.deleted_at','=',null)
            ->where('blogs.status','=',Blog::BLOG_STATUS_ACTIVE)
            ->orderBy('blog_date_time', 'desc')
            ->take(3)
            ->get();


        foreach($blogs as $blog)
        {

            $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
            if(\App\Utilities\Utility::isFileExist(Tag::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog->blog_id))
            {
                $blog->hasCoverPicture = 1;
            }
            else{
                $blog->hasCoverPicture = 0;
            }

            $blog->miladi_blog_date_time = $blog->blog_date_time;
            $blog->blog_date_time = jDateTime::strftime('Y/n/j', strtotime($blog->blog_date_time));

        }



        return view('home.main', ['tours' => $tours, 'blogs' => $blogs]);
//        return view('welcome', ['tours' => $tours]);

    }


    public function termsOfUse()
    {
        try
        {
            return view('home.termsOfUse');
        }
        catch (Exception $ex)
        {
            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);

        }
    }


    public function about()
    {
        try
        {
            return view('about.create');

        }
        catch (Exception $ex)
        {
            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);

        }
    }

    public function refreshCaptcha(){
        return captcha_img('math');
    }

    public function faq()
    {
        try
        {
            return view('home.faqs');
        }
        catch (Exception $ex)
        {
            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);

        }
    }
}