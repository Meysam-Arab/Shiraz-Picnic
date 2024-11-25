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
use File;

class Feature extends Model
{
    use SoftDeletes;


    const FEATURE_ICON_FILE_NAME = 'feature_icon';
    const FEATURE_ICON_FILE_SIZE = 20000;
    const FEATURE_ICON_FILE_TYPES  = array( 'svg');

    protected $table = 'features';
    protected $primaryKey = 'feature_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function toursFeatures()
    {
        return $this->hasMany(TourFeature::class);
    }

    public function gardensFeatures()
    {
        return $this->hasMany(GardenFeature::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($feature) {
            $feature->toursFeatures()->delete();
            $feature->gardensFeatures()->delete();
        });
    }

    public function initialize()
    {
        $this -> feature_id = null;
        $this -> feature_guid = null;
        $this -> name = null;
        $this -> description = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> feature_id = $request ->input('feature_id');
        $this -> feature_guid = $request ->input('feature_guid');
        $this -> name = $request ->input('name');
        $this -> description = $request ->input('description');

    }

    public function store()
    {

        $this->feature_guid = uniqid('',true);
        $this->save();
        self::generateAllDirectories();
    }


    public static function findByIdAndGuid($id, $guid)
    {
        $feature = new Feature();
        $query = $feature->newQuery();
        $query->where('feature_id', '=', $id);
        $query->where('feature_guid', 'like', $guid);
        $features = $query->get();
        if (count($features) == 0){
            return null;
        }
        else{
            return $features[0];
        }

    }

    public static function removeByIdAndGuid($feature_id, $feature_guid){
        Feature::findByIdAndGuid($feature_id,$feature_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $feature = new Feature();
        $query = $feature->newQuery();
        $query->where('feature_id', '=', $Id);
        $query->where('feature_guid', 'like', $guid);
        $features = $query->get()->first();
        if (count($features) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/features/'.$this->feature_id.'/icon';
        File::makeDirectory($directory,0777,true);

    }

    public function edit($request)
    {
        $oldFeature = Feature::findByIdAndGuid($request -> input('feature_id'),$request -> input('feature_guid'));

        $oldFeature->name=$request -> input('name');
        $oldFeature->description=$request -> input('description');

        $oldFeature->save();
    }
}