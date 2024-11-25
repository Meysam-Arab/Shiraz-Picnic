<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:14 PM
 */

namespace App;

use App\Utilities\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Log;
use Morilog\Jalali\jDateTime;

class Reserve extends Model
{
    use SoftDeletes;

    const StatusNonActive = 0;
    const StatusActive = 1;
    const StatusWaitForCheck = 2;
    const StatusDisapproved  = 3;

    const TypeSingleDay  = 1;
    const TypeSeveralDays  = 2;

    protected $table = 'reservs';
    protected $primaryKey = 'reserve_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['start_date_time','end_date_time'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Get the user that owns the comment.
     *
     */
    public function garden()
    {
        return $this->belongsTo(Garden::class, 'garden_id');
    }

    /**
     * Get the user that owns the comment.
     *
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }


    public function initialize()
    {
        $this -> reserve_id = null;
        $this -> reserve_guid = null;
        $this -> garden_id = null;
        $this -> start_date_time = null;
        $this -> end_date_time = null;
        $this -> info = null;
        $this -> status = null;

    }

    public function initializeByRequest(Request $request)
    {

        $this -> reserve_id = $request ->input('reserve_id');
        $this -> reserve_guid = $request ->input('reserve_guid');
        $this -> garden_id = $request ->input('garden_id');
        $this -> start_date_time = $request ->input('start_date_time');
        $this -> end_date_time = $request ->input('end_date_time');
        $this -> info = $request ->input('info');
        $this -> status = $request ->input('status');

    }

    public function store()
    {
        $this->reserve_guid = uniqid('',true);
        $this->save();

    }

    //meysam - $ored = 'asc' , 'desc'
    public function select($order = null, $orderColumn = null, $topCount = null)
    {
        $query = $this->newQuery();
        if($this->reserve_id != null){
            $query->where('reserve_id', '=', $this->reserve_id);
        }
        if($this->reserve_guid != null){
            $query->where('reserve_guid', 'like', $this->reserve_guid);
        }
        if($this->garden_id != null){
            $query->where('garden_id', '=', $this->garden_id);
        }
        if($this->start_date_time != null){
            $query->where('start_date_time', 'like', $this->start_date_time);
        }
        if($this->end_date_time != null){
            $query->where('end_date_time', 'like', $this->end_date_time);
        }
        if($this->info != null){
            $query->where('info', 'like', $this->info);
        }
        if($this->status != null){
            $query->where('status', '=', $this->status);
        }

        $query->where('deleted_at', null);


        if (null != $order)
        {
            if (null != $orderColumn)
            {
                $query ->orderBy($orderColumn,$order);
            }
            else
            {
                $query ->orderBy('reserve_id',$order);
            }
        }


        if (null != $topCount)
        {
            $query ->take($topCount);
        }

        $reservs = $query->get();

        return $reservs;
    }

    ////simplest query
    public function makeWhere($query){
        if($this->reserve_id != null){
            $query->where('reserve_id', '=', $this->reserve_id);
        }
        if($this->reserve_guid != null){
            $query->where('reserve_guid', 'like', $this->reserve_guid);
        }
        if($this->garden_id != null){
            $query->where('garden_id', '=', $this->garden_id);
        }
        if($this->start_date_time != null){
            $query->where('start_date_time', 'like', $this->start_date_time);
        }
        if($this->end_date_time != null){
            $query->where('end_date_time', 'like', $this->end_date_time);
        }
        if($this->info != null){
            $query->where('info', 'like', $this->info);
        }
        if($this->status != null){
            $query->where('status', '=', $this->status);
        }

        return $query;
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $reserve = new Reserve();
        $query = $reserve->newQuery();
        $query->where('reserve_id', '=', $id);
        $query->where('reserve_guid', 'like', $guid);
        $reservs = $query->get();
        if (count($reservs) == 0){
            return null;
        }
        else{
            return $reservs[0];
        }

    }

    public static function removeByIdAndGuid($reserve_id, $reserve_guid){
        Reserve::findByIdAndGuid($reserve_id,$reserve_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $reserve = new Reserve();
        $query = $reserve->newQuery();
        $query->where('reserve_id', '=', $Id);
        $query->where('reserve_guid', 'like', $guid);
        $reservs = $query->get()->first();
        if (count($reservs) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function findById($id)
    {
        $reserve = new Reserve();
        $query = $reserve->newQuery();
        $query->where('reserve_id', '=', $id);
        $reservs = $query->get();
        if (count($reservs) == 0){
            return null;
        }
        else{
            return $reservs[0];
        }

    }

    public static function calculateGardenTotalCost($gardenId, $reserveType, $startDateMiladi, $endDateMiladi, $startWeekDayId, $endWeekDayId, $shiftId_1, $shiftId_2)
    {
//        Log::info('$startWeekDayId:'.json_encode($startWeekDayId));
//        Log::info('$endWeekDayId:'.json_encode($endWeekDayId));
//        Log::info('$shiftId_1:'.json_encode($shiftId_1));
//        Log::info('$shiftId_2:'.json_encode($shiftId_2));



        $cost = 0;
        $garden = Garden::findById($gardenId);
        $periodicCosts = json_decode( $garden->periodic_costs);
        $periodicHolidays = json_decode( $garden->periodic_holidays);
        $oneTimeHolidays = json_decode( $garden->on_time_holidays);
//        Log::info(json_encode($periodicCosts));
//        Log::info(json_encode($garden));
//        Log::info(json_encode($startDateMiladi));
//        Log::info(json_encode($endDateMiladi));
        if($reserveType == Reserve::TypeSeveralDays)
        {
            //multiple days

            $sDate = Carbon::parse($startDateMiladi);
            $eDate = Carbon::parse($endDateMiladi);

            $cost += Reserve::getPeriodicCost($periodicCosts, $startWeekDayId, $shiftId_1);
            $cost += Reserve::getPeriodicCost($periodicCosts, $endWeekDayId, $shiftId_2);

            while(true)
            {

                $date = $sDate->addDay(1);
//                Log::info('$date:'.json_encode($date));
//                Log::info('$date:'.json_encode($date->toDateString()));
//                Log::info('$date:'.json_encode($eDate->toDateString()));
                $dayOfweek = Utility::getCorrectDayOFWeek($date->dayOfWeek);
                if(strcmp($date->toDateString(),$eDate->toDateString()) == 0)
                    break;
                if(Reserve::isInPeriodicHolidays($periodicHolidays,$dayOfweek))
                    continue;
                if(Reserve::isInOneTimeHolidays($oneTimeHolidays,$date->format('Y-m-d')))
                    continue;

                $cost += Reserve::getPeriodicCost($periodicCosts, $dayOfweek, Garden::ShiftFullId);

            }

        }
        else
        {
            //single day
//            foreach($periodicCosts as $periodicCost)
//            {
//                if($periodicCost->day_of_week == $startWeekDayId)
//                {
//                    $cost = $periodicCost->shifts[$shiftId_1]->cost;
//                }
//            }
            $cost = Reserve::getPeriodicCost($periodicCosts,$startWeekDayId,$shiftId_1);
        }
        //meysam -  find day of the week for specific
        return $cost;
    }

    public static function calculateTourTotalCost($tourId, $personCount, $info)
    {

        $tour = Tour::findById($tourId);

        if($tour->wholesale_discount  != null)
        {
            $tour->wholesale_discount = json_decode($tour->wholesale_discount );
        }

        if($tour->wholesale_discount->is_active == 1 && $personCount > 1)
        {
            $mainPersonCost = $tour->cost;

            $reductionPersonCost = 0.01 * ($personCount - 1) * $tour->wholesale_discount->percent * $mainPersonCost;
            $mainPersonCost -= $reductionPersonCost;

            if($mainPersonCost < 0)
                $mainPersonCost = 0;
            $cost = (($personCount - 1) * $tour->cost) + $mainPersonCost;
        }
        else
        {
            $cost = $tour->cost * $personCount;
        }
//        Log::info(json_encode($tour));
//        Log::info(json_encode($personCount));


        $cost = $cost + $info->optional_cost;


//        Log::info(json_encode($tour));

        //meysam -  find day of the week for specific
        return $cost;
    }

    public static function isInPeriodicHolidays($periodicHolidays, $dayOfWeek)
    {
        foreach($periodicHolidays as $periodicHoliday)
        {
            if($periodicHoliday->day_of_week == $dayOfWeek)
            {
                return true;
            }
        }

        return false;
    }

    public static function isInOneTimeHolidays($oneTimeHolidays, $date)
    {
//        Log::info('$date:'.json_encode($date));

        foreach($oneTimeHolidays as $oneTimeHoliday)
        {
            $shamsiDate = jDateTime::strftime('Y-n-j', strtotime($date));
            if(strcmp($shamsiDate,$oneTimeHoliday->date) == 0)
            {
                return true;
            }
        }

        return false;
    }

    public static function getPeriodicCost($periodicCosts, $dayOfWeek, $shiftId)
    {
        foreach($periodicCosts as $periodicCost)
        {
            if($periodicCost->day_of_week == $dayOfWeek)
            {
                return $periodicCost->shifts[$shiftId]->cost;
            }
        }

        return 0;
    }

    public static function getStatusStringByCode($statusCode)
    {
        if($statusCode == Reserve::StatusActive)
            return trans('messages.lblStatusActive');
        else if($statusCode == Reserve::StatusWaitForCheck)
            return trans('messages.lblStatusWaitForApproval');
        else if($statusCode == Reserve::StatusDisapproved)
            return trans('messages.lblStatusDisapproved');
        else
            return trans('messages.txtUndefined');
    }

    public static function isPeriodicCostEnabled($gardenId, $reserveType, $startDateMiladi, $endDateMiladi, $startWeekDayId, $endWeekDayId, $shiftId_1, $shiftId_2)
    {

        $garden = Garden::findById($gardenId);
        $periodicCosts = json_decode( $garden->periodic_costs);
        $periodicHolidays = json_decode( $garden->periodic_holidays);
        $oneTimeHolidays = json_decode( $garden->on_time_holidays);
//        Log::info(json_encode($periodicCosts));
//        Log::info(json_encode($garden));
//        Log::info(json_encode($startDateMiladi));
//        Log::info(json_encode($endDateMiladi));
        if($reserveType == Reserve::TypeSeveralDays)
        {
            //multiple days

            $sDate = Carbon::parse($startDateMiladi);
            $eDate = Carbon::parse($endDateMiladi);

            while(true)
            {

                $date = $sDate->addDay(1);

                $dayOfweek = Utility::getCorrectDayOFWeek($date->dayOfWeek);
                if(strcmp($date->toDateString(),$eDate->toDateString()) == 0)
                    break;
                if(Reserve::isInPeriodicHolidays($periodicHolidays,$dayOfweek))
                    continue;
                if(Reserve::isInOneTimeHolidays($oneTimeHolidays,$date->format('Y-m-d')))
                    continue;

                if(Reserve::isShiftEnabled($periodicCosts, $dayOfweek, Garden::ShiftFullId))
                    return true;
                else
                    return false;

            }

        }
        else
        {
            //single day

            if(Reserve::isShiftEnabled($periodicCosts,$startWeekDayId,$shiftId_1))
                return true;
            else
                return false;

        }
        //meysam -  find day of the week for specific
        return false;




    }

    public static function isShiftEnabled($periodicCosts, $dayOfWeek, $shiftId)
    {
        foreach($periodicCosts as $periodicCost)
        {
            if($periodicCost->day_of_week == $dayOfWeek)
            {
                if($periodicCost->shifts[$shiftId]->status == Garden::StatusActive)
                    return true;
                else
                    return false;
            }
        }

        return false;
    }
}