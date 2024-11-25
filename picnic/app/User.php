<?php

namespace App;

use App\Http\Controllers\UserController;
use App\Utilities\Utility;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Log;
use DB;
use File;
use Hash;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const PASSWORD_RECOVERY_ENCRYPTION_KEY = "meysam@fardan7e.ir";

    const USER_AVATAR_FILE_NAME = 'avatar';
    const USER_AVATAR_FILE_SIZE = 6291456;
    const USER_AVATAR_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');

    const TypeNormal = 0;
    const TypeAdmin = 1;
    const TypeOperator = 2;
    const TypeGuard = 3;
    const TypeLeader = 4;
    const TypeOwner = 5;

    const Types = [User::TypeNormal
//        , User::TypeAdmin
//        , User::TypeOperator
        , User::TypeGuard
        , User::TypeLeader
        , User::TypeOwner];

    const StatusNonActive = 0;
    const StatusActive = 1;


    const Statuses = [User::StatusNonActive
        , User::StatusActive];

    const SocialTelegram = 0;
    const SocialInstagram = 1;
    const SocialWhatsapp = 2;
    const SocialSoroush  = 3;
    const SocialBale  = 4;
    const SocialIgap  = 5;
    const SocialLine  = 6;
    const SocialFacebook  = 7;
    const SocialTwitter  = 8;

    const Socials = [User::SocialTelegram
        , User::SocialInstagram
        , User::SocialWhatsapp
        , User::SocialSoroush
        , User::SocialBale
        , User::SocialIgap
        , User::SocialLine
        , User::SocialFacebook
        , User::SocialTwitter];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'remember_token', 'password',
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Get all of the tasks for the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all of the tasks for the user.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function toursLeaders()
    {
        return $this->hasMany(TourLeader::class);
    }

    public function gardensGuardes()
    {
        return $this->hasMany(GardenGuard::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * Get all of the tasks for the user.
     */
    public function gardens()
    {
        return $this->hasMany(Garden::class);
    }

//    /**
//     * Get all of the tasks for the user.
//     */
//    public function logEvents()
//    {
//        return $this->hasMany(LogEvent::class);
//    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            $user->transactions()->delete();
            $user->accounts()->delete();
            $user->gardens()->delete();
//            $user->logEvents()->delete();
            $user->toursLeaders()->delete();
            $user->gardensGuardes()->delete();
            $user->tours()->delete();
        });
    }


    public function initialize()
    {
        $this->user_id = null;
        $this->user_guid = null;
        $this->password = null;
        $this->type = null;
        $this->status = null;
        $this->name_family = null;
        $this->email = null;
        $this->mobile = null;
        $this->notification_email = null;
        $this->notification_sms = null;
        $this->social = null;
        $this->info = null;
    }

    public function initializeByRequest($request = null)
    {

        $this->user_id = $request ->input('user_id');
        $this->user_guid = $request ->input('user_guid');
        $this->password = $request ->input('password');
        $this->type = $request ->input('type');
        $this->status = $request ->input('status');
        $this->name_family = $request ->input('name_family');
        $this->email = $request ->input('email');
        $this->mobile = $request ->input('mobile');
        $this->notification_email = $request ->input('notification_email');
        $this->notification_sms = $request ->input('notification_sms');
        $this->social = $request ->input('social');
        $this->info = $request ->input('info');

        $this->deleted_at = null;
    }


    public function set($id,$guid)
    {
        $this->user_id = $id;
        $this->user_guid = $guid;

    }

    public static function findById($id)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('user_id', '=', $id);
        $users = $query->get();

        return $users[0];
    }

    public static function findByEmail($mail)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('email', 'like', $mail);
        $users = $query->get();
        if(count($users)!=0)
            return $users[0];
        else
            return null;
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('user_id', '=', $id);
        $query->where('user_guid', 'like', $guid);
        $user = $query->get()->first();
        if (!$user){
            return null;
        }
        else{
            return $user;
        }


    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('user_id', '=', $Id);
        $query->where('user_guid', '=', $guid);
        $users = $query->get();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function existByEmail($email, $forEdit, $userId)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('email', 'like', $email);
        if($forEdit)
        {
            $query->where('user_id', '<>', $userId);
        }

        $users = $query->get()->first();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function register(){
        $this->user_guid = uniqid('',true);
//        if($this->type == null)
//            $this->type = User::TypeNormal;

        if($this->status == null)
            $this->status = User::StatusActive;

//        $this->notification_email = 1;
//        $this->notification_sms = 1;
        $this->save();
        //meysam - set the invite code
        $user_id = DB::table('users')->where('user_guid', $this->user_guid)->value('user_id');
        $this->user_id = $user_id;
        ///////////////////////////////
        self::generateAllDirectories();
    }

    public static function edit($request)
    {
        $oldUser = User::findByIdAndGuid($request -> input('user_id'),$request -> input('user_guid'));


        if(Auth::user()->type == \App\User::TypeAdmin ||
            Auth::user()->type == \App\User::TypeOperator)
        {
            if($request->has('status'))
                $oldUser->status=$request -> input('status');
            if($request->has('type'))
                $oldUser->type=$request -> input('type');
        }

        //meysam - create info json
        $info = new \stdClass();

        if(strcmp($request -> input('show_contact'),"on") == 0)
            $info->show_contact=1;
        else
            $info->show_contact=0;
        if(strcmp($request -> input('show_social'),"on") == 0)
            $info->show_social=1;
        else
            $info->show_social=0;
        $oldUser -> info = json_encode($info);

        $oldUser->name_family=$request -> input('name_family');
        $oldUser->mobile=$request -> input('mobile');

        if(strcmp($request -> input('notification_email'),"on") == 0)
            $oldUser->notification_email=1;
        else
            $oldUser->notification_email=0;

        //meysam - create social json
        $socials = array();
        $socialCount = 0;
        $requestSocialName = "social_name_".$socialCount;
        while($request->has($requestSocialName))
        {
            $social = new \stdClass();
            $social->code =  $request->input($requestSocialName);
            $social->address =  Utility::addHTTP($request->input("social_address_".$socialCount));
            array_push($socials, $social);

            $socialCount++;
            $requestSocialName = "social_name_".$socialCount;
        }
        $oldUser->social = json_encode($socials);


        if(strcmp($request -> input('notification_sms'),"on") == 0)
            $oldUser->notification_sms=1;
        else
            $oldUser->notification_sms=0;

        if($request['password']!=null){
            $request['password'] = bcrypt($request['password']);
            $oldUser->password=$request -> input('password');
        }
        $oldUser->email=$request -> input('email');

        $oldUser->save();
    }

    public function editPassword($request)
    {
        $oldUser = User::findByIdAndGuid($request['user_id'],$request['user_guid'] );

        if($request['password']!=null){
            $password = bcrypt($request['password']);
            $oldUser->password=$password;
        }

        $oldUser->save();
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/users/'.$this->user_id.'/avatar';
        File::makeDirectory($directory,0777,true);

    }

    public static function existById($Id)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('user_id', '=', $Id);
        $users = $query->get()->first();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByIdAndGuid($user_id, $user_guid){
        User::findByIdAndGuid($user_id,$user_guid)->delete();
        //////////////meysam - delete directory///////////////
        $address = storage_path() . '/app/files/users/'.$user_id;
        Utility::deleteDir($address);
        //////////////////////////////////////////////////////
    }


    public static function findByMobile($mobile)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('mobile', 'like', $mobile);
        $users = $query->get();
        if(count($users)!=0)
            return $users[0];
        else
            return null;
    }


    public static function getStatusStringByCode($statusCode)
    {
        if($statusCode == User::StatusActive)
            return trans('messages.lblStatusActive');
        else
            return trans('messages.lblStatusNonActive');
    }

    public static function getTypeStringByCode($typeCode)
    {
        if($typeCode == User::TypeNormal)
            return trans('messages.lblTypeNormal');
        else if($typeCode == User::TypeAdmin)
            return trans('messages.lblTypeAdmin');
        else if($typeCode == User::TypeOperator)
            return trans('messages.lblTypeOperator');
        else if($typeCode == User::TypeGuard)
            return trans('messages.lblTypeGuard');
        else if($typeCode == User::TypeLeader)
            return trans('messages.lblTypeLeader');
        else if($typeCode == User::TypeOwner)
            return trans('messages.lblTypeOwner');
        else
            return trans('messages.lblTypeNormal');
    }

    public static function getSocialStringByCode($socialCode)
    {
        if($socialCode == User::SocialTelegram)
            return "تلگرام";
        else if($socialCode == User::SocialInstagram)
            return "اینستاگرام";
        else if($socialCode == User::SocialWhatsapp)
            return "واتس آپ";
        else if($socialCode == User::SocialSoroush)
            return "سروش";
        else if($socialCode == User::SocialBale)
            return "بله";
        else if($socialCode == User::SocialIgap)
            return "آی گپ";
        else if($socialCode == User::SocialLine)
            return "لاین";
        else if($socialCode == User::SocialFacebook)
            return "فیسبوک";
        else if($socialCode == User::SocialTwitter)
            return "تویتر";
        else
            return trans('messages.txtUndefined');
    }

}
