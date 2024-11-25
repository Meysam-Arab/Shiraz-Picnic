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


class GardenReport extends Model
{
    use SoftDeletes;


    protected $table = 'gardens_reports';
    protected $primaryKey = 'garden_report_id';
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
    public function report()
    {
        return $this->belongsTo(Report::class);
    }



    public function initialize()
    {
        $this -> garden_report_id = null;
        $this -> garden_report_guid = null;
        $this -> garden_id = null;
        $this -> report_id = null;
        $this -> description = null;
        $this -> count = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> garden_report_id = $request ->input('garden_report_id');
        $this -> garden_report_guid = $request ->input('garden_report_guid');
        $this -> report_id = $request ->input('report_id');
        $this -> garden_id = $request ->input('garden_id');
        $this -> description = $request ->input('description');
        $this -> count = $request ->input('count');
    }

    public function store()
    {
        $this->garden_report_guid = uniqid('',true);
        $this->save();

    }

    public static function findByGardenIdAndReportId($garden_id, $report_id)
    {
        $garden_report = new GardenReport();
        $query = $garden_report->newQuery();
        $query->where('garden_id', '=', $garden_id);
        $query->where('report_id', '=', $report_id);
        $garden_reports = $query->get();
        if (count($garden_reports) == 0){
            return null;
        }
        else{
            return $garden_reports[0];
        }
    }

    public static function findById($id)
    {
        $garden_report = new GardenReport();
        $query = $garden_report->newQuery();
        $query->where('garden_report_id', '=', $id);
        $garden_reports = $query->get();
        if (count($garden_reports) == 0){
            return null;
        }
        else{
            return $garden_reports[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $garden_report = new GardenReport();
        $query = $garden_report->newQuery();
        $query->where('garden_report_id', '=', $id);
        $query->where('garden_report_guid', 'like', $guid);
        $garden_reports = $query->get();
        if (count($garden_reports) == 0){
            return null;
        }
        else{
            return $garden_reports[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        GardenReport::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $garden_report = new GardenReport();
        $query = $garden_report->newQuery();
        $query->where('garden_report_id', '=', $Id);
        $query->where('garden_report_guid', 'like', $guid);
        $garden_reports = $query->get();
        if (count($garden_reports) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function edit($request)
    {
        $oldGardenReport = GardenReport::findByIdAndGuid($request -> input('garden_report_id'),$request -> input('garden_report_guid'));

        $oldGardenReport->garden_id=$request -> input('garden_id');
        $oldGardenReport->report_id=$request -> input('report_id');
        $oldGardenReport->description=$request -> input('description');
        $oldGardenReport->count=$request -> input('count');

        $oldGardenReport->save();
    }

    public static function isRelationExistByGardenIdAndReportId($gardenId, $reportId)
    {
        $garden_report = new GardenReport();
        $query = $garden_report->newQuery();
        $query->where('report_id', '=', $reportId);
        $query->where('garden_id', '=', $gardenId);
        $garden_reports = $query->get();
        if (count($garden_reports) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByGardenIdAndReportId($gardenId, $reportId)
    {
        GardenReport::findByGardenIdAndReportId($gardenId, $reportId)->delete();
    }
}