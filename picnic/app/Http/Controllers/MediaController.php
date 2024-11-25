<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Media;
use App\User;
use Illuminate\Http\Request;
use App\OperationMessage;
use DB;
use Validator;
use Exception;
use App\LogEvent;
use Route;
use Log;
use App\Utility;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function remove($media_id,$media_guid){

        try
        {
            if (!Media::existByIdAndGuid($media_id,$media_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Media::removeByIdAndGuid($media_id,$media_guid);
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