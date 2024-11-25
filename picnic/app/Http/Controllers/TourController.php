<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:18 PM
 */

namespace App\Http\Controllers;

use App\Media;
use App\Tour;
use App\Tag;
use App\TourDiscount;
use App\TourFeature;
use App\TourLeader;
use App\TourReport;
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

class TourController extends Controller
{


    public function __construct(Tour $tour)
    {

    }

    //meysam - for admins - see the list of tour...
    public function listShow($user_id) {

        try
        {

            $user = User::findById($user_id);
            if($user->type == \App\User::TypeOwner)
            {

                $tours = DB::table('tours')
                    ->where('tours.user_id','=',$user->user_id)
                    ->where('tours.deleted_at','=',null)
                    ->orderBy('tour_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($tours as $tour)
                {
                    $tour->info = json_decode($tour->info);
                    $tour->site_share = json_decode($tour->info->site_share);
                    $tour->tour_address = json_decode($tour->tour_address);
                }

//                Log::info(json_encode($reservs));
            }
            else if($user->type == \App\User::TypeLeader)
            {
                $user_id =$user->user_id;
                $tours = DB::table('tours')
                    ->join('tours_leaders', function($join) use ( $user_id)
                    {
                        $join->on('tours_leaders.tour_id','=','tours.tour_id');
                    })
                    ->where('tours.deleted_at','=',null)
                    ->where('tours.status','=',Tour::StatusActive)
                    ->where('tours_leaders.deleted_at','=',null)
                    ->where('tours_leaders.user_id','=',$user->user_id)
                    ->whereRaw('tours.tour_id IN (select tours_leaders.tour_id from tours where tours_leaders.user_id = ? and tours_leaders.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('tours.tour_id', 'desc')
                    ->get();

//                Log::info(json_encode($tours));
//                Log::info(json_encode($user_id));

                foreach($tours as $tour)
                {
                    $tour->info = json_decode($tour->info);
                    $tour->site_share = json_decode($tour->info->site_share);
                    $tour->tour_address = json_decode($tour->tour_address);

                    $tour->owner = User::findById($tour->user_id);

                    $tour->miladi_start_date_time = $tour->start_date_time;
                    $tour->miladi_end_date_time = $tour->end_date_time;
                    $tour->miladi_deadline_date_time = $tour->deadline_date_time;


                    $tour->start_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->start_date_time));
                    $tour->end_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->end_date_time));
                    $tour->deadline_date_time = jDateTime::strftime('Y/n/j', strtotime($tour->deadline_date_time));
                }

            }
            else
            {
                $message = trans('messages.msgErrorUnauthorizedAccess');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            return view('tour.index', ['tours' => $tours]);
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

    //meysam - for public - see the list of tour...
    public function listAll() {

        try
        {
            $tours = DB::table('tours')
                ->where('tours.deleted_at','=',null)
                ->where('tours.status','=',Tour::StatusActive)
                ->orderBy('tour_id', 'desc')
                ->get();

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

            return view('tour.list', ['tours' => $tours]);
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
//            Log::info('in tour index');

            if(Auth::user()->type == \App\User::TypeOwner)
            {

                $tours = DB::table('tours')
                    ->where('tours.user_id','=',Auth::user()->user_id)
                    ->where('tours.deleted_at','=',null)
                    ->orderBy('tour_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($tours as $tour)
                {
                    $tour->info = json_decode($tour->info);
                    $tour->site_share = json_decode($tour->info->site_share);
                    $tour->tour_address = json_decode($tour->tour_address );
//                    $tour->address = $tour->tour_address->address;

                    $tour->owner = User::findById($tour->user_id);

                }

//                Log::info(json_encode($reservs));
            }
            else if(Auth::user()->type == \App\User::TypeAdmin || Auth::user()->type == \App\User::TypeOperator)
            {

                $tours = DB::table('tours')
                    ->where('tours.deleted_at','=',null)
                    ->orderBy('tour_id', 'desc')
                    ->get();

//                Log::info(json_encode($reservs));
                foreach($tours as $tour)
                {
                    $tour->info = json_decode($tour->info);
                    $tour->site_share = json_decode($tour->info->site_share);
                    $tour->tour_address = json_decode($tour->tour_address );
//                    $tour->address = $tour->tour_address->address;

                    $tour->owner = User::findById($tour->user_id);

                }

            }
            else if(Auth::user()->type == \App\User::TypeLeader)
            {
                $user_id = Auth::user()->user_id;
                $tours = DB::table('tours')
                    ->join('tours_leaders', function($join) use ( $user_id)
                    {
                        $join->on('tours_leaders.tour_id','=','tours.tour_id');
                    })
                    ->where('tours.deleted_at','=',null)
                    ->where('tours.status','=',Tour::StatusActive)
                    ->where('tours_leaders.deleted_at','=',null)
                    ->where('tours_leaders.user_id','=',Auth::user()->user_id)
                    ->whereRaw('tours.tour_id IN (select tours_leaders.tour_id from tours where tours_leaders.user_id = ? and tours_leaders.deleted_at is null )', [Auth::user()->user_id])
                    ->orderBy('tours.tour_id', 'desc')
                    ->get();

//                Log::info(json_encode($tours));
//                Log::info(json_encode($user_id));

                foreach($tours as $tour)
                {
                    $tour->info = json_decode($tour->info);
                    $tour->site_share = json_decode($tour->info->site_share);
                    $tour->tour_address = json_decode($tour->tour_address );
//                    $tour->address = $tour->tour_address->address;

                    $tour->owner = User::findById($tour->user_id);

                }

            }
            else
            {
                $message = trans('messages.msgErrorUnauthorizedAccess');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            return view('tour.index', ['tours' => $tours]);

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

    public function reservs($tour_id,$tour_guid,$offset,$limit) {
        try
        {
            if(!Tour::existByIdAndGuid($tour_id,$tour_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

                $reservs = DB::table('reservs')
                    ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->where('transactions.deleted_at','=',null)
                    ->where('reservs.tour_id','=',$tour_id)
                    ->where('reservs.garden_id','=',null)
                    ->where('reservs.deleted_at','=',null)
                    ->select( 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info','reservs.created_at', 'reservs.status', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('reserve_id', 'desc')
                    ->skip($offset*$limit)->take($limit)
                    ->get();

//            $total_count = DB::table('reservs')
//                ->join('transactions', 'reservs.reserve_id', '=', 'transactions.reserve_id')
//                ->where('transactions.deleted_at','=',null)
//                ->where('reservs.tour_id','=',$tour_id)
//                ->where('reservs.garden_id','=',null)
//                ->where('reservs.deleted_at','=',null)
//                ->select( 'reservs.reserve_id','reservs.reserve_guid', 'reservs.info','reservs.created_at', 'reservs.status', 'transactions.amount', 'transactions.transaction_id', 'transactions.transaction_guid')
//                ->orderBy('reserve_id', 'desc')
//                ->get()->count();

            foreach($reservs as $reserve)
            {
                $reserve->info = json_decode($reserve->info);
            }
            $total_count = Tour::getTotalNumberOfPersons($reservs);

//                Log::info(json_encode($reservs));


            $tour = Tour::findById($tour_id);
            $tour->info = json_decode($tour->info);

            $total_optional_info = Tour::getTotalOptionalFeatures($reservs, $tour->tour_id);

//                Log::info(json_encode($total_optional_info));
            return view('reserve.indexTour',['reservs' => $reservs, 'tour' => $tour, 'total_count' => $total_count, 'total_optional_info' => $total_optional_info]);

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

    public function transactions($tour_id,$tour_guid) {

        try
        {
            if(!Tour::existByIdAndGuid($tour_id,$tour_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $transactions = DB::table('transactions')
                ->join('reservs', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                ->where('transactions.deleted_at','=',null)
                ->where('reservs.tour_id','=',$tour_id)
                ->where('reservs.garden_id','=',null)
                ->where('reservs.deleted_at','=',null)
                ->select( 'transactions.reserve_id','transactions.created_at','transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                ->orderBy('transaction_id', 'desc')
                ->get();


            $tour =Tour::findById($tour_id);
            $tour->info = json_decode($tour->info);

            $totalSiteShare = 0;
            $totalCost = 0;
            foreach($transactions as $transaction)
            {
                $transaction->info = json_decode($transaction->info);
                $totalCost += $transaction->amount;

                $totalSiteShare += (0.01* $tour->info->site_share * $transaction->amount);
            }


//                Log::info(json_encode($reservs));
            return view('transaction.index',['transactions' => $transactions, 'totalCost' => $totalCost, 'tour_id' => $tour_id, 'totalSiteShare'=> $totalSiteShare]);

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
    create tour get
     */
    public function create()
    {

        $features = DB::table('features')
            ->where('deleted_at', null)
            ->get();

        $leaders = DB::table('users')
            ->where('type', User::TypeLeader)
            ->where('deleted_at', null)
            ->get();

        $owners = null;
        if(Auth::user()->type == \App\User::TypeAdmin
        || Auth::user()->type == \App\User::TypeOperator)
        {
            $owners = DB::table('users')
                ->where('type', User::TypeOwner)
                ->where('deleted_at', null)
                ->get();
        }

        return view('tour.create',['features' => $features, 'leaders' => $leaders, 'owners' => $owners]);
    }

    public function store(Request $request)
    {
//        Log::info('meysam store tour: '.json_encode($request->all()));
//        return redirect()->back();

        $validation = Validator::make($request->all(), [
            'start_point_lat' => 'required',
            'start_point_lon'=>'required',
            'end_point_lat'=>'required',
            'end_point_lon'=>'required',
            'title'=>'required',
            'description'=>'required',
            'total_capacity'=>'required',
            'remaining_capacity'=>'required',
            'start_date_time'=>'required',
            'end_date_time'=>'required',
            'deadline_date_time'=>'required',
            'cost'=>'required',
            'coordination_tel'=>'required',
            'minimum_age'=>'required',
            'maximum_age'=>'required',
            'gender'=>'required',
            'hardship_level'=>'required',
            'start_address_0'=>'required',
            'end_address_0'=>'required',
        ]);


        //meysam - avatar file if exist
        if(!$request->hasFile('avatar_picture'))
        {
            $message = trans('messages.msgErrorNoBanner');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        //meysam - pictures if exist
        if($request->hasFile('picture'))
        {
            $files = $request->file('picture');
            if(count($files) < 3)
            {
                $message = trans('messages.msgErrorAtLeastThreePicture');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
        }
        else
        {
            $message = trans('messages.msgErrorAtLeastThreePicture');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }



        try
        {
            if($validation->passes()){

                $tour = new Tour();

                DB::beginTransaction();
                $tour->initialize();


                $tour -> tour_id = null;
                $tour -> tour_guid = null;

                if(Auth::user()->type == \App\User::TypeAdmin ||
                    Auth::user()->type == \App\User::TypeOperator)
                {
                    $tour -> user_id = $request ->input('owner');
                    $tour -> status  = $request ->input('status');
                    $siteShare = $request ->input('site_share');
                }
                else
                {
                    $tour -> user_id = Auth::user()->user_id;
                    $tour -> status  = Tour::StatusWaitForCheck;
                    $siteShare = Tour::TourSiteShare;

                }

                $tour -> title = $request ->input('title');
                $tour -> description  = $request ->input('description');
                $tour -> total_capacity = $request ->input('total_capacity');
                $tour -> remaining_capacity = $request ->input('remaining_capacity');
                $tour -> start_date_time  = $request ->input('start_date_time');
                $tour -> end_date_time  = $request ->input('end_date_time');
                $tour -> deadline_date_time  = $request ->input('deadline_date_time');
                $tour -> minimum_age = $request ->input('minimum_age');
                $tour -> maximum_age = $request ->input('maximum_age');
                $tour -> like_count = 0;
                $tour -> gender  = $request ->input('gender');
                $tour -> hardship_level = $request ->input('hardship_level');
                $tour -> cost  = $request ->input('cost');

                if($request ->has('stroked_cost'))
                    $tour->stroked_cost = $request ->input('stroked_cost');








                //meysam - create tour_address json
                $tourAddress = new \stdClass();
//                $tourAddress->address = $request ->input('end_address');
//                $tourAddress->latitude = $request ->input('end_point_lat');
//                $tourAddress->longitude = $request ->input('end_point_lon');
                $tourAddress->description = $request ->input('end_description');
//                $tour -> tour_address = json_encode($tourAddress);


                $endAddresses = array();
                $endAddressCount = 0;
                $requestEndAddressName = "end_address_".$endAddressCount;
                while($request->has($requestEndAddressName))
                {
                    $endAddress = new \stdClass();
                    $endAddress->address =  $request->input($requestEndAddressName);

                    if($request->has("destination_start_date_time_".$endAddressCount))
                        $endAddress->start_date_time =  $request->input("destination_start_date_time_".$endAddressCount);
                    else
                        $endAddress->start_date_time =  Carbon::now()->toDateTimeString();

                    if($request->has("destination_end_date_time_".$endAddressCount))
                        $endAddress->end_date_time =  $request->input("destination_end_date_time_".$endAddressCount);
                    else
                        $endAddress->end_date_time =  Carbon::now()->toDateTimeString();

                    if($request ->has('end_point_lat'))
                        $endAddress->latitude = $request ->input('end_point_lat');
                    else
                        $endAddress->latitude = "";
                    if($request ->has('end_point_lon'))
                        $endAddress->longitude = $request ->input('end_point_lon');
                    else
                        $endAddress->longitude = "";

                            array_push($endAddresses, $endAddress);

                    $endAddressCount++;
                    $requestEndAddressName = "end_address_".$endAddressCount;
                }
                $tourAddress->address = $endAddresses;
                $tour -> tour_address = json_encode($tourAddress);









                //meysam - create gathering_place json
                $gatheringPlace = new \stdClass();
//                $gatheringPlace->address = $request ->input('start_address');
//                $gatheringPlace->latitude = $request ->input('start_point_lat');
//                $gatheringPlace->longitude = $request ->input('start_point_lon');
                $gatheringPlace->description = $request ->input('start_description');

                $startAddresses = array();
                $startAddressCount = 0;
                $requestStartAddressName = "start_address_".$startAddressCount;
                while($request->has($requestStartAddressName))
                {
                    $startAddress = new \stdClass();
                    $startAddress->address =  $request->input($requestStartAddressName);

                    if($request->has("gathering_start_date_time_".$startAddressCount))
                        $startAddress->start_date_time =  $request->input("gathering_start_date_time_".$startAddressCount);
                    else
                        $startAddress->start_date_time =  Carbon::now()->toDateTimeString();

                    if($request->has("gathering_end_date_time_".$startAddressCount))
                        $startAddress->end_date_time =  $request->input("gathering_end_date_time_".$startAddressCount);
                    else
                        $startAddress->end_date_time =  Carbon::now()->toDateTimeString();


//                    $startAddress->start_date_time =  $request->input("gathering_start_date_time_".$startAddressCount);
//                    $startAddress->end_date_time =  $request->input("gathering_end_date_time_".$startAddressCount);

                    if($request ->has('start_point_lat'))
                        $startAddress->latitude = $request ->input('start_point_lat');
                    else
                        $startAddress->latitude = "";
                    if($request ->has('start_point_lon'))
                        $startAddress->longitude = $request ->input('start_point_lon');
                    else
                        $startAddress->longitude = "";

                    array_push($startAddresses, $startAddress);

                    $startAddressCount++;
                    $requestStartAddressName = "start_address_".$startAddressCount;
                }
                $gatheringPlace->address = $startAddresses;

                $tour -> gathering_place = json_encode($gatheringPlace);






                //meysam - create info json
                $info = new \stdClass();
                $info->site_share = $siteShare;
                $info->coordination_tel = $request ->input('coordination_tel');
                if($request ->has('regulations'))
                    $info->regulations = $request ->input('regulations');
                else
                    $info->regulations = "";
                if($request ->has('company'))
                    $info->company = $request ->input('company');
                else
                    $info->company = "";
                $tour -> info = json_encode($info);

                //meysam - create social json
                $socials = array();
                $socialCount = 0;
                $requestSocialName = "social_name_".$socialCount;
                while($request->has($requestSocialName))
                {
                    $social = new \stdClass();
                    $social->code =  $request->input($requestSocialName);
                    $social->address = Utilities\Utility::addHTTP($request->input("social_address_".$socialCount)) ;
                    array_push($socials, $social);

                    $socialCount++;
                    $requestSocialName = "social_name_".$socialCount;
                }
                $tour->social = json_encode($socials);

                //meysam - create wholesale_discount json
                $wholesale_discount = new \stdClass();
                if(strcmp($request->input('has_wholesale_discount'),'on') == 0)
                {
                    $wholesale_discount->is_active =  1;

                }
                else
                    $wholesale_discount->is_active = 0;
                $wholesale_discount->percent = $request->input('wholesale_discount_percent');
                $tour->wholesale_discount = json_encode($wholesale_discount);
                //////////////////////////////////////////




                $tour->store();


//                Log::info('tour:'.json_encode($tour));

                //meysam - save tour features
                $featureCount = 0;
                $requestFeature = "feature_".$featureCount;
                while ($request->has($requestFeature))
                {
                    $featureId = $request->input($requestFeature);
                    $requestFeatureDescription = $request->input("feature_".$featureId."_description");

                    $requestFeatureCapacity = $request->input("feature_".$featureId."_capacity");
                    if(strcmp($requestFeatureCapacity,'') == 0)
                        $requestFeatureCapacity = null;

                    $requestFeatureCount = $request->input("feature_".$featureId."_count");
                    if(strcmp($requestFeatureCount,'') == 0)
                        if(strcmp($requestFeatureCapacity,'') == 0)
                            $requestFeatureCount = null;
                        else
                            $requestFeatureCount = 0;

                    $requestFeatureCost = $request->input("feature_".$featureId."_cost");
                    if(strcmp($requestFeatureCost,'') == 0)
                        $requestFeatureCost = null;

                    $requestFeatureChecked = $request->input("feature_".$featureId."_checked");
                    $requestFeatureOptional = $request->input("feature_".$featureId."_optional");
                    $requestFeatureRequired = $request->input("feature_".$featureId."_required");

                    if(strcmp($requestFeatureChecked,'on') == 0)
                    {
                        $tourFeature = new TourFeature();
                        $tourFeature->initialize();
                        $tourFeature->feature_id = $featureId;
                        $tourFeature->tour_id = $tour->tour_id;
                        $tourFeature->description = $requestFeatureDescription;
                        $tourFeature->cost = $requestFeatureCost;
                        $tourFeature->capacity = $requestFeatureCapacity;
                        $tourFeature->count = $requestFeatureCount;

                        if(strcmp($requestFeatureOptional,'on') == 0)
                            $tourFeature->is_optional = 1;
                        else
                            $tourFeature->is_optional = 0;

                        if(strcmp($requestFeatureRequired,'on') == 0)
                            $tourFeature->is_required = 1;
                        else
                            $tourFeature->is_required = 0;

                        $tourFeature->store();
                    }


                    $featureCount++;
                    $requestFeature = "feature_".$featureCount;
                }


                //meysam - save tour leaders
                $leaderCount = 0;
                $requestLeader = "leader_".$leaderCount;
                while ($request->has($requestLeader))
                {
                    $tourLeader = new TourLeader();
                    $tourLeader->initialize();
                    $tourLeader->user_id = $request->input($requestLeader);
                    $tourLeader->tour_id = $tour->tour_id;
                    $tourLeader->store();

                    $leaderCount++;
                    $requestLeader = "leader_".$leaderCount;
                }

                //meysam - save tour discounts
                $discountCount = 0;
                $requestDiscount = "discount_code_".$discountCount;
                while ($request->has($requestDiscount))
                {
                    $tourDiscount = new TourDiscount();
                    $tourDiscount->initialize();
                    $tourDiscount->code = $request->input($requestDiscount);
                    $tourDiscount->capacity = $request->input('discount_capacity_'.$discountCount);
                    $tourDiscount->remaining_capacity = $request->input('discount_remaining_capacity_'.$discountCount);
                    $tourDiscount->percent = $request->input('discount_percent_'.$discountCount);
                    $tourDiscount->description = $request->input('discount_description_'.$discountCount);
                    $tourDiscount->tour_id = $tour->tour_id;
                    $tourDiscount->store();

                    $discountCount++;
                    $requestDiscount = "discount_code_".$discountCount;
                }

                //meysam - save avatar file if exist
                if($request->hasFile('avatar_picture'))
                {

                    $request['tag'] = Tag::TAG_TOUR_AVATAR;
                    if(Utilities\Utility::checkFileExtention($request) && Utilities\Utility::checkFileSize($request))
                    {
                        Utilities\Utility::saveFile($request,$tour->tour_id);
                    }
                    else
                    {
                        Tour::deleteFileDirectory($tour->tour_id);
                        DB::rollback();
                        $message = trans('messages.msgErrorFileFormatOrSize');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }

                //meysam - save pictures if exist
                if($request->hasFile('picture'))
                {
//                    Log::info('pictures exist');
                    $files = $request->file('picture');
//                    Log::info('$files count:'.json_encode(count($files)));
                    foreach ($files as $file) {

                        $media = new Media();
                        $media->initialize();

//                        $media = new \stdClass();
//                        $media->media_id =  $fileCount;
//                        $media->media_guid = uniqid('',true);
                        $media->type = Media::TYPE_PICTURE;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->tour_id =$tour->tour_id;

                        $media->store();

                        $tag = Tag::TAG_TOUR_PICTURE;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_TOUR_PICTURE ,$tour->tour_id, $media->media_id);
                        }
                        else
                        {
                            Tour::deleteFileDirectory($tour->tour_id);
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

//                        Log::info('picture id:'.json_encode($media->media_id));

//                        Log::info('picture exist:'.json_encode($file->getClientOriginalName().$file->getClientOriginalExtension()));
//                        $filename = $file->getClientOriginalName();
//                        $extension = $file->getClientOriginalExtension();
                    }
                }

                //meysam - save films if exist
                if($request->hasFile('film'))
                {
                    $files = $request->file('film');
                    foreach ($files as $file) {

                        $media = new Media();
                        $media->initialize();

                        $media->type = Media::TYPE_VIDEO;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->tour_id =$tour->tour_id;

                        $media->store();

                        $tag = Tag::TAG_TOUR_CLIP;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_TOUR_CLIP ,$tour->tour_id, $media->media_id);
                        }
                        else
                        {
                            Tour::deleteFileDirectory($tour->tour_id);
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

                    }
                }

                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/tours/index')->with('messages', $messages);
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

    public function edit($tour_id, $tour_guid)
    {

        if(!Tour::existByIdAndGuid($tour_id,$tour_guid))
        {
            $message = trans('messages.msgErrorItemNotExist');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        $tour = Tour::findByIdAndGuid($tour_id, $tour_guid);


        $tour->gathering_place = json_decode($tour->gathering_place );


        $tour->social = json_decode( $tour->social);
        $tour->info = json_decode($tour->info);
        $tour->tour_address = json_decode($tour->tour_address );
        $tour->wholesale_discount = json_decode( $tour->wholesale_discount);

//        Log::info('$tour->info '.json_encode($tour->info->regulations));


        $tour_leaders = DB::table('tours_leaders')
             ->join('users', function($join)
            {
                $join->on('tours_leaders.user_id','=','users.user_id');
            })
            ->where('users.type', User::TypeLeader)
            ->where('tours_leaders.deleted_at', null)
            ->where('tours_leaders.tour_id', $tour_id)
            ->where('users.deleted_at', null)
            ->select('users.name_family', 'tours_leaders.tour_leader_id', 'tours_leaders.tour_leader_guid', 'tours_leaders.user_id')
            ->get();

        $tour_features = DB::table('tours_features')
            ->join('features', function($join)
            {
                $join->on('tours_features.feature_id','=','features.feature_id');
            })
            ->where('tours_features.deleted_at', null)
            ->where('tours_features.tour_id', $tour_id)
            ->where('features.deleted_at', null)
            ->select('features.name', 'features.description as feature_description', 'tours_features.tour_feature_id', 'tours_features.tour_feature_guid', 'tours_features.tour_id', 'tours_features.feature_id', 'tours_features.description', 'tours_features.cost', 'tours_features.capacity', 'tours_features.count', 'tours_features.is_optional', 'tours_features.is_required')
            ->get();



        $features = DB::table('features')
            ->where('deleted_at', null)
            ->get();

        $leaders = DB::table('users')
            ->where('type', User::TypeLeader)
            ->where('deleted_at', null)
            ->get();

        $owners = null;
        if(Auth::user()->type == \App\User::TypeAdmin
            || Auth::user()->type == \App\User::TypeOperator)
        {
            $owners = DB::table('users')
                ->where('type', User::TypeOwner)
                ->where('deleted_at', null)
                ->get();
        }

        $media = DB::table('media')
            ->where('tour_id', $tour_id)
            ->where('garden_id', null)
            ->where('deleted_at', null)
            ->get();

        $discounts = DB::table('tours_discounts')
            ->where('tour_id', $tour_id)
            ->where('deleted_at', null)
            ->get();


        return view('tour.edit',['tour' => $tour,'tour_leaders'=> $tour_leaders, 'features' => $features, 'leaders' => $leaders, 'owners' => $owners, 'media'=>$media, 'tour_features'=>$tour_features, 'discounts' => $discounts]);
    }

    public function update(Request $request)
    {
//        Log::info('meysam update tour: '.json_encode($request->all()));
//        if($request ->has('regulations'))
//            Log::info('it has reg ');
//        return redirect()->back();

        $validation = Validator::make($request->all(), [
            'start_point_lat' => 'required',
            'start_point_lon'=>'required',
            'end_point_lat'=>'required',
            'end_point_lon'=>'required',
            'title'=>'required',
            'description'=>'required',
            'total_capacity'=>'required',
            'remaining_capacity'=>'required',
            'start_date_time'=>'required',
            'end_date_time'=>'required',
            'deadline_date_time'=>'required',
            'cost'=>'required',
            'coordination_tel'=>'required',
            'minimum_age'=>'required',
            'maximum_age'=>'required',
            'gender'=>'required',
            'hardship_level'=>'required',
            'start_address_0'=>'required',
            'end_address_0'=>'required',
        ]);

        try
        {
            if($validation->passes()){

                if(!Tour::existByIdAndGuid($request->input('tour_id'), $request->input('tour_guid')))
                {
                    $message = trans('messages.msgErrorItemNotExist');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                $tour = Tour::findById($request->input('tour_id'));

                DB::beginTransaction();
                Tour::edit($request);

//                Log::info('tour:'.json_encode($tour));

                //meysam - save tour features
                $old_tour_features = DB::table('tours_features')
                    ->where('tours_features.deleted_at', null)
                    ->where('tours_features.tour_id', $tour->tour_id)
                    ->get();
                foreach ($old_tour_features as $old_feature)
                {
                    $old_feature->is_feature_exist = false;
                }

//                Log::info('old features:'.json_encode($old_tour_features));
                $featureCount = 0;
                $requestFeature = "feature_".$featureCount;
                while ($request->has($requestFeature))
                {
                    $featureId = $request->input($requestFeature);
                    $requestFeatureDescription = $request->input("feature_".$featureId."_description");

                    $requestFeatureCapacity = $request->input("feature_".$featureId."_capacity");
                    if(strcmp($requestFeatureCapacity,'') == 0)
                        $requestFeatureCapacity = null;

                    $requestFeatureCount = $request->input("feature_".$featureId."_count");
                    if(strcmp($requestFeatureCount,'') == 0)
                        if(strcmp($requestFeatureCapacity,'') == 0)
                            $requestFeatureCount = null;
                        else
                            $requestFeatureCount = 0;

                    $requestFeatureCost = $request->input("feature_".$featureId."_cost");
                    if(strcmp($requestFeatureCost,'') == 0)
                        $requestFeatureCost = null;

                    $requestFeatureChecked = $request->input("feature_".$featureId."_checked");
                    $requestFeatureOptional = $request->input("feature_".$featureId."_optional");
                    $requestFeatureRequired = $request->input("feature_".$featureId."_required");

                    if(strcmp($requestFeatureChecked,'on') == 0)
                    {

                        $tourFeature = new TourFeature();
                        $tourFeature->initialize();
                        $tourFeature->feature_id = $featureId;
                        $tourFeature->tour_id = $tour->tour_id;
                        $tourFeature->description = $requestFeatureDescription;
                        $tourFeature->cost = $requestFeatureCost;
                        $tourFeature->capacity = $requestFeatureCapacity;
                        $tourFeature->count = $requestFeatureCount;

                        if(strcmp($requestFeatureOptional,'on') == 0)
                            $tourFeature->is_optional = 1;
                        else
                            $tourFeature->is_optional = 0;

                        if(strcmp($requestFeatureRequired,'on') == 0)
                            $tourFeature->is_required = 1;
                        else
                            $tourFeature->is_required = 0;

//                        Log::info('$tourFeature:'.json_encode($tourFeature));

                        $is_feature_exist = false;
                        foreach ($old_tour_features as $old_feature)
                        {
                            if($old_feature->feature_id == $tourFeature->feature_id)
                            {
                                //meysam -edit existing old feature
                                $temp_tour_feature = TourFeature::findById($old_feature->tour_feature_id);
                                $temp_tour_feature->description = $tourFeature->description;
                                $temp_tour_feature->cost = $tourFeature->cost;
                                $temp_tour_feature->capacity = $tourFeature->capacity;
                                $temp_tour_feature->count = $tourFeature->count;
                                $temp_tour_feature->is_optional = $tourFeature->is_optional;
                                $temp_tour_feature->is_required = $tourFeature->is_required;

                                $temp_tour_feature->save();



                                $old_feature->is_feature_exist = true;
                                $is_feature_exist = true;
                                break;
                            }
                        }
                        if(!$is_feature_exist)
                        {
                            $tourFeature->store();
                        }

                    }


                    $featureCount++;
                    $requestFeature = "feature_".$featureCount;
                }
                //meysam - find deleted features and remove them....
                foreach ($old_tour_features as $old_feature)
                {
                    if(!$old_feature->is_feature_exist)
                    {
                        TourFeature::removeByIdAndGuid($old_feature->tour_feature_id,$old_feature->tour_feature_guid);
                    }
                }
                //////////////////////////////////////////////////////////////


                //meysam - save tour leaders
                $old_tour_leaders = DB::table('tours_leaders')
                    ->join('users', function($join)
                    {
                        $join->on('tours_leaders.user_id','=','users.user_id');
                    })
                    ->where('users.type', User::TypeLeader)
                    ->where('tours_leaders.deleted_at', null)
                    ->where('tours_leaders.tour_id', $tour->tour_id)
                    ->where('users.deleted_at', null)
                    ->select('users.name_family', 'tours_leaders.tour_leader_id', 'tours_leaders.tour_leader_guid', 'tours_leaders.user_id')
                    ->get();
                $leaderCount = 0;
                $requestLeader = "leader_".$leaderCount;
                while ($request->has($requestLeader))
                {
                    $tourLeader = new TourLeader();
                    $tourLeader->initialize();
                    $tourLeader->user_id = $request->input($requestLeader);
                    $tourLeader->tour_id = $tour->tour_id;

                    $is_leader_exist = false;
                    foreach ($old_tour_leaders as $old_leader)
                    {
                        if($old_leader->user_id == $tourLeader->user_id)
                        {
                            $is_leader_exist = true;
                            break;
                        }
                    }
                    if(!$is_leader_exist)
                        $tourLeader->store();

                    $leaderCount++;
                    $requestLeader = "leader_".$leaderCount;
                }
                //////////////////////////////////////////////////



                //meysam - save tour discounts
                $old_tour_discounts = DB::table('tours_discounts')
                    ->where('tours_discounts.deleted_at', null)
                    ->where('tours_discounts.tour_id', $tour->tour_id)
                    ->get();

                $discountCount = 0;
                $requestDiscount = "discount_code_".$discountCount;
                while ($request->has($requestDiscount))
                {
                    $tourDiscount = new TourDiscount();
                    $tourDiscount->initialize();
                    $tourDiscount->code = $request->input($requestDiscount);
                    $tourDiscount->capacity = $request->input('discount_capacity_'.$discountCount);
                    $tourDiscount->remaining_capacity = $request->input('discount_remaining_capacity_'.$discountCount);
                    $tourDiscount->percent = $request->input('discount_percent_'.$discountCount);
                    $tourDiscount->description = $request->input('discount_description_'.$discountCount);
                    $tourDiscount->tour_id = $tour->tour_id;

                    $is_discount_exist = false;
                    foreach ($old_tour_discounts as $old_discount)
                    {
                        if($old_discount->code == $tourDiscount->code)
                        {
                            $tourDiscount->tour_discount_id = $old_discount->tour_discount_id;
                            $tourDiscount->tour_discount_guid = $old_discount->tour_discount_guid;
                            $is_discount_exist = true;
                            $old_discount->exist = true;
                            break;
                        }
                    }
                    if(!$is_discount_exist)
                        $tourDiscount->store();
                    else
                    {
                        TourDiscount::editByObject($tourDiscount);
                    }

                    $discountCount++;
                    $requestDiscount = "discount_code_".$discountCount;
                }

                foreach ($old_tour_discounts as $old_discount)
                {
                    if(!isset($old_discount->exist))
                    {
                        TourDiscount::removeByIdAndGuid($old_discount->tour_discount_id,$old_discount->tour_discount_guid);
                    }
                }

                //////////////////////////////////////////////////





                //meysam - save avatar file if exist
                if($request->hasFile('avatar_picture'))
                {
                    $request['tag'] = Tag::TAG_TOUR_AVATAR;
                    if(Utilities\Utility::checkFileExtention($request) && Utilities\Utility::checkFileSize($request))
                    {
                        $fileNameWithoutExtention = Tour::TOUR_AVATAR_FILE_NAME;
                        if(Utilities\Utility::isFileExist(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$request->input('tour_id')))
                        {
                            Utilities\Utility::deleteFile(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$request->input('tour_id'));
                        }
                        Utilities\Utility::saveFile($request,$tour->tour_id);
                    }
                    else
                    {
                        DB::rollback();
                        $message = trans('messages.msgErrorFileFormatOrSize');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }

                //meysam - create media json
                if($request->hasFile('picture'))
                {
                    $files = $request->file('picture');
                    foreach ($files as $file)
                    {

                        $media = new Media();
                        $media->initialize();
                        $media->type = Media::TYPE_PICTURE;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->tour_id =$tour->tour_id;

                        $media->store();

                        $tag = Tag::TAG_TOUR_PICTURE;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_TOUR_PICTURE ,$tour->tour_id, $media->media_id);
                        }
                        else
                        {
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }


                    }
                }

                //meysam - create media json
                if($request->hasFile('film'))
                {
                    $files = $request->file('film');
                    foreach ($files as $file)
                    {

                        $media = new Media();
                        $media->initialize();
                        $media->type = Media::TYPE_VIDEO;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->tour_id =$tour->tour_id;

                        $media->store();

                        $tag = Tag::TAG_TOUR_CLIP;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_TOUR_CLIP ,$tour->tour_id, $media->media_id);
                        }
                        else
                        {
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

                    }
                }

                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/tours/index')->with('messages', $messages);
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

    public function detail($tour_id){
        try
        {
//            Log::info('hello1');
            ///////////////meysam - test/////////////////////////

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
//            $info = new \stdClass();
//            $info->site_share = 2;//in percent...
//            $info->coordination_tel = "09029027302";//tel number for coordination with host...
//            $info->regulations = "قانون خاصی ندارد";
//            $info->company = "شرکت پرواز پیک نیک";
//            Log::info('info:'.json_encode($info));

            /// //////////////////////////////////////////////////

            $tour = Tour::findById($tour_id);

            if ($tour == null || $tour->status != Tour::StatusActive){
                $message = trans('messages.msgErrorTourInactive');
                $messages = [$message];

                return redirect()->back()->with('messages', $messages);
            }

            $socials = json_decode( $tour->social);
            $info = json_decode( $tour->info);//contains site share for now....
//            Log::info('$info:'.json_encode($info));
//            Log::info('$info-> regulations:'.json_encode($info-> regulations));

            $features = DB::table('features')
                ->join('tours_features', function($join) use ( $tour_id)
                {
                    $join->on('tours_features.feature_id','=','features.feature_id');
                })
                ->where('features.deleted_at','=',null)
                ->where('tours_features.deleted_at','=',null)
                ->where('tours_features.tour_id','=',$tour_id)
                ->select('features.feature_id','features.feature_guid','features.description','features.name','tours_features.tour_feature_id as feature_uid', 'tours_features.description as more_description','tours_features.cost as cost','tours_features.capacity as capacity','tours_features.count as count','tours_features.is_optional as is_optional','tours_features.is_required as is_required')
                ->orderBy('features.feature_id', 'desc')
                ->get();


            $tour->tour_address = json_decode($tour->tour_address );

            $tour->miladi_start_date_time = $tour->start_date_time;
            $tour->miladi_end_date_time = $tour->end_date_time;
            $tour->miladi_deadline_date_time = $tour->deadline_date_time;

            $tour->start_date_time = jDateTime::strftime('Y/n/j H:i', strtotime($tour->start_date_time));
            $tour->end_date_time = jDateTime::strftime('Y/n/j H:i', strtotime($tour->end_date_time));
            $tour->deadline_date_time = jDateTime::strftime('Y/n/j H:i', strtotime($tour->deadline_date_time));


            if($tour->gathering_place  != null)
            {
                $tour->gathering_place = json_decode($tour->gathering_place );
            }
            if($tour->wholesale_discount  != null)
            {
                $tour->wholesale_discount = json_decode($tour->wholesale_discount );
            }


            $fileNameWithoutExtention = Tour::TOUR_AVATAR_FILE_NAME;
            if(\App\Utilities\Utility::isFileExist(Tag::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour->tour_id))
            {
                $tour->hasAvatarPicture = 1;
            }
            else{
                $tour->hasAvatarPicture = 0;
            }


            $pictures = DB::table('media')
                ->where('tour_id', $tour_id)
                ->where('type', Media::TYPE_PICTURE)
                ->where('garden_id', null)
                ->where('deleted_at', null)
                ->get();

            $films = DB::table('media')
                ->where('tour_id', $tour_id)
                ->where('type', Media::TYPE_VIDEO)
                ->where('garden_id', null)
                ->where('deleted_at', null)
                ->get();

//            $tour = new Tour();
//            $tour->name = "نام باغ";
//            $tour->address = "آدرس باغ";

//            Log::info('tour:'.json_encode($tour));


            $today = jDateTime::strftime('Y-m-d', strtotime(Carbon::now()->format('Y-m-d')));

            $leaders = DB::table('users')
                ->join('tours_leaders', 'tours_leaders.user_id', '=', 'users.user_id')
                ->where('tours_leaders.deleted_at','=',null)
                ->where('tours_leaders.tour_id','=',$tour_id)
                ->where('users.deleted_at','=',null)
                ->select( 'users.name_family', 'users.user_id', 'users.user_guid', 'users.info', 'users.social')
                ->orderBy('tours_leaders.tour_leader_id', 'asc')
                ->get();

            foreach ($leaders as $leader)
            {
                $leader->info = json_decode($leader->info);
                $leader->social = json_decode($leader->social);
            }

            $owner = User::findById($tour->user_id);

//            $discounts = DB::table('tours_discounts')
//                ->where('tour_id', $tour_id)
//                ->where('deleted_at', null)
//                ->get();
//            Log::info('leaders:'.json_encode($leaders));
//            Log::info('hello20');


            return view('tour.new1_details', ['features' => $features,'today' => $today, 'tour' => $tour, 'pictures' => $pictures, 'films' => $films, 'socials' => $socials, 'info' => $info, 'leaders' => $leaders, 'owner' => $owner, 'has_map'=>true]);
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

    public function remove($tour_id,$tour_guid){

        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            Tour::removeByIdAndGuid($tour_id,$tour_guid);
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

    public function getFile($tour_id, $tour_guid, $media_id = null, $tag)
    {
//        Log::info('$tag:'.json_encode($tag));
//        if(TOUR::existByIdAndGuid($tour_id,$tour_guid) == false)
//        {
//            return abort(404);
//        }
        try
        {
//            Log::info('$tag:'.json_encode($tag));

            if($tag==Tag::TAG_TOUR_AVATAR_DOWNLOAD)
            {
                $fileNameWithoutExtention = TOUR::TOUR_AVATAR_FILE_NAME;
//                Log::info('$fileNameWithoutExtention:'.json_encode($fileNameWithoutExtention));
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour_id))
                {
//                    Log::info('$fileNameWithoutExtention:'.json_encode($fileNameWithoutExtention));
                    return \App\Utilities\Utility::getFile(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour_id);
                }

            }
            else if($tag==Tag::TAG_TOUR_PICTURE_DOWNLOAD)
            {
                $fileNameWithoutExtention = $media_id;
//                Log::info('$fileNameWithoutExtention1:'.json_encode($fileNameWithoutExtention));

                if(\App\Utilities\Utility::isFileExist(TAG::TAG_TOUR_PICTURE, $fileNameWithoutExtention,$tour_id))
                {
//                    Log::info('$fileNameWithoutExtention2:'.json_encode($fileNameWithoutExtention));
                    return \App\Utilities\Utility::getFile(TAG::TAG_TOUR_PICTURE, $fileNameWithoutExtention,$tour_id);
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

        $tour_id = $request->input('tour_id');
        $tour_guid= $request->input('tour_guid');
        $media_id = null;
        $media_guid = null;
        if($request->has('media_id'))
            $media_id= $request->input('media_id');
        if($request->has('media_guid'))
            $media_guid= $request->input('media_guid');
        $tag= $request->input('tag');

        $success_message =[trans('messages.msgOperationSuccess')];
        if(TOUR::existByIdAndGuid($tour_id,$tour_guid) == false)
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

            if($tag==Tag::TAG_TOUR_AVATAR_DELETE)
            {
                $fileNameWithoutExtention = TOUR::TOUR_AVATAR_FILE_NAME;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour_id))
                {
                    if(\App\Utilities\Utility::deleteFile(TAG::TAG_TOUR_AVATAR, $fileNameWithoutExtention,$tour_id))
                    {
                        return response()->json([
                            'success' => true,
                            'tag' => Tag::TAG_TOUR_AVATAR,
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
            else if($tag==Tag::TAG_TOUR_PICTURE_DELETE)
            {
                if(Media::existById($media_id) == false)
                {
                    return abort(404);
                }
                $fileNameWithoutExtention = $media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_TOUR_PICTURE, $fileNameWithoutExtention,$tour_id))
                {
                    Media::removeByIdAndGuid($media_id,$media_guid);
                    if(\App\Utilities\Utility::deleteFile(TAG::TAG_TOUR_PICTURE, $fileNameWithoutExtention,$tour_id))
                    {
                        return response()->json([
                            'success' => true,
                            'tag' => Tag::TAG_TOUR_PICTURE,
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

    public function removeTourLeader($tour_leader_id, $tour_leader_guid, $tour_id, $tour_guid)
    {
        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!TourLeader::existByIdAndGuid($tour_leader_id,$tour_leader_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            TourLeader::removeByIdAndGuid($tour_leader_id,$tour_leader_guid);
            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);


//            return redirect('/tours/edit',['tour_id'=>$tour_id, 'tour_guid'=>$tour_guid])->with('messages', $messages);

//            return response()->json([
//                'success' => true,
//                'messages' => $messages
//            ]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
//            return response()->json([
//                'success' => false,
//                'messages' => $messages
//            ]);
        }
    }

    public function removeTourBanner( $tour_id, $tour_guid)
    {
        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!Utilities\Utility::isFileExist(Tag::TAG_TOUR_AVATAR,Tour::TOUR_AVATAR_FILE_NAME,$tour_id))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Utilities\Utility::deleteFile(Tag::TAG_TOUR_AVATAR,Tour::TOUR_AVATAR_FILE_NAME,$tour_id);
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

    public function removeTourPicture( $tour_id, $tour_guid, $media_id, $media_guid)
    {
        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!Media::existById($media_id)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Media::removeByIdAndGuid($media_id, $media_guid);

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

    public function removeTourFeature($tour_feature_id, $tour_feature_guid, $tour_id, $tour_guid)
    {
        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!TourFeature::existByIdAndGuid($tour_feature_id,$tour_feature_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            TourFeature::removeByIdAndGuid($tour_feature_id,$tour_feature_guid);
            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
//            return redirect()->back()->with('messages', $messages);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
//            return redirect()->back()->with('messages', $messages);
            return response()->json([
                'success' => false,
                'messages' => $messages
            ]);
        }
    }

    public function getFileStream($tour_id, $tour_guid, $media_id = null, $tag)
    {
//        Log::info('-1');
        if(Tour::existByIdAndGuid($tour_id,$tour_guid) == false)
        {
            return abort(404);
        }
        try
        {
//            Log::info('0');
            if($tag==Tag::TAG_TOUR_CLIP_DOWNLOAD)
            {
//                Log::info('1');
                $fileNameWithoutExtention = $media_id;
                if(Utilities\Utility::isFileExist(TAG::TAG_TOUR_CLIP, $fileNameWithoutExtention,$tour_id))
                {
//                    Log::info('2');
                    return Utilities\Utility::getFileStream(TAG::TAG_TOUR_CLIP, $fileNameWithoutExtention,$tour_id);
                }

            }
            else
            {
                return abort(404);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?Utilities\Utility::getLoggedInUserId():-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            return abort(404);
        }
    }

    public function removeTourMedia( $tour_id, $tour_guid, $media_id, $media_guid)
    {
        try
        {
            if (!Tour::existByIdAndGuid($tour_id,$tour_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!Media::existById($media_id)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Media::removeByIdAndGuid($media_id, $media_guid);

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