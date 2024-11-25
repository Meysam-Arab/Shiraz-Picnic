<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Feature;
use App\FeedBack;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use Validator;
use Exception;
use App\LogEvent;
use Route;
use Log;
use App\Utilities;
use Illuminate\Support\Facades\Auth;
use App\Utilities\Utility;

class FeatureController extends Controller
{

    //meysam - for admins - see the list of features...
    public function index() {

        try
        {
//            Log::info('in feature index');

            $features = DB::table('features')
                ->where('deleted_at', null)
                ->get();

            return view('feature.index', ['features' => $features]);

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
    create feature get
     */
    public function create()
    {
        return view('feature.create');
    }

    /**
    create feature get
     */
    public function edit($feature_id,$feature_guid)
    {
        $feature = DB::table('features')
            ->where('features.feature_id', $feature_id)
            ->where('features.feature_guid', $feature_guid)
            ->where('deleted_at', null)
            ->first();

        return view('feature.edit',['feature' => $feature]);
    }


    //meysam - for users or public - storing new feature ...
    public function store(Request $request)
    {

//        Log::info('meysam1'.json_encode($request->all()));

        $validation = Validator::make($request->all(), [
            'name' => 'required|max:500',
            'icon'=>'required',
        ]);

        try
        {
            if($validation->passes()){

                $feature = new Feature();

//                if(strcmp($request ->input('capacity'),"") == 0)
//                    unset($request['capacity']);
//                if(strcmp($request ->input('cost'),"") == 0)
//                    unset($request['cost']);
//                if(strcmp($request ->input('count'),"") == 0)
//                    unset($request['count']);

                DB::beginTransaction();
                $feature->initializeByRequest($request);
                $feature->store();
//                Log::info('feature:'.json_encode($feature));

                //meysam - save file if exist
                if($request->hasFile('icon'))
                {
                    $request['tag'] = Tag::TAG_FEATURE_ICON;
                    Utilities\Utility::saveFile($request,$feature->feature_id);
                }

                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/features/index')->with('messages', $messages);

            }
            else
                {
                $messages = $validation->errors()->all();
                return redirect()->back()->with('messages', $messages);
            }
        }
        catch (Exception $e)
        {
            DB::rollback();

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    //meysam - for users or public - storing new feature ...
    public function update(Request $request)
    {

//        Log::info('meysam1'.json_encode($request->all()));

        $validation = Validator::make($request->all(), [
            'name' => 'required|max:500',
        ]);

        try
        {
            if($validation->passes()){

                $feature = new Feature();

//                if(strcmp($request ->input('capacity'),"") == 0)
//                    unset($request['capacity']);
//                if(strcmp($request ->input('cost'),"") == 0)
//                    unset($request['cost']);
//                if(strcmp($request ->input('count'),"") == 0)
//                    unset($request['count']);

                DB::beginTransaction();

                $feature->edit($request);
//                Log::info('feature:'.json_encode($feature));


                //meysam - save file if exist
                if($request->hasFile('icon'))
                {
                    $request['tag'] = Tag::TAG_FEATURE_ICON;
                    $fileNameWithoutExtention = Feature::FEATURE_ICON_FILE_NAME;
                    if(Utilities\Utility::isFileExist(TAG::TAG_FEATURE_ICON, $fileNameWithoutExtention,$request->input('feature_id')))
                    {
                        Utilities\Utility::deleteFile(TAG::TAG_FEATURE_ICON, $fileNameWithoutExtention,$request->input('feature_id'));
                    }
                    Utilities\Utility::saveFile($request,$request->input('feature_id'));
                }

                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/features/index')->with('messages', $messages);

            }
            else
            {
                $messages = $validation->errors()->all();
                return redirect()->back()->with('messages', $messages);
            }
        }
        catch (Exception $e)
        {
            DB::rollback();

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    public function detail($feature_id,$feature_guid){
        try
        {
//            Log::info('$feedback_id:'.json_encode($feedback_id));

            $feature = Feature::findByIdAndGuid($feature_id,$feature_guid);
//            Log::info('$feedback:'.json_encode($feedback));
            return view('feature.details', ['feature' => $feature]);
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

    public function remove($feature_id,$feature_guid){

        try
        {
            if (!Feature::existByIdAndGuid($feature_id,$feature_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            Feature::removeByIdAndGuid($feature_id,$feature_guid);

            if(Utilities\Utility::isFileExist(Tag::TAG_FEATURE_ICON,Feature::FEATURE_ICON_FILE_NAME,$feature_id))
            {
                Utilities\Utility::deleteFile(Tag::TAG_FEATURE_ICON,Feature::FEATURE_ICON_FILE_NAME,$feature_id);
            }

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

    public function getFile($feature_id, $feature_guid, $tag)
    {
        if(Feature::existByIdAndGuid($feature_id,$feature_guid) == false)
        {
            return abort(404);
        }
        try
        {
            if($tag==Tag::TAG_FEATURE_ICON_DOWNLOAD)
            {
                $fileNameWithoutExtention = Feature::FEATURE_ICON_FILE_NAME;
                if(Utility::isFileExist(TAG::TAG_FEATURE_ICON, $fileNameWithoutExtention,$feature_id))
                {
                    return Utility::getFile(TAG::TAG_FEATURE_ICON, $fileNameWithoutExtention,$feature_id);
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