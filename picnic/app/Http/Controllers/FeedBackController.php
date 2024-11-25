<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\FeedBack;
use Illuminate\Http\Request;
use DB;
use Validator;
use Exception;
use App\LogEvent;
use Route;
use Log;
use App\Utilities;
use Illuminate\Support\Facades\Auth;

class FeedBackController extends Controller
{
    protected $feedback;

    public function __construct(FeedBack $feedback)
    {
        $this->feedback = $feedback;
    }


    //meysam - for admins - see the list of feedbacks...
    public function index() {

        try
        {
//            Log::info('in feedback index');


            $feedback = new FeedBack();
            $feedback->initialize();
            $feedbacks = $feedback->select('desc', null, null);

            return view('feedback.index', ['feedbacks' => $feedbacks]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }
    /**
    create feedback get
     */
    public function create()
    {
        return view('feedback.create');
    }


    //meysam - for users or public - storing new feedback ...
    public function store(Request $request)
    {

//        Log::info('meysam1'.json_encode($request->all()));

        $validation = Validator::make($request->all(), [
            'title' => 'max:50',
            'description' => 'required',
            'email'=>'email',
            'tel'=>'numeric',
            'name_and_family'=>'max:500',
//            'coinhive-captcha-token' => 'required',
            'captcha' => 'required|captcha',
        ]);

        //////CAPTCHA/////////////////////////////////////////////////
//        if(!$request->has('coinhive-captcha-token'))
//        {
//            $message = trans('messages.msgErrorWrongCaptcha');
//            $messages = [$message];
//
//            return redirect()->back()->with('messages', $messages);
//        }
//
//        $post_data = [
//            'secret' => "71dB6OFizbg4zi4HdrtlVVTmgjL55QD3", // <- Your secret key
//            'token' => $request ->input('coinhive-captcha-token'),
//            'hashes' => 1024
//        ];
//
//
//        $post_context = stream_context_create([
//            'http' => [
//                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//                'method'  => 'POST',
//                'content' => http_build_query($post_data)
//            ]
//        ]);
//
//        $url = 'https://api.coinhive.com/token/verify';
//        $response = json_decode(file_get_contents($url, false, $post_context));
//
//        if (!$response || !$response->success) {
//            // All bad. Token not verified!
//            $message = trans('messages.msgErrorWrongCaptcha');
//            $messages = [$message];
//            return response()->json([
//                'success' => false,
//                'messages' => $messages
//            ]);
//        }
        //////////////////////////////////////////////////////


        $this->feedback->initializeByRequest($request);

        try
        {
            if($validation->passes()){
                $this->feedback->store();
                try
                {
                    Utilities\Utility::sendInformMail($request ->input('description'),'meysamarab@yahoo.com');
                    Utilities\Utility::sendInformMail($request ->input('description'),'fardan7eghlim@gmail.com');

                }
                catch(Exception $e)
                {
                    $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
                    $logEvent->store();
                }

                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return response()->json([
                    'success' => true,
                    'messages' => $messages
                ]);

            }else{
                $messages = $validation->errors()->all();
                return response()->json([
                    'success' => false,
                    'messages' => $messages
                ]);
            }
        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return response()->json([
                'success' => false,
                'messages' => $messages
            ]);
        }
    }

    public function detail($feedback_id,$feedback_guid){
        try
        {
//            Log::info('$feedback_id:'.json_encode($feedback_id));

            $feedback = FeedBack::findByIdAndGuid($feedback_id,$feedback_guid);
//            Log::info('$feedback:'.json_encode($feedback));
            return view('feedback.details', ['feedback' => $feedback]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    public function remove($feedback_id,$feedback_guid){

        try
        {
            if (!FeedBack::existByIdAndGuid($feedback_id,$feedback_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            FeedBack::removeByIdAndGuid($feedback_id,$feedback_guid);
            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }
}