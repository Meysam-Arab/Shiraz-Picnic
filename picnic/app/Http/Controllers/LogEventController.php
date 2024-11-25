<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;


class LogEventController extends Controller
{
//    public function apiStore(Request $request)
//    {
////        /////////////////////check token
////        $token = null;
////        if (session('tokenRefreshed'))
////            $token = session('token');
////        else
////            $token = JWTAuth::parseToken()->getToken()->get();
////        $user = JWTAuth::parseToken()->authenticate($token);
////        //////////////////////////////
//        //validation
//        if (!$request->has('tag') || !$request->has('controller_and_action_name')||
//            !$request->has('error_message') ) {
//            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_LOG_STORE]);
//
//        }
//        ////////////////////////////////////////////
//        try {
////                $request['user_id'] = $user->user_id;
////                $this->contactUs->intializeByRequest($request);
////                $this->contactUs->store();
//
//            $logEvent = new LogEvent(null," خطا ::". $request['controller_and_action_name'], "Main Message: " . $request['error_message']);
//            $logEvent->store();
//
//            return json_encode([ 'error' => 0, 'tag' => RequestResponseAPI::TAG_LOG_STORE]);
//        } catch (\Exception $ex) {
//            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
//            $logEvent->store();
//
//            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_LOG_STORE]);
//
//        }
//    }
}