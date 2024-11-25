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

class BlogMedia extends Model
{
    use SoftDeletes;

    const TYPE_PICTURE = 1;
    const TYPE_VIDEO = 2;

    protected $table = 'blogs_media';
    protected $primaryKey = 'blog_media_id';
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
    protected $fillable = ['title','description','link', 'size', 'type'];

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
     * Get the language that owns the trans_media.
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function initialize()
    {
        $this -> blog_media_id = null;
        $this -> blog_media_guid = null;
        $this -> blog_id = null;
        $this -> link = null;
        $this -> size = null;
        $this -> type = null;
        $this -> title = null;
        $this -> description = null;
        $this -> extension = null;
        $this -> mime_type = null;
    }

    public function intializeByRequest(Request $request)
    {

        $this -> blog_media_id = $request ->input('blog_media_id');
        $this -> blog_media_guid = $request ->input('blog_media_guid');
        $this -> blog_id = $request ->input('blog_id');
        $this -> link = $request ->input('link');
        $this -> size = $request ->input('size');
        $this -> type = $request ->input('type');
        $this -> title = $request ->input('title');
        $this -> description = $request ->input('description');
        $this -> extension = $request ->input('extension');
        $this -> mime_type = $request ->input('mime_type');

    }

    public function store()
    {
        $this->blog_media_guid = uniqid('',true);

        $this->save();

    }

    public static function removeByIdAndGuid($id, $guid){
        BlogMedia::findByIdAndGuid($id,$guid)->delete();
    }

    public function delete()
    {
//        Log::info('haaaa:'.json_encode($this));
        if($this->type == BlogMedia::TYPE_PICTURE)
        {
                $address = storage_path() . '/app/files/blogs/' .$this->blog_id. '/pictures/'.$this->blog_media_id.'.'.$this->extension;
                File::Delete($address);
        }
        if($this->type == BlogMedia::TYPE_VIDEO)
        {
                $address = storage_path() . '/app/files/blogs/' .$this->blog_id. '/clips/'.$this->blog_media_id.'.'.$this-> extension;
            
            File::Delete($address);
        }
        parent::delete();
    }


    public static function existByIdAndGuid($Id,$guid)
    {
        $medi = new BlogBlogMedia();
        $query = $medi->newQuery();
        $query->where('blog_media_id', '=', $Id);
        $query->where('blog_media_guid', '=', $guid);
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
        $medi = new BlogMedia();
        $query = $medi->newQuery();
        $query->where('blog_media_id', '=', $Id);
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
        $medi = new BlogMedia();
        $query = $medi->newQuery();
        $query->where('blog_media_id', '=', $id);
        $query->where('blog_media_guid', 'like', $guid);
        $media = $query->get();
        if (count($media) == 0){
            return null;
        }
        else{
            return $media[0];
        }

    }
}