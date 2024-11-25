<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Garden;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use DB;
use Morilog\Jalali\jDateTime;
use Validator;
use Exception;
use App\LogEvent;
use Route;
use Log;
use App\Utilities;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GardenController extends Controller
{
    protected $garden;

    public function __construct(Garden $garden)
    {
        $this->garden = $garden;
    }

    //meysam - for admins - see the list of garden...
    public function listShow($user_id) {

        try
        {

            $user = User::findById($user_id);
            if($user->type == \App\User::TypeOwner)
            {

                $gardens = DB::table('gardens')
                    ->where('gardens.user_id','=',$user->user_id)
                    ->where('gardens.deleted_at','=',null)
                    ->orderBy('garden_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);
                }

//                Log::info(json_encode($reservs));
            }
            else if($user->type == \App\User::TypeGuard)
            {
                $user_id =$user->user_id;
                $gardens = DB::table('gardens')
                    ->join('gardens_guards', function($join) use ( $user_id)
                    {
                        $join->on('gardens_guards.garden_id','=','gardens.garden_id');
                    })
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->where('gardens_guards.deleted_at','=',null)
                    ->where('gardens_guards.user_id','=',$user->user_id)
                    ->whereRaw('gardens.garden_id IN (select gardens_guards.garden_id from gardens where gardens_guards.user_id = ? and gardens_guards.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('gardens.garden_id', 'desc')
                    ->get();

//                Log::info(json_encode($gardens));
//                Log::info(json_encode($user_id));

                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);
                }

            }
            else
            {
                $message = trans('messages.msgErrorUnauthorizedAccess');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            return view('garden.index', ['gardens' => $gardens]);
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

    //meysam - for public - see the list of garden...
    public function listAll() {

        try
        {
                $gardens = DB::table('gardens')
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->orderBy('garden_id', 'desc')
                    ->get();

                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);

                    $fileNameWithoutExtention = Garden::GARDEN_AVATAR_FILE_NAME;
                    if(\App\Utilities\Utility::isFileExist(Tag::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden->garden_id))
                    {
                        $garden->hasAvatarPicture = 1;
                    }
                    else{
                        $garden->hasAvatarPicture = 0;
                    }
                }

            return view('garden.list', ['gardens' => $gardens]);
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

    public function index() {

        try
        {
//            Log::info('in garden index');
//            $this->garden->initialize();
//            $gardens = $this->garden->select('desc', null);

            if(Auth::user()->type == \App\User::TypeOwner)
            {

                $gardens = DB::table('gardens')
                    ->where('gardens.user_id','=',Auth::user()->user_id)
                    ->where('gardens.deleted_at','=',null)
                    ->orderBy('garden_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);
                }

//                Log::info(json_encode($reservs));
            }
            else if(Auth::user()->type == \App\User::TypeAdmin || Auth::user()->type == \App\User::TypeOperator)
            {

                $gardens = DB::table('gardens')
                    ->where('gardens.deleted_at','=',null)
                    ->orderBy('garden_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);
                }

            }
            else if(Auth::user()->type == \App\User::TypeGuard)
            {
                $user_id = Auth::user()->user_id;
                $gardens = DB::table('gardens')
                    ->join('gardens_guards', function($join) use ( $user_id)
                    {
                        $join->on('gardens_guards.garden_id','=','gardens.garden_id');
                    })
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->where('gardens_guards.deleted_at','=',null)
                    ->where('gardens_guards.user_id','=',Auth::user()->user_id)
                    ->whereRaw('gardens.garden_id IN (select gardens_guards.garden_id from gardens where gardens_guards.user_id = ? and gardens_guards.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('gardens.garden_id', 'desc')
                    ->get();

//                Log::info(json_encode($gardens));
//                Log::info(json_encode($user_id));

                foreach($gardens as $garden)
                {
                    $garden->info = json_decode($garden->info);
                    $garden->site_share = json_decode($garden->info->site_share);
                }

            }
            else
            {
                $message = trans('messages.msgErrorUnauthorizedAccess');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            return view('garden.index', ['gardens' => $gardens]);

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

    public function reservs($garden_id,$garden_guid,$offset,$limit) {
        try
        {
            if(!Garden::existByIdAndGuid($garden_id,$garden_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

                $reservs = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->where('transactions.deleted_at','=',null)
                    ->where('reservs.garden_id','=',$garden_id)
                    ->where('reservs.tour_id','=',null)
                    ->where('reservs.deleted_at','=',null)
                    ->select( 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info','reservs.created_at', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

            $total_count = DB::table('reservs')
                ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                ->where('transactions.deleted_at','=',null)
                ->where('reservs.garden_id','=',$garden_id)
                ->where('reservs.tour_id','=',null)
                ->where('reservs.deleted_at','=',null)
                ->select( 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info','reservs.created_at', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                ->orderBy('reserve_id', 'desc')
                ->get()->count();

//                Log::info(json_encode($reservs));
                foreach($reservs as $reserve)
                {
                    $reserve->info = json_decode($reserve->info);
                }

                $garden = Garden::findById($garden_id);

//                Log::info(json_encode($reservs));
                return view('reserve.indexGarden',['reservs' => $reservs, 'garden' => $garden, 'total_count' => $total_count]);

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

    public function transactions($garden_id,$garden_guid) {

        try
        {
            if(!Garden::existByIdAndGuid($garden_id,$garden_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $transactions = DB::table('transactions')
                ->join('reservs', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                ->where('transactions.deleted_at','=',null)
                ->where('reservs.garden_id','=',$garden_id)
                ->where('reservs.tour_id','=',null)
                ->where('reservs.deleted_at','=',null)
                ->select( 'transactions.created_at','transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                ->orderBy('transaction_id', 'desc')
                ->get();

            $garden = Garden::findById($garden_id);
            $garden->info = json_decode($garden->info);

            $totalSiteShare = 0;
            $totalCost = 0;
            foreach($transactions as $transaction)
            {
                $transaction->info = json_decode($transaction->info);
                $totalCost += $transaction->amount;

                $totalSiteShare += (0.01* $garden->info->site_share * $transaction->amount);
            }

//                Log::info(json_encode($reservs));
            return view('transaction.index',['transactions' => $transactions, 'totalCost' => $totalCost, 'garden_id' => $garden_id, 'totalSiteShare' => $totalSiteShare]);

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
    create garden get
     */
    public function create()
    {
        return view('garden.create');
    }

    public function detail($garden_id){
        try
        {

            ///////////////meysam - test/////////////////////////
//            $on_time_holidays = array();
//            $on_time_holiday = new \stdClass();
//            $on_time_holiday->date = "1397-10-20";
//            array_push($on_time_holidays, $on_time_holiday);
//            $on_time_holiday = new \stdClass();
//            $on_time_holiday->date = "1397-10-25";
//            array_push($on_time_holidays, $on_time_holiday);
//            Log::info('on_time_holidays:'.json_encode($on_time_holidays));
//
//
//            $periodic_holidays = array();
//            $periodic_holiday = new \stdClass();
//            $periodic_holiday->day_of_week = "0";
//            array_push($periodic_holidays, $periodic_holiday);
//            $periodic_holiday = new \stdClass();
//            $periodic_holiday->day_of_week = "2";
//            array_push($periodic_holidays, $periodic_holiday);
//            Log::info('periodic_holidays:'.json_encode($periodic_holidays));
//
//            $periodic_costs = array();
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "0";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "1";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "2";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "3";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "4";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "5";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 200000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//            ///////////////////////////////////////
//            $periodic_cost = new \stdClass();
//            $periodic_cost->day_of_week = "6";
//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = 0;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 1;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 300000;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = 2;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->name = Garden::getShiftStringByCode($shift->shift_id);
//            $shift->status = Garden::StatusActive;
//            $shift->cost = 400000;
//            array_push($shifts, $shift);
//
//            $periodic_cost->shifts = $shifts;
//            array_push($periodic_costs, $periodic_cost);
//            ///////////////////////////////////////
//
//            Log::info('periodic_costs:'.json_encode($periodic_costs));

//
//            $socials = array();
//            $social = new \stdClass();
//            $social->name = "telegram";
//            $social->address = "t.me/myzoomit";
//            array_push($socials, $social);
//            $social = new \stdClass();
//            $social->name = "instagram";
//            $social->address = "https://www.instagram.com/td110/";
//            array_push($socials, $social);
//            $social = new \stdClass();
//            $social->name = "soroush";
//            $social->address = "https://sapp.ir/new_tv";
//            array_push($socials, $social);
//            Log::info('socials:'.json_encode($socials));

//
//            $shifts = array();
//            $shift = new \stdClass();
//            $shift->shift_id = Garden::ShiftDayId;
//            $shift->open_time = "08:00";
//            $shift->close_time = "20:00";
//            $shift->status = Garden::StatusActive;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = Garden::ShiftNightId;
//            $shift->open_time = "20:00";
//            $shift->close_time = "08:00";
//            $shift->status = Garden::StatusActive;
//            array_push($shifts, $shift);
//            $shift = new \stdClass();
//            $shift->shift_id = Garden::ShiftFullId;
//            $shift->open_time = "08:00";
//            $shift->close_time = "08:00";
//            $shift->status = Garden::StatusActive;
//            array_push($shifts, $shift);
//            Log::info('shifts:'.json_encode($shifts));

//            $info = new \stdClass();
//            $info->site_share = 10;//in percent...
//            $info->coordination_tel = "09029027302";//tel number for coordination with host...
//            $info->janitor_tel = "09029027302";//janitor tel number...
//            $info->day_reserve_limit = 30;//janitor tel number...
//            Log::info('info:'.json_encode($info));

            /// //////////////////////////////////////////////////

//            $features = new \stdClass();
//            $features->surface_area = 1250;
//
//            $features->pool = true;
//            $features->pool_covered  = true;
//            $features->pool_open  = false;
//            $features->pool_description = "استخر سه در چهار پر عمق";
//
//            $features->garage  = true;
//            $features->garage_open = true;
//            $features->garage_covered = false;
//            $features->garage_capacity  = 4;//-1 for no data
//            $features->garage_description  = "پارکینگ شیک و زیبا";
//
//            $features->arbour = true;
//            $features->arbour_count = 8;
//            $features->arbour_description = "آلاچیق با ویو زیبا";
//
//            $features->wc  = true;
//            $features->wc_western = true;
//            $features->wc_persian = false;
//            $features->wc_count  = 4;//-1 for no data
//            $features->wc_description  = "متن توضیحات";
//
//            $features->building  = true;
//            $features->floor_count  = 8;//-1 for no data
//            $features->elevator  = true;
//            $features->room_count  = 3;
//            $features->building_description  = "متن توضیحات";
//
//            $features->tel  = true;
//            $features->tel_description  = "متن توضیحات";
//
//            $features->bathroom  = true;
//            $features->bathroom_count  = 1;
//            $features->bathroom_description  = "متن توضیحات";
//
//            $features->barbecue  = true;
//            $features->barbecue_description  = "متن توضیحات";
//
//            $features->plumbing = true;
//            $features->plumbing_description  = "متن توضیحات";
//
//            $features->wifi = false;
//            $features->wifi_description  = "متن توضیحات";
//
//            $features->electricity   = true;
//            $features->electricity_description  = "متن توضیحات";
//
//            $features->lighting  = true;
//            $features->lighting_description  = "ندارد";
//
//            $features->sauna  = true;
//            $features->sauna_description  = "متن توضیحات";
//
//            $features->jacuzzi  = true;
//            $features->jacuzzi_description  = "متن توضیحات";
//
//            $features->oven  = true;
//            $features->oven_description  = "متن توضیحات";
//
//            $features->macro  = true;
//            $features->macro_description  = "متن توضیحات";
//
//            $features->sports_equipment  = true;
//            $features->sports_equipment_description  = "متن توضیحات";
//
//            $features->pool_table  = true;
//            $features->pool_table_description  = "متن توضیحات";
//
//            $features->refrigerator  = true;
//            $features->refrigerator_description  = "متن توضیحات";
//
//            $features->vacuum_cleaner  = true;
//            $features->vacuum_cleaner_description  = "متن توضیحات";
//
//            $features->otto  = true;
//            $features->otto_description  = "متن توضیحات";
//
//            $features->terrace  = true;
//            $features->terrace_description  = "متن توضیحات";
//
//            $features->digital_receiver  = true;
//            $features->digital_receiver_description  = "متن توضیحات";
//
//            $features->closet  = true;
//            $features->closet_description  = "متن توضیحات";
//
//            $features->drawer  = true;
//            $features->drawer_description  = "متن توضیحات";
//
//            $features->dining_table = true;
//            $features->dining_table_description  = "متن توضیحات";
//
//            $features->janitor  = true;
//            $features->janitor_outside  = true;
//            $features->janitor_description  = "متن توضیحات";
//
//            $features->guard  = true;
//            $features->guard_outside  = true;
//            $features->guard_description  = "متن توضیحات";
//
//            $features->cooling_system = true;
//            $features->cooling_system_description  = "متن توضیحات";
//
//            $features->heating_system = true;
//            $features->heating_system_description  = "متن توضیحات";
//
//            $features->furniture  = true;
//            $features->furniture_description  = "متن توضیحات";
//
//            $features->television  = true;
//            $features->television_description  = "متن توضیحات";
//
//            $features->washing_machine = true;
//            $features->washing_machine_description  = "متن توضیحات";
//
//            $features->hairdryer  = true;
//            $features->hairdryer_description  = "متن توضیحات";
//
//            $features->volleyball_field  = true;
//            $features->volleyball_field_description  = "متن توضیحات";
//
//            $features->football_field  = true;
//            $features->football_field_description  = "متن توضیحات";
//
//            $features->tennis_field  = true;
//            $features->tennis_field_description  = "متن توضیحات";
//
//            $features->basketball_field  = true;
//            $features->basketball_field_description  = "متن توضیحات";
//
//            $features->foosball  = true;
//            $features->foosball_description  = "متن توضیحات";
//
//            $features->ping_pong  = true;
//            $features->ping_pong_description  = "متن توضیحات";
//
//            Log::info('feature:'.json_encode($features));



            $garden = Garden::findById($garden_id);
            $periodic_holidays = json_decode( $garden->periodic_holidays);
            $on_time_holidays = json_decode( $garden->on_time_holidays);
            $periodic_costs = json_decode( $garden->periodic_costs);
            $socials = json_decode( $garden->social);
            $gardenInfo = json_decode( $garden->info);//contains site share and....
            $features = json_decode( $garden->features);

            if($garden->lat_lon != null)
            {
                $map = json_decode($garden->lat_lon);
                $garden->latitude = $map->latitude;
                $garden->longitude = $map->longitude;
            }
            else
            {
                $garden->latitude = null;
                $garden->longitude = null;
            }
            if($garden->lat_lon != null)
            {
                $map = json_decode($garden->lat_lon);
                $request['latitude'] = $map ->latitude;
                $request['longitude'] = $map ->longitude;
            }

            $fileNameWithoutExtention = Garden::GARDEN_AVATAR_FILE_NAME;
            if(\App\Utilities\Utility::isFileExist(Tag::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden->garden_id))
            {
                $garden->hasAvatarPicture = 1;
            }
            else{
                $garden->hasAvatarPicture = 0;
            }

            $pictures =  json_decode( $garden->media);



            $temp_reserved_dates = DB::table('reservs')
                ->where('garden_id', '=', $garden_id)
                ->where('start_date','>=',Carbon::now()->format('Y-m-d'))
                ->where('deleted_at',null)
                ->get();

//            Log::info('temp_reserved_dates:'.json_encode($temp_reserved_dates));

            $reserved_dates = array();
            if($temp_reserved_dates != null)
            {
                foreach ($temp_reserved_dates as $reserved_date)
                {

                    $info = json_decode($reserved_date->info);
//                    Log::info('$info->reserved_kind:'.json_encode($info->reserved_kind));
                    if(strcmp($info->reserved_kind,Utilities\Utility::convert("1")) == 0 )
                    {
                        //meysam - singular day event...
                        $day_data = new \stdClass();
//                        $day_data->date =$reserved_date->start_date;
                        $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->start_date)); // 1395/02/19
//                        Log::info('reserved_dates:'.json_encode($reserved_dates));
                        $day_data->shift_id = $info->shift_id_1;


                        array_push($reserved_dates, $day_data);
                    }
                    else
                    {
                        //meysam - multiple day event...
                        $day_data = new \stdClass();
//                        $day_data->date =$reserved_date->start_date;
                        $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->start_date)); // 1395/02/19

                        $day_data->shift_id = $info->shift_id_1;


                        array_push($reserved_dates, $day_data);

                        $sDate = Carbon::parse($reserved_date->start_date);
                        $eDate = Carbon::parse($reserved_date->end_date);

//                        $count = 0;
                        while(true)
                        {
//                            $count +=1;
//                            $date = jDate::forge($sDate)->reforge('+ 1 days')->format('Y/M/D');
                            $date = $sDate->addDay(1);
//                            Log::info('$date:'.json_encode($date));
//                            Log::info('$date:'.json_encode($date->toDateString()));
//                            Log::info('$date:'.json_encode($eDate->toDateString()));
                            if(strcmp($date->toDateString(),$eDate->toDateString()) == 0)
                                break;
                            $day_data = new \stdClass();
//                            $day_data->date =$date;
                            $day_data->date = jDateTime::strftime('Y-n-j', strtotime($date->toDateString())); // 1395/02/19
                            $day_data->shift_id = strval(Garden::ShiftFullId);
                            array_push($reserved_dates, $day_data);
                        }
                        $day_data = new \stdClass();
//                        $day_data->date =$reserved_date->end_date;
                        $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->end_date)); // 1395/02/19

                        $day_data->shift_id = $info->shift_id_2;


                        array_push($reserved_dates, $day_data);
                    }
                }
            }
//            Log::info('$reserved_dates:'.json_encode($reserved_dates));
//            $finalDates = array();
//            $finalDateStrings = array();
//            foreach ($reserved_dates as $reserved_date_1)
//            {
//                foreach ($reserved_dates as $reserved_date_2)
//                {
//                    if(strcmp($reserved_date_1->date, $reserved_date_2->date) == 0 && strcmp($reserved_date_1->shift_id, $reserved_date_2->shift_id) != 0)
//                    {
//                        $reserved_date_1->shift_id = strval(Garden::ShiftFullId);
//                    }
//                }
//
//                if (!in_array($reserved_date_1->date, $finalDateStrings))
//                {
//                    array_push($finalDateStrings, $reserved_date_1->date);
//                    array_push($finalDates, $reserved_date_1);
//                }
//
//            }
////
//            $reserved_dates = $finalDates;


//            Log::info('$reserved_dates:'.json_encode($reserved_dates));


//            $garden = new Garden();
//            $garden->name = "نام باغ";
//            $garden->address = "آدرس باغ";

//            Log::info('garden:'.json_encode($garden));
//            Log::info('pictures:'.json_encode($pictures));
//            Log::info('periodic_holidays:'.json_encode($periodic_holidays));
//            Log::info('on_time_holidays:'.json_encode($on_time_holidays));
//            Log::info('periodic_costs:'.json_encode($periodic_costs));
//            Log::info('socials:'.json_encode($socials));
//              Log::info('info:'.json_encode($info));

//            Log::info('media id:'.json_encode($pictures[0]->media_id));
//            Log::info('periodic_holidays:'.var_dump($periodic_holidays));
//            Log::info(json_encode($gardenInfo));
            $today = jDateTime::strftime('Y-m-d', strtotime(Carbon::now()->format('Y-m-d')));
            $reserve_day_limit = jDateTime::strftime('Y-m-d', strtotime(Carbon::now()->addDay($gardenInfo->day_reserve_limit)->format('Y-m-d')));

//            Log::info(json_encode($today));
//            Log::info(json_encode($reserve_day_limit_count));


            return view('garden.details', ['features' => $features,'today' => $today, 'garden' => $garden, 'pictures' => $pictures, 'periodic_holidays' => $periodic_holidays, 'on_time_holidays' => $on_time_holidays, 'periodic_costs' => $periodic_costs, 'socials' => $socials, 'reserved_dates' => $reserved_dates,'reserve_day_limit' => $reserve_day_limit, 'has_map'=>true]);
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

    public function remove($garden_id,$garden_guid){

        try
        {
            if (!Garden::existByIdAndGuid($garden_id,$garden_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            Garden::removeByIdAndGuid($garden_id,$garden_guid);
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

    public function getFile($garden_id, $garden_guid, $media_id = null, $tag)
    {
        if(GARDEN::existByIdAndGuid($garden_id,$garden_guid) == false)
        {
            return abort(404);
        }
        try
        {
//            Log::info('$tag:'.json_encode($tag));

            if($tag==Tag::TAG_GARDEN_AVATAR_DOWNLOAD)
            {
                $fileNameWithoutExtention = GARDEN::GARDEN_AVATAR_FILE_NAME;
//                Log::info('$fileNameWithoutExtention:'.json_encode($fileNameWithoutExtention));
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden_id))
                {
//                    Log::info('$fileNameWithoutExtention:'.json_encode($fileNameWithoutExtention));
                    return \App\Utilities\Utility::getFile(TAG::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden_id);
                }

            }
            if($tag==Tag::TAG_GARDEN_PICTURE_DOWNLOAD)
            {
                $fileNameWithoutExtention = $media_id;
//                Log::info('$fileNameWithoutExtention1:'.json_encode($fileNameWithoutExtention));

                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_PICTURE, $fileNameWithoutExtention,$garden_id))
                {
//                    Log::info('$fileNameWithoutExtention2:'.json_encode($fileNameWithoutExtention));
                    return \App\Utilities\Utility::getFile(TAG::TAG_GARDEN_PICTURE, $fileNameWithoutExtention,$garden_id);
                }

            }
            else if($tag==Tag::TAG_GARDEN_CLIP_DOWNLOAD)
            {
                $fileNameWithoutExtention = $media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id))
                {
                    return \App\Utilities\Utility::getFile(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id);
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

    public function getFileStream($garden_id, $garden_guid, $media_id = null, $tag)
    {
        if(GARDEN::existByIdAndGuid($garden_id,$garden_guid) == false)
        {
            return abort(404);
        }
        try
        {
            if($tag==Tag::TAG_GARDEN_CLIP_DOWNLOAD)
            {
                $fileNameWithoutExtention = $media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id))
                {
                    return \App\Utilities\Utility::getFileStream(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id);
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
    public function removeFile(Request $request){


//        Log::info('in remove:'.json_encode($request->all()));

        $garden_id = $request->input('garden_id');
        $garden_guid= $request->input('garden_guid');
        $media_id = null;
        $media_guid = null;
        if($request->has('media_id'))
            $media_id= $request->input('media_id');
        if($request->has('media_guid'))
            $media_guid= $request->input('media_guid');
        $tag= $request->input('tag');

        $success_message =[trans('messages.msgOperationSuccess')];
        if(GARDEN::existByIdAndGuid($garden_id,$garden_guid) == false)
        {
//            return abort(404);
            $errors = [trans('messages.msgErrorItemNotExist')];
            return response()->json([
                'success' => false,
                'messages' => $errors
            ]);
        }
        try
        {

            if($tag==Tag::TAG_GARDEN_AVATAR_DELETE)
            {
                $fileNameWithoutExtention = GARDEN::GARDEN_AVATAR_FILE_NAME;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden_id))
                {
                    if(\App\Utilities\Utility::deleteFile(TAG::TAG_GARDEN_AVATAR, $fileNameWithoutExtention,$garden_id))
                    {
                        return response()->json([
                            'success' => true,
                            'tag' => Tag::TAG_GARDEN_AVATAR,
                            'messages' => $success_message
                        ]);
                    }
                    else
                    {
                        $errors = [trans('messages.msgOperationError')];
                        return response()->json([
                            'success' => false,
                            'messages' => $errors,
                            'tag' => $tag
                        ]);
                    }
                }

            }
            if($tag==Tag::TAG_GARDEN_PICTURE_DELETE)
            {
                if(Media::existById($media_id) == false)
                {
                    return abort(404);
                }
                $fileNameWithoutExtention = $media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_PICTURE, $fileNameWithoutExtention,$garden_id))
                {
                    Media::removeByIdAndGuid($media_id,$media_guid);
                    if(\App\Utilities\Utility::deleteFile(TAG::TAG_GARDEN_PICTURE, $fileNameWithoutExtention,$garden_id))
                    {
                        return response()->json([
                            'success' => true,
                            'tag' => Tag::TAG_GARDEN_PICTURE,
                            'messages' => $success_message
                        ]);
                    }
                    else
                    {
                        $errors = [trans('messages.msgOperationError')];
                        return response()->json([
                            'success' => false,
                            'messages' => $errors,
                            'tag' => $tag
                        ]);
                    }
                }

            }
            if($tag==Tag::TAG_GARDEN_CLIP_DELETE)
            {
                if(Media::existById($media_id) == false)
                {
                    return abort(404);
                }
                $fileNameWithoutExtention = $media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id))
                {
                    Media::removeByIdAndGuid($media_id,$media_guid);
                    if(\App\Utilities\Utility::deleteFile(TAG::TAG_GARDEN_CLIP, $fileNameWithoutExtention,$garden_id))
                    {
                        return response()->json([
                            'success' => true,
                            'tag' => Tag::TAG_GARDEN_CLIP,
                            'messages' => $success_message
                        ]);
                    }
                    else
                    {
                        $errors = [trans('messages.msgOperationError')];
                        return response()->json([
                            'success' => false,
                            'messages' => $errors,
                            'tag' => $tag
                        ]);
                    }
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