<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:06 PM
 */

namespace App\Http\Controllers;

use App\FeedBack;
use App\Garden;
use App\Reserve;
use App\Tour;
use App\Transaction;
use App\Tag;
use App\User;
use App\Utilities\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;
use App\LogEvent;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{

    public function __construct(User $user)
    {

    }

    //meysam - see the list...
    public function index() {

        try
        {

            $users = DB::table('users')
                ->where('users.deleted_at','=',null)
                ->orderBy('user_id', 'desc')
                ->get();

//            Log::info($users);
            return view('user.index', ['users' => $users]);

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

    //meysam
    public function dashboard() {

        try
        {

            return view('user.dashboard');
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
     * نمایش لاگین

     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
//        Log::info('hiii'.json_encode($request->all()));
        try
        {
            $validation = Validator::make($request->all(), [

                'mobile_login' => 'required|max:15',
                'password_login' => 'required|min:6|max:100',
                'captcha' => 'required|captcha',
            ]);

//            if(!$request->has('coinhive-captcha-token'))
//            {
//                $message = trans('messages.msgErrorWrongCaptcha');
//                $messages = [$message];
//
//                return redirect()->back()->with('messages', $messages);
//            }
//
//            $post_data = [
//                'secret' => "71dB6OFizbg4zi4HdrtlVVTmgjL55QD3", // <- Your secret key
//                'token' => $_POST['coinhive-captcha-token'],
//                'hashes' => 256
//            ];
//
//            $post_context = stream_context_create([
//                'http' => [
//                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//                    'method'  => 'POST',
//                    'content' => http_build_query($post_data)
//                ]
//            ]);
//
//            $url = 'https://api.coinhive.com/token/verify';
//            $response = json_decode(file_get_contents($url, false, $post_context));
//
////            if ($response && $response->success) {
////                // All good. Token verified!
////            }

//            if (!$response || !$response->success) {
                // All bad. Token not verified!
//                    $message = trans('messages.msgErrorWrongCaptcha');
//                    $messages = [$message];
//                    return redirect()->back()->with('messages', $messages);
//            }


//            if (!filter_var($request->input('mobile_login'), FILTER_VALIDATE_EMAIL)) {
//                    $message = trans('messages.msgErrorWrongMobile');
//                    $messages = [$message];
//                    return redirect()->back()->with('messages', $messages);
//            }



            if($validation->passes()){

//                Log::info('meysam1');

                 if ( Auth::guard('web')->attempt(['mobile' => $request->input('mobile_login'), 'password' => $request->input('password_login')], $request->input('remember')))
                    {
//                        Log::info('meysam3');
                        // The user is being remembered...

//                        return redirect('/users/dashboard');
                     return redirect()->intended('/users/dashboard');
                    }
                    else
                    {
//                        Log::info('meysam4');
                        $message = trans('messages.msgErrorUserNamePassword');
                        $messages = [$message];
                        return redirect()->back()->with('errors', $messages);
                    }

            }else{
//                Log::info('meysam2');
                $errors = $validation->errors()->all();
                return redirect()->back()->with('errors', $errors);
            }

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

//    /**
//     * Handle an json authentication attempt.
//     *
//     * @param  \Illuminate\Http\Request $request
//     *
//     * @return json Response
//     */
//    public function jsonAuthenticate(Request $request)
//    {
//        try
//        {
//            $validation = Validator::make($request->all(), [
//
//                'email_login' => 'required|max:260',
//                'password_login' => 'required|min:6|max:100',
//                'captcha' => 'required|captcha',
//            ]);
//
//                if (!filter_var($request->input('email_login'), FILTER_VALIDATE_EMAIL)) {
//                    $message = trans('messages.msgErrorWrongEmail');
//                    $messages = [$message];
//
//                    $data = array('success' => false, 'messages' => $messages);
//                    return response()->json($data);
//                }
//
//
//            if($validation->passes()){
//
//
//                    if ( Auth::guard('web')->attempt(['email' => $request->input('email_login'), 'password' => $request->input('password_login')], $request->input('remember')))
//                    {
//                        // The user is being remembered...
//                        $data = array('success' => true);
//                        return response()->json($data);
//                    }
//                    else
//                    {
//                        $message = trans('messages.msgOperationFailed');
//                        $messages = [$message];
//
//                        $data = array('success' => false, 'messages' => $messages);
//                        return response()->json($data);
//                    }
//
//            }else{
//                $errors = $validation->errors()->all();
//                $data = array('success' => false, 'messages' => $errors);
//                return response()->json($data);
//            }
//        }
//        catch (Exception $e)
//        {
//            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
//            $message = trans('messages.msgOperationFailed');
//            $messages = [$message];
//            $data = array('success' => false, 'messages' => $messages);
//            return response()->json($data);
//        }
//    }

    /**
     * کاربر خروج
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * کاربر ثبت نام
     */
    public function create()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        try
        {

            $validation = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'family' => 'required|max:50',
                'email' => 'required|email|unique:users|max:260',
                'password' => 'required|confirmed|min:6|max:100',
                'type' => 'required',
                'mobile' => 'required|numeric',
                'status' => 'required',

            ]);
//            log::info(json_encode($request->all()));


            if (User::existByEmail($request->input('email'),null,null)) {

                $old_users = DB::table('users')
                    ->where('users.deleted_at','=',null)
                    ->where('users.email','like',$request->input('email'))
                    ->orderBy('user_id', 'desc')
                    ->get();
                foreach ($old_users as $oldUser)
                {
                    if($oldUser->type == $request->input('type'))
                    {
                        $message = trans('messages.msgErrorEmailExist');
                        $messages = [$message];
                        return redirect()->back()->with('errors', $messages);
                    }
                }
            }


            if($validation->passes()){
                $password = $request['password'];
                $request['password'] = bcrypt($request['password']);


                $request['name_family'] = $request->input('name')." ".$request->input('family');


                if($request->has("notification_email"))
                    $request['notification_email'] = 1;
                else
                    $request['notification_email'] = 0;

                if($request->has("notification_sms"))
                    $request['notification_sms'] = 1;
                else
                    $request['notification_sms'] = 0;


                $user = new User();
                $user->initializeByRequest($request);

                //meysam - create social json
                $socials = array();
                $socialCount = 0;
                $requestSocialName = "social_name_".$socialCount;
                while($request->has($requestSocialName))
                {
                    $social = new \stdClass();
                    $social->code =  $request->input($requestSocialName);
                    $social->address = Utility::addHTTP($request->input("social_address_".$socialCount));
                    array_push($socials, $social);

                    $socialCount++;
                    $requestSocialName = "social_name_".$socialCount;
                }
                $user->social = json_encode($socials);
                //////////////////////////////////////////

                //meysam - create info json
                $info = new \stdClass();

                if($request->has("show_contact"))
                    $info->show_contact = 1;
                else
                    $info->show_contact = 0;

                if($request->has("show_social"))
                    $info->show_social = 1;
                else
                    $info->show_social = 0;


                $user -> info = json_encode($info);
                ////////////////////////////////

                $user->register();

                //meysam - save avatar file if exist
                if($request->hasFile('avatar'))
                {
                    $request['tag'] = Tag::TAG_USER_AVATAR;
                    Utility::saveFile($request,$user->user_id);
                }

                try
                {
                    $message = trans('messages.welcome');
                    Utility::sendRegisterMailV2($message,$request->input('email'),$password );
                }
                catch(Exception $e)
                {
                    $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
                    $logEvent->store();
                }

                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/users/index')->with('messages', $messages);
            }else{
                $errors = $validation->errors()->all();
                return redirect()->back()->with('errors', $errors);
            }

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('errors', $messages);
        }
    }

    /**
     * meysam - user edit get
     */
    public function edit($user_id,$user_guid)
    {
        try
        {
            if(!User::existByIdAndGuid($user_id,$user_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $user = User::findByIdAndGuid($user_id, $user_guid);

            $user->social = json_decode( $user->social);
            $user->info = json_decode($user->info);

//            Log::info('in edit: user:'.json_encode($user));

            $fileNameWithoutExtention = User::USER_AVATAR_FILE_NAME;
            if(Utility::isFileExist(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$user->user_id))
            {
                $user->hasAvatar = 1;
            }
            else{
                $user->hasAvatar = 0;
            }


            return view('user.edit', ['user' => $user]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('errors', $messages);
        }
    }

    public function remove($user_id,$user_guid){

        try
        {
            if (!User::existByIdAndGuid($user_id,$user_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            User::removeByIdAndGuid($user_id,$user_guid);
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


    /**
     * Update the specified user in storage.
     *
     *
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'user_guid' => 'required',
            'name_family' => 'max:100',
            'email' => 'required|email|max:260',
            'password' => 'confirmed|min:6|max:100',
            'mobile' => 'numeric|digits_between:5,20',
        ]);

//        Log::info('in edit: request:'.json_encode($request->all()));

        if(!User::existByIdAndGuid($request->input('user_id'), $request->input('user_guid')))
        {
            $message = trans('messages.msgErrorItemNotExist');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        if (User::existByEmail($request->input('email'),true,$request->input('user_id'))) {

            $message = trans('messages.msgErrorEmailExist');
            $messages = [$message];
            return redirect()->back()->with('errors', $messages);
        }


        if($request->has('password') && $request->has('new_password'))
        {
            $user = User::findByIdAndGuid($request['user_id'],$request['user_guid']);
            if (password_verify($request['password'], $user->password)) {
                $request['password'] = bcrypt($request['new_password']);
            }
            else
            {
                $message = trans('messages.msgErrorMismatchPassword');
                $messages = [$message];
                return redirect()->back()->with('errors', $messages);
            }
        }
        if($validation->passes())
        {
            try
            {
                $user = User::findById($request->input('user_id'));

                User::edit($request);

                //meysam - save file if exist
                if($request->hasFile('avatar'))
                {
                    $request['tag'] = Tag::TAG_USER_AVATAR;
                    $fileNameWithoutExtention = User::USER_AVATAR_FILE_NAME;
                    if(Utility::isFileExist(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$request->input('user_id')))
                    {
                        Utility::deleteFile(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$request->input('user_id'));
                    }
                    Utility::saveFile($request,$request->input('user_id'));
                }

                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/users/dashboard')->with('messages', $messages);
            }
            catch (Exception $e)
            {
                $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
                $logEvent->store();
                $message = trans('messages.msgOperationFailed');
                $messages = [$message];
                return redirect()->back()->with('errors', $messages);
            }
        }
        else
        {
            $errors = $validation->errors()->all();
            return redirect()->back()->with('errors', $errors);
        }

    }

    /**
     * meysam - go to forget password form
     */
    public function reset()
    {
        return view('user.forget');
    }

    public function forget(Request $request){

        $validation = Validator::make($request->all(), [

            'email' => 'required|max:260',
            'new_password' => 'required|confirmed|min:6|max:100',
            'captcha' => 'required|captcha',
        ]);


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
//            'token' => $_POST['coinhive-captcha-token'],
//            'hashes' => 256
//        ];
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
////            if ($response && $response->success) {
////                // All good. Token verified!
////            }
//
//        if (!$response || !$response->success) {
//            // All bad. Token not verified!
//            $message = trans('messages.msgErrorWrongCaptcha');
//            $messages = [$message];
//            return redirect()->back()->with('messages', $messages);
//        }



        if(!$validation->passes()){
            $errors = $validation->errors()->all();
            return redirect()->back()->with('errors', $errors);
        }
        ////////////////////////////////////////////


        try {

            $isEmail = false;
            if(Utility::checkEmail($request['email']))
            {
                $isEmail = true;
            }

            $data_to_hash = $request['email'].'#'.$request['new_password'].'#'.Carbon::now();

            $hashed_data_in_link = openssl_encrypt(strval($data_to_hash),"AES-128-ECB",User::PASSWORD_RECOVERY_ENCRYPTION_KEY);

            $hashed_data_in_link = urlencode($hashed_data_in_link);

            if($isEmail)
                $user = User::findByEmail($request['email']);
            else
                $user = User::findByMobile($request['email']);

            if($user != null){
                try
                {

                    if($isEmail)
                        Utility::sendRecoveryMailV2($hashed_data_in_link,$request['email'],$request['new_password']);
                    else
                        Utility::sendRecoverySMS($hashed_data_in_link,$request['email'],$request['new_password']);
                }
                catch (\Exception $ex)
                {
                    $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
                    $logEvent->store();
                }
            }
            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        } catch (\Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('errors', $messages);
        }
    }

    public function detail($user_id, $user_guid){

        try
        {
            if (!User::existByIdAndGuid($user_id,$user_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $user = User::findByIdAndGuid($user_id,$user_guid);

            $user->info = json_decode($user->info);
            $user->social = json_decode($user->social);

            $fileNameWithoutExtention = User::USER_AVATAR_FILE_NAME;
            if(\App\Utilities\Utility::isFileExist(Tag::TAG_USER_AVATAR, $fileNameWithoutExtention,$user->user_id))
            {
                $user->hasAvatarPicture = 1;
            }
            else{
                $user->hasAvatarPicture = 0;
            }

            return view('user.details', ['user' => $user]);

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

    public function recover(){
        try {

            $link = $_GET['link'];
            $data_to_hash = openssl_decrypt($link,"AES-128-ECB",User::PASSWORD_RECOVERY_ENCRYPTION_KEY);
            $data_to_hash = urldecode($data_to_hash);

            $a=explode("#",$data_to_hash);

            $email=$a[0];
            $new_password=$a[1];


            $isEmail = false;
            if(Utility::checkEmail($email))
            {
                $isEmail = true;
            }

            if($isEmail)
                $user = User::findByEmail($email);
            else
                $user = User::findByMobile($email);

            if($user != null){
                $user->newQuery()->where(['user_id'=> $user->user_id])->update([
                    'password' =>Hash::make($new_password),
                ]);
            }
            else
            {
                $message = trans('messages.msgErrorUserExist');
                $messages = [$message];
                return redirect('/')->with('errors', $messages);
            }

            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect('/')->with('messages', $messages);

        } catch (\Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect('/')->with('errors', $messages);
        }
    }

    public function saveFile(Request $request){
        try
        {
            $success_message =[Lang::get('messages.msgOperationSuccess')];
//            log::info('ta '.$request['tag']);

            if($request['tag']==Tag::TAG_USER_AVATAR)
            {
                if(Utility::checkFileExtention($request) && Utility::checkFileSize($request))
                {
                    Utility::saveFile($request,$request->input('user_id'));

                    return response()->json([
                        'success' => true,
                        'tag' => Tag::TAG_USER_AVATAR,
                        'messages' => $success_message
                    ]);
                }
                else
                {
                    $errors = [
                        Lang::get('messages.msgErrorFileFormatOrSize')
                    ];
                    return response()->json([
                        'success' => false,
                        'tag' => Tag::TAG_USER_AVATAR,
                        'messages' => $errors
                    ]);
                }

            }
            else
            {
                $errors = [trans('messages.msgOperationError')];
                return response()->json([
                    'success' => false,
                    'messages' => $errors,
                    'tag' => $request['tag']
                ]);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return json_encode(['success' => false, 'messages' => $messages, 'tag' => $request['tag']]);

        }
    }

    public function removeUserAvatar( $user_id, $user_guid)
    {
        try
        {
            if (!User::existByIdAndGuid($user_id,$user_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!Utility::isFileExist(Tag::TAG_USER_AVATAR,User::USER_AVATAR_FILE_NAME,$user_id))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Utility::deleteFile(Tag::TAG_USER_AVATAR,User::USER_AVATAR_FILE_NAME,$user_id);
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

    public function downloadFile($user_id, $user_guid, $tag)
    {
        if(User::existByIdAndGuid($user_id,$user_guid) == false)
        {
            return abort(404);
        }
        try
        {
            if($tag==Tag::TAG_USER_AVATAR_DOWNLOAD)
            {
                $fileNameWithoutExtention = User::USER_AVATAR_FILE_NAME;
                if(Utility::isFileExist(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$user_id))
                {
                    return Utility::downloadFile(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$user_id);
                }

            }
            else
            {
                return abort(404);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            return abort(404);
        }
    }

    public function getFile($user_id, $user_guid, $tag)
    {
        if(User::existByIdAndGuid($user_id,$user_guid) == false)
        {
            return abort(404);
        }
        try
        {
            if($tag==Tag::TAG_USER_AVATAR_DOWNLOAD)
            {
                $fileNameWithoutExtention = User::USER_AVATAR_FILE_NAME;
                if(Utility::isFileExist(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$user_id))
                {
                    return Utility::getFile(TAG::TAG_USER_AVATAR, $fileNameWithoutExtention,$user_id);
                }

            }
            else
            {
                return abort(404);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            return abort(404);
        }
    }
}