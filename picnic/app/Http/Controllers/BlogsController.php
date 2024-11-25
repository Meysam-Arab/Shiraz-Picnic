<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/28/2018
 * Time: 7:19 PM
 */

namespace App\Http\Controllers;

use App\Blog;
use App\BlogMedia;
use App\Media;
use App\QR;
use App\QrBlog;
use App\Tag;
use App\TransBlog;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Validator;
use Exception;
use App\LogEvent;
use Route;
use Log;
use App\Utilities;
use Illuminate\Support\Facades\Auth;

class BlogsController extends Controller
{
    protected $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function catalog()
    {

//        $lastestNews = DB::table('blogs')->where('type',Blog::BLOG_TYPE_NEWS)->take(3)->get();
//        $lastestInstructions = DB::table('blogs')->where('type',Blog::BLOG_TYPE_INSTRUCTION)->take(3)->get();


        $lastestNews = DB::table('blogs')
            ->where('blogs.type','=',Blog::BLOG_TYPE_NEWS)
            ->where('blogs.status','=',Blog::BLOG_STATUS_ACTIVE)
            ->where('blogs.deleted_at','=',null)
            ->orderBy('blogs.blog_id', 'desc')
            ->take(3)
            ->get();

        $lastestInstructions = DB::table('blogs')
            ->where('blogs.type','=',Blog::BLOG_TYPE_INSTRUCTION)
            ->where('blogs.status','=',Blog::BLOG_STATUS_ACTIVE)
            ->where('blogs.deleted_at','=',null)
            ->orderBy('blogs.blog_id', 'desc')
            ->take(3)
            ->get();



//        Log::info(json_encode($lastestInstructions));
        return view('blog.catalog', ['lastestNews' => $lastestNews, 'lastestInstructions' => $lastestInstructions]);

    }

    //meysam - see the list...
    public function index() {

        try
        {

            $blogs = DB::table('blogs')
                ->where('blogs.deleted_at','=',null)
                ->orderBy('blogs.blog_id', 'desc')
                ->get();


            return view('blog.index', ['blogs' => $blogs]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Utilities\Utility::getLoggedInUserId():-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    //meysam - see the list...
    public function showList($type = null) {

        try
        {

            if(strcmp($type, 'null') == 0)
                $type = null;

//            Log::info(json_encode($type));
//            Log::info(json_encode($organization));

            if($type != null)
            {


//                    Log::info('hahaha...1');
                    $blogs = DB::table('blogs')
                        ->where('blogs.type','=',$type)
                        ->where('blogs.status','=',Blog::BLOG_STATUS_ACTIVE)
                        ->where('blogs.deleted_at','=',null)
                        ->orderBy('blogs.blog_id', 'desc')
                        ->get();


            }
            else
            {


//                    Log::info('hahaha...4');
                    $blogs = DB::table('blogs')
                        ->where('blogs.deleted_at','=',null)
                        ->orderBy('blogs.blog_id', 'desc')
                        ->get();

            }

            foreach ($blogs as $blog) {


                $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog->blog_id))
                {
                    $blog->hasCoverPicture = 1;
                }
                else{
                    $blog->hasCoverPicture = 0;
                }
            }

//            Log::info('blogs:'.json_encode(count($blogs)));

//            $blogs->forget('blog_guid');
//            $blogs->forget('created_date');
//            $blogs->forget('updated_date');
//            $blogs->forget('deleted_date');

//            Log::info('blogs:'.json_encode($blogs));

            return view('blog.list', ['blogs' => $blogs]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Utilities\Utility::getLoggedInUserId():-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    /**
    create get
     */
    public function create()
    {
        $types = Blog::BLOG_TYPES;
        return view('blog.create',['types' => $types]);
    }

    //meysam - storing new ...
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required|max:500',
            'description' => 'required',
            'blog_date_time' => 'required|date',
            'type' => 'required|digits:1',
            'status' => 'required|digits:1'
        ]);

        try
        {
//            Log::info('before validate check');
            if($validation->passes()){
//                Log::info('after validate check');
                DB::beginTransaction();

                $blog = new Blog();
                $blog->initializeByRequest($request);
                $blog->store();


                //meysam - save avatar file if exist
                if($request->hasFile('avatar_picture'))
                {
                    $request['tag'] = Tag::TAG_BLOG_AVATAR;
                    if(Utilities\Utility::checkFileExtention($request) && Utilities\Utility::checkFileSize($request))
                    {
                        Utilities\Utility::saveFile($request,$blog->blog_id);
                    }
                    else
                    {
                        Blog::deleteFileDirectory($blog->blog_id);
                        DB::rollback();
                        $message = trans('messages.msgErrorFileFormatOrSize');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }

                //meysam - save pictures if exist
                if($request->hasFile('picture'))
                {
                    $files = $request->file('picture');
                    foreach ($files as $file) {

                        $media = new BlogMedia();
                        $media->initialize();

                        $media->type = BlogMedia::TYPE_PICTURE;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->blog_id =$blog->blog_id;

                        $media->store();

                        $tag = Tag::TAG_BLOG_PICTURE;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_BLOG_PICTURE ,$blog->blog_id, $media->blog_media_id);
                        }
                        else
                        {
                            Blog::deleteFileDirectory($blog->blog_id);
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

                    }
                }

                //meysam - save films if exist
                if($request->hasFile('film'))
                {
                    $files = $request->file('film');
                    foreach ($files as $file) {

                        $media = new BlogMedia();
                        $media->initialize();

                        $media->type = BlogMedia::TYPE_VIDEO;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->blog_id =$blog->blog_id;

                        $media->store();

                        $tag = Tag::TAG_BLOG_CLIP;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_BLOG_CLIP ,$blog->blog_id, $media->blog_media_id);
                        }
                        else
                        {
                            Blog::deleteFileDirectory($blog->blog_id);
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

                    }
                }

                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];

                DB::commit();
                return redirect('/blogs/index')->with('messages', $messages);

            }else{
                $errors = $validation->errors()->all();
                return redirect()->back()->with('errors', $errors);
            }


        }
        catch (Exception $e)
        {

            DB::rollback();
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    /**
     * meysam - edit get
     */
    public function edit($blog_id, $blog_guid)
    {
        try
        {

            if(!Blog::existByIdAndGuid($blog_id,$blog_guid))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $blog = Blog::findByIdAndGuid($blog_id,$blog_guid);


            $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
            if(Utilities\Utility::isFileExist(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog->blog_id))
            {
                $blog->hasBanner = 1;
            }
            else{
                $blog->hasBanner = 0;
            }

            unset($blog->create_date);
            unset($blog->updated_date);
            unset($blog->deleted_date);

            $pictures = DB::table('blogs_media')
                ->where('blog_id', $blog_id)
                ->where('type', BlogMedia::TYPE_PICTURE)
                ->where('deleted_at', null)
                ->get();

            $films = DB::table('blogs_media')
                ->where('blog_id', $blog_id)
                ->where('type', BlogMedia::TYPE_VIDEO)
                ->where('deleted_at', null)
                ->get();


            $types = Blog::BLOG_TYPES;
            return view('blog.edit', ['blog' => $blog, 'pictures'=>$pictures, 'films'=>$films, 'types' => $types]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    /**
     * Update the specified item in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     *
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'blog_id' => 'required',
            'blog_guid' => 'required',
            'title' => 'required|max:500',
            'description' => 'required',
            'blog_date_time' => 'required|date',
            'status' => 'required|digits:1',
              'type' => 'required|digits:1'
        ]);

        if (!Blog::existByIdAndGuid($request->input('blog_id'),$request->input('blog_guid'))) {

            $message = trans('messages.msgErrorItemNotExist');
            $messages = [$message];
            return redirect()->back()->with('errors', $messages);
        }

        if($validation->passes())
        {
            try
            {

                $blog = Blog::findById($request->input('blog_id'));

                DB::beginTransaction();
                Blog::edit($request);


                //meysam - save avatar file if exist
                if($request->hasFile('avatar_picture'))
                {
                    $request['tag'] = Tag::TAG_BLOG_AVATAR;
                    if(Utilities\Utility::checkFileExtention($request) && Utilities\Utility::checkFileSize($request))
                    {
                        $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
                        if(Utilities\Utility::isFileExist(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$request->input('blog_id')))
                        {
                            Utilities\Utility::deleteFile(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$request->input('blog_id'));
                        }
                        Utilities\Utility::saveFile($request,$blog->blog_id);
                    }
                    else
                    {
                        DB::rollback();
                        $message = trans('messages.msgErrorFileFormatOrSize');
                        $messages = [$message];
                        return redirect()->back()->with('messages', $messages);
                    }
                }

                //meysam - create media json
                if($request->hasFile('picture'))
                {
                    $files = $request->file('picture');
                    foreach ($files as $file)
                    {

                        $media = new BlogMedia();
                        $media->initialize();
                        $media->type = Media::TYPE_PICTURE;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->blog_id =$blog->blog_id;

                        $media->store();

                        $tag = Tag::TAG_BLOG_PICTURE;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_BLOG_PICTURE ,$blog->blog_id, $media->blog_media_id);
                        }
                        else
                        {
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }


                    }
                }

                //meysam - create media json
                if($request->hasFile('film'))
                {
                    $files = $request->file('film');
                    foreach ($files as $file)
                    {

                        $media = new BlogMedia();
                        $media->initialize();
                        $media->type = BlogMedia::TYPE_VIDEO;
                        $media->mime_type =  $file->getClientMimeType();
                        $media->extension =  $file->getClientOriginalExtension();
                        $media->link =  null;
                        $media->title =  "ندارد";
                        $media->description =  "ندارد";
                        $media->size =$file->getClientSize();
                        $media->blog_id =$blog->blog_id;

                        $media->store();

                        $tag = Tag::TAG_BLOG_CLIP;
                        if(Utilities\Utility::checkFileExtentionOfFile($file,$tag ) && Utilities\Utility::checkFileSizeOfFile($file,$tag))
                        {
                            Utilities\Utility::saveRawFile($file, Tag::TAG_BLOG_CLIP ,$blog->blog_id, $media->blog_media_id);
                        }
                        else
                        {
                            DB::rollback();
                            $message = trans('messages.msgErrorFileFormatOrSize');
                            $messages = [$message];
                            return redirect()->back()->with('messages', $messages);
                        }

                    }
                }


                DB::commit();
                $message = trans('messages.msgOperationSuccess');
                $messages = [$message];
                return redirect('/blogs/index')->with('messages', $messages);            }
            catch (Exception $e)
            {
                DB::rollback();
                $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
                $logEvent->store();

                $message = trans('messages.msgOperationFailed');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
        }
        else
        {
            $errors = $validation->errors()->all();
            return redirect()->back()->with('errors', $errors);
        }
    }

    public function detail($blog_id){
        try
        {
//            Log::info('haha:'.json_encode(Language::getIdByLocal(App::getLocale())));

            if (!Blog::existById($blog_id))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            $blog = Blog::findById($blog_id);
            $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
            if(Utilities\Utility::isFileExist(Tag::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog->blog_id))
            {
                $blog->hasCoverPicture = 1;
            }
            else{
                $blog->hasCoverPicture = 0;
            }

            $pictures = DB::table('blogs_media')
                ->where('blog_id', $blog_id)
                ->where('type', BlogMedia::TYPE_PICTURE)
                ->where('deleted_at', null)
                ->get();

            $films = DB::table('blogs_media')
                ->where('blog_id', $blog_id)
                ->where('type', BlogMedia::TYPE_VIDEO)
                ->where('deleted_at', null)
                ->get();

            return view('blog.details', ['blog' => $blog, 'pictures' => $pictures, 'films' => $films]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    public function remove($blog_id,$blog_guid){

        try
        {
            if (!Blog::existByIdAndGuid($blog_id,$blog_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Blog::removeByIdAndGuid($blog_id,$blog_guid);

            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    public function getFile($blog_id, $blog_guid, $media_id = null, $tag)
    {
//        Log::info('$tag:'.json_encode($tag));

        try
        {
            if($tag==Tag::TAG_BLOG_AVATAR_DOWNLOAD)
            {
//                Log::info('TAG_BLOG_AVATAR_DOWNLOAD');
                $fileNameWithoutExtention = Blog::BLOG_BANNER_FILE_NAME;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog_id))
                {
                    return \App\Utilities\Utility::getFile(TAG::TAG_BLOG_AVATAR, $fileNameWithoutExtention,$blog_id);
                }

            }
            else if($tag==Tag::TAG_BLOG_PICTURE_DOWNLOAD)
            {
                $fileNameWithoutExtention = $media_id;
//                Log::info('$fileNameWithoutExtention1:'.json_encode($fileNameWithoutExtention));

                if(\App\Utilities\Utility::isFileExist(TAG::TAG_BLOG_PICTURE, $fileNameWithoutExtention,$blog_id))
                {
//                    Log::info('$fileNameWithoutExtention2:'.json_encode($fileNameWithoutExtention));
                    return \App\Utilities\Utility::getFile(TAG::TAG_BLOG_PICTURE, $fileNameWithoutExtention,$blog_id);
                }

            }
            else
            {
                return abort(404);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            return abort(404);
        }
    }

    public function getFileStream($blog_id, $blog_guid, $blog_media_id = null, $tag)
    {
//        Log::info('-1');
        if(Blog::existByIdAndGuid($blog_id,$blog_guid) == false)
        {
            return abort(404);
        }
        try
        {
//            Log::info('0');
            if($tag==Tag::TAG_BLOG_CLIP_DOWNLOAD)
            {
//                Log::info('1');
                $fileNameWithoutExtention = $blog_media_id;
                if(\App\Utilities\Utility::isFileExist(TAG::TAG_BLOG_CLIP, $fileNameWithoutExtention,$blog_id))
                {
//                    Log::info('2');
                    return \App\Utilities\Utility::getFileStream(TAG::TAG_BLOG_CLIP, $fileNameWithoutExtention,$blog_id);
                }

            }
            else
            {
                return abort(404);
            }
        }
        catch (\Exception $e)
        {

            $logEvent = new LogEvent((Auth::check() == true?\App\Utilities\Utility::getLoggedInUserId():-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            return abort(404);
        }
    }

    public function removeBlogBanner( $blog_id, $blog_guid)
    {
        try
        {
            if (!Blog::existByIdAndGuid($blog_id,$blog_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!Utilities\Utility::isFileExist(Tag::TAG_BLOG_AVATAR,Blog::BLOG_BANNER_FILE_NAME,$blog_id))
            {
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            Utilities\Utility::deleteFile(Tag::TAG_BLOG_AVATAR,Blog::BLOG_BANNER_FILE_NAME,$blog_id);
            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }

    public function removeBlogMedia( $blog_id, $blog_guid, $media_id, $media_guid)
    {
        try
        {
            if (!Blog::existByIdAndGuid($blog_id,$blog_guid)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }
            if (!BlogMedia::existById($media_id)){
                $message = trans('messages.msgErrorItemNotExist');
                $messages = [$message];
                return redirect()->back()->with('messages', $messages);
            }

            BlogMedia::removeByIdAndGuid($media_id, $media_guid);

            $message = trans('messages.msgOperationSuccess');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = trans('messages.msgOperationFailed');
            $messages = [$message];
            return redirect()->back()->with('messages', $messages);
        }
    }
}