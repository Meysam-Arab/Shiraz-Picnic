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
use ViewComponents\Eloquent\EloquentDataProvider;

class FeedBack extends Model
{
    use SoftDeletes;

    protected $table = 'feedbacks';
    protected $primaryKey = 'feedback_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description','email','tel', 'name_and_family'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function initialize()
    {
        $this -> feedback_id = null;
        $this -> feedback_guid = null;
        $this -> title = null;
        $this -> description = null;
        $this -> email = null;
        $this -> tel = null;
        $this -> name_and_family = null;

    }

    public function initializeByRequest(Request $request)
    {

        $this -> feedback_id = $request ->input('feedback_id');
        $this -> feedback_guid = $request ->input('feedback_guid');
        $this -> title = $request ->input('title');
        $this -> description = $request ->input('description');
        $this -> email = $request ->input('email');
        $this -> tel = $request ->input('tel');
        $this -> name_and_family = $request ->input('name_and_family');


    }

    public function store()
    {
        $this->feedback_guid = uniqid('',true);
        $this->save();

    }

    //meysam - $ored = 'asc' , 'desc'
    public function select($order = null, $orderColumn = null, $topCount = null)
    {
        $query = $this->newQuery();
        if($this->feedback_id != null){
            $query->where('feedback_id', '=', $this->feedback_id);
        }
        if($this->feedback_guid != null){
            $query->where('feedback_guid', 'like', $this->feedback_guid);
        }
        if($this->title != null){
            $query->where('title', 'like', $this->title);
        }
        if($this->description != null){
            $query->where('description', 'like', $this->description);
        }
        if($this->email != null){
            $query->where('email', 'like', $this->email);
        }
        if($this->tel != null){
            $query->where('tel', '=', $this->tel);
        }
        if($this->name_and_family != null){
            $query->where('name_and_family', 'like', $this->name_and_family);
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
                $query ->orderBy('feedback_id',$order);
            }
        }


        if (null != $topCount)
        {
            $query ->take($topCount);
        }

        $feedbacks = $query->get();

        return $feedbacks;

//        $feedback = $query->get();
//
//        return $feedbacks;
    }

    ////simplest query
    public function makeWhere($query){
        if($this->feedback_id != null){
            $query->where('feedback_id', '=', $this->feedback_id);
        }
        if($this->feedback_guid != null){
            $query->where('feedback_guid', 'like', $this->feedback_guid);
        }
        if($this->title != null){
            $query->where('title', 'like', $this->title);
        }
        if($this->description != null){
            $query->where('description', 'like', $this->description);
        }
        if($this->email != null){
            $query->where('email', 'like', $this->email);
        }
        if($this->tel != null){
            $query->where('tel', '=', $this->tel);
        }
        if($this->name_and_family != null){
            $query->where('name_and_family', 'like', $this->name_and_family);
        }

        return $query;
    }

    public static function findByIdAndGuid($id, $guid)
    {
        $feedback = new FeedBack();
        $query = $feedback->newQuery();
        $query->where('feedback_id', '=', $id);
        $query->where('feedback_guid', 'like', $guid);
        $feedbacks = $query->get();
        if (count($feedbacks) == 0){
            return null;
        }
        else{
            return $feedbacks[0];
        }

    }

    public static function removeByIdAndGuid($feedback_id, $feedback_guid){
        FeedBack::findByIdAndGuid($feedback_id,$feedback_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $feedback = new FeedBack();
        $query = $feedback->newQuery();
        $query->where('feedback_id', '=', $Id);
        $query->where('feedback_guid', 'like', $guid);
        $feedbacks = $query->get()->first();
        if (count($feedbacks) == 0){
            return false;
        }
        else{
            return true;
        }
    }
}