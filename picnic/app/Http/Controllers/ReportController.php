<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Report;
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

class ReportController extends Controller
{

    //meysam - for admins - see the list of reports...
    public function index() {

        try
        {
//            Log::info('in report index');

            $reports = DB::table('reports')
                ->where('deleted_at', null)
                ->get();

            return view('report.index', ['reports' => $reports]);

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
    create report get
     */
    public function create()
    {
        return view('report.create');
    }

    /**
    create report get
     */
    public function edit($report_id,$report_guid)
    {
        $report = DB::table('reports')
            ->where('reports.report_id', $report_id)
            ->where('reports.report_guid', $report_guid)
            ->where('deleted_at', null)
            ->first();

        return view('report.edit',['report' => $report]);
    }


    //meysam - for users or public - storing new report ...
    public function store(Request $request)
    {

//        Log::info('meysam1'.json_encode($request->all()));

        $validation = Validator::make($request->all(), [
            'title' => 'required|max:1000',
            'description'=>'required',
        ]);

        try
        {
            if($validation->passes()){

                $report = new Report();

                DB::beginTransaction();
                $report->initializeByRequest($request);
                $report->store();
//                Log::info('report:'.json_encode($report));


                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/reports/index')->with('messages', $messages);

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

    //meysam - for users or public - storing new report ...
    public function update(Request $request)
    {

//        Log::info('meysam1'.json_encode($request->all()));

        $validation = Validator::make($request->all(), [
            'title' => 'required|max:1000',
            'description'=>'required',
        ]);

        try
        {
            if($validation->passes()){

                $report = new Report();

                DB::beginTransaction();

                $report->edit($request);
//                Log::info('report:'.json_encode($report));

                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/reports/index')->with('messages', $messages);

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

    public function detail($report_id,$report_guid){
        try
        {
//            Log::info('$feedback_id:'.json_encode($feedback_id));

            $report = Report::findByIdAndGuid($report_id,$report_guid);
//            Log::info('$feedback:'.json_encode($feedback));
            return view('report.details', ['report' => $report]);
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

    public function remove($report_id,$report_guid){

        try
        {
            if (!Report::existByIdAndGuid($report_id,$report_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            Report::removeByIdAndGuid($report_id,$report_guid);

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