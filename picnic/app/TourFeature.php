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


class TourFeature extends Model
{
    use SoftDeletes;


    protected $table = 'tours_features';
    protected $primaryKey = 'tour_feature_id';
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
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }



    public function initialize()
    {
        $this -> tour_feature_id = null;
        $this -> tour_feature_guid = null;
        $this -> tour_id = null;
        $this -> feature_id = null;
        $this -> description = null;
        $this -> cost = null;
        $this -> capacity = null;
        $this -> count = null;
        $this -> is_optional = null;
        $this -> is_required = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> tour_feature_id = $request ->input('tour_feature_id');
        $this -> tour_feature_guid = $request ->input('tour_feature_guid');
        $this -> feature_id = $request ->input('feature_id');
        $this -> tour_id = $request ->input('tour_id');
        $this -> description = $request ->input('description');
        $this -> cost = $request ->input('cost');
        $this -> capacity = $request ->input('capacity');
        $this -> count = $request ->input('count');
        $this -> is_optional = $request ->input('is_optional');
        $this -> is_required = $request ->input('is_required');
    }

    public function store()
    {
        $this->tour_feature_guid = uniqid('',true);
        $this->save();

    }

    public static function findByTourIdAndFeatureId($tour_id, $feature_id)
    {
        $tour_feature = new TourFeature();
        $query = $tour_feature->newQuery();
        $query->where('tour_id', '=', $tour_id);
        $query->where('feature_id', '=', $feature_id);
        $tour_features = $query->get();
        if (count($tour_features) == 0){
            return null;
        }
        else{
            return $tour_features[0];
        }
    }

    public static function findById($id)
    {
        $tour_feature = new TourFeature();
        $query = $tour_feature->newQuery();
        $query->where('tour_feature_id', '=', $id);
        $tour_features = $query->get();
        if (count($tour_features) == 0){
            return null;
        }
        else{
            return $tour_features[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $tour_feature = new TourFeature();
        $query = $tour_feature->newQuery();
        $query->where('tour_feature_id', '=', $id);
        $query->where('tour_feature_guid', 'like', $guid);
        $tour_features = $query->get();
        if (count($tour_features) == 0){
            return null;
        }
        else{
            return $tour_features[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        TourFeature::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $tour_feature = new TourFeature();
        $query = $tour_feature->newQuery();
        $query->where('tour_feature_id', '=', $Id);
        $query->where('tour_feature_guid', 'like', $guid);
        $tour_features = $query->get();
        if (count($tour_features) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function edit($request)
    {
        $oldTourFeature = TourFeature::findByIdAndGuid($request -> input('tour_feature_id'),$request -> input('tour_feature_guid'));

        $oldTourFeature->tour_id=$request -> input('tour_id');
        $oldTourFeature->feature_id=$request -> input('feature_id');
        $oldTourFeature->description=$request -> input('description');
        $oldTourFeature->cost=$request -> input('cost');
        $oldTourFeature->capacity=$request -> input('capacity');
        $oldTourFeature->count=$request -> input('count');
        $oldTourFeature->is_optional=$request -> input('is_optional');
        $oldTourFeature->is_required=$request -> input('is_required');

        $oldTourFeature->save();
    }

    public static function isRelationExistByTourIdAndFeatureId($tourId, $featureId)
    {
        $tour_feature = new TourFeature();
        $query = $tour_feature->newQuery();
        $query->where('feature_id', '=', $featureId);
        $query->where('tour_id', '=', $tourId);
        $tour_features = $query->get();
        if (count($tour_features) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByTourIdAndFeatureId($tourId, $featureId)
    {
        TourFeature::findByTourIdAndFeatureId($tourId, $featureId)->delete();
    }

    public static function editCapacityAndCount($tour_feature_id,$capacity, $count)
    {
        $oldTourFeature = TourFeature::findById($tour_feature_id);


        $oldTourFeature->capacity=$capacity;
        $oldTourFeature->count=$count;


        $oldTourFeature->save();
    }


}