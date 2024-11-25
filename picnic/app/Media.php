<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:14 PM
 */

namespace App;

use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Log;

class Media extends Model
{
    use SoftDeletes;

    const TYPE_PICTURE = 1;
    const TYPE_VIDEO = 2;

    protected $table = 'media';
    protected $primaryKey = 'media_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    const StatusNonActive = 0;
    const StatusActive = 1;
    const StatusWaitForCheck = 2;
    const StatusDisapproved  = 3;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description','link', 'size', 'type', 'is_approved'];

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
        $this -> media_id = null;
        $this -> media_guid = null;
        $this -> tour_id = null;
        $this -> garden_id = null;
        $this -> link = null;
        $this -> size = null;
        $this -> type = null;
        $this -> title = null;
        $this -> description = null;
        $this -> view_count = null;
        $this -> like_count = null;
        $this -> is_approved = null;
        $this -> extension = null;
        $this -> mime_type = null;
    }

    public function intializeByRequest(Request $request)
    {

        $this -> media_id = $request ->input('media_id');
        $this -> media_guid = $request ->input('media_guid');
        $this -> tour_id = $request ->input('tour_id');
        $this -> garden_id = $request ->input('garden_id');
        $this -> link = $request ->input('link');
        $this -> size = $request ->input('size');
        $this -> type = $request ->input('type');
        $this -> title = $request ->input('title');
        $this -> description = $request ->input('description');
        $this -> view_count = $request ->input('view_count');
        $this -> like_count = $request ->input('like_count');
        $this -> is_approved = $request ->input('is_approved');
        $this -> extension = $request ->input('extension');
        $this -> mime_type = $request ->input('mime_type');

    }

    public function store()
    {
        $this->media_guid = uniqid('',true);
        $this->view_count = 0;
        $this->like_count = 0;
        $this->is_approved = Media::StatusWaitForCheck;

        $this->save();

    }

    public static function removeByIdAndGuid($id, $guid){
        Media::findByIdAndGuid($id,$guid)->delete();
    }

    public function delete()
    {
//        Log::info('haaaa:'.json_encode($this));
        if($this->type == Media::TYPE_PICTURE)
        {
//            Log::info('haaaa-1');
            if($this->garden_id != null)
            {
                $address = storage_path() . '/app/files/gardens/' .$this->garden_id. '/pictures/'.$this->media_id.'.'.$this->extension;
            }
            else
            {
//                Log::info('haaaa-2');
                $address = storage_path() . '/app/files/tours/' .$this->tour_id. '/pictures/'.$this->media_id.'.'.$this->extension;
            }
//            Log::info('haaaa-3:'.json_encode($address));
            File::Delete($address);
//            Log::info('haaaa-4');
        }
        if($this->type == Media::TYPE_VIDEO)
        {
//            Log::info('haaaa-4');
            if($this->garden_id != null)
            {
                $address = storage_path() . '/app/files/gardens/' .$this->garden_id. '/clips/'.$this->media_id.'.'.$this->extension;
            }
            else
            {
                $address = storage_path() . '/app/files/tours/' .$this->tour_id. '/clips/'.$this->media_id.'.'.$this-> extension;
            }
            File::Delete($address);
        }
        parent::delete();
    }


    public static function existByIdAndGuid($Id,$guid)
    {
        $medi = new Media();
        $query = $medi->newQuery();
        $query->where('media_id', '=', $Id);
        $query->where('media_guid', '=', $guid);
        $media = $query->get()->first();
        if (count($media) == 0){
            return false;
        }
        else{
            return true;
        }
    }
    public static function existById($Id)
    {
        $medi = new Media();
        $query = $medi->newQuery();
        $query->where('media_id', '=', $Id);
        $media = $query->get()->first();
        if (count($media) == 0){
            return false;
        }
        else{
            return true;
        }
    }
    public static function findByIdAndGuid($id, $guid)
    {
        $medi = new Media();
        $query = $medi->newQuery();
        $query->where('media_id', '=', $id);
        $query->where('media_guid', 'like', $guid);
        $media = $query->get();
        if (count($media) == 0){
            return null;
        }
        else{
            return $media[0];
        }

    }
}