<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:20 PM
 */

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Log;

class Transaction extends Model
{
    use SoftDeletes;

    const MESSAGE_TRANSACTION_ZARINPAL_DEFECTIVE_INFORMATION = -1;
    const MESSAGE_TRANSACTION_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE = -2;
    const MESSAGE_TRANSACTION_ZARINPAL_SHAPARAK_RESTRICTION_TRANSACTION = -3;
    const MESSAGE_TRANSACTION_ZARINPAL_BELOW_SILVER_LEVEL = -4;
    const MESSAGE_TRANSACTION_ZARINPAL_REQUEST_NOT_FIND = -11;
    const MESSAGE_TRANSACTION_ZARINPAL_CAN_NOT_EDIT_REQUEST = -12;
    const MESSAGE_TRANSACTION_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND = -21;
    const MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_FAILED = -22;
    const MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_TRANSACTION_MISMATCH = -33;
    const MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_COUNT_EXCEEDED = -34;
    const MESSAGE_TRANSACTION_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED = -40;
    const MESSAGE_TRANSACTION_ZARINPAL_INCORRECT_ADDETIONAL_DATA = -41;
    const MESSAGE_TRANSACTION_ZARINPAL_ID_VALIDATION_TIME = -42;
    const MESSAGE_TRANSACTION_ZARINPAL_REQUEST_ARCHIVED= -54;
    const MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL = 100;
    const MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL_TRANSACTION_VERIFICATION_ALREDY_DONE = 101;
    const MESSAGE_UNDEFINED_ERROR= -2000;

    const TRANSACTION_TYPE_INCOMING= 0;
    const TRANSACTION_TYPE_OUTGOING= 1;

    const TRANSACTION_STATUS_NOT_SETTLED= 0;
    const TRANSACTION_STATUS_SETTLED= 1;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';


    /**
     * Get the user that owns the comment.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function initialize()
    {
        $this->transaction_id=null;
        $this->transaction_guid=null;
        $this->user_id=null;
        $this->reserve_id=null;
        $this->amount=null;
        $this->status=null;
        $this->description=null;
        $this->authority=null;
        $this->info=null;
        $this->type=null;
        $this->deleted_at=null;
    }
    public function initializeByObject(Transaction $transaction)
    {
        if( $transaction->transaction_id != null){
            $this->transaction_id = $transaction->transaction_id;
        }
        if($transaction->transaction_guid != null){
            $this->transaction_guid = $transaction->transaction_guid;
        }
        if($transaction->user_id != null){
            $this->user_id = $transaction->user_id;
        }
        if($transaction->reserve_id != null){
            $this->reserve_id = $transaction->reserve_id;
        }
        if($transaction->amount != null){
            $this->amount = $transaction->amount;
        }
        if( $transaction->status != null){
            $this->status = $transaction->status;
        }
        if( $transaction->description != null){
            $this->description = $transaction->description;
        }
        if( $transaction->authority != null){
            $this->authority = $transaction->authority;
        }
        if( $transaction->info != null){
            $this->info = $transaction->info;
        }
        if( $transaction->type != null){
            $this->type = $transaction->type;
        }
    }
    public function initializeByRequest($request)
    {

        $this->transaction_id=$request->input('transaction_id');
        $this->transaction_guid=$request->input('transaction_guid');
        $this->user_id=$request->input('user_id');
        $this->reserve_id=$request->input('reserve_id');
        $this->amount=$request->input('amount');
        $this->status=$request->input('status');
        $this->description=$request->input('description');
        $this->authority=$request->input('authority');
        $this->info=$request->input('info');
        $this->type=$request->input('type');

    }
    public function getFullDetailTransaction( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->newQuery();
        //
        if($params1!=null) {

            $query=\App\Utility::fillQueryAlias($query,$params1,$distinct);
        }
        $query =Self::makeWhere($query);

        //
        if($params2!=null) {
            $query=\App\Utility::fillQueryJoin($query,$params2);
        }
        //filtering
        if($params3!=null) {
            $query=\App\Utility::fillQueryFilter($query,$params3);
        }
        $transactions = $query->get();
        return $transactions;

//        return $query->get();
    }
    public function makeWhere($query){
        if($this->transaction_id != null){
            $query->where('transactions.'.'transaction_id', '=', $this->transaction_id);
        }
        if($this->transaction_guid != null){
            $query->where('transactions.'.'transaction_guid', '=', $this->transaction_guid);
        }
        if($this->user_id != null){
            $query->where('transactions.'.'user_id', '=', $this->user_id);
        }
        if($this->reserve_id != null){
            $query->where('transactions.'.'reserve_id', '=', $this->reserve_id);
        }
        if($this->amount != null){
            $query->where('transactions.'.'amount', '=', $this->amount);
        }
        if( $this->description != null){
            $query->where('transactions.'.'description', 'like', '%'.$this->description.'%');
        }
        if( $this->authority != null){
            $query->where('transactions.'.'authority', '=', $this->authority);
        }
        if( $this->status != null){
            $query->where('transactions.'.'status', '=', $this->status);
        }
        if( $this->info != null){
            $query->where('transactions.'.'info', 'like', $this->info);
        }
        if( $this->type != null){
            $query->where('transactions.'.'type', '=', $this->type);
        }
        return $query;
    }
    public function select($order = null, $orderColumn = null, $topCount = null)
    {
        $query = $this->newQuery();
        if($this->transaction_id != null){
            $query->where('transactions.'.'transaction_id', '=', $this->transaction_id);
        }
        if($this->transaction_guid != null){
            $query->where('transactions.'.'transaction_guid', '=', $this->transaction_guid);
        }
        if($this->user_id != null){
            $query->where('transactions.'.'user_id', '=', $this->user_id);
        }
        if($this->reserve_id != null){
            $query->where('transactions.'.'reserve_id', '=', $this->reserve_id);
        }
        if($this->amount != null){
            $query->where('transactions.'.'amount', '=', $this->amount);
        }
        if( $this->description != null){
            $query->where('transactions.'.'description', 'like', '%'.$this->description.'%');
        }
        if( $this->authority != null){
            $query->where('transactions.'.'authority', '=', $this->authority);
        }
        if( $this->status != null){
            $query->where('transactions.'.'status', '=', $this->status);
        }
        if( $this->info != null){
            $query->where('transactions.'.'info', 'like', $this->info);
        }
        if( $this->type != null){
            $query->where('transactions.'.'type', '=', $this->type);
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
                $query ->orderBy('transaction_id',$order);
            }
        }

        if (null != $topCount)
        {
            $query ->take($topCount);
        }

        $transaction = $query->get();

        return $transaction;
    }
    public function store()
    {
        $this->transaction_guid = uniqid('',true);
        $this->save();
    }
    public function find($id)
    {
        return $this->find($id);
    }
    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->newQuery();
            $query->where('transaction_id', '=', $id);
            $query->where('transaction_guid', 'like', $guid);
            $transactions = $query->get();
            if(count($transactions)==0)
                return false;
            return $transactions[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }
    public function set($id,$guid)
    {
        $this->transaction_id = $id;
        $this->transaction_guid = $guid;
    }

    public static function updateTransaction(Transaction $transaction)
    {
        $oldTransaction = new Transaction();
        $oldTransaction = $oldTransaction->findByIdAndGuid($transaction -> transaction_id,$transaction-> transaction_guid);

        if($transaction->transaction_id != null){
            $oldTransaction->transaction_id = $transaction->transaction_id;
        }
        if($transaction->transaction_guid != null){
            $oldTransaction->transaction_guid = $transaction->transaction_guid;
        }
        if($transaction->user_id != null){
            $oldTransaction->user_id = $transaction->user_id;
        }
        if($transaction->amount != null){
            $oldTransaction->amount = $transaction->amount;
        }
        if($transaction->description != null){
            $oldTransaction->description = $transaction->description;
        }
        if($transaction->authority != null){
            $oldTransaction->authority = $transaction->authority;
        }
        if($transaction->status != null){
            $oldTransaction->status = $transaction->status;
        }
        if($transaction->info != null){
            $oldTransaction->info = $transaction->info;
        }
        if($transaction->type != null){
            $oldTransaction->type = $transaction->type;
        }
        $oldTransaction->save();
    }
    public static function getMessage($code = null)
    {
        switch ($code)
        {
            case self::MESSAGE_TRANSACTION_ZARINPAL_BELOW_SILVER_LEVEL:
                return  trans('messages.msgTransactionZarinPalBelowSilverLevel');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_DEFECTIVE_INFORMATION:
                return  trans('messages.msgTransactionZarinPalDefectiveInformation');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_CAN_NOT_EDIT_REQUEST:
                return  trans('messages.msgTransactionZarinPalCanNotEditRequest');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND:
                return  trans('messages.msgTransactionZarinPalFinancialOperationNotFind');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_ID_VALIDATION_TIME:
                return  trans('messages.msgTransactionZarinPalIdValidationTime');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_INCORRECT_ADDETIONAL_DATA:
                return  trans('messages.msgTransactionZarinPalIncorrectAdditionalData');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE:
                return trans('messages.msgTransactionZarinPalIncorrectIpOrMerchantCode');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED:
                return  trans('messages.msgTransactionZarinPalMethodAccessNotAllowed');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL:
                return  trans('messages.msgTransactionZarinPalOperationSuccessful');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_OPERATION_SUCCESSFUL_TRANSACTION_VERIFICATION_ALREDY_DONE:
                return trans('messages.msgTransactionZarinPalOperationSuccessfulTransactionVerificationAlredyDone');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_REQUEST_ARCHIVED:
                return  trans('messages.msgTransactionZarinPalRequestArchived');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_REQUEST_NOT_FIND:
                return  trans('messages.msgTransactionZarinPalRequestNotFind');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_SHAPARAK_RESTRICTION_TRANSACTION:
                return trans('messages.msgTransactionZarinPalShaparakRestrictionTransaction');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_COUNT_EXCEEDED:
                return  trans('messages.msgTransactionZarinPalTransactionCountExceeded');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_FAILED:
                return  trans('messages.msgTransactionZarinPalTransactionFailed');
                break;
            case self::MESSAGE_TRANSACTION_ZARINPAL_TRANSACTION_TRANSACTION_MISMATCH:
                return  trans('messages.msgTransactionZarinPalTransactionTransactionMismatch');
                break;
            case self::MESSAGE_UNDEFINED_ERROR:
                return  trans('messages.msgErrorUndefined');
                break;
            case self::TRANSACTION_STATUS_NOT_SETTLED:
                return  trans('messages.msgStatusNotSettled');
                break;
            case self::TRANSACTION_STATUS_SETTLED:
                return  trans('messages.msgStatusSettled');
                break;
            default:
                return  trans('messages.msgErrorItemNotExist');
                break;
        }
    }
    public static function isTokenExist($token)
    {
        $transactions = DB::table('transactions')
            ->where('authority', $token)
            ->get();
        if(sizeof($transactions) > 0 )
            return true;
        return false;
    }

    public static function editInfo($transactionId,$transactionGuid, $encodedInfo)
    {
        $oldtransaction = Transaction::findByIdAndGuidST($transactionId,$transactionGuid);
//        Log::info('$oldtransaction'.json_encode($oldtransaction));
        $oldtransaction->info=$encodedInfo;
        $oldtransaction->save();
    }

    public static function editReserveId($transactionId,$transactionGuid, $reserveId)
    {
        $oldtransaction = Transaction::findByIdAndGuidST($transactionId,$transactionGuid);
//        Log::info('$oldtransaction'.json_encode($oldtransaction));

        $oldtransaction->reserve_id=$reserveId;


        $oldtransaction->save();
    }

    public static function editType($transactionId,$transactionGuid, $typeCode)
    {
        $oldtransaction = Transaction::findByIdAndGuidST($transactionId,$transactionGuid);
//        Log::info('$oldtransaction'.json_encode($oldtransaction));

        $oldtransaction->type=$typeCode;


        $oldtransaction->save();
    }

    public static function editReserveIdAndStatusAndAuthority($transactionId,$transactionGuid, $reserveId = null, $status = null, $authority = null)
    {
        $oldtransaction = Transaction::findByIdAndGuidST($transactionId,$transactionGuid);
//        Log::info('$oldtransaction'.json_encode($oldtransaction));
        if($reserveId != null)
            $oldtransaction->reserve_id=$reserveId;
        if($status != null)
            $oldtransaction->status=$status;
        if($authority != null)
            $oldtransaction->authority=$authority;

        $oldtransaction->save();
    }

    public static function findByIdAndGuidST($id, $guid)
    {
        $transaction = new Transaction();
        $query = $transaction->newQuery();
        $query->where('transaction_id', '=', $id);
        $query->where('transaction_guid', 'like', $guid);
        $transactions = $query->get();
        if (count($transactions) == 0){
            return null;
        }
        else{
            return $transactions[0];
        }
    }
}