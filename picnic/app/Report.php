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

class Report extends Model
{
    use SoftDeletes;


    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function toursReports()
    {
        return $this->hasMany(TourReport::class);
    }

    public function gardensReports()
    {
        return $this->hasMany(GardenReport::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($report) {
            $report->toursReports()->delete();
            $report->gardensReports()->delete();
        });
    }

    public function initialize()
    {
        $this -> report_id = null;
        $this -> report_guid = null;
        $this -> title = null;
        $this -> description = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> report_id = $request ->input('report_id');
        $this -> report_guid = $request ->input('report_guid');
        $this -> title = $request ->input('title');
        $this -> description = $request ->input('description');

    }

    public function store()
    {

        $this->report_guid = uniqid('',true);
        $this->save();
        self::generateAllDirectories();
    }


    public static function findByIdAndGuid($id, $guid)
    {
        $report = new Report();
        $query = $report->newQuery();
        $query->where('report_id', '=', $id);
        $query->where('report_guid', 'like', $guid);
        $reports = $query->get();
        if (count($reports) == 0){
            return null;
        }
        else{
            return $reports[0];
        }

    }

    public static function removeByIdAndGuid($report_id, $report_guid){
        Report::findByIdAndGuid($report_id,$report_guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $report = new Report();
        $query = $report->newQuery();
        $query->where('report_id', '=', $Id);
        $query->where('report_guid', 'like', $guid);
        $reports = $query->get()->first();
        if (count($reports) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/reports/'.$this->report_id.'/icon';
        File::makeDirectory($directory,0777,true);

    }

    public function edit($request)
    {
        $oldReport = Report::findByIdAndGuid($request -> input('report_id'),$request -> input('report_guid'));

        $oldReport->name=$request -> input('name');
        $oldReport->description=$request -> input('description');

        $oldReport->save();
    }
}