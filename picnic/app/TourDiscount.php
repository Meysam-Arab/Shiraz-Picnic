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


class TourDiscount extends Model
{
    use SoftDeletes;


    protected $table = 'tours_discounts';
    protected $primaryKey = 'tour_discount_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * Get the blog that owns the trans_media.
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function initialize()
    {
        $this -> tour_discount_id = null;
        $this -> tour_discount_guid = null;
        $this -> tour_id = null;
        $this -> description = null;
        $this -> percent = null;
        $this -> capacity = null;
        $this -> code = null;
        $this -> remaining_capacity = null;

    }

    public function initializeByRequest(Request $request)
    {

        $this -> tour_discount_id = $request ->input('tour_discount_id');
        $this -> tour_discount_guid = $request ->input('tour_discount_guid');
        $this -> tour_id = $request ->input('tour_id');
        $this -> description = $request ->input('description');
        $this -> percent = $request ->input('percent');
        $this -> capacity = $request ->input('capacity');
        $this -> code = $request ->input('code');
        $this -> remaining_capacity = $request ->input('remaining_capacity');
    }

    public function store()
    {
        $this->tour_discount_guid = uniqid('',true);
        $this->save();

    }

    public static function findByTourId($tour_id)
    {
        $tour_discount = new TourDiscount();
        $query = $tour_discount->newQuery();
        $query->where('tour_id', '=', $tour_id);
        $tour_discounts = $query->get();
        if (count($tour_discounts) == 0){
            return null;
        }
        else{
            return $tour_discounts[0];
        }
    }

    public static function findById($id)
    {
        $tour_discount = new TourDiscount();
        $query = $tour_discount->newQuery();
        $query->where('tour_discount_id', '=', $id);
        $tour_discounts = $query->get();
        if (count($tour_discounts) == 0){
            return null;
        }
        else{
            return $tour_discounts[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $tour_discount = new TourDiscount();
        $query = $tour_discount->newQuery();
        $query->where('tour_discount_id', '=', $id);
        $query->where('tour_discount_guid', 'like', $guid);
        $tour_discounts = $query->get();
        if (count($tour_discounts) == 0){
            return null;
        }
        else{
            return $tour_discounts[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        TourDiscount::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $tour_discount = new TourDiscount();
        $query = $tour_discount->newQuery();
        $query->where('tour_discount_id', '=', $Id);
        $query->where('tour_discount_guid', 'like', $guid);
        $tour_discounts = $query->get();
        if (count($tour_discounts) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function edit($request)
    {
        $oldTourDiscount = TourDiscount::findByIdAndGuid($request -> input('tour_discount_id'),$request -> input('tour_discount_guid'));

        $oldTourDiscount-> tour_id=$request -> input('tour_id');
        $oldTourDiscount -> description = $request -> input('description');
        $oldTourDiscount -> percent = $request -> input('percent');
        $oldTourDiscount -> capacity = $request -> input('capacity');
        $oldTourDiscount -> code = $request -> input('code');
        $oldTourDiscount -> remaining_capacity = $request -> input('remaining_capacity');

        $oldTourDiscount->save();
    }

    public static function editByObject($discountObject)
    {
        $oldTourDiscount = TourDiscount::findByIdAndGuid($discountObject -> tour_discount_id,$discountObject -> tour_discount_guid);

        $oldTourDiscount-> tour_id = $discountObject->tour_id;
        $oldTourDiscount -> description = $discountObject->description;
        $oldTourDiscount -> percent = $discountObject->percent;
        $oldTourDiscount -> capacity = $discountObject->capacity;
        $oldTourDiscount -> code = $discountObject->code;
        $oldTourDiscount -> remaining_capacity = $discountObject->remaining_capacity;

        $oldTourDiscount->save();
    }

    public static function isRelationExistByTourId($tourId)
    {
        $tour_discount = new TourDiscount();
        $query = $tour_discount->newQuery();
        $query->where('tour_id', '=', $tourId);
        $tour_discounts = $query->get();
        if (count($tour_discounts) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByTourIdAndDiscountId($tourId)
    {
        TourDiscount::findByTourIdAndDiscountId($tourId)->delete();
    }
}