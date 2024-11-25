<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 12:14 PM
 */

namespace App;

use App\Utilities\Utility;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use File;
use Log;


class Blog extends Model
{
    use SoftDeletes;

    const BLOG_BANNER_FILE_NAME = 'blog_banner';

    const BLOG_BANNER_FILE_SIZE = 6291456;
    const BLOG_PICTURE_FILE_SIZE = 6291456;
    const BLOG_CLIP_FILE_SIZE = 6291456;

    const BLOG_BANNER_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');
    const BLOG_PICTURE_FILE_TYPES  = array( 'png', 'jpg', 'jpeg', 'bmp');
    const BLOG_CLIP_FILE_TYPES  = array( 'webm', 'ogg', 'mp4');


    const BLOG_TYPE_NEWS = 0;
    const BLOG_TYPE_INSTRUCTION = 1;
//    const BLOG_TYPE_NOTICES = 2;

    const BLOG_TYPES  = array( Blog::BLOG_TYPE_NEWS, Blog::BLOG_TYPE_INSTRUCTION);


    const BLOG_STATUS_INACTIVE = 0;
    const BLOG_STATUS_ACTIVE = 1;


    const BLOG_STATUSES  = array( Blog::BLOG_STATUS_INACTIVE, Blog::BLOG_STATUS_ACTIVE);

    protected $table = 'blogs';
    protected $primaryKey = 'blog_id';
    protected $dates = ['deleted_at'];
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Get all of the gardenGuarde for the user.
     */
    public function blogMedia()
    {
        return $this->hasMany(BlogMedia::class, 'blog_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($blog) {
            $blog->blogMedia()->delete();

        });
    }

    public function initialize()
    {
        $this -> blog_id = null;
        $this -> blog_guid = null;
        $this -> blog_date_time = null;
        $this -> type = null;
        $this -> title = null;
        $this -> description = null;
        $this -> status = null;
    }

    public function initializeByRequest(Request $request)
    {

        $this -> blog_id = $request ->input('blog_id');
        $this -> blog_guid = $request ->input('blog_guid');
        $this -> blog_date_time = $request ->input('blog_date_time');
        $this -> type = $request ->input('type');
        $this -> title = $request ->input('title');
        $this -> description = $request ->input('description');
        $this -> status = $request ->input('status');

    }

    public function store()
    {
        $this->blog_guid = uniqid('',true);
        $this->save();
        self::generateAllDirectories();

    }

    public static function findById($id)
    {
        $blog = new Blog();
        $query = $blog->newQuery();
        $query->where('blog_id', '=', $id);
        $blogs = $query->get();
        if (count($blogs) == 0){
            return null;
        }
        else{
            return $blogs[0];
        }

    }

    public static function findByIdAndGuid($id, $guid)
    {
        $blog = new Blog();
        $query = $blog->newQuery();
        $query->where('blog_id', '=', $id);
        $query->where('blog_guid', 'like', $guid);
        $blogs = $query->get();
        if (count($blogs) == 0){
            return null;
        }
        else{
            return $blogs[0];
        }

    }

    public function delete()
    {
//        Log::info('in delete');
//        app('App\Http\Controllers\BlogsController')->removeFile($this->blog_id,$this->blog_guid,Tag::TAG_BLOG_PICTURE_DELETE);

//        Log::info('in delete 2');
        parent::delete();
    }

    public static function removeByIdAndGuid($id, $guid){

        Blog::findByIdAndGuid($id,$guid)->delete();

        //////////////meysam - delete directory///////////////
        $address = storage_path() . '/app/files/blogs/'.$id;
        Utility::deleteDir($address);
        //////////////////////////////////////////////////////
    }

    public static function deleteFileDirectory($id){
        //////////////meysam - delete directory///////////////
        $address = storage_path() . '/app/files/blogs/'.$id;
        Utility::deleteDir($address);
        //////////////////////////////////////////////////////
    }

    public static function existByIdAndGuid($Id,$guid)
    {
        $blog = new Blog();
        $query = $blog->newQuery();
        $query->where('blog_id', '=', $Id);
        $query->where('blog_guid', 'like', $guid);
        $blogs = $query->get();
        if (count($blogs) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function existById($Id)
    {
        $blog = new Blog();
        $query = $blog->newQuery();
        $query->where('blog_id', '=', $Id);
        $blogs = $query->get();
        if (count($blogs) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function edit($request)
    {
        $oldBlog = Blog::findByIdAndGuid($request -> input('blog_id'),$request -> input('blog_guid'));

        $oldBlog->blog_date_time=$request -> input('blog_date_time');
        $oldBlog->type=$request -> input('type');
        $oldBlog->status=$request -> input('status');
        $oldBlog->title=$request -> input('title');
        $oldBlog->description=$request -> input('description');
        $oldBlog->save();
    }

    public function generateAllDirectories()
    {
        $directory = storage_path().'/app/files/blogs/'.$this->blog_id.'/pictures';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/blogs/'.$this->blog_id.'/cover';
        File::makeDirectory($directory,0777,true);
        $directory = storage_path().'/app/files/blogs/'.$this->blog_id.'/clips';
        File::makeDirectory($directory,0777,true);

    }

    public static function getTypeString($typeId)
    {
        if($typeId == Blog::BLOG_TYPE_NEWS)
            return "اطلاع رسانی";
        if($typeId == Blog::BLOG_TYPE_INSTRUCTION)
            return "آموزنده";

        return "نامشخص";
    }

    public static function getStatusString($statusId)
    {
        if($statusId == Blog::BLOG_STATUS_ACTIVE)
            return trans('messages.lblStatusActive');
        if($statusId == Blog::BLOG_STATUS_INACTIVE)
            return trans('messages.lblStatusNonActive');

        return "نامشخص";
    }

}