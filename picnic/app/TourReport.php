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


class TourReport extends Model
{
    use SoftDeletes;


    protected $table = 'tours_reports';
    protected $primaryKey = 'tour_report_id';
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
    public function report()
    {
        return $this->belongsTo(Report::class);
    }



    public function initialize()
    {
        $this -> tour_report_id = null;
        $this -> tour_report_guid = null;
        $this -> tour_id = null;
        $this -> report_id = null;
        $this -> description = null;
        $this -> count = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> tour_report_id = $request ->input('tour_report_id');
        $this -> tour_report_guid = $request ->input('tour_report_guid');
        $this -> report_id = $request ->input('report_id');
        $this -> tour_id = $request ->input('tour_id');
        $this -> description = $request ->input('description');
        $this -> count = $request ->input('count');
    }

    public function store()
    {
        $this->tour_report_guid = uniqid('',true);
        $this->save();

    }

    public static function findByTourIdAndReportId($tour_id, $report_id)
    {
        $tour_report = new TourReport();
        $query = $tour_report->newQuery();
        $query->where('tour_id', '=', $tour_id);
        $query->where('report_id', '=', $report_id);
        $tour_reports = $query->get();
        if (count($tour_reports) == 0){
            return null;
        }
        else{
            return $tour_reports[0];
        }
    }

    public static function findById($id)
    {
        $tour_report = new TourReport();
        $query = $tour_report->newQuery();
        $query->where('tour_report_id', '=', $id);
        $tour_reports = $query->get();
        if (count($tour_reports) == 0){
            return null;
        }
        else{
            return $tour_reports[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $tour_report = new TourReport();
        $query = $tour_report->newQuery();
        $query->where('tour_report_id', '=', $id);
        $query->where('tour_report_guid', 'like', $guid);
        $tour_reports = $query->get();
        if (count($tour_reports) == 0){
            return null;
        }
        else{
            return $tour_reports[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        TourReport::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $tour_report = new TourReport();
        $query = $tour_report->newQuery();
        $query->where('tour_report_id', '=', $Id);
        $query->where('tour_report_guid', 'like', $guid);
        $tour_reports = $query->get();
        if (count($tour_reports) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function edit($request)
    {
        $oldTourReport = TourReport::findByIdAndGuid($request -> input('tour_report_id'),$request -> input('tour_report_guid'));

        $oldTourReport->tour_id=$request -> input('tour_id');
        $oldTourReport->report_id=$request -> input('report_id');
        $oldTourReport->description=$request -> input('description');
        $oldTourReport->count=$request -> input('count');

        $oldTourReport->save();
    }

    public static function isRelationExistByTourIdAndReportId($tourId, $reportId)
    {
        $tour_report = new TourReport();
        $query = $tour_report->newQuery();
        $query->where('report_id', '=', $reportId);
        $query->where('tour_id', '=', $tourId);
        $tour_reports = $query->get();
        if (count($tour_reports) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByTourIdAndReportId($tourId, $reportId)
    {
        TourReport::findByTourIdAndReportId($tourId, $reportId)->delete();
    }
}