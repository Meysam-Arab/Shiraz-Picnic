<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Garden;
use App\Reserve;
use App\Tour;
use App\TourDiscount;
use App\Transaction;
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

class ReserveController extends Controller
{
    protected $reserve;

    public function __construct(Reserve $reserve)
    {
        $this->reserve = $reserve;
    }


    //meysam - for admins - see the list of reservs...
    public function index($offset,$limit) {

        try
        {
            $offset = 0;
            $limit = 500;
//            Log::info('in reserve index');

            if(Auth::user()->type == \App\User::TypeOwner)
            {

                $reservs = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->leftJoin('gardens', function($join)
                    {
                        $join->on('gardens.garden_id','=','reservs.garden_id')
                            ->where('gardens.user_id','=',Auth::user()->user_id)
                            ->where('gardens.deleted_at','=',null)
                            ->whereRaw('reservs.garden_id IN (select gardens.garden_id from gardens where gardens.user_id = ? and gardens.deleted_at is null )', [Auth::user()->user_id]);
                    })
                    ->leftJoin('tours', function($join)
                    {
                        $join->on('tours.tour_id','=','reservs.tour_id')
                            ->where('tours.user_id','=',Auth::user()->user_id)
                            ->where('tours.deleted_at','=',null)
                            ->whereRaw('reservs.tour_id IN (select tours.tour_id from tours where tours.user_id = ? and tours.deleted_at is null )', [Auth::user()->user_id]);

                    })
                    ->where('reservs.deleted_at','=',null)
                    ->where('transactions.deleted_at','=',null)
                    ->select('gardens.info as garden_info','gardens.name as garden_name','gardens.address as garden_address','tours.info as tour_info','tours.title as tour_title','tours.tour_address as tour_address', 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'reservs.garden_id', 'reservs.tour_id', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

                $total_count = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->leftJoin('gardens', function($join)
                    {
                        $join->on('gardens.garden_id','=','reservs.garden_id')
                            ->where('gardens.user_id','=',Auth::user()->user_id)
                            ->where('gardens.deleted_at','=',null)
                            ->whereRaw('reservs.garden_id IN (select gardens.garden_id from gardens where gardens.user_id = ? and gardens.deleted_at is null )', [Auth::user()->user_id]);
                    })
                    ->leftJoin('tours', function($join)
                    {
                        $join->on('tours.tour_id','=','reservs.tour_id')
                            ->where('tours.user_id','=',Auth::user()->user_id)
                            ->where('tours.deleted_at','=',null)
                            ->whereRaw('reservs.tour_id IN (select tours.tour_id from tours where tours.user_id = ? and tours.deleted_at is null )', [Auth::user()->user_id]);

                    })
                    ->where('reservs.deleted_at','=',null)
                    ->where('transactions.deleted_at','=',null)
                    ->select('gardens.info as garden_info','gardens.name as garden_name','gardens.address as garden_address','tours.info as tour_info','tours.title as tour_title','tours.tour_address as tour_address', 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'reservs.garden_id', 'reservs.tour_id', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get()->count();

//                Log::info('1:'.json_encode($reservs));
                foreach($reservs as $reserve)
                {
                    $reserve->info = json_decode($reserve->info);
                    if($reserve->garden_id != null)
                    {
                        $reserve->garden_info = json_decode($reserve->garden_info);
                        $reserve->site_share = json_decode($reserve->garden_info->site_share);

                        $reserve->title = $reserve->garden_name;
                        $reserve->address = $reserve->garden_address;

                        $reserve->name_and_family = $reserve->info->name_and_family;
                        $reserve->national_code =$reserve->info->national_code;
                    }
                    else
                    {
                        $reserve->tour_info = json_decode($reserve->tour_info);
                        $reserve->site_share = json_decode($reserve->tour_info->site_share);

                        $reserve->tour_address = json_decode($reserve->tour_address );
                        $reserve->tour_address = $reserve->tour_address->address;

                        $reserve->title = $reserve->tour_title;
                        $reserve->address = $reserve->tour_address;


                        $reserve->name_and_family = $reserve->info->persons[0]->name_and_family;
                        $reserve->national_code =$reserve->info->persons[0]->national_code;
                    }

                }

//                Log::info('2:'.json_encode($reservs));
            }
            if(Auth::user()->type == \App\User::TypeAdmin || Auth::user()->type == \App\User::TypeOperator)
            {

                $reservs = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->leftJoin('gardens', function($join)
                    {
                        $join->on('gardens.garden_id','=','reservs.garden_id')
                            ->where('gardens.deleted_at','=',null);
                    })
                    ->leftJoin('tours', function($join)
                    {
                        $join->on('tours.tour_id','=','reservs.tour_id')
                            ->where('tours.deleted_at','=',null);

                    })
                    ->where('reservs.deleted_at','=',null)
                    ->where('transactions.deleted_at','=',null)
                    ->select('gardens.info as garden_info','gardens.name as garden_name','gardens.address as garden_address','tours.info as tour_info','tours.title as tour_title','tours.tour_address as tour_address', 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'reservs.garden_id', 'reservs.tour_id', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

                $total_count = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->leftJoin('gardens', function($join)
                    {
                        $join->on('gardens.garden_id','=','reservs.garden_id')
                            ->where('gardens.deleted_at','=',null);
                    })
                    ->leftJoin('tours', function($join)
                    {
                        $join->on('tours.tour_id','=','reservs.tour_id')
                            ->where('tours.deleted_at','=',null);

                    })
                    ->where('reservs.deleted_at','=',null)
                    ->where('transactions.deleted_at','=',null)
                    ->select('gardens.info as garden_info','gardens.name as garden_name','gardens.address as garden_address','tours.info as tour_info','tours.title as tour_title','tours.tour_address as tour_address', 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date', 'reservs.end_date', 'reservs.status', 'reservs.garden_id', 'reservs.tour_id', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get()->count();

//                Log::info('1:'.json_encode($reservs));
                foreach($reservs as $reserve)
                {
                    $reserve->info = json_decode($reserve->info);
                    if($reserve->garden_id != null)
                    {
                        $reserve->garden_info = json_decode($reserve->garden_info);
                        $reserve->site_share = json_decode($reserve->garden_info->site_share);

                        $reserve->title = $reserve->garden_name;
                        $reserve->address = $reserve->garden_address;

                        $reserve->name_and_family = $reserve->info->name_and_family;
                        $reserve->national_code =$reserve->info->national_code;
                    }
                    else
                    {
                        $reserve->tour_info = json_decode($reserve->tour_info);
                        $reserve->site_share = json_decode($reserve->tour_info->site_share);

                        $reserve->tour_address = json_decode($reserve->tour_address );
                        $reserve->tour_address = $reserve->tour_address->address;

                        $reserve->title = $reserve->tour_title;
                        $reserve->address = $reserve->tour_address;


                        $reserve->name_and_family = $reserve->info->persons[0]->name_and_family;
                        $reserve->national_code =$reserve->info->persons[0]->national_code;
                    }

                }
            }
            if(Auth::user()->type == \App\User::TypeGuard)
            {
                $user_id = Auth::user()->user_id;
                $reservs = DB::table('reservs')
                    ->join('gardens', 'gardens.garden_id', '=', 'reservs.garden_id')
                    ->join('gardens_guards', function($join) use ( $user_id)
                    {
                        $join->on('gardens_guards.garden_id','=','reservs.garden_id');
                    })
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->where('gardens_guards.deleted_at','=',null)
                    ->where('gardens_guards.user_id','=',Auth::user()->user_id)
                    ->where('reservs.deleted_at','=',null)
                    ->whereRaw('reservs.garden_id IN (select gardens_guards.garden_id from gardens where gardens_guards.user_id = ? and gardens_guards.deleted_at is null )', [Auth::user()->user_id])
                    ->select('gardens.info as garden_info','gardens.name','gardens.address','reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date', 'reservs.end_date','reservs.garden_id', 'reservs.tour_id', 'reservs.status')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

                $total_count = DB::table('reservs')
                    ->join('gardens', 'gardens.garden_id', '=', 'reservs.garden_id')
                    ->join('gardens_guards', function($join) use ( $user_id)
                    {
                        $join->on('gardens_guards.garden_id','=','reservs.garden_id');
                    })
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->where('gardens_guards.deleted_at','=',null)
                    ->where('gardens_guards.user_id','=',Auth::user()->user_id)
                    ->where('reservs.deleted_at','=',null)
                    ->whereRaw('reservs.garden_id IN (select gardens_guards.garden_id from gardens where gardens_guards.user_id = ? and gardens_guards.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('reserve_id', 'desc')
                    ->get()->count();

                foreach($reservs as $reserve)
                {
                    $reserve->info = json_decode($reserve->info);

                        $reserve->garden_info = json_decode($reserve->garden_info);
                        $reserve->site_share = json_decode($reserve->garden_info->site_share);

                        $reserve->title = $reserve->garden_name;
                        $reserve->address = $reserve->garden_address;

                        $reserve->name_and_family = $reserve->info->name_and_family;
                        $reserve->national_code =$reserve->info->national_code;


                }

            }
            if(Auth::user()->type == \App\User::TypeLeader)
            {
                $user_id = Auth::user()->user_id;
                $reservs = DB::table('reservs')
                    ->join('tours', 'tours.tour_id', '=', 'reservs.tour_id')
                    ->join('transactions', 'transactions.reserve_id', '=', 'reservs.reserve_id')
                    ->join('tours_leaders', function($join) use ( $user_id)
                    {
                        $join->on('tours_leaders.tour_id','=','reservs.tour_id');
                    })
                    ->where('tours.deleted_at','=',null)
                    ->where('tours.status','=',Tour::StatusActive)
                    ->where('tours_leaders.deleted_at','=',null)
                    ->where('tours_leaders.user_id','=',Auth::user()->user_id)
                    ->where('reservs.deleted_at','=',null)
                    ->whereRaw('reservs.tour_id IN (select tours_leaders.tour_id from tours where tours_leaders.user_id = ? and tours_leaders.deleted_at is null )', [Auth::user()->user_id])
                    ->select('transactions.amount','tours.info as tour_info','tours.title as tour_title','tours.tour_address as tour_address','reservs.reserve_id','reservs.reserve_guid', 'reservs.info', 'reservs.start_date','reservs.garden_id', 'reservs.tour_id', 'reservs.end_date', 'reservs.status')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

                $total_count = DB::table('reservs')
                    ->join('gardens', 'gardens.garden_id', '=', 'reservs.garden_id')
                    ->join('gardens_guards', function($join) use ( $user_id)
                    {
                        $join->on('gardens_guards.garden_id','=','reservs.garden_id');
                    })
                    ->where('gardens.deleted_at','=',null)
                    ->where('gardens.status','=',Garden::StatusActive)
                    ->where('gardens_guards.deleted_at','=',null)
                    ->where('gardens_guards.user_id','=',Auth::user()->user_id)
                    ->where('reservs.deleted_at','=',null)
                    ->whereRaw('reservs.garden_id IN (select gardens_guards.garden_id from gardens where gardens_guards.user_id = ? and gardens_guards.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('reserve_id', 'desc')
                    ->get()->count();

            }


            return view('reserve.index', ['reservs' => $reservs, 'total_count' => $total_count]);

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

    public function listShow($user_id)
    {
        try {

            $reserve = new Reserve();
            $reserve->user_id =$user_id;
            $reservs = $reserve->select('desc', null, null);
            foreach ($reservs as $reserve) {
                $transaction = DB::table('transactions')
                    ->where('transactions.user_id','=',$user_id)
                    ->where('transactions.info','like',"%".QR::StatusActive."%")
                    ->where('transactions.deleted_at','=',null)
//                ->select('transactions.transaction_id','transactions.transaction_guid', 'transactions.amount', 'transactions.description', 'transactions.authority', 'trans_qrs.father_name', 'families.type', 'families.family_id', 'families.family_guid', 'families.status', 'families.qr_id_1', 'families.qr_id_2')
//                ->orderBy('transactions', 'desc')
                    ->get()->first();
                $reserve->trasnaction = $transaction;

                $info = json_decode($reserve->info);
                $reserve->name_and_family = $info->name." ".$info->family;
            }


            return view('reserve.list', ['reservs' =>$reservs]);

        } catch (Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

    }


    /**
    create reserve get
     */
    public function create()
    {
        return view('reserve.create');
    }

    public function detail($reserve_id,$reserve_guid){
        try
        {
            $reserve = Reserve::findByIdAndGuid($reserve_id,$reserve_guid);
            $reserve->info = json_decode($reserve->info);
            $reserve->start_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->start_date)); // 1395/02/19
            $reserve->end_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->end_date)); // 1395/02/19

            $url = url('/reservs/detail/'.$reserve->reserve_id.'/'.$reserve->reserve_guid);
            $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($url);
            $png = base64_encode($png);

            $garden = null;
            $tour = null;
            if($reserve->garden_id != null)
            {
                $garden = Garden::findById($reserve->garden_id);
                $garden->info = json_decode($garden->info);
                return view('reserve.details_garden', ['reserve' => $reserve, 'garden' => $garden, 'png' => $png]);

            }
            else
            {
                $tour = Tour::findById($reserve->tour_id);
                $tour->social = json_decode( $tour->social);
                $tour->info = json_decode($tour->info);
//                $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
//                $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
                $tour->tour_address = json_decode($tour->tour_address );
//                $tour->address = $tour->tour_address->address;

//                Log::info('$reserve->info:'.json_encode($reserve->info));
                $discount = null;
                if(isset($reserve->info->tour_discount_id))
                {
                    $discount = TourDiscount::findById($reserve->info->tour_discount_id);
                }
//                Log::info('$discount:'.json_encode($discount));

                return view('reserve.details_tour', ['reserve' => $reserve, 'tour' => $tour, 'png' => $png, 'discount' => $discount]);

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

    public function remove($reserve_id,$reserve_guid){

        try
        {
            if (!Reserve::existByIdAndGuid($reserve_id,$reserve_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            Reserve::removeByIdAndGuid($reserve_id,$reserve_guid);
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