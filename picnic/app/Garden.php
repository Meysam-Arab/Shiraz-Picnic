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
use Morilog\Jalali\jDateTime;

class Garden extends Model
{
    use SoftDeletes;

    const GARDEN_AVATAR_FILE_NAME = 'garden_avatar';
//    const GARDEN_PICTURE_FILE_NAME = 'garden_picture';
//    const GARDEN_CLIP_FILE_NAME = 'garden_clip';

    const GARDEN_AVATAR_FILE_SIZE = 6291456;
    const GARDEN_PICTURE_FILE_SIZE = 6291456;
//    const GARDEN_CLIP_FILE_SIZE = 104857600;

    const GARDEN_PICTURE_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');
//    const GARDEN_CLIP_FILE_TYPES  = array( 'webm', 'ogg', 'mp4');
    const GARDEN_AVATAR_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');

    const TypePublic = 0;
    const TypePrivate = 1;

    const StatusNonActive = 0;
    const StatusActive = 1;
    const StatusWaitForCheck = 2;
    const StatusDisapproved  = 3;

    const ShiftDayId = 0;
    const ShiftNightId = 1;
    const ShiftFullId = 2;

    protected $table = 'gardens';
    protected $primaryKey = 'garden_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','address'];

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
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Get all of the tasks for the user.
     */
    public function reservs()
    {
        return $this->hasMany(Reserve::class, 'reserve_id');
    }

    /**
     * Get all of the gardenFeatures for the user.
     */
    public function gardensFeatures()
    {
        return $this->hasMany(GardenFeature::class, 'garden_id');
    }

    /**
     * Get all of the gardenReports for the user.
     */
    public function gardensReports()
    {
        return $this->hasMany(GardenReport::class, 'garden_id');
    }

    /**
     * Get all of the gardenGuarde for the user.
     */
    public function gardensGuards()
    {
        return $this->hasMany(GardenGuard::class, 'garden_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($garden) {
            $garden->reservs()->delete();
            $garden->gardensFeatures()->delete();
            $garden->gardensReports()->delete();
            $garden->gardensGuards()->delete();
        });
    }


    public function initialize()
    {
        $this -> garden_id = null;
        $this -> garden_guid = null;
        $this -> user_id = null;
        $this -> type = null;
        $this -> media = null;
        $this -> address = null;
        $this -> lat_lon = null;
        $this -> name = null;
        $this -> periodic_holidays = null;
        $this -> on_time_holidays = null;
        $this -> periodic_costs = null;
        $this -> social = null;
        $this -> status = null;
        $this -> regulation = null;
        $this -> like_count = null;
        $this -> report_count = null;
        $this -> info = null;
        $this -> features = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> garden_id = $request ->input('garden_id');
        $this -> garden_guid = $request ->input('garden_guid');
        $this -> user_id = $request ->input('user_id');
        $this -> type = $request ->input('type');
        $this -> media = $request ->input('media');
        $this -> address = $request ->input('address');
        $this -> lat_lon = $request ->input('lat_lon');
        $this -> name = $request ->input('name');
        $this -> periodic_holidays = $request ->input('periodic_holidays');
        $this -> on_time_holidays = $request ->input('on_time_holidays');
        $this -> periodic_costs = $request ->input('periodic_costs');
        $this -> status = $request ->input('status');
        $this -> social = $request ->input('social');
        $this -> regulation = $request ->input('regulation');
        $this -> like_count = $request ->input('like_count');
        $this -> report_count = $request ->input('report_count');
        $this -> info = $request ->input('info');
        $this -> features = $request ->input('features');
    }

    public function store()
    {
        $this->garden_guid = uniqid('',true);
        $this->save();

        self::generateAllDirectories();
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/gardens/'.$this->garden_id.'/avatar';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/gardens/'.$this->garden_id.'/pictures';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/gardens/'.$this->garden_id.'/clips';
        File::makeDirectory($directory,0777,true);
    }

    //meysam - $ored = 'asc' , 'desc'
    public function select($order = null, $orderColumn = null, $topCount = null)
    {
        $query = $this->newQuery();
        if($this->garden_id != null){
            $query->where('garden_id', '=', $this->garden_id);
        }
        if($this->garden_guid != null){
            $query->where('garden_guid', 'like', $this->garden_guid);
        }
        if($this->user_id != null){
            $query->where('user_id', '=', $this->user_id);
        }
        if($this->type != null){
            $query->where('type', '=', $this->type);
        }
        if($this->media != null){
            $query->where('media', 'like', $this->media);
        }
        if($this->address != null){
            $query->where('address', 'like', $this->address);
        }
        if($this->lat_lon != null){
            $query->where('lat_lon', 'like', $this->lat_lon);
        }
        if($this->name != null){
            $query->where('name', 'like', $this->name);
        }
        if($this->periodic_holidays != null){
            $query->where('periodic_holidays', 'like', $this->periodic_holidays);
        }
        if($this->on_time_holidays != null){
            $query->where('on_time_holidays', 'like', $this->on_time_holidays);
        }
        if($this->periodic_costs != null){
            $query->where('periodic_costs', 'like', $this->periodic_costs);
        }
        if($this->social != null){
            $query->where('social', 'like', $this->social);
        }
        if($this->status != null){
            $query->where('status', '=', $this->status);
        }
        if($this->regulation != null)
        {
            $query->where('regulation', 'like', $this->regulation);
        }
        if($this->like_count != null)
        {
            $query->where('like_count', '=', $this->like_count);
        }
        if($this->report_count != null)
        {
            $query->where('report_count', '=', $this->report_count);
        }
        if($this->info != null)
        {
            $query->where('info', 'like', $this->info);
        }
        if($this->features != null)
        {
            $query->where('features', 'like', $this->features);
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
                $query ->orderBy('garden_id',$order);
            }
        }


        if (null != $topCount)
        {
            $query ->take($topCount);
        }

        $gardens = $query->get();

        return $gardens;
    }

    ////simplest query
    public function makeWhere($query){
        if($this->garden_id != null){
            $query->where('garden_id', '=', $this->garden_id);
        }
        if($this->garden_guid != null){
            $query->where('garden_guid', 'like', $this->garden_guid);
        }
        if($this->user_id != null){
            $query->where('user_id', '=', $this->user_id);
        }
        if($this->type != null){
            $query->where('type', '=', $this->type);
        }
        if($this->media != null){
            $query->where('media', 'like', $this->media);
        }
        if($this->address != null){
            $query->where('address', 'like', $this->address);
        }
        if($this->lat_lon != null){
            $query->where('lat_lon', 'like', $this->lat_lon);
        }
        if($this->name != null){
            $query->where('name', 'like', $this->name);
        }
        if($this->periodic_holidays != null){
            $query->where('periodic_holidays', 'like', $this->periodic_holidays);
        }
        if($this->on_time_holidays != null){
            $query->where('on_time_holidays', 'like', $this->on_time_holidays);
        }
        if($this->periodic_costs != null){
            $query->where('periodic_costs', 'like', $this->periodic_costs);
        }
        if($this->social != null){
            $query->where('social', 'like', $this->social);
        }
        if($this->status != null){
            $query->where('status', '=', $this->status);
        }
        if($this->regulation != null)
        {
            $query->where('regulation', 'like', $this->regulation);
        }
        if($this->like_count != null)
        {
            $query->where('like_count', '=', $this->like_count);
        }
        if($this->report_count != null)
        {
            $query->where('report_count', '=', $this->report_count);
        }
        if($this->info != null)
        {
            $query->where('info', 'like', $this->info);
        }
        if($this->features != null)
        {
            $query->where('features', 'like', $this->features);
        }

        return $query;
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $garden = new Garden();
        $query = $garden->newQuery();
        $query->where('garden_id', '=', $id);
        $query->where('garden_guid', 'like', $guid);
        $garden = $query->get()->first();
        if (!$garden){
            return null;
        }
        else{
            return $garden;
        }

    }

    public static function removeByIdAndGuid($garden_id, $garden_guid){
        Garden::findByIdAndGuid($garden_id,$garden_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $garden = new Garden();
        $query = $garden->newQuery();
        $query->where('garden_id', '=', $Id);
        $query->where('garden_guid', 'like', $guid);
        $gardens = $query->get();
        if (count($gardens) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function getStatusStringByCode($statusCode)
    {
        if($statusCode == Garden::StatusActive)
            return trans('messages.lblStatusActive');
        else if($statusCode == Garden::StatusWaitForCheck)
            return trans('messages.lblStatusWaitForApproval');
        else if($statusCode == Garden::StatusDisapproved)
            return trans('messages.lblStatusDisapproved');
        else
            return trans('messages.txtUndefined');
    }

    public static function getTypeStringByCode($typeCode)
    {
        if($typeCode == Garden::TypePublic)
            return trans('messages.txtTypePublic');
        else if($typeCode == Garden::TypePrivate)
            return trans('messages.txtTypePrivate');
        else
            return trans('messages.txtUndefined');
    }

    public static function getShiftStringByCode($shiftCode)
    {
        if($shiftCode == Garden::ShiftDayId)
            return trans('messages.txtShiftDay');
        else if($shiftCode == Garden::ShiftNightId)
            return trans('messages.txtShiftNight');
        else if($shiftCode == Garden::ShiftFullId)
            return trans('messages.txtShiftFull');
        else
            return trans('messages.txtUndefined');
    }

    public static function changeStatus($garden_id, $status)
    {
        $oldGarden = Garden::findById($garden_id);
        $oldGarden -> status = $status;
        $oldGarden->save();
    }

    public static function findById($id)
    {
        $garden = new Garden();
        $query = $garden->newQuery();
        $query->where('garden_id', '=', $id);
        $garden = $query->get()->first();
        if (!$garden){
            return null;
        }
        else{
            return $garden;
        }

    }

    public static function getReservedDatesArray($garden_id)
    {
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
                if(strcmp($info->reserved_kind,Utility::convert("1")) == 0)
                {
                    //meysam - singular day event...
                    $day_data = new \stdClass();
                    $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->start_date)); // 1395/02/19
                    $day_data->shift_id = $info->shift_id_1;
                    array_push($reserved_dates, $day_data);
                }
                else
                {
                    //meysam - multiple day event...
                    $day_data = new \stdClass();
                    $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->start_date)); // 1395/02/19
                    $day_data->shift_id = $info->shift_id_1;
                    array_push($reserved_dates, $day_data);

                    $sDate = Carbon::parse($reserved_date->start_date);
                    $eDate = Carbon::parse($reserved_date->end_date);
                    while(true)
                    {
                        $date = $sDate->addDay(1);
                        if(strcmp($date->toDateString(),$eDate->toDateString()) == 0)
                            break;
                        $day_data = new \stdClass();
                        $day_data->date = jDateTime::strftime('Y-n-j', strtotime($date->toDateString())); // 1395/02/19
                        $day_data->shift_id = Garden::ShiftFullId;
                        array_push($reserved_dates, $day_data);
                    }
                    $day_data = new \stdClass();
                    $day_data->date = jDateTime::strftime('Y-n-j', strtotime($reserved_date->end_date)); // 1395/02/19
                    $day_data->shift_id = $info->shift_id_2;
                    array_push($reserved_dates, $day_data);
                }
            }
        }

        return $reserved_dates;

    }
}