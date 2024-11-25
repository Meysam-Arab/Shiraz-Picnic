<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:19 PM
 */

namespace App\Http\Controllers;


use App\Garden;
use App\LogEvent;
use App\Reserve;
use App\Tour;
use App\TourDiscount;
use App\Transaction;
use App\User;
use App\Utilities\Utility;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Log;
use Morilog\Jalali\jDateTime;
use Auth;
use Route;
use DB;
use Illuminate\Http\RedirectResponse;
use Morilog\Jalali\jDate;
use Ramsey\Uuid\Generator\RandomGeneratorFactory;


class TransactionController extends Controller
{
    protected $transaction;

    /**
     * TransactionController constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function listShow($user_id)
    {
        try {

            $offset = 0;
            $limit = 1000;

//            $transaction = new Transaction();
//            $transaction->user_id =$user_id;
//            $transactions = $transaction->select('desc', null, null);

            $end_date = Carbon::now();
            $start_date = Carbon::now();
            $start_date->subMonth();
            $start_date = $start_date->toDateString();
            $end_date = $end_date->toDateString();



//            Log::info('$start_date:'.json_encode($start_date));
//            Log::info('$end_date:'.json_encode($end_date));

            $transactions = DB::table('transactions')
                ->where('transactions.deleted_at','=',null)
                ->where('transactions.user_id','=',$user_id)
                ->where('transactions.created_at','>=',$start_date)
                ->where('transactions.created_at','<=',$end_date)
                ->select( 'transactions.created_at', 'transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                ->orderBy('transaction_id', 'desc')
                ->skip($offset*$limit)->take($limit)
                ->get();


            $totalCost = 0;
            foreach($transactions as $transaction)
            {
                $transaction->info = json_decode($transaction->info);
                $totalCost += $transaction->amount;
            }

            return view('transaction.index',['transactions' => $transactions, 'totalCost' => $totalCost, 'user_id' => $user_id]);

        } catch (Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

    }

    public function indexAdmin()
    {
        try {

            $transaction = new Transaction();
            $transactions = $transaction->select('desc', null, null);

            $totalCost = 0;
            $user_id = null;
            foreach($transactions as $transaction)
            {
                $transaction->info = json_decode($transaction->info);
                $totalCost += $transaction->amount;

            }

            return view('transaction.index', ['transactions' =>$transactions, 'totalCost' => $totalCost, 'user_id' => $user_id]);

        } catch (Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    /**
     * meysam - show an specific transaction
     */
    public function show($transaction_id, $transaction_guid)
    {
        $transaction = DB::table('transactions')
            ->where('transaction_id', '=', $transaction_id)
            ->where('transaction_guid', 'like', $transaction_guid)
            ->where('deleted_at',null)
            ->get()->first();

        $info = json_decode($transaction->info);
        $transaction->info = $info;
        $reserve = Reserve::findById($transaction->reserve_id);
if($reserve == null)
        {
            $message = trans('messages.msgErrorTransactionReserveNotSet');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
        $reserve->info = json_decode($reserve->info);

        $garden = null;
        $tour = null;
        if(isset($info->garden_id))
        {
            $reserve->garden_id = $info->garden_id;
//            unset($info->garden_id);
            $reserve->start_date = $info->start_date;
            unset($info->start_date);

            $reserve->end_date = $info->end_date;
            unset($info->end_date);

            $garden = Garden::findById($info->garden_id);
            $garden->info = json_decode($garden->info);

        }
        else if(isset($info->tour_id))
        {
            $reserve->tour_id = $info->tour_id;


            $tour = Tour::findById($info->tour_id);

            $tour->social = json_decode( $tour->social);
            $tour->info = json_decode($tour->info);

            $reserve->start_date = $tour->start_date_time;
            $reserve->end_date = $tour->end_date_time;

            $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
            $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
            $tour->tour_address = json_decode($tour->tour_address );
//            $tour->address = $tour->tour_address->address;



//            unset($info->tour_id);
        }
        else
        {

        }

        $url = url('/reservs/detail/'.$reserve->reserve_id.'/'.$reserve->reserve_guid);

        $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($url);
        $png = base64_encode($png);


        $discount = null;
        if(isset($reserve->info->tour_discount_id))
        {
            $discount = TourDiscount::findById($reserve->info->tour_discount_id);
        }

        return view('transaction.result',['reserve' => $reserve, 'garden' => $garden, 'tour' => $tour, 'png' => $png, 'transaction' => $transaction, 'discount' => $discount])->with('messages' ,[trans('messages.msgTransactionResult')]);
    }

    public function storeGarden(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'garden_id' => 'required|numeric',
            'shift_id_1' => 'required|numeric',
            'shift_id_2' => 'numeric',
            'reserved_kind' => 'required|numeric',
            'start_date' => 'required',
            'name_and_family' => 'required',
            'mobile' => 'required',
            'email' => 'email',
            'national_code' => 'required|numeric|melli_code',
        ]);

//        Log::info('data:'.json_encode($request->all()));
//        Log::info('meysam - log - 1');
        //////CAPTCHA/////////////////////////////////////////////////
//
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
//            return redirect()->back()->with('messages', $messages);
//        }


        if (!$request->has('chk_terms')) {
            // All bad. Token not verified!
            $message = trans('messages.msgErrorTermsOfUse');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        //////////////////////////////////////////////////////

        //TODO: meysam - remove when bugs fixed
        if($request->input('reserved_kind') != Reserve::TypeSingleDay )
        {
            $message = trans('messages.msgErrorReservedDate');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        if($request->input('reserved_kind') == Reserve::TypeSingleDay )
        {
//            $request['shift_id_1'] = Garden::ShiftFullId;
            $request['end_date'] = $request->input('start_date');
            $request['shift_id_2'] = -1;
        }

        $request->session()->forget('verification');
        //meysam - check user status...
        $garden = new Garden();
        $garden->initialize();
        $garden->garden_id = $request->input('garden_id');
        $garden = $garden->select('asc')->first();
        $garden->info = json_decode($garden->info);

        //میثم - اگر باغ غیر فعال بود خطا برگردانیم که باغ غیر فعال است
        if ($garden->status != Garden::StatusActive){
            $message = trans('messages.msgErrorGardenInactive');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }

        $reserved_dates = Garden::getReservedDatesArray($garden->garden_id);

        $periodic_holidays = json_decode($garden->periodic_holidays);
        $onetime_holidays = json_decode($garden->on_time_holidays);

//        Log::info('$periodic_holidays:'.json_encode($periodic_holidays));
//        Log::info('$onetime_holidays:'.json_encode($onetime_holidays));

//        Log::info('$request->input(\'start_date\'):'.json_encode(Utility::convert($request->input('start_date'))));
//        Log::info('$request->input(\'end_date\'):'.json_encode(Utility::convert($request->input('end_date'))));
//
//        Log::info('shift_id_1:'.json_encode(Utility::convert($request->input('shift_id_1'))));
//        Log::info('shift_id_2:'.json_encode(Utility::convert($request->input('shift_id_2'))));

        $request_start_date =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('start_date')));
        $request_end_date =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('end_date')));
        $request_start_time = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('start_date')))->getTimestamp();
        $request_end_time =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('end_date')))->getTimestamp();

        //میثم - اگر روز شروع یا خود روز انتخابی کمتر یا مساوی امروز بود خطا برگردانیم که نمی شود
        if($request_start_date->lte(Carbon::now()))
        {
            $message = trans('messages.msgErrorReservedDateAfterToday');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }

        //میثم - اگر روز شروع یا انتخابی بیشتر یا مساوی محدودیت حداکثر تاریخ انتخاب بود خطا برگردانیم که نمی شود
        if($request_start_date->gte(Carbon::now()->addDay($garden->info->day_reserve_limit)))
        {
            $message = trans('messages.msgErrorInvalidReserveDate');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }

        //میثم - اگر روز پایانی انتخابی بزرگتر یا مساوی محدودیت حداکثر تاریخ بود خطا برگردانیم که نمیشود
        if($request->input('reserved_kind') == Reserve::TypeSeveralDays && $request_end_date->gte(Carbon::now()->addDay($garden->info->day_reserve_limit)))
        {
            $message = trans('messages.msgErrorInvalidReserveDate');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }

        //میثم - اگر انتخاب چند روزه بود و روز پایانی کوچکتر یا مساوی امروز بود خطا برگردانیم که نمی شود.
        if($request->input('reserved_kind') == Reserve::TypeSeveralDays && $request_end_date->lte($request_start_date))
        {
            $message = trans('messages.msgErrorReservedDateIncorrect');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }

//        Log::info('$request_start_date:'.json_encode($request_start_date));
//        Log::info('$request_end_date:'.json_encode($request_end_date));
//        Log::info('$request_start_time:'.json_encode($request_start_time));
//        Log::info('$request_end_time:'.json_encode($request_end_time));
//
//        Log::info('$reserved_dates:'.json_encode($reserved_dates));

        foreach ($onetime_holidays as $onetime_holiday)
        {

            $onetime_holiday_time = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y-n-j', Utility::convert($onetime_holiday->date))->getTimestamp();
//                Log::info('$reserved_date_time:'.json_encode($reserved_date_time));

            //میثم - اگر روز شروع یا انتخابی جزو روزهای تعطیل خاص ست شده توسط صاحب باغ بود خطا برگرداند
            if($onetime_holiday_time == $request_start_time)
            {
                $message = trans('messages.msgErrorHoliday');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
        }

        foreach ($periodic_holidays as $periodic_holiday)
        {

            $start_day_of_week = Utility::getCorrectDayOFWeek($request_start_date->dayOfWeek);

//                Log::info('$start_day_of_week:'.json_encode($start_day_of_week));
//                Log::info('$periodic_holiday:'.json_encode($periodic_holiday));

            //میثم - اگر تاریخ شروع یا انتخابی جزو روزهای دوره ای تعطیلی ست شده توسط صاحب باغ بود خطا برگرداند که نمی شود
            if($periodic_holiday->day_of_week == (int)$start_day_of_week)
            {
                $message = trans('messages.msgErrorHoliday');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

        }

        foreach ($reserved_dates as $reserved_date)
        {

            $reserved_date_time = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y-n-j', Utility::convert($reserved_date->date))->getTimestamp();
//                Log::info('$reserved_date_time:'.json_encode($reserved_date_time));

            //میثم - اگر یک روزه بود و تاریخ شروع رزرو شده بود یا یکی از شیفت هایش قبلا رزرو شده بود خطا بدهد و برگرداند
            if($reserved_date_time == $request_start_time &&
                ($reserved_date -> shift_id == $request->input('shift_id_1') || $reserved_date -> shift_id == Garden::ShiftFullId || $request->input('shift_id_1') == Garden::ShiftFullId))
            {
                $message = trans('messages.msgErrorReservedDate');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
        }

        if($request->input('reserved_kind') == Reserve::TypeSeveralDays)
        {

            foreach ($reserved_dates as $reserved_date)
            {
                $reserved_date_time = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y-n-j', Utility::convert($reserved_date->date))->getTimestamp();
//                Log::info('$reserved_date_time:'.json_encode($reserved_date_time));

//                //میثم - اگر چند روزه بود و روز آخر و اول یکیش رزرو شده بود یا شیفت انتخاب شده داشت خطا برگردد که نمی شود
//                if($request_start_time <= $reserved_date_time && $request_end_time >= $reserved_date_time)
//                {
//                    if($reserved_date -> shift_id == $request->input('shift_id_1') || $reserved_date -> shift_id == Garden::ShiftFullId || $reserved_date -> shift_id == $request->input('shift_id_2'))
//                    {
//                        $message = trans('messages.msgErrorReservedDateExist');
//                        $messages = [$message];
//                        return redirect()->back()->with('messages', $messages);
//                    }
//
//                }

                //میثم - اگر چند روزه بود و تاریخ پایان رزرو شده بود یا یکی از شیفت هایش قبلا رزرو شده بود خطا بدهد و برگرداند
                if($reserved_date_time == $request_end_time &&
                    ($reserved_date -> shift_id == $request->input('shift_id_2') || $reserved_date -> shift_id == Garden::ShiftFullId || $request->input('shift_id_2') == Garden::ShiftFullId))
                {
                    $message = trans('messages.msgErrorReservedDate');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                // meysam - تمامی روزهای بین روز شروع و پایان چک شود که روز رزرو شده نباشد بینش
                if($request_start_time < $reserved_date_time && $request_end_time > $reserved_date_time)
                {
                        $message = trans('messages.msgErrorReservedDateExist');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);

                }
            }

            foreach ($onetime_holidays as $onetime_holiday)
            {

                $onetime_holiday_time = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y-n-j', Utility::convert($onetime_holiday->date))->getTimestamp();
//                Log::info('$reserved_date_time:'.json_encode($reserved_date_time));

                //میثم - اگر انتخاب چند روزه بود و روز پایانی نیز جزو روزهای تعطیل ست شده توسط صاحب باغ بود خطا برگرداند که نمی شود
                if($onetime_holiday_time == $request_end_time)
                {
                    $message = trans('messages.msgErrorHoliday');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                // meysam - تمامی روزهای بین روز شروع و پایان چک شود که روز تعطیل خاص نباشد بینش
                if($request_start_time < $onetime_holiday_time && $request_end_time > $onetime_holiday_time)
                {
                    $message = trans('messages.msgErrorHoliday');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);

                }
            }

            foreach ($periodic_holidays as $periodic_holiday)
            {

                $end_day_of_week = Utility::getCorrectDayOFWeek($request_end_date->dayOfWeek);
//                Log::info('$start_day_of_week:'.json_encode($start_day_of_week));
//                Log::info('$periodic_holiday:'.json_encode($periodic_holiday));

                //میثم - اگر چند روزه بود و تاریخ پایان جزو روزهای دوره ای تعطیلی ست شده توسط صاحب باغ بود خطا برگرداند که نمی شود
                if($periodic_holiday->day_of_week == (int)$end_day_of_week)
                {
                    $message = trans('messages.msgErrorHoliday');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                // meysam - تمامی روزهای بین روز شروع و پایان چک شود که روز تعطیل دوره ای نباشد بینش
                if((int)$end_day_of_week < (int)$start_day_of_week)
                {
                    if($periodic_holiday->day_of_week > (int)$end_day_of_week && $periodic_holiday->day_of_week > (int)$start_day_of_week)
                    {
                        $message = trans('messages.msgErrorHoliday');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }
                else
                {
                    if($periodic_holiday->day_of_week < (int)$end_day_of_week && $periodic_holiday->day_of_week > (int)$start_day_of_week)
                    {
                        $message = trans('messages.msgErrorHoliday');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }
            }

        }

//        Log::info('meysam - log - 2');
        /////////////////////////////////

        if($validation->passes())
        {
            $myInfo = new \stdClass();
            $myInfo->garden_id = $request->input('garden_id');
            $myInfo->shift_id_1 = $request->input('shift_id_1');

            if($request->has('shift_id_2'))
                $myInfo->shift_id_2 = $request->input('shift_id_2');
            else
                $myInfo->shift_id_2 = null;

            $myInfo->reserved_kind = $request->input('reserved_kind');
            $startDate = \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/m/d', Utility::convert($request->input('start_date')));
            $endDate =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/m/d', Utility::convert($request->input('end_date')));

            $myInfo->start_week_day_id = Utility::getCorrectDayOFWeek($startDate->dayOfWeek);
            $myInfo->end_week_day_id = Utility::getCorrectDayOFWeek($endDate->dayOfWeek);

            $myInfo->start_date = $startDate->toDateString();
            $myInfo->end_date =$endDate->toDateString();
            $myInfo->name_and_family = $request->input('name_and_family');
            $myInfo->mobile = $request->input('mobile');
            $myInfo->email = $request->input('email');
            $myInfo->national_code = $request->input('national_code');
            $myInfo->reserved_kind = $request->input('reserved_kind');

        }
        else
        {
            $errors = $validation->errors()->all();
            return redirect()->back()->with('errors', $errors);
        }


        if(!Reserve::isPeriodicCostEnabled($myInfo->garden_id, $myInfo->reserved_kind, $myInfo->start_date ,$myInfo->end_date, $myInfo->start_week_day_id, $myInfo->end_week_day_id, $myInfo->shift_id_1, $myInfo->shift_id_2))
        {
            $message = trans('messages.msgErrorReserveShift');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

//        Log::info('data:'.json_encode($request->all()));
        ///////////////////////////////////////////////
        $myInfoJson = json_encode($myInfo);
        //store in database and retrieve in verification...
        $transaction = new Transaction();
        $transaction->user_id = $garden->user->user_id;
        $transaction->amount = Reserve::calculateGardenTotalCost($myInfo->garden_id, $myInfo->reserved_kind, $myInfo->start_date ,$myInfo->end_date, $myInfo->start_week_day_id, $myInfo->end_week_day_id, $myInfo->shift_id_1, $myInfo->shift_id_2);
        $transaction->description = "رزرو در سایت شیراز پیک نیک";

        $transaction->type = transaction::TRANSACTION_TYPE_INCOMING;
        $transaction->info = $myInfoJson;


        /// //////////////////////////////// for test/////////////////
//        $transaction->authority = - Carbon::now()->timestamp;
//        $transaction->status = Transaction::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL;
//        $transaction->initializeByObject($transaction);
//        $transaction->store();
//
//        return redirect()->action(
//            'TransactionController@verification', ['Status' => 100, 'Authority' => $transaction->authority]
//        );
        ////////////////////////////////////// end for test/////////////

////        Log::info('meysam - log - 3');
//        try {
//            //get Authority from zarinpal site...
//            // meysam - get new from zarinpal...
//            $MerchantID = "c24ac388-9df6-11e8-a25d-000c295eb8fc";//get from zarinpal ...
//            $Amount = $transaction->amount;
//            $Description = "پرداخت هزینه سفارش شیراز پیک نیک";
//            if($request->has('email'))
//                $Email = $request->input('email');
//            else
//                $Email = "fardan7eghlim@gmail.com";
//            $Mobile = $request->input('mobile');
//            $CallbackURL = "http://www.shirazpicnic.ir/transactions/verification";
//
//            $data = array('MerchantID' => $MerchantID,
//                'Amount' => $Amount,
//                'Description' => $Description,
//                'Email' => $Email,
//                'Mobile' => $Mobile,
//                'CallbackURL' => $CallbackURL);
//            $jsonData = json_encode($data);
//
//            //meysam - main
//            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
//
//            //meysam - for test
////            $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
//
//
//            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/json',
//                'Content-Length: ' . strlen($jsonData)
//            ));
//            $result = curl_exec($ch);
//
//            $err = curl_error($ch);
//            $result = json_decode($result, true);
//            curl_close($ch);
//
////            Log::info('$result:'.json_encode($result));
//
//            if ($err) {
////                Log::info('meysam - log - 4');
//                echo "cURL Error #:" . $err;
//            } else {
//                if ($result["Status"] == 100) {
////                    Log::info('meysam - log - 5');
//                    /////store Authority in session////
//                    // Via the global helper...
//                    if(strlen($result["Authority"]) == 36)
//                    {
////                        Log::info('meysam - log - 6');
//                        ////store in database and retrieve in verification...
//                        $transaction->authority = $result["Authority"];
//                        $transaction->status = $result["Status"];
//                        $transaction->initializeByObject($transaction);
//                        $transaction->store();
//                        /// ////////////////////////////////
//
////                            meysam - main
//                            return  new RedirectResponse('https://www.zarinpal.com/pg/StartPay/' . $result["Authority"].'/ZarinGate');
//
////                            meysam - test
////                        return  new RedirectResponse('https://sandbox.zarinpal.com/pg/StartPay/' . $result["Authority"]);
//                    }
//                    else
//                    {
//                        //return error
//                        ////store in database and retrieve in verification...
////                        Log::info('meysam - log - 7');
//                        $transaction->status = $result["Status"];
//                        $transaction->authority = -rand(10,100);
//
//                        $transaction->initializeByObject($transaction);
//                        $transaction->store();
//                        /// ////////////////////////////////
//                        $message = trans('messages.msgOperationFailed');
//                        $messages = [$message];
//                        return redirect()->back()->with('messages', $messages);
//                    }
//                } else {
////                    Log::info('meysam - log - 8');
//                    ////store in database and retrieve in verification...
//                    $transaction->status = $result["Status"];
//                    $transaction->authority = -rand(10,100);
//
//                    $transaction->initializeByObject($transaction);
//                    $transaction->store();
//                    /// ////////////////////////////////
//
//                    $message = trans('messages.msgOperationFailed');
//                    $messages = [$message];
//                    return redirect()->back()->with('messages', $messages);
//                }
//            }
//
//        } catch (Exception $e) {
////            Log::info('meysam - log - 9');
//            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
//            $message = trans('messages.msgOperationFailed');
//            $messages = [$message];
//            return redirect()->back()->with('messages', $messages);
//        }

    }

    public function storeTour(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'tour_id' => 'required|numeric',
        ]);

//        Log::info('tour data:'.json_encode($request->all()));
//        Log::info('meysam - log - 1');
        //////CAPTCHA/////////////////////////////////////////////////
//        if(!$request->has('coinhive-captcha-token'))
//        {
//            $message = trans('messages.msgErrorWrongCaptcha');
//            $messages = [$message];
//
//            return redirect()->back()->with('messages', $messages);
//        }
//
//
//        $post_data = [
//            'secret' => "71dB6OFizbg4zi4HdrtlVVTmgjL55QD3", // <- Your secret key
//            'token' => $_POST['coinhive-captcha-token'],
//            'hashes' => 256
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
//            return redirect()->back()->with('messages', $messages);
//        }



          if (!$request->has('chk_terms')) {
            // All bad. Token not verified!
            $message = trans('messages.msgErrorTermsOfUse');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

        //////////////////////////////////////////////////////

        $request->session()->forget('verification');
        //meysam - check user status...
        $tour = new Tour();
        $tour->initialize();
        $tour->tour_id = $request->input('tour_id');
        $tour = DB::table('tours')
            ->where('deleted_at', null)
->where('tour_id', $tour->tour_id)
            ->orderBy('tour_id', 'desc')
            ->get()->first();


        ///////////////////////////////////////چک کردن مهلت ثبت نام و پایان نیافته بودن
        $is_ended = false;
        $temp_start_date = \Carbon\Carbon::parse($tour->start_date_time,"Asia/Tehran");
        if ($temp_start_date->lt(\Carbon\Carbon::now("Asia/Tehran")))
            $is_ended = true;

        $temp_deadline_date_time = \Carbon\Carbon::parse($tour->deadline_date_time,"Asia/Tehran");
        if ($temp_deadline_date_time->lt(\Carbon\Carbon::now("Asia/Tehran")))
            $is_ended = true;

        if ($is_ended)
        {
            // All bad. Token not verified!
            $message = "مهلت ثبت نام پایان یافته است";
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
        /////////////////////////////////////////////////////////////


        $tour->info = json_decode($tour->info);

        $tourId = $request->input('tour_id');
        $features = DB::table('features')
            ->join('tours_features', function($join) use ( $tourId)
            {
                $join->on('tours_features.feature_id','=','features.feature_id');
            })
            ->where('features.deleted_at','=',null)
            ->where('tours_features.deleted_at','=',null)
            ->where('tours_features.tour_id','=',$tourId)
            ->select('features.feature_id','features.feature_guid','features.description','features.name','tours_features.tour_feature_id as feature_uid', 'tours_features.description as more_description','tours_features.cost as cost','tours_features.capacity as capacity','tours_features.count as count','tours_features.is_optional as is_optional','tours_features.is_required as is_required')
            ->orderBy('features.feature_id', 'desc')
            ->get();

//        $tour->features = json_decode($tour->features);
        if ($tour->status != Tour::StatusActive){
            $message = trans('messages.msgErrorTourInactive');
            $messages = [$message];

            return redirect()->back()->with('messages', $messages);
        }


//        Log::info('meysam - log - 2');
        /////////////////////////////////

        if($validation->passes())
        {
            $myInfo = new \stdClass();
            $myInfo->tour_id = $request->input('tour_id');

            $myInfo = Tour::checkOptionalFeatures($myInfo,$request, $features);

            $persons = array();

            $counter = 0;
            $fieldName = 'national_code_'.$counter;

            while ($request->has($fieldName)) {
//                Log::info('$fieldName:'.json_encode($fieldName));
//                Log::info('$request->has($fieldName):'.json_encode($request->has($fieldName)));

                $person = new \stdClass();
                $person->name_and_family = $request->input('name_and_family_' . $counter);
                if ($request->has('mobile_' . $counter)) {
                    $person->mobile = $request->input('mobile_' . $counter);

                    if (!Utility::check_mobile_number($person->mobile)) {
                        $message = trans('messages.msgErrorMobileNumber');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                } else
                    $person->mobile = "";

                if ($request->has('email_' . $counter)) {
                    $person->email = $request->input('email_' . $counter);

                    if (!Utility::checkEmail($person->email)) {
                        $message = trans('messages.msgErrorWrongEmail');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                } else
                    $person->email = "";

                if ($request->has('personal_description_' . $counter))
                {
                    $person->personal_description = $request->input('personal_description_' . $counter);
                } else
                    $person->personal_description = "";

       if (!$request->has('national_code_' . $counter)) {
                    $message = trans('messages.msgErrorNationalCode');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }
                $person->national_code = $request->input('national_code_' . $counter);
              
                if (!Utility::check_national_code($person->national_code)) {
                    $message = trans('messages.msgErrorNationalCode');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

//TODO: meysam - check if each national code registered one time ...
                if(Tour::isNationalCodeExistInReserves($person->national_code,$tourId))
                {
                    $message = trans('messages.msgErrorNationalIdAlreadyRegistered');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                if (!$request->has('birth_date_' . $counter)) {
                    $message = trans('messages.msgErrorBirthDate');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                $person->birth_date = $request->input('birth_date_' . $counter);

               if ($request->has('start_address_' . $counter)) {
                    $person->start_address = $request->input('start_address_' . $counter);
                    if(empty($person->start_address)) {

                        $message = '&#1604;&#1591;&#1601;&#1575; &#1605;&#1705;&#1575;&#1606; &#1587;&#1608;&#1575;&#1585; &#1588;&#1583;&#1606; &#1585;&#1575; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;';
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);

                    }

                } else
                    $person->start_address = "";



                try
                {
                    $request_birth_date =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($person->birth_date));

                }
                catch (\Exception $ex)
                {
                    $message = trans('messages.msgErrorWrongDate');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                if($tour->maximum_age != null && $request_birth_date->age > $tour->maximum_age)
                {
                    $message = trans('messages.msgErrorAge');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }

                if($tour->minimum_age != null && $request_birth_date->age < $tour->minimum_age)
                {
                    $message = trans('messages.msgErrorAge');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }



                array_push($persons, $person);

                $counter++;
                $fieldName = 'national_code_'.$counter;

            }

//            Log::info('persons:'.json_encode($persons));
            $myInfo->persons = $persons;

        }
        else
        {
            $errors = $validation->errors()->all();
            return redirect()->back()->with('errors', $errors);
        }


//        Log::info('count(persons) :'.json_encode(count($persons) ));
//        Log::info('persons :'.json_encode($persons ));
//        Log::info('$tour->remaining_capacity :'.json_encode($tour->remaining_capacity ));

        if(count($persons) > $tour->remaining_capacity)
        {
           $message = trans('messages.msgErrorCapacity');
           $messages = [$message];
           return redirect()->back()->with('messages', $messages);
        }

//        Log::info('data:'.json_encode($request->all()));
        ///////////////////////////////////////////////

        //store in database and retrieve in verification...
        $transaction = new Transaction();
        $transaction->user_id = $tour->user_id;
        $transaction->amount = Reserve::calculateTourTotalCost($myInfo->tour_id, count($myInfo->persons), $myInfo);
//        Log::info('$transaction->amount:'.json_encode($transaction->amount));
        //meysam - check discount code and reduction in cost...
        if($request->has('discount_code') && ($transaction->amount > 0))
        {
            $discounts = DB::table('tours_discounts')
                ->where('tour_id', $tour->tour_id)
                ->where('deleted_at', null)
                ->get();
            foreach ($discounts as $discount)
            {
                if(strcmp($discount->code,$request->input('discount_code')) == 0)
                {
                    $myInfo->tour_discount_id = $discount->tour_discount_id;
                    if(($discount->remaining_capacity - count($myInfo->persons)) >= 0)
                    {
//                        Log::info('$discount:'.json_encode($discount));

                        $rawAmount = $transaction->amount;
                        $reductionAmount = 0.01* $discount->percent * $rawAmount;
                        $transaction->amount = $rawAmount - $reductionAmount;

//                        Log::info('$rawAmount:'.json_encode($rawAmount));
//                        Log::info('$reductionAmount:'.json_encode($reductionAmount));

                        if($transaction->amount < 0)
                            $transaction->amount = 0;

                        $discount->remaining_capacity -= count($myInfo->persons);
                        TourDiscount::editByObject($discount);
                    }
                    else
                    {
                        $message = trans('messages.msgErrorDiscountCapacity');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }

                }
            }
        }
        ////////////////////////////////////////////////////////////////


        $transaction->description = "رزرو در سایت شیراز پیک نیک";

        $transaction->type = transaction::TRANSACTION_TYPE_INCOMING;

        $myInfoJson = json_encode($myInfo);
        $transaction->info = $myInfoJson;


        /// //////////////////////////////// for test/////////////////
////        Log::info('meysam log 3');
//        if($transaction->amount == 0)
//        {
//            $transaction->authority = -Carbon::now()->timestamp;
//            $transaction->status = Transaction::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL;
//            $transaction->initializeByObject($transaction);
//            $transaction->store();
//
//            return redirect()->action(
//                'TransactionController@verificationForFree', ['Status' => 100, 'Authority' => $transaction->authority]
//            );
//        }
//
//        $transaction->authority = -Carbon::now()->timestamp;
//        $transaction->status = Transaction::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL;
//        $transaction->initializeByObject($transaction);
//        $transaction->store();
//
//        return redirect()->action(
//            'TransactionController@verification', ['Status' => 100, 'Authority' => $transaction->authority]
//        );
        ////////////////////////////////////// end for test/////////////


        if($transaction->amount == 0)
        {
            $transaction->authority = -Carbon::now()->timestamp;
            $transaction->status = Transaction::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL;
            $transaction->initializeByObject($transaction);
            $transaction->store();

            return redirect()->action(
                'TransactionController@verificationForFree', ['Status' => 100, 'Authority' => $transaction->authority]
            );
        }


//        Log::info('meysam - log - 3');
        try {
            //get Authority from zarinpal site...
            // meysam - get new from zarinpal...
            $MerchantID = "c24ac388-9df6-11e8-a25d-000c295eb8fc";//get from zarinpal ...
            $Amount = $transaction->amount;
            $Description = "پرداخت هزینه سفارش شیراز پیک نیک";
            if($request->has('email'))
                $Email = $request->input('email');
            else
                $Email = "fardan7eghlim@gmail.com";
            $Mobile = $request->input('mobile');
            $CallbackURL = "http://www.shirazpicnic.ir/transactions/verification";

            $data = array('MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL);
            $jsonData = json_encode($data);

            //meysam - main
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');

            //meysam - for test
       //     $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');


            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ));
            $result = curl_exec($ch);

            $err = curl_error($ch);
            $result = json_decode($result, true);
            curl_close($ch);

//            Log::info('$result:'.json_encode($result));

            if ($err) {
//                Log::info('meysam - log - 4');
                echo "cURL Error #:" . $err;
            } else {
                if ($result["Status"] == 100) {
//                    Log::info('meysam - log - 5');
                    /////store Authority in session////
                    // Via the global helper...
                    if(strlen($result["Authority"]) == 36)
                    {
//                        Log::info('meysam - log - 6');
                        ////store in database and retrieve in verification...
                        $transaction->authority = $result["Authority"];
                        $transaction->status = $result["Status"];
                        $transaction->initializeByObject($transaction);
                        $transaction->store();
                        /// ////////////////////////////////

//                            meysam - main
                            return  new RedirectResponse('https://www.zarinpal.com/pg/StartPay/' . $result["Authority"].'/ZarinGate');

//                            meysam - test
              //          return  new RedirectResponse('https://sandbox.zarinpal.com/pg/StartPay/' . $result["Authority"]);
                    }
                    else
                    {
                        //return error
                        ////store in database and retrieve in verification...
//                        Log::info('meysam - log - 7');
                        $transaction->status = $result["Status"];
                        $transaction->authority = -rand(10,100);

                        $transaction->initializeByObject($transaction);
                        $transaction->store();
                        /// ////////////////////////////////
                        $message = trans('messages.msgOperationFailed');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                } else {
//                    Log::info('meysam - log - 8');
                    ////store in database and retrieve in verification...
                    $transaction->status = $result["Status"];
                    $transaction->authority = -rand(10,100);

                    $transaction->initializeByObject($transaction);
                    $transaction->store();
                    /// ////////////////////////////////

                    $message = trans('messages.msgOperationFailed');
                    $messages = [$message];
                    return redirect()->back()->with('messages', $messages);
                }
            }

        } catch (Exception $e) {
//            Log::info('meysam - log - 9');
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }

    }

    public function verification()
    {
        if(session('verification', false))
        {
            return redirect('/');
        }

        // Store a piece of data in the session...
        session(['verification' => true]);

        ///////meysam - for test
//        $Status = $_GET['Status'];
//        $Authority = $_GET['Authority'];
//        //get stored transaction...
//        $transaction = DB::table('transactions')
//                ->where('authority', '=', $Authority)
//                ->where('deleted_at',null)
//                ->get()->first();
//        $transaction->status = $Status;
//
//        $reserve = new Reserve();
//        $info = json_decode($transaction->info);
//        $info->transaction_id = $transaction->transaction_id;
//
//
//        $garden = null;
//        $tour = null;
//        if(isset($info->garden_id))
//        {
//            $reserve->garden_id = $info->garden_id;
////            unset($info->garden_id);
//            $reserve->start_date = $info->start_date;
//            unset($info->start_date);
//
//            $reserve->end_date = $info->end_date;
//            unset($info->end_date);
//
//            $garden = Garden::findById($info->garden_id);
//            $garden->info = json_decode($garden->info);
//
//        }
//        else if(isset($info->tour_id))
//        {
//            $reserve->tour_id = $info->tour_id;
//
//
//            $tour = Tour::findById($info->tour_id);
//
//            $tour->social = json_decode( $tour->social);
//            $tour->info = json_decode($tour->info);
//
//            $reserve->start_date = $tour->start_date_time;
//            $reserve->end_date = $tour->end_date_time;
//
//            $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
//            $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
//            $tour->tour_address = json_decode($tour->tour_address );
////            $tour->address = $tour->tour_address->address;
//
//
//
////            unset($info->tour_id);
//        }
//        else
//        {
//
//        }
////        Log::info('here');
//
//        $reserve->info = json_encode($info);
//        $reserve->status = Reserve::StatusActive;
//        $reserve->store();
//
//        $reserve = Reserve::findById($reserve->reserve_id);
//        $reserve->info = json_decode($reserve->info);
//
//        if(isset($info->garden_id))
//        {
//            if($reserve->start_date != null && $reserve->end_date != null)
//            {
//                $reserve->start_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->start_date)); // 1395/02/19
//                $reserve->end_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->end_date)); // 1395/02/19
//            }
//        }
//        else
//        {
////            Log::info('here editRemainingCapacity');
//            Tour::editRemainingCapacity($tour->tour_id,$tour->tour_guid,$tour->remaining_capacity - count($reserve->info->persons));
//            //TODO: meysam - check if remaining capacity is full return error
//            Tour::editRemainingCapacityForFeatures($tour->tour_id,$info->informations );
//
//        }
//
//
//        $url = url('/reservs/detail/'.$reserve->reserve_id.'/'.$reserve->reserve_guid);
//
//        $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($url);
//        $png = base64_encode($png);
//
//
////        Log::info('dd'.json_encode($info));
//        Transaction::editReserveId($transaction->transaction_id,$transaction->transaction_guid,$reserve->reserve_id);
//        /////////meysam - send sms and email/////////////
////        $gardenOwner = User::findById($garden->user_id) ;
////        $message = "رزرو شما با موفقیت انجام شد. لینک بلیط:".$url;
//
////        try
////        {
////
////            if($reserve->info->email != null && strcmp($reserve->info->email,"") != 0)
////                Utility::sendInformMail($message,$reserve->info->email);
////        }
////        catch (\Exception $ex)
////        {
////            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
////            $logEvent->store();
////        }
//
////        Utility::sendReserveSMS($message, $info->persons[0]->mobile);
////        Log::info(json_encode($gardenOwner));
////        $message = " یک رزرو برای باغ ".$garden->name." ثبت شد. لینک مشاهده: ".$url;
////        Utility::sendReserveSMS($message, $gardenOwner->mobile);
//
////        try
////        {
////
////            if($gardenOwner->email != null && strcmp($gardenOwner->email,"") != 0)
////                Utility::sendInformMail($message,$gardenOwner->email);
////        }
////        catch (\Exception $ex)
////        {
////            $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
////            $logEvent->store();
////        }
//
//
//
//        //////////////////////////////////////////////////
////        Log::info('tour:'.json_encode($tour));
//
//        $discount = null;
//        if(isset($info->tour_discount_id))
//        {
//            $discount = TourDiscount::findById($info->tour_discount_id);
//        }
//
//        return view('transaction.result',['reserve' => $reserve, 'garden' => $garden, 'tour' => $tour, 'png' => $png, 'transaction' => $transaction, 'discount' => $discount])->with('messages' ,[trans('messages.msgTransactionResult')]);
        ////////////////////////////////////meysam - end for test

        try {

            $Status = $_GET['Status'];
            $Authority = $_GET['Authority'];
            //get stored transaction...
            $transaction = DB::table('transactions')
                ->where('authority', '=', $Authority)
                ->where('deleted_at',null)
                ->get()->first();
            $transaction->status = $Status;
            $transaction->authority = $Authority;

            $reserve = new Reserve();
            $reserve->reserve_id = null;
            $info = json_decode($transaction->info);
            $info->transaction_id = $transaction->transaction_id;

            $garden = null;
            $tour = null;
            if(isset($info->garden_id))
            {
                $reserve->garden_id = $info->garden_id;
//            unset($info->garden_id);
                $reserve->start_date = $info->start_date;
                unset($info->start_date);

                $reserve->end_date = $info->end_date;
                unset($info->end_date);

                $garden = Garden::findById($info->garden_id);
                $garden->info = json_decode($garden->info);

            }
            else if(isset($info->tour_id))
            {
                $reserve->tour_id = $info->tour_id;


                $tour = Tour::findById($info->tour_id);

        $tour->social = json_decode( $tour->social);
                $tour->info = json_decode($tour->info);

                $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
                $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
 $tour->tour_address = json_decode($tour->tour_address );
            //$tour->address = $tour->tour_address->address;

                $reserve->start_date = $tour->start_date_time;
                $reserve->end_date = $tour->end_date_time;

//            unset($info->tour_id);
            }
            else
            {

            }


            $reserve->info = json_encode($info);
            $reserve->status = Reserve::StatusActive;

            ///////////////////////////

                //////set created_at manualy wrt company time_zone////////
                $data = array('MerchantID' => 'c24ac388-9df6-11e8-a25d-000c295eb8fc', 'Authority' => $Authority, 'Amount' => $transaction->amount);
                $jsonData = json_encode($data);

                //meysam - main
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');

                //meysam - for test
           //     $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');

                curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)
                ));

                $result = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);
                $result = json_decode($result, true);


            $png = null;
            if ($err) {


                $png = null;

            } else {
                if ($result['Status'] == 100) {


                    $transaction->status = $result['Status'];
                    $reserve->store();

                    $reserve = Reserve::findById($reserve->reserve_id);
                    $reserve->start_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->start_date)); // 1395/02/19
                    $reserve->end_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->end_date)); // 1395/02/19

                    $reserve->info = json_decode($reserve->info);

                    $garden = null;
                    $tour = null;
                    if($reserve->garden_id != null)
                    {
                        $garden = Garden::findById($reserve->garden_id);
                        $garden->info = json_decode($garden->info);
                    }
                    else
                    {
                        $tour = Tour::findById($reserve->tour_id);
        $tour->social = json_decode( $tour->social);
                        $tour->info = json_decode($tour->info);
        $tour->tour_address = json_decode($tour->tour_address );
                    }


                    $url = url('/reservs/detail/'.$reserve->reserve_id.'/'.$reserve->reserve_guid);
                    $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($url);
                    $png = base64_encode($png);

                    if(!isset($info->garden_id))
                    {
//                Log::info('$tour:'.json_encode($tour));
//                Log::info('$reserve->info->persons:'.json_encode($reserve->info->persons));

                        Tour::editRemainingCapacity($tour->tour_id,$tour->tour_guid,$tour->remaining_capacity - count($reserve->info->persons));
                        Tour::editRemainingCapacityForFeatures($tour->tour_id,$info->informations );
                    }
                    /////////meysam - send sms and email/////////////

                    $message = "رزرو شما با موفقیت انجام شد. لینک بلیط:".$url;
                    Utility::sendReserveSMS($message, $info->persons[0]->mobile);

//                    if($garden != null)
//                    {
//                        $owner = User::findById($garden->user_id);
//                        $message = " یک رزرو برای باغ ".$garden->name." ثبت شد. لینک مشاهده: ".$url;
//
//                    }
//                    else
//                    {
//                        $owner = User::findById($tour->user_id);
//                        $message = " رزرو برای تور ".$tour->name." ثبت شد. لینک مشاهده: ".$url;
//
//                    }

//                    Utility::sendReserveSMS($message, $owner->mobile);
//                    try
//                    {
//                        if($gardenOwner->email != null && strcmp($owner->email,"") != 0)
//                            Utility::sendInformMail($message,$owner->email);
//                    }
//                    catch (\Exception $ex)
//                    {
//                        $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
//                        $logEvent->store();
//                    }
                    //////////////////////////////////////////////////



                } else {
                    if($Status == "NOK")
                    {
                        //transaction failed.... insert to transaction and return to transaction page
                        $transaction->status = -2;
                    }
                    else
                    {
                        $transaction->status = $result['Status'];
                    }
                }
            }
            ////////////////////////////////////////////////////////////

  $discount = null;
        if(isset($info->tour_discount_id))
        {
            $discount = TourDiscount::findById($info->tour_discount_id);
        }

            Transaction::editReserveIdAndStatusAndAuthority($transaction->transaction_id,$transaction->transaction_guid,$reserve->reserve_id);
            return view('transaction.result',['reserve' => $reserve, 'garden' => $garden, 'tour' => $tour, 'png' => $png, 'transaction' => $transaction, 'discount' => $discount])->with('messages' ,[trans('messages.msgTransactionResult')]);

        } catch (Exception $e) {
        $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
        $logEvent->store();
        $message = trans('messages.msgOperationFailed');
        $messages = [$message];
        return redirect()->back()->with('messages', $messages);
        }

    }

    public function verificationForFree()
    {
        if(session('verification', false))
        {
            return redirect('/');
        }

        // Store a piece of data in the session...
        session(['verification' => true]);

        try {

            $Status = $_GET['Status'];
            $Authority = $_GET['Authority'];
            //get stored transaction...
            $transaction = DB::table('transactions')
                ->where('authority', '=', $Authority)
                ->where('deleted_at',null)
                ->get()->first();
            $transaction->status = $Status;
            $transaction->authority = $Authority;

            $reserve = new Reserve();
            $reserve->reserve_id = null;
            $info = json_decode($transaction->info);
            $info->transaction_id = $transaction->transaction_id;

            $garden = null;
            $tour = null;
            if(isset($info->garden_id))
            {
                $reserve->garden_id = $info->garden_id;
//            unset($info->garden_id);
                $reserve->start_date = $info->start_date;
                unset($info->start_date);

                $reserve->end_date = $info->end_date;
                unset($info->end_date);

                $garden = Garden::findById($info->garden_id);
                $garden->info = json_decode($garden->info);

            }
            else if(isset($info->tour_id))
            {
                $reserve->tour_id = $info->tour_id;


                $tour = Tour::findById($info->tour_id);
                $tour->social = json_decode( $tour->social);
                $tour->info = json_decode($tour->info);

                $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
                $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
                $tour->tour_address = json_decode($tour->tour_address );
//                $tour->address = $tour->tour_address->address;

                $reserve->start_date = $tour->start_date_time;
                $reserve->end_date = $tour->end_date_time;

            }
            else
            {
                //meysam - this most never occure....
            }


            $reserve->info = json_encode($info);
            $reserve->status = Reserve::StatusActive;

            ///////////////////////////


            $png = null;

            $reserve->store();

            $reserve = Reserve::findById($reserve->reserve_id);
            $reserve->start_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->start_date)); // 1395/02/19
            $reserve->end_date = \Morilog\Jalali\jDateTime::strftime('Y/n/j', strtotime($reserve->end_date)); // 1395/02/19

            $reserve->info = json_decode($reserve->info);

            $garden = null;
            $tour = null;
            if($reserve->garden_id != null)
            {
                $garden = Garden::findById($reserve->garden_id);
                $garden->info = json_decode($garden->info);
            }
            else
            {
                $tour = Tour::findById($reserve->tour_id);
                $tour->social = json_decode( $tour->social);
                $tour->info = json_decode($tour->info);
                $tour->start_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->start_date_time));
                $tour->end_date_time = jDateTime::strftime('Y-n-j h:m:s', strtotime($tour->end_date_time));
                $tour->tour_address = json_decode($tour->tour_address );
            }


            $url = url('/reservs/detail/'.$reserve->reserve_id.'/'.$reserve->reserve_guid);
            $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($url);
            $png = base64_encode($png);

            if(!isset($info->garden_id))
            {
//                Log::info('$tour editRemainingCapacity:'.json_encode($tour));
//                Log::info('$reserve->info->persons:'.json_encode($reserve->info->persons));

                Tour::editRemainingCapacity($tour->tour_id,$tour->tour_guid,$tour->remaining_capacity - count($reserve->info->persons));
                Tour::editRemainingCapacityForFeatures($tour->tour_id,$info->informations );

            }
            /////////meysam - send sms and email/////////////

//            $message = "رزرو شما با موفقیت انجام شد. لینک بلیط:".$url;
//            Utility::sendReserveSMS($message, $info->persons[0]->mobile);
              $owner = null;
                    if($garden != null)
                    {
                        $owner = User::findById($garden->user_id);
                       $message = " یک رزرو برای باغ ".$garden->name." ثبت شد. لینک مشاهده: ".$url;

                    }
                    else
                    {
                        $owner = User::findById($tour->user_id);
                        $message = " رزرو برای تور ".$tour->name." ثبت شد. لینک مشاهده: ".$url;

                    }

                   // Utility::sendReserveSMS($message, $owner->mobile);
                    try
                    {
                       // if($gardenOwner->email != null && strcmp($owner->email,"") != 0)
                           // Utility::sendInformMail($message,$owner->email);
                        Utility::sendInformMail($message,'fardan7eghlim@gmail.com');
                        //Utility::sendInformMail($message,'meysamarab@yahoo.com');
                    }
                    catch (\Exception $ex)
                    {
                        $logEvent = new LogEvent(null, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
                        $logEvent->store();
                    }
            //////////////////////////////////////////////////
//            Log::info('tour free:'.json_encode($tour));


            $discount = null;
            if(isset($info->tour_discount_id))
            {
                $discount = TourDiscount::findById($info->tour_discount_id);
            }


            Transaction::editReserveIdAndStatusAndAuthority($transaction->transaction_id,$transaction->transaction_guid,$reserve->reserve_id);
            return view('transaction.result',['reserve' => $reserve, 'garden' => $garden, 'tour' => $tour, 'png' => $png, 'transaction' => $transaction, 'discount' => $discount])->with('messages' ,[trans('messages.msgTransactionResult')]);

        } catch (Exception $e) {
        $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
        $logEvent->store();
        $message = trans('messages.msgOperationFailed');
        $messages = [$message];
        return redirect()->back()->with('messages', $messages);
        }

    }

    public function search(Request $request)
    {
//        $validation = Validator::make($request->all(), [
//            'start_date' => 'required|numeric',
//            'end_date' => 'required|numeric',
//        ]);


//        Log::info('all:'.json_encode($request->all()));

        try {

//            if(!$validation->passes())
//            {
//                $errors = $validation->errors()->all();
//                return redirect()->back()->with('errors', $errors);
//            }



            $start_date = null;
            $end_date = null;
            if($request->has('start_date'))
                $start_date =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('start_date')))->toDateString();
            if($request->has('end_date'))
                $end_date =  \Morilog\Jalali\jDateTime::createCarbonFromFormat('Y/n/j', Utility::convert($request->input('end_date')))->toDateString();

//            Log::info('$start_date:'.json_encode($start_date));
//            Log::info('$end_date:'.json_encode($end_date));

            $user_id = null;
            if($request->has('user_id'))
                $user_id = $request->input('user_id');

            if($request->has('tour_id'))
            {
                $query = DB::table('transactions')
                    ->join('reservs', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->where('transactions.deleted_at','=',null)
                    ->where('reservs.tour_id','=',$request->input('tour_id'))
                    ->where('reservs.garden_id','=',null)
                    ->where('reservs.deleted_at','=',null)
                    ->select( 'transactions.created_at','transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('transaction_id', 'desc');

            }
            else if($request->has('garden_id'))
            {
                $query = DB::table('transactions')
                    ->join('reservs', 'reservs.reserve_id', '=', 'transactions.reserve_id')
                    ->where('transactions.deleted_at','=',null)
                    ->where('reservs.tour_id','=',null)
                    ->where('reservs.garden_id','=',$request->input('garden_id'))
                    ->where('reservs.deleted_at','=',null)
                    ->select( 'transactions.created_at','transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('transaction_id', 'desc');
            }
            else
            {
                $query = DB::table('transactions')
                    ->where('transactions.deleted_at','=',null)
                    ->select( 'transactions.created_at','transactions.amount','transactions.type','transactions.description', 'transactions.status','transactions.authority','transactions.info', 'transactions.transaction_id', 'transactions.transaction_guid')
                    ->orderBy('transaction_id', 'desc');
            }



            if($start_date != null)
                $query->where('transactions.created_at','>=',$start_date);

            if($end_date != null)
                $query->where('transactions.created_at','<=',$end_date);

            if($user_id != null)
                $query->where('transactions.user_id','=',$user_id);

            $transactions = $query ->get();


            $totalCost = 0;
            foreach($transactions as $transaction)
            {
                $transaction->info = json_decode($transaction->info);
                $totalCost += $transaction->amount;

            }
            return view('transaction.index',['transactions' => $transactions, 'totalCost' => $totalCost, 'user_id' => $user_id]);

        } catch (Exception $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }



    }

    public function settle($transaction_id, $transaction_guid)
    {

        try
        {

            Transaction::editType($transaction_id,$transaction_guid,Transaction::TRANSACTION_STATUS_SETTLED);

            return redirect()->back()->with('messages' ,[trans('messages.msgOperationSuccess')]);
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