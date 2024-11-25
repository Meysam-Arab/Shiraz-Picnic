<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:14 PM
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use SoftDeletes;


    const StatusNonActive = 0;
    const StatusActive = 1;
    const StatusWaitForCheck = 2;
    const StatusDisapproved  = 3;

    protected $table = 'accounts';
    protected $primaryKey = 'account_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_number','card_number','shaba_number'];

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
        return $this->belongsTo(User::class);
    }


    public function initialize()
    {
        $this -> account_id = null;
        $this -> account_guid = null;
        $this -> user_id = null;
        $this -> account_number = null;
        $this -> card_number = null;
        $this -> shaba_number = null;
        $this -> status = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> account_id =  $request ->input('account_id');
        $this -> account_guid =  $request ->input('account_guid');
        $this -> user_id =  $request ->input('user_id');
        $this -> account_number = $request ->input('account_number');
        $this -> card_number = $request ->input('card_number');
        $this -> shaba_number = $request ->input('shaba_number');
        $this -> status = $request ->input('status');


    }

    public function store()
    {
        $this->account_guid = uniqid('',true);
        $this->save();

    }

    //meysam - $ored = 'asc' , 'desc'
    public function select($order = null, $orderColumn = null, $topCount = null)
    {
        $query = $this->newQuery();
        if($this->account_id != null){
            $query->where('account_id', '=', $this->account_id);
        }
        if($this->account_guid != null){
            $query->where('account_guid', 'like', $this->account_guid);
        }
        if($this->user_id != null){
            $query->where('user_id', '=', $this->user_id);
        }
        if($this->account_number != null){
            $query->where('account_number', 'like', $this->account_number);
        }
        if($this->card_number != null){
            $query->where('card_number', 'like', $this->card_number);
        }
        if($this->shaba_number != null){
            $query->where('shaba_number', 'like', $this->shaba_number);
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
                $query ->orderBy('account_id',$order);
            }
        }


        if (null != $topCount)
        {
            $query ->take($topCount);
        }

        $accounts = $query->get();

        return $accounts;
    }

    ////simplest query
    public function makeWhere($query){
        if($this->account_id != null){
            $query->where('account_id', '=', $this->account_id);
        }
        if($this->account_guid != null){
            $query->where('account_guid', 'like', $this->account_guid);
        }
        if($this->user_id != null){
            $query->where('user_id', '=', $this->user_id);
        }
        if($this->account_number != null){
            $query->where('account_number', 'like', $this->account_number);
        }
        if($this->card_number != null){
            $query->where('card_number', 'like', $this->card_number);
        }
        if($this->shaba_number != null){
            $query->where('shaba_number', 'like', $this->shaba_number);
        }
        if($this->status != null){
            $query->where('status', '=', $this->status);
        }

        return $query;
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $account = new Account();
        $query = $account->newQuery();
        $query->where('account_id', '=', $id);
        $query->where('account_guid', 'like', $guid);
        $accounts = $query->get();
        if (count($accounts) == 0){
            return null;
        }
        else{
            return $accounts[0];
        }

    }

    public static function removeByIdAndGuid($account_id, $account_guid){
        Account::findByIdAndGuid($account_id,$account_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $account = new Account();
        $query = $account->newQuery();
        $query->where('account_id', '=', $Id);
        $query->where('account_guid', 'like', $guid);
        $accounts = $query->get()->first();
        if (count($accounts) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function getStatusStringByCode($statusCode)
    {
        if($statusCode == Account::StatusActive)
            return trans('messages.lbl_status_active');
        else if($statusCode == Account::StatusWaitForCheck)
            return trans('messages.lbl_status_wait_for_approval');
        else if($statusCode == Account::StatusDisapproved)
            return trans('messages.lbl_status_disapproved');
        else
            return trans('messages.lbl_status_non_active');
    }

    public static function changeStatus($account_id, $status)
    {
        $oldAccount = Account::findById($account_id);
        $oldAccount -> status = $status;
        $oldAccount->save();
    }

    public static function findById($id)
    {
        $account = new Account();
        $query = $account->newQuery();
        $query->where('account_id', '=', $id);
        $accounts = $query->get();
        if (count($accounts) == 0){
            return null;
        }
        else{
            return $accounts[0];
        }

    }
}