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


class GardenGuard extends Model
{
    use SoftDeletes;


    protected $table = 'gardens_guards';
    protected $primaryKey = 'garden_guard_id';
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function initialize()
    {
        $this -> garden_guard_id = null;
        $this -> garden_guard_guid = null;
        $this -> garden_id = null;
        $this -> user_id = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> garden_guard_id = $request ->input('garden_guard_id');
        $this -> garden_guard_guid = $request ->input('garden_guard_guid');
        $this -> user_id = $request ->input('user_id');
        $this -> garden_id = $request ->input('garden_id');
    }

    public function store()
    {
        $this->garden_guard_guid = uniqid('',true);
        $this->save();

    }

    public static function findByGardenIdAndGuardId($garden_id, $guard_id)
    {
        $garden_guard = new GardenGuard();
        $query = $garden_guard->newQuery();
        $query->where('garden_id', '=', $garden_id);
        $query->where('user_id', '=', $guard_id);
        $garden_guards = $query->get();
        if (count($garden_guards) == 0){
            return null;
        }
        else{
            return $garden_guards[0];
        }
    }

    public static function findById($id)
    {
        $garden_guard = new GardenGuard();
        $query = $garden_guard->newQuery();
        $query->where('garden_guard_id', '=', $id);
        $garden_guards = $query->get();
        if (count($garden_guards) == 0){
            return null;
        }
        else{
            return $garden_guards[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $garden_guard = new GardenGuard();
        $query = $garden_guard->newQuery();
        $query->where('garden_guard_id', '=', $id);
        $query->where('garden_guard_guid', 'like', $guid);
        $garden_guards = $query->get();
        if (count($garden_guards) == 0){
            return null;
        }
        else{
            return $garden_guards[0];
        }

    }

    public static function removeByIdAndGuid($id, $guid){

        GardenGuard::findByIdAndGuid($id,$guid)->delete();
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $garden_guard = new GardenGuard();
        $query = $garden_guard->newQuery();
        $query->where('garden_guard_id', '=', $Id);
        $query->where('garden_guard_guid', 'like', $guid);
        $garden_guards = $query->get();
        if (count($garden_guards) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function edit($request)
    {
        $oldGardenGuard = GardenGuard::findByIdAndGuid($request -> input('garden_guard_id'),$request -> input('garden_guard_guid'));

        $oldGardenGuard->garden_id=$request -> input('garden_id');
        $oldGardenGuard->user_id=$request -> input('user_id');

        $oldGardenGuard->save();
    }

    public static function isRelationExistByGardenIdAndGuardId($gardenId, $guardId)
    {
        $garden_guard = new GardenGuard();
        $query = $garden_guard->newQuery();
        $query->where('user_id', '=', $guardId);
        $query->where('garden_id', '=', $gardenId);
        $garden_guards = $query->get();
        if (count($garden_guards) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function removeByGardenIdAndGuardId($gardenId, $guardId)
    {
        GardenGuard::findByGardenIdAndGuardId($gardenId, $guardId)->delete();
    }
}