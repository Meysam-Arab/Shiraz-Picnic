<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:14 PM
 */

namespace App;

use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use File;
use Log;


class TourLeader extends Model
{
    use SoftDeletes;


    protected $table = 'tours_leaders';
    protected $primaryKey = 'tour_leader_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * Get the blog that owns the trans_media.
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the language that owns the trans_media.
     */
    public function leader()
    {
        return $this->belongsTo(User::class);
    }



    public function initialize()
    {
        $this -> tour_leader_id = null;
        $this -> tour_leader_guid = null;
        $this -> tour_id = null;
        $this -> user_id = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> tour_leader_id = $request ->input('tour_leader_id');
        $this -> tour_leader_guid = $request ->input('tour_leader_guid');
        $this -> user_id = $request ->input('user_id');
        $this -> tour_id = $request ->input('tour_id');
    }

    public function store()
    {
        $this->tour_leader_guid = uniqid('',true);
        $this->save();

    }

    public static function findByTourIdAndLeaderId($tour_id, $leader_id)
    {
        $tour_leader = new TourLeader();
        $query = $tour_leader->newQuery();
        $query->where('tour_id', '=', $tour_id);
        $query->where('user_id', '=', $leader_id);
        $tour_leaders = $query->get();
        if (count($tour_leaders) == 0){
            return null;
        }
        else{
            return $tour_leaders[0];
        }
    }

    public static function findById($id)
    {
        $tour_leader = new TourLeader();
        $query = $tour_leader->newQuery();
        $query->where('tour_leader_id', '=', $id);
        $tour_leaders = $query->get();
        if (count($tour_leaders) == 0){
            return null;
        }
        else{
            return $tour_leaders[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $tour_leader = new TourLeader();
        $query = $tour_leader->newQuery();
        $query->where('tour_leader_id', '=', $id);
        $query->where('tour_leader_guid', 'like', $guid);
        $tour_leaders = $query->get();
        if (count($tour_leaders) == 0){
            return null;
        }
        else{
            return $tour_leaders[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        TourLeader::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $tour_leader = new TourLeader();
        $query = $tour_leader->newQuery();
        $query->where('tour_leader_id', '=', $Id);
        $query->where('tour_leader_guid', 'like', $guid);
        $tour_leaders = $query->get();
        if (count($tour_leaders) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function edit($request)
    {
        $oldTourLeader = TourLeader::findByIdAndGuid($request -> input('tour_leader_id'),$request -> input('tour_leader_guid'));

        $oldTourLeader->tour_id=$request -> input('tour_id');
        $oldTourLeader->user_id=$request -> input('user_id');

        $oldTourLeader->save();
    }

    public static function isRelationExistByTourIdAndLeaderId($tourId, $leaderId)
    {
        $tour_leader = new TourLeader();
        $query = $tour_leader->newQuery();
        $query->where('user_id', '=', $leaderId);
        $query->where('tour_id', '=', $tourId);
        $tour_leaders = $query->get();
        if (count($tour_leaders) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByTourIdAndLeaderId($tourId, $leaderId)
    {
        TourLeader::findByTourIdAndLeaderId($tourId, $leaderId)->delete();
    }
}