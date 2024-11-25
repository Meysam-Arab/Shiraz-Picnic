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
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\jDateTime;
use Log;
use File;

class Tour extends Model
{
    use SoftDeletes;

    const TourSiteShare = 0.5;

    const TOUR_AVATAR_FILE_NAME = 'tour_avatar';
    const TOUR_PICTURE_FILE_NAME = 'tour_picture';
//    const TOUR_CLIP_FILE_NAME = 'tour_clip';

    const TOUR_AVATAR_FILE_SIZE = 6291456;
    const TOUR_PICTURE_FILE_SIZE = 6291456;
    const TOUR_CLIP_FILE_SIZE = 104857600;

    const TOUR_PICTURE_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');
    const TOUR_CLIP_FILE_TYPES  = array( 'webm', 'ogg', 'mp4');
    const TOUR_AVATAR_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');

    const StatusNonActive = 0;
    const StatusActive = 1;
    const StatusWaitForCheck = 2;
    const StatusDisapproved  = 3;
    const StatusCanceled  = 4;

    const Statuses = [Tour::StatusNonActive
        , Tour::StatusActive
        , Tour::StatusWaitForCheck
        , Tour::StatusDisapproved
        , Tour::StatusCanceled];

    const HardshipLevelEasy = 0;
    const HardshipLevelMedium = 1;
    const HardshipLevelHard = 2;
    const HardshipLevelVeryHard  = 3;
    const HardshipLevelUltraHard  = 4;

    const HardshipLevels = [Tour::HardshipLevelEasy
        , Tour::HardshipLevelMedium
        , Tour::HardshipLevelHard
        , Tour::HardshipLevelVeryHard
        , Tour::HardshipLevelUltraHard];

    const SocialTelegram = 0;
    const SocialInstagram = 1;
    const SocialWhatsapp = 2;
    const SocialSoroush  = 3;
    const SocialBale  = 4;
    const SocialIgap  = 5;
    const SocialLine  = 6;
    const SocialFacebook  = 7;
    const SocialTwitter  = 8;

    const Socials = [Tour::SocialTelegram
        , Tour::SocialInstagram
        , Tour::SocialWhatsapp
        , Tour::SocialSoroush
        , Tour::SocialBale
        , Tour::SocialIgap
        , Tour::SocialLine
        , Tour::SocialFacebook
        , Tour::SocialTwitter];

    const GenderBoth  = 3;
    const GenderMale  = 1;
    const GenderFemale  = 2;

    const Genders = [Tour::GenderBoth
        , Tour::GenderMale
        , Tour::GenderFemale];


    protected $table = 'tours';
    protected $primaryKey = 'tour_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','tour_address','description'];

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
        return $this->hasMany(Reserve::class, 'tour_id');
    }

    public function toursLeaders()
    {
        return $this->hasMany(TourLeader::class);
    }

    public function toursReports()
    {
        return $this->hasMany(TourReport::class);
    }

    public function toursFeatures()
    {
        return $this->hasMany(TourFeature::class);
    }

    public function toursDiscounts()
    {
        return $this->hasMany(TourDiscount::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($tour) {
            $tour->reservs()->delete();
            $tour->toursLeaders()->delete();
            $tour->toursReports()->delete();
            $tour->toursFeatures()->delete();
            $tour->toursDiscounts()->delete();
        });
    }

    public function initialize()
    {
        $this -> tour_id = null;
        $this -> tour_guid = null;
        $this -> user_id = null;
        $this -> title = null;
        $this -> description  = null;
        $this -> tour_address = null;
        $this -> total_capacity = null;
        $this -> remaining_capacity = null;
        $this -> start_date_time  = null;
        $this -> end_date_time  = null;
        $this -> deadline_date_time  = null;
        $this -> info = null;
        $this -> status  = null;
        $this -> minimum_age = null;
        $this -> maximum_age = null;
        $this -> like_count = null;
        $this -> gender  = null;
        $this -> hardship_level = null;
        $this -> cost  = null;
        $this -> gathering_place = null;
        $this -> social  = null;
        $this -> stroked_cost = null;
        $this -> wholesale_discount   = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> tour_id = $request ->input('tour_id');
        $this -> tour_guid = $request ->input('tour_guid');
        $this -> user_id = $request ->input('user_id');
        $this -> title = $request ->input('title');
        $this -> description  = $request ->input('description');
        $this -> tour_address = $request ->input('tour_address');
        $this -> total_capacity = $request ->input('total_capacity');
        $this -> remaining_capacity = $request ->input('remaining_capacity');
        $this -> start_date_time  = $request ->input('start_date_time');
        $this -> end_date_time  = $request ->input('end_date_time');
        $this -> deadline_date_time  = $request ->input('deadline_date_time');
        $this -> info = $request ->input('info');
        $this -> status  = $request ->input('status');
        $this -> minimum_age = $request ->input('minimum_age');
        $this -> maximum_age = $request ->input('maximum_age');
        $this -> like_count = $request ->input('like_count');
        $this -> gender  = $request ->input('gender');
        $this -> hardship_level = $request ->input('hardship_level');
        $this -> cost  = $request ->input('cost');
        $this -> gathering_place = $request ->input('gathering_place');
        $this -> social  = $request ->input('social');
        $this -> stroked_cost = $request ->input('stroked_cost');
        $this -> wholesale_discount   = $request ->input('wholesale_discount');
    }

    public function store()
    {
        $this->tour_guid = uniqid('',true);
        $this->save();
        self::generateAllDirectories();
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/tours/'.$this->tour_id.'/avatar';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/tours/'.$this->tour_id.'/pictures';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/tours/'.$this->tour_id.'/clips';
        File::makeDirectory($directory,0777,true);
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $tour = new Tour();
        $query = $tour->newQuery();
        $query->where('tour_id', '=', $id);
        $query->where('tour_guid', 'like', $guid);
        $tour = $query->get()->first();
        if (!$tour){
            return null;
        }
        else{
            return $tour;
        }

    }

    public static function removeByIdAndGuid($tour_id, $tour_guid){
        Tour::findByIdAndGuid($tour_id,$tour_guid)->delete();
        //////////////meysam - delete directory///////////////
        $address = storage_path() . '/app/files/tours/'.$tour_id;
        Utility::deleteDir($address);
        //////////////////////////////////////////////////////
    }

    public static function deleteFileDirectory($id){
        //////////////meysam - delete directory///////////////
        $address = storage_path() . '/app/files/tours/'.$id;
        Utility::deleteDir($address);
        //////////////////////////////////////////////////////
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $tour = new Tour();
        $query = $tour->newQuery();
        $query->where('tour_id', '=', $Id);
        $query->where('tour_guid', 'like', $guid);
        $tours = $query->get();
        if (count($tours) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function getStatusStringByCode($statusCode)
    {
        if($statusCode == Tour::StatusActive)
            return trans('messages.lblStatusActive');
        else if($statusCode == Tour::StatusWaitForCheck)
            return trans('messages.lblStatusWaitForApproval');
        else if($statusCode == Tour::StatusDisapproved)
            return trans('messages.lblStatusDisapproved');
        else if($statusCode == Tour::StatusCanceled)
            return "کنسل شده";
        else if($statusCode == Tour::StatusNonActive)
            return "غیر فعال";
        else
            return trans('messages.txtUndefined');
    }

    public static function getHardshipLevelStringByCode($hardshipLevelCode)
    {
        if($hardshipLevelCode == Tour::HardshipLevelEasy)
            return "خیلی سبک";
        else if($hardshipLevelCode == Tour::HardshipLevelMedium)
            return "سبک";
        else if($hardshipLevelCode == Tour::HardshipLevelHard)
            return "معمولی";
        else if($hardshipLevelCode == Tour::HardshipLevelVeryHard)
            return "سنگین";
        else if($hardshipLevelCode == Tour::HardshipLevelUltraHard)
            return "خیلی سنگین";
        else
            return trans('messages.txtUndefined');
    }

    public static function getSocialStringByCode($socialCode)
    {
        if($socialCode == Tour::SocialTelegram)
            return "تلگرام";
        else if($socialCode == Tour::SocialInstagram)
            return "اینستاگرام";
        else if($socialCode == Tour::SocialWhatsapp)
            return "واتس آپ";
        else if($socialCode == Tour::SocialSoroush)
            return "سروش";
        else if($socialCode == Tour::SocialBale)
            return "بله";
        else if($socialCode == Tour::SocialIgap)
            return "آی گپ";
        else if($socialCode == Tour::SocialLine)
            return "لاین";
        else if($socialCode == Tour::SocialFacebook)
            return "فیسبوک";
        else if($socialCode == Tour::SocialTwitter)
            return "تویتر";
        else
            return trans('messages.txtUndefined');
    }

    public static function getGenderStringByCode($genderCode)
    {
        if($genderCode == Tour::GenderBoth)
            return trans('messages.lblGenderBoth');
        else if($genderCode == Tour::GenderMale)
            return trans('messages.lblGenderMale');
        else if($genderCode == Tour::GenderFemale)
            return trans('messages.lblGenderFemale');
        else
            return trans('messages.txtUndefined');
    }

    public static function changeStatus($tour_id, $status)
    {
        $oldTour = Tour::findById($tour_id);
        $oldTour -> status = $status;
        $oldTour->save();
    }

    public static function findById($id)
    {
        $tour = new Tour();
        $query = $tour->newQuery();
        $query->where('tour_id', '=', $id);
        $tour = $query->get()->first();
//        Log::info('$tour:'.json_encode($tour));
        if (!$tour){
//            Log::info('$tour no');
            return null;
        }
        else{
//            Log::info('$tour yes');
            return $tour;
        }

    }

    public static function checkOptionalFeatures($info, Request $request, $features)
    {

        $informations = array();
        $optionalCost = 0;
        $buyableCount = 0;
        $currentBuyable = 'buyable_'.$buyableCount;
        while ($request->has($currentBuyable))
        {
            $featureUid = $request->input($currentBuyable);

            $featureCheck = $request->input("chk_".$featureUid);
            if(strcmp($featureCheck,"on") == 0)
            {
                foreach ($features as $feature)
                {
                    if($feature->feature_uid == $featureUid)
                    {
                        $optionalCost +=  ($feature->cost * $request->input("quantity_".$featureUid));
                        $information = new \stdClass();
                        $information->feature_id = $feature->feature_id;
                        $information->count = $request->input("quantity_".$featureUid);
                        $information->name = $feature->name;
                        array_push($informations, $information);

                    }
                }
            }

            $buyableCount++;
            $currentBuyable = 'buyable_'.$buyableCount;
        }


        $info->informations = $informations;
        $info -> optional_cost = $optionalCost;
        return $info;
    }

    public static function getTotalOptionalFeatures($reserves, $tour_id)
    {

        $total_optional_info = new \stdClass();
        $total_optional_info->total_persons = 0;

        $features = DB::table('features')
            ->join('tours_features', function($join)
            {
                $join->on('tours_features.feature_id','=','features.feature_id');
            })
            ->where('features.deleted_at','=',null)
            ->where('tours_features.deleted_at','=',null)
            ->where('tours_features.tour_id','=',$tour_id)
            ->select('features.feature_id','features.feature_guid','features.description','features.name','tours_features.tour_feature_id as feature_uid', 'tours_features.description as more_description','tours_features.cost as cost','tours_features.capacity as capacity','tours_features.count as count','tours_features.is_optional as is_optional','tours_features.is_required as is_required')
            ->orderBy('features.feature_id', 'desc')
            ->get();

        foreach ($reserves as $reserve)
        {
            $total_optional_info->total_persons += count($reserve->info->persons);
        }

        $total_optional_info -> features = $features;

        return $total_optional_info;
    }

    public static function editRemainingCapacity($tourId,$tourGuid, $remainingCapacity)
    {
        $oldtour = Tour::findByIdAndGuid($tourId,$tourGuid);

        $oldtour->remaining_capacity=$remainingCapacity;


        $oldtour->save();
    }

    public static function getTotalNumberOfPersons($reserves)
    {
        $totalNumber = 0;
        foreach ($reserves as $reserve)
        {
            $persons = $reserve->info->persons;
            $totalNumber += count($persons);
        }

        return $totalNumber;
    }

    public static function editRemainingCapacityForFeatures($tourId,$informations)
    {
        $tourFeatures = DB::table('tours_features')
            ->where('tours_features.deleted_at','=',null)
            ->where('tours_features.tour_id','=',$tourId)
            ->orderBy('tours_features.tour_feature_id', 'desc')
            ->get();

        foreach ($tourFeatures as $tour_feature)
        {
            if($tour_feature->is_optional)
            {
                foreach ($informations as $information)
                {
                    if($information->feature_id == $tour_feature->feature_id)
                    {
                        $tour_feature->count = $tour_feature->count + $information->count;
//                        $tour_feature->capacity = $tour_feature->capacity - $information->count;
                        if( ($tour_feature->capacity  - $tour_feature->count) < 0)
                            return false;

                        TourFeature::editCapacityAndCount($tour_feature->tour_feature_id,$tour_feature->capacity,$tour_feature->count);
                    }
                }
            }
        }

        return true;

    }

    public static function edit($request)
    {
        $oldTour = Tour::findByIdAndGuid($request -> input('tour_id'),$request -> input('tour_guid'));


        if(Auth::user()->type == \App\User::TypeAdmin ||
            Auth::user()->type == \App\User::TypeOperator)
        {
            $oldTour -> user_id = $request ->input('owner');
            $oldTour -> status  = $request ->input('status');
            $siteShare = $request ->input('site_share');
        }
        else
        {
            $oldTour -> user_id = Auth::user()->user_id;
            $oldTour -> status  = Tour::StatusWaitForCheck;
            $siteShare = Tour::TourSiteShare;

        }

        $oldTour -> title = $request ->input('title');
        $oldTour -> description  = $request ->input('description');
        $oldTour -> total_capacity = $request ->input('total_capacity');
        $oldTour -> remaining_capacity = $request ->input('remaining_capacity');
        $oldTour -> start_date_time  = $request ->input('start_date_time');
        $oldTour -> end_date_time  = $request ->input('end_date_time');
        $oldTour -> deadline_date_time  = $request ->input('deadline_date_time');
        $oldTour -> minimum_age = $request ->input('minimum_age');
        $oldTour -> maximum_age = $request ->input('maximum_age');
//        $oldTour -> like_count = 0;
        $oldTour -> gender  = $request ->input('gender');
        $oldTour -> hardship_level = $request ->input('hardship_level');
        $oldTour -> cost  = $request ->input('cost');

        if($request ->has('stroked_cost'))
            $oldTour->stroked_cost = $request ->input('stroked_cost');

        //meysam - create tour_address json
        $tourAddress = new \stdClass();
//        $tourAddress->address = $request ->input('end_address');
//        $tourAddress->latitude = $request ->input('end_point_lat');
//        $tourAddress->longitude = $request ->input('end_point_lon');
        $tourAddress->description = $request ->input('end_description');



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


//            $endAddress->start_date_time =  $request->input("destination_start_date_time_".$endAddressCount);
//            $endAddress->end_date_time =  $request->input("destination_end_date_time_".$endAddressCount);

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
        $oldTour -> tour_address = json_encode($tourAddress);



        //meysam - create gathering_place json
        $gatheringPlace = new \stdClass();
//        $gatheringPlace->address = $request ->input('start_address');
//        $gatheringPlace->latitude = $request ->input('start_point_lat');
//        $gatheringPlace->longitude = $request ->input('start_point_lon');
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


//            $startAddress->start_date_time =  $request->input("gathering_start_date_time_".$startAddressCount);
//            $startAddress->end_date_time =  $request->input("gathering_end_date_time_".$startAddressCount);

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
        $oldTour -> gathering_place = json_encode($gatheringPlace);












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
        $oldTour -> info = json_encode($info);


        //meysam - create wholesale_discount json
        $wholesale_discount = new \stdClass();
        if(strcmp($request->input('has_wholesale_discount'),'on') == 0)
        {
            $wholesale_discount->is_active =  1;

        }
        else
            $wholesale_discount->is_active = 0;
        $wholesale_discount->percent = $request->input('wholesale_discount_percent');
        $oldTour->wholesale_discount = json_encode($wholesale_discount);
        //////////////////////////////////////////


        //meysam - create social json
        $socials = array();
        $socialCount = 0;
        $requestSocialName = "social_name_".$socialCount;
        while($request->has($requestSocialName))
        {
            $social = new \stdClass();
            $social->code =  $request->input($requestSocialName);
            $social->address = Utility::addHTTP( $request->input("social_address_".$socialCount));
            array_push($socials, $social);

            $socialCount++;
            $requestSocialName = "social_name_".$socialCount;
        }
        $oldTour->social = json_encode($socials);

        $oldTour->save();
    }

    public static function isNationalCodeExistInReserves($nationalId, $tourId)
    {
        $reserves = Reserve::query()->where('status', Reserve::StatusActive)->where('tour_id', $tourId)->get();
        $isExist = false;
        foreach ($reserves as $reserve)
        {
            $reserve->info = json_decode($reserve->info);

            $reserve->name_and_family = $reserve->info->persons[0]->name_and_family;
            $reserve->national_code =$reserve->info->persons[0]->national_code;

            if(strcmp($reserve->national_code, $nationalId) == 0)
            {
                $isExist = true;
                break;
            }

        }

        return $isExist;

    }

}