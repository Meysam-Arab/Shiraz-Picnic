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


class GardenFeature extends Model
{
    use SoftDeletes;


    protected $table = 'gardens_features';
    protected $primaryKey = 'garden_feature_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * Get the blog that owns the trans_media.
     */
    public function garden()
    {
        return $this->belongsTo(Garden::class);
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
        $this -> garden_feature_id = null;
        $this -> garden_feature_guid = null;
        $this -> garden_id = null;
        $this -> feature_id = null;
        $this -> capacity = null;
        $this -> is_optional = null;
        $this -> is_required = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> garden_feature_id = $request ->input('garden_feature_id');
        $this -> garden_feature_guid = $request ->input('garden_feature_guid');
        $this -> feature_id = $request ->input('feature_id');
        $this -> garden_id = $request ->input('garden_id');
        $this -> capacity = $request ->input('capacity');
        $this -> is_optional = $request ->input('is_optional');
        $this -> is_required = $request ->input('is_required');
    }

    public function store()
    {
        $this->garden_feature_guid = uniqid('',true);
        $this->save();

    }

    public static function findByGardenIdAndFeatureId($garden_id, $feature_id)
    {
        $garden_feature = new GardenFeature();
        $query = $garden_feature->newQuery();
        $query->where('garden_id', '=', $garden_id);
        $query->where('feature_id', '=', $feature_id);
        $garden_features = $query->get();
        if (count($garden_features) == 0){
            return null;
        }
        else{
            return $garden_features[0];
        }
    }

    public static function findById($id)
    {
        $garden_feature = new GardenFeature();
        $query = $garden_feature->newQuery();
        $query->where('garden_feature_id', '=', $id);
        $garden_features = $query->get();
        if (count($garden_features) == 0){
            return null;
        }
        else{
            return $garden_features[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $garden_feature = new GardenFeature();
        $query = $garden_feature->newQuery();
        $query->where('garden_feature_id', '=', $id);
        $query->where('garden_feature_guid', 'like', $guid);
        $garden_features = $query->get();
        if (count($garden_features) == 0){
            return null;
        }
        else{
            return $garden_features[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        GardenFeature::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $garden_feature = new GardenFeature();
        $query = $garden_feature->newQuery();
        $query->where('garden_feature_id', '=', $Id);
        $query->where('garden_feature_guid', 'like', $guid);
        $garden_features = $query->get();
        if (count($garden_features) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function edit($request)
    {
        $oldGardenFeature = GardenFeature::findByIdAndGuid($request -> input('garden_feature_id'),$request -> input('garden_feature_guid'));

        $oldGardenFeature->garden_id=$request -> input('garden_id');
        $oldGardenFeature->feature_id=$request -> input('feature_id');
        $oldGardenFeature->capacity=$request -> input('capacity');
        $oldGardenFeature->is_optional=$request -> input('is_optional');
        $oldGardenFeature->is_required=$request -> input('is_required');

        $oldGardenFeature->save();
    }

    public static function isRelationExistByGardenIdAndFeatureId($gardenId, $featureId)
    {
        $garden_feature = new GardenFeature();
        $query = $garden_feature->newQuery();
        $query->where('feature_id', '=', $featureId);
        $query->where('garden_id', '=', $gardenId);
        $garden_features = $query->get();
        if (count($garden_features) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByGardenIdAndFeatureId($gardenId, $featureId)
    {
        GardenFeature::findByGardenIdAndFeatureId($gardenId, $featureId)->delete();
    }
}