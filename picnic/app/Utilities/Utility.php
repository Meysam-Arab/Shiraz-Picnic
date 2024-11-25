<?php
/**
 * Created by PhpStorm.
 * User: meysam
 * Date: 12/4/2016
 * Time: 10:13 AM
 */

namespace App\Utilities;

use App\Associate;
use App\Blog;
use App\Feature;
use App\LogEvent;
use App\TOUR;
use App\GARDEN;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\Auth;
use Log;
use Mail;
use File;
use Response;
use Symfony\Component\Debug\BufferingLogger;
use Kavenegar\KavenegarApi;

//use phplusir\smsir;

class Utility
{
    const GUARD_TYPE_USER = 0;
    const GUARD_TYPE_ASSOCIATE = 1;

    const ENCRYPTION_PASSWORD = "meysamderweisen1397";

    const SMSIR_APIKEY = '670a9dd0555399ecfcf480bf';
    const SMSIR_SECRETKEY = ')C~65x2WGG&A$Iy';
    const SMSIR_GROUPID = 25516;


    //function for filtering query dynemically
    public static function fillQueryFilter($query, $params)
    {
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'orderBy':
                    $query->orderBy($param[1], $param[2]);
                    break;
                case 'take':
                    $query->take($param[1]);
                    break;
                case 'skip':
                    $query->skip($param[1]);
                    break;
                case 'where':
                    $query->where($param[1], $param[2], $param[3]);
                    break;
                case 'groupBy':
                    $query->groupBy($param[1]);
                    break;
                case 'having':
                    $query->having($param[1], $param[2], $param[3]);
                    break;
                case 'orWhere':
                    $query->orWhere($param[1], $param[2]);
                    break;
                case 'whereRaw':
                    $query->whereRaw($param[1]);
                    break;
                case 'orWhereRaw':
                    $query->orWhereRaw($param[1]);
                    break;
                case 'between':
                    $query->whereBetween($param[1], [$param[2], $param[3]]);
                    break;
                case 'orbetween':
                    $query->orWhereBetween($param[1], [$param[2], $param[3]]);
                    break;
                case 'whereIn':
                    $query->whereIn($param[1], $param[2]);
                    break;
            }
        }
        return $query;
    }


    //function for filtering query dynemically
    public static function fillQueryJoin($query, $params)
    {
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'join':
                    $query->join($param[1], $param[2][0], $param[2][1], $param[2][2]);
//                    $query->join($param[1], function ($query,$pt) {
//                        $query->on($pt[2][0], $pt[2][1], $pt[2][2]);
//                        foreach ($pt[3] as $item){
//                            $query->where($item[0], $item[1], $item[2]);
//                        }
//                      });
                    break;
                case 'leftjoin':
                    $query->leftjoin($param[1], $param[2][0], $param[2][1], $param[2][2]);
                    break;
                case 'crossjoin':
                    $query->crossjoin($param[1], $param[2][0], $param[2][1], $param[2][2]);
                    break;
            }
        }
        return $query;
    }

    //function for aliasing query dynemically
    public static function fillQueryAlias($query, $params, $distinctFlag = NULL)
    {
        $temp = array();
        foreach ($params as $param) {
            switch ($param[0]) {
                case 'se':
                    array_push($temp, $param[1] . '.' . $param[2]);
                    break;
                case 'st':
                    array_push($temp, $param[1] . '.*');
                    break;
                case 'as':
                    array_push($temp, $param[1] . '.' . $param[2] . ' as ' . $param[3]);
                    break;
            }
        }
        IF ($distinctFlag == true) {
            $query->select($temp)->distinct();
        } else
            $query->select($temp);


        return $query;
    }

    //remove foreign guid
    public static function removeForeignGuid($list, $a, $b)
    {
        foreach ($list as $obj) {
            if ($obj[$a] != Auth()->user()->id) {
                $obj[$b] = null;
            }
        }
        return $list;
    }

    //size of file in link
    public static function curl_get_file_size($url)
    {
        // Assume failure.
        $result = -1;
        $curl = curl_init($url);
        // Issue a HEAD request and follow any redirects.
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );
        $data = curl_exec($curl);
        curl_close($curl);
        if ($data) {
            $content_length = "unknown";
            $status = "unknown";

            if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches)) {
                $status = (int)$matches[1];
            }

            if (preg_match("/Content-Length: (\d+)/", $data, $matches)) {
                $content_length = (int)$matches[1];
            }
            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            if ($status == 200 || ($status > 300 && $status <= 308)) {
                $result = $content_length;
            }
        }
        return $result;
    }

    public static function convert($string)
    {
        $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $estern = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($estern, $western, $string);
    }

    public static function cleanDateTime($dateTime)
    {
        $dateTime = str_replace("/", "", $dateTime);
        $dateTime = str_replace(" ", "", $dateTime);
        $dateTime = str_replace(":", "", $dateTime);
        $dateTime = str_replace("-", "", $dateTime);

        return $dateTime;
    }

    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnoprqstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function sendRecoveryMailV2($mmessage, $email, $pass)
    {

        Mail::send('emails.recovery', ['pass' => $pass, 'messageText' => $mmessage], function ($message) use ($email) {

            $message->from('info@shirazpicnic.ir', 'Do Not Reply');
            $message->to($email);
            $message->subject('درخواست تغییر رمز عبور');

        });

    }

    public static function sendRecoverySMS($message, $number, $pass)
    {
//        Log::info('number:'.json_encode($number).' message: '.json_encode($message));
        $receptor = $number;
        $message = "لطفا روی لینک زیر کلیک کنید، فشردن این لینک به معنی تایید شما برای تغییر رمز عبورتان در سایت شیراز پیک نیک خواهد بود. پس از فشردن این لینک رمز شما به رمز درخواستی تغییر می یابد ".url("/users/recover?link={$message}")." رمز عبور جدید : ".$pass;

        self::SendSMS($message, $receptor);

    }

    public static function sendReserveSMS($message, $number)
    {
        self::SendSMS($message, $number);
    }

    public static function SendSMS($message, $number)
    {
        try {

            date_default_timezone_set("Asia/Tehran");

            // your PayamakSefid panel configuration
            $APIKey = Utility::SMSIR_APIKEY;
            $SecretKey = Utility::SMSIR_SECRETKEY;

            // Send data
            $SendData = array(
                'Message' => $message,
                'MobileNumbers' => [$number],
                'CanContinueInCaseOfError' => true
            );

            $PayamakSefid_SendByMobileNumbers = new PayamakSefid_Send($APIKey,$SecretKey);
            $SendByMobileNumbers = $PayamakSefid_SendByMobileNumbers->SendByMobileNumbers($SendData);
//            echo "<pre>";
//            var_dump($SendByMobileNumbers);

//            $temp = json_decode($SendByMobileNumbers);
//            Log::info('$temp :'.json_encode($temp ));

        } catch (Exeption $e) {
//            echo 'Error SendByMobileNumbers : '.$e->getMessage();
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
        }

    }

    public static function sendRegisterMailV2($mmessage, $email, $pass)
    {
        Mail::send('emails.register', ['pass' => $pass, 'message' => $mmessage, 'email' => $email], function ($message) use ($email) {

            $message->from('info@shirazpicnic.ir', 'Do Not Reply');
            $message->to($email);
            $message->subject('ثبت نام در شیراز پیک نیک');

        });
    }

    public static function sendInformMail($description, $email)
    {
        //dispatch((new SendEmail())->delay(60 * 5));
        Mail::send('emails.inform', ['description' => $description], function ($message) use ($email) {

            $message->from('info@shirazpicnic.ir', 'Do Not Reply');
            $message->to($email);
            $message->subject('پیام جدید در قسمت ارتباط با ما');

        });
    }

    public static function downloadFile($tag, $fileNameWithoutExtention, $item_id)
    {
        $file = null;

        $address = $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_USER_AVATAR) {
            $address = $address . '/users/' . $item_id . '/avatar';
        } else if ($tag == Tag::TAG_TOUR_PICTURE) {
            $address = $address . '/tours/' . $item_id . '/pictures';
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {
            $address = $address . '/tours/' . $item_id . '/avatar';
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {
            $address = $address . '/features/' . $item_id . '/icon';
        }
        $files1 = scandir($address);
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        $add = null;
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $add = $address . '/' . $value;
                break;
            }
        }
        return $response = response()->download($add);
    }

    public static function getFile($tag, $fileNameWithoutExtention, $item_id)
    {
        $file = null;
//        Log::info('getFile:'.json_encode($fileNameWithoutExtention));
//        Log::info('$item_id:'.json_encode($item_id));
        $address = $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_USER_AVATAR) {
            $address = $address . '/users/' . $item_id . '/avatar';
        }
        else if ($tag == Tag::TAG_GARDEN_PICTURE)
        {
            $address = $address . '/gardens/' . $item_id . '/pictures';
        } else if ($tag == Tag::TAG_GARDEN_AVATAR)
        {
            $address = $address . '/gardens/' . $item_id . '/avatar';
        } else if ($tag == Tag::TAG_TOUR_PICTURE)
        {
            $address = $address . '/tours/' . $item_id . '/pictures';
        } else if ($tag == Tag::TAG_TOUR_AVATAR)
        {
            $address = $address . '/tours/' . $item_id . '/avatar';
        } else if ($tag == Tag::TAG_FEATURE_ICON)
        {
            $address = $address . '/features/' . $item_id . '/icon';
        }else if ($tag == Tag::TAG_BLOG_PICTURE)
        {
            $address = $address . '/blogs/' . $item_id . '/pictures';
        } else if ($tag == Tag::TAG_BLOG_AVATAR)
        {
            $address = $address . '/blogs/' . $item_id . '/cover';
        }
        $files1 = scandir($address);
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        $add = null;
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $add = $address . '/' . $value;
                break;
            }
        }

        $file = File::get($add);
        $type = File::mimeType($add);

        $response = Response::make($file);
        $response->header("Content-Type", $type);
        return $response;
    }

    public static function getFileStream($tag, $fileNameWithoutExtention, $item_id)
    {
        $file = null;

        $address = $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_GARDEN_CLIP)
        {
            $address = $address . '/gardens/' . $item_id . '/clips';
        }else if ($tag == Tag::TAG_BLOG_CLIP)
        {
            $address = $address.'/blogs/' .$item_id . '/clips';
        }
        else if ($tag == Tag::TAG_TOUR_CLIP)
        {
            $address = $address.'/tours/' .$item_id . '/clips';
        }
        $files1 = scandir($address);
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        $add = null;
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $add = $address . '/' . $value;
                break;
            }
        }

//        $file = File::get($add);
        $type = File::mimeType($add);
//
//        $response = Response::make($file);
//        $response->header("Content-Type", $type);
//        return $response;

        $filename = $add;
        $headers = array(
            'Content-type' => $type,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        );
        return Response::make(file_get_contents($filename), 200, $headers);
    }

    public static function saveFile($request, $item_id)
    {
        if ($request->input('tag') == Tag::TAG_USER_AVATAR) {
//            Log::info('meysam - in save file');
            $address = storage_path() . '/app/files/users/' . $item_id . '/avatar';
            $fileName = User::USER_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_USER_AVATAR, $fileName, $item_id);

            $file = $request->file('avatar');
            $fileName = User::USER_AVATAR_FILE_NAME . '.' . $request->file('avatar')->getClientOriginalExtension();
            $file->move($address, $fileName);

            $fileAddress = $address.'/'.$fileName;
            Utility::rescaleImage($fileAddress, 200, 218, File::mimeType($fileAddress));

            if($file->getClientSize() > User::USER_AVATAR_FILE_SIZE)
            {
//                $fileAddress = $address.'/'.$fileName;
                Utility::reduceFileSize($fileAddress,File::mimeType($fileAddress),User::USER_AVATAR_FILE_SIZE);
            }

        }
        if ($request->input('tag') == Tag::TAG_GARDEN_AVATAR) {
            $address = storage_path() . '/app/files/gardens/' . $item_id . '/avatar';
            $fileName = GARDEN::GARDEN_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_GARDEN_AVATAR, $fileName, $item_id);

            $file = $request->file('avatar_picture');
            $fileName = GARDEN::GARDEN_AVATAR_FILE_NAME . '.' . $request->file('avatar_picture')->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($request->input('tag') == Tag::TAG_TOUR_AVATAR) {
            $address = storage_path() . '/app/files/tours/' . $item_id . '/avatar';
            $fileName = Tour::TOUR_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_TOUR_AVATAR, $fileName, $item_id);

            $file = $request->file('avatar_picture');
            $fileName = Tour::TOUR_AVATAR_FILE_NAME . '.' . $request->file('avatar_picture')->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($request->input('tag') == Tag::TAG_TOUR_PICTURE) {
            $address = storage_path() . '/app/files/tours/' . $item_id . '/pictures';
            $fileName = Tour::TOUR_PICTURE_FILE_NAME;
            self::deleteFile(Tag::TAG_TOUR_PICTURE, $fileName, $item_id);

            $file = $request->file('picture');
            $fileName = Tour::TOUR_PICTURE_FILE_NAME . '.' . $request->file('picture')->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($request->input('tag') == Tag::TAG_FEATURE_ICON) {
            $address = storage_path() . '/app/files/features/' . $item_id . '/icon';
            $fileName = Feature::FEATURE_ICON_FILE_NAME;
            self::deleteFile(Tag::TAG_FEATURE_ICON, $fileName, $item_id);

            $file = $request->file('icon');
            $fileName = Feature::FEATURE_ICON_FILE_NAME . '.' . $request->file('icon')->getClientOriginalExtension();
            $file->move($address, $fileName);

//            if($file->getClientSize() > Feature::FEATURE_ICON_FILE_SIZE)
//            {
//                $fileAddress = $address.'/'.$fileName;
//                Utility::reduceFileSize($fileAddress,File::mimeType($fileAddress),Feature::FEATURE_ICON_FILE_SIZE);
//            }
        }
        if ($request->input('tag') == Tag::TAG_BLOG_AVATAR) {
            $address = storage_path() . '/app/files/blogs/' . $item_id . '/cover';
            $fileName = Blog::BLOG_BANNER_FILE_NAME;
            self::deleteFile(Tag::TAG_BLOG_AVATAR, $fileName, $item_id);

            $file = $request->file('avatar_picture');
            $fileName = Blog::BLOG_BANNER_FILE_NAME . '.' . $request->file('avatar_picture')->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
    }

    public static function deleteDir($dirPath) {
        $olddir = $dirPath;
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($olddir);
    }

    public static function saveRawFile($file, $tag, $item_id, $fileName)
    {
        if ($tag == Tag::TAG_USER_AVATAR) {
//            Log::info('meysam - in save file');
            $address = storage_path() . '/app/files/users/' . $item_id . '/avatar';
            $fileName = User::USER_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_USER_AVATAR, $fileName, $item_id);
            $fileName = User::USER_AVATAR_FILE_NAME . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);

        }
        if ($tag == Tag::TAG_GARDEN_AVATAR) {
            $address = storage_path() . '/app/files/gardens/' . $item_id . '/avatar';
            $fileName = GARDEN::GARDEN_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_GARDEN_AVATAR, $fileName, $item_id);

            $fileName = GARDEN::GARDEN_AVATAR_FILE_NAME . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_TOUR_AVATAR) {
            $address = storage_path() . '/app/files/tours/' . $item_id . '/avatar';
            $fileName = Tour::TOUR_AVATAR_FILE_NAME;
            self::deleteFile(Tag::TAG_TOUR_AVATAR, $fileName, $item_id);

            $fileName = Tour::TOUR_AVATAR_FILE_NAME . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_TOUR_PICTURE) {
            $address = storage_path() . '/app/files/tours/' . $item_id . '/pictures';
            self::deleteFile(Tag::TAG_TOUR_PICTURE, $fileName, $item_id);
            $fileName = $fileName . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_FEATURE_ICON) {
            $address = storage_path() . '/app/files/features/' . $item_id . '/icon';
            $fileName = Feature::FEATURE_ICON_FILE_NAME;
            self::deleteFile(Tag::TAG_FEATURE_ICON, $fileName, $item_id);
            $fileName = Feature::FEATURE_ICON_FILE_NAME . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_BLOG_PICTURE) {
            $address = storage_path() . '/app/files/blogs/' . $item_id . '/pictures';
            self::deleteFile(Tag::TAG_BLOG_PICTURE, $fileName, $item_id);
            $fileName = $fileName . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_BLOG_CLIP) {
            $address = storage_path() . '/app/files/blogs/' . $item_id . '/clips';
            self::deleteFile(Tag::TAG_BLOG_CLIP, $fileName, $item_id);
            $fileName = $fileName . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
        if ($tag == Tag::TAG_TOUR_CLIP) {
            $address = storage_path() . '/app/files/tours/' . $item_id . '/clips';
            self::deleteFile(Tag::TAG_TOUR_CLIP, $fileName, $item_id);
            $fileName = $fileName . '.' . $file->getClientOriginalExtension();
            $file->move($address, $fileName);
        }
    }

    public static function deleteFile($tag, $fileNameWithoutExtention, $item_id)
    {

        $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_USER_AVATAR) {
            $address = $address . '/users/' . $item_id . '/avatar';
        }
        if ($tag == Tag::TAG_FEATURE_ICON) {
            $address = $address . '/features/' . $item_id . '/icon';
        }
        if ($tag == Tag::TAG_TOUR_AVATAR) {
            $address = $address . '/tours/' . $item_id . '/avatar';
        }
        if ($tag == Tag::TAG_BLOG_AVATAR) {
            $address = $address . '/blogs/' . $item_id . '/cover';
        }
        if ($tag == Tag::TAG_TOUR_PICTURE) {
            $address = $address . '/tours/' . $item_id . '/pictures';
        }
        if ($tag == Tag::TAG_BLOG_PICTURE) {
            $address = $address . '/blogs/' . $item_id . '/pictures';
        }
        if ($tag == Tag::TAG_BLOG_CLIP) {
            $address = $address . '/blogs/' . $item_id . '/clips';
        }
        if ($tag == Tag::TAG_TOUR_CLIP) {
            $address = $address . '/tours/' . $item_id . '/clips';
        }
        $files1 = scandir($address);

        $nameOfFile = "";
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $nameOfFile = $value;
                break;
            }
        }
        //delete file
        if ($nameOfFile != "") {
            if (File::exists($address . '/' . $nameOfFile)) {
                //delete file
                File::delete($address . '/' . $nameOfFile);
            }

        }

        return true;
    }

    public static function isFileExist($tag, $fileNameWithoutExtention, $item_id)
    {
//        Log::info('meysam - in isFileExist file');

        $address = $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_USER_AVATAR) {
            $address = $address . '/users/' . $item_id . '/avatar';
        }
        else if ($tag == Tag::TAG_GARDEN_AVATAR) {
            $address = $address . '/gardens/' . $item_id . '/avatar';
        } else if ($tag == Tag::TAG_GARDEN_PICTURE) {
            $address = $address . '/gardens/' . $item_id . '/pictures';
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {
            $address = $address . '/tours/' . $item_id . '/avatar';
        }
        else if ($tag == Tag::TAG_TOUR_PICTURE) {
            $address = $address . '/tours/' . $item_id . '/pictures';
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {
            $address = $address . '/features/' . $item_id . '/icon';
        }
        else if ($tag == Tag::TAG_BLOG_AVATAR) {
            $address = $address . '/blogs/' . $item_id . '/cover';
        }
        else if ($tag == Tag::TAG_BLOG_PICTURE) {
            $address = $address . '/blogs/' . $item_id . '/pictures';
        }
        else if ($tag == Tag::TAG_BLOG_CLIP) {
            $address = $address . '/blogs/' . $item_id . '/clips';
        }
        else if ($tag == Tag::TAG_TOUR_CLIP) {
            $address = $address . '/tours/' . $item_id . '/clips';
        }

        $files1 = scandir($address);
        $exist = false;
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $exist = true;
                break;
            }
        }

        return $exist;

    }

    public static function checkFileSize($request)
    {
//        Log::info('meysam - in checkFileSize file');

        $tag = $request->input('tag');
        if ($tag == Tag::TAG_USER_AVATAR) {
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $size = $file->getClientSize();
                if ($size > User::USER_AVATAR_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }

        }
        else if ($tag == Tag::TAG_GARDEN_AVATAR) {

            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $size = $file->getClientSize();
                if ($size > GARDEN::GARDEN_AVATAR_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_GARDEN_PICTURE) {

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $size = $file->getClientSize();
                if ($size > GARDEN::GARDEN_PICTURE_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {

            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $size = $file->getClientSize();
                if ($size > Tour::TOUR_AVATAR_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_PICTURE) {

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $size = $file->getClientSize();
                if ($size > Tour::TOUR_PICTURE_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {

            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $size = $file->getClientSize();
                if ($size > Feature::FEATURE_ICON_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_AVATAR) {
            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $size = $file->getClientSize();
                if ($size > Blog::BLOG_BANNER_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_PICTURE) {
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $size = $file->getClientSize();
                if ($size > Blog::BLOG_PICTURE_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_CLIP) {
            if ($request->hasFile('film')) {
                $file = $request->file('film');
                $size = $file->getClientSize();
                if ($size > Blog::BLOG_CLIP_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_CLIP) {
            if ($request->hasFile('film')) {
                $file = $request->file('film');
                $size = $file->getClientSize();
                if ($size > Tour::TOUR_CLIP_FILE_SIZE) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;

    }

    public static function checkFileExtention($request)
    {
//        log::info('in checkFileExtention');
        $tag = $request->input('tag');
        if ($tag == Tag::TAG_USER_AVATAR) {
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $ext = $file->getClientOriginalExtension();
                $allowed = User::USER_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_GARDEN_AVATAR) {
            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $ext = $file->getClientOriginalExtension();
                $allowed = GARDEN::GARDEN_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_GARDEN_PICTURE) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();

                $allowed = GARDEN::GARDEN_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {
            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $ext = $file->getClientOriginalExtension();
                $allowed = Tour::TOUR_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_PICTURE) {
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $ext = $file->getClientOriginalExtension();

                $allowed = Tour::TOUR_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $ext = $file->getClientOriginalExtension();

                $allowed = Feature::FEATURE_ICON_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_AVATAR) {
            if ($request->hasFile('avatar_picture')) {
                $file = $request->file('avatar_picture');
                $ext = $file->getClientOriginalExtension();

                $allowed = Blog::BLOG_BANNER_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_PICTURE) {
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $ext = $file->getClientOriginalExtension();

                $allowed = Blog::BLOG_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_BLOG_CLIP) {
            if ($request->hasFile('film')) {
                $file = $request->file('film');
                $ext = $file->getClientOriginalExtension();

                $allowed = Blog::BLOG_CLIP_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        else if ($tag == Tag::TAG_TOUR_CLIP) {
            if ($request->hasFile('film')) {
                $file = $request->file('film');
                $ext = $file->getClientOriginalExtension();

                $allowed = Tour::TOUR_CLIP_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    public static function checkFileSizeOfFile($file, $tag)
    {
//        Log::info('meysam - in checkFileSize file');


        if ($tag == Tag::TAG_USER_AVATAR) {
                $size = $file->getClientSize();
                if ($size > User::USER_AVATAR_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_GARDEN_AVATAR) {
             $size = $file->getClientSize();
                if ($size > GARDEN::GARDEN_AVATAR_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_GARDEN_PICTURE) {
             $size = $file->getClientSize();
                if ($size > GARDEN::GARDEN_PICTURE_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {
                $size = $file->getClientSize();
                if ($size > Tour::TOUR_AVATAR_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_TOUR_PICTURE) {
                $size = $file->getClientSize();
                if ($size > Tour::TOUR_PICTURE_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {
                $size = $file->getClientSize();
                if ($size > Feature::FEATURE_ICON_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_BLOG_AVATAR) {
                $size = $file->getClientSize();
                if ($size > Blog::BLOG_BANNER_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_BLOG_PICTURE) {
                $size = $file->getClientSize();
                if ($size > Blog::BLOG_PICTURE_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_BLOG_CLIP) {

                $size = $file->getClientSize();
                if ($size > Blog::BLOG_CLIP_FILE_SIZE) {
                    return false;
                }
        }
        else if ($tag == Tag::TAG_TOUR_CLIP) {

                $size = $file->getClientSize();
                if ($size > Tour::TOUR_CLIP_FILE_SIZE) {
                    return false;
                }
        }
        return true;

    }

    public static function checkFileExtentionOfFile($file, $tag)
    {

        if ($tag == Tag::TAG_USER_AVATAR) {
                $ext = $file->getClientOriginalExtension();
                $allowed = User::USER_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_GARDEN_AVATAR) {
                $ext = $file->getClientOriginalExtension();
                $allowed = GARDEN::GARDEN_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_GARDEN_PICTURE) {

                $ext = $file->getClientOriginalExtension();

                $allowed = GARDEN::GARDEN_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_TOUR_AVATAR) {
                $ext = $file->getClientOriginalExtension();
                $allowed = Tour::TOUR_AVATAR_FILE_TYPES;
                if (!in_array($ext, $allowed)) {
                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_TOUR_PICTURE) {
                $ext = $file->getClientOriginalExtension();

                $allowed = Tour::TOUR_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_FEATURE_ICON) {
                $ext = $file->getClientOriginalExtension();

                $allowed = Feature::FEATURE_ICON_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_BLOG_AVATAR) {
                $ext = $file->getClientOriginalExtension();
                $allowed = Blog::BLOG_BANNER_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_BLOG_PICTURE) {
                $ext = $file->getClientOriginalExtension();
                $allowed = Blog::BLOG_PICTURE_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_BLOG_CLIP) {
                $ext = $file->getClientOriginalExtension();
                $allowed = Blog::BLOG_CLIP_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
        else if ($tag == Tag::TAG_TOUR_CLIP) {
                $ext = $file->getClientOriginalExtension();
                $allowed = Tour::TOUR_CLIP_FILE_TYPES;
                if (!in_array($ext, $allowed)) {

                    return false;
                } else {
                    return true;
                }
        }
    }

    public static function getFileList($tag, $fileNameWithoutExtention, $item_id)
    {
//        Log::info('meysam - in isFileExist file');

        $address = $address = storage_path() . '/app/files';
        if ($tag == Tag::TAG_GARDEN_PICTURE) {
            $address = $address . '/gardens/' . $item_id . '/pictures';
        } else if ($tag == Tag::TAG_TOUR_PICTURE) {
            $address = $address . '/tours/' . $item_id . '/pictures';
        }


        $files = scandir($address);
        $exist = false;
        $search = $fileNameWithoutExtention;
        $search_length = strlen($search);
        foreach ($files as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $exist = true;
                break;
            }
        }

        return $exist;

    }

    public static function reduceFileSize($imageAddrerss,$imageType, $maxFileSize)
    {
        //get file size on server
        $filesizeonserver = filesize($imageAddrerss);

        if($filesizeonserver > $maxFileSize){
            do{
                clearstatcache();
                Utility::resizeImage($imageAddrerss, 0.05, $imageType);
                $filesizeonserver = filesize($imageAddrerss);
            } while ($filesizeonserver > $maxFileSize);
        }

    }

    public static function resizeImage($file, $percent, $imageType){

        list($width, $height) = getimagesize($file);
        $newwidth = $width-($width*$percent);
        $newheight = $height-($height*$percent);

        $thumb = imagecreatetruecolor($newwidth, $newheight);

        switch($imageType){
            case 'image/png':
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $source = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $source = imagecreatefromgif($file);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($file);
                break;
        }

        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        switch($imageType){
            case 'image/png':
                $image = imagepng($thumb, $file, 0);
                break;
            case 'image/gif':
                $image = imagegif($file);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagejpeg($thumb, $file, 100);
                break;
        }

        return $image;
    }

    public static function getPersianDayOfWeekCode($date)
    {
        return date('w', strtotime($date));

    }

    public static function getPersianDayOfWeek($date)
    {
        $dayOfWeek = date('w', strtotime($date));
        if ($dayOfWeek == 0) {
            return "شنبه";
        }
        if ($dayOfWeek == 1) {
            return "یکشنبه";
        }
        if ($dayOfWeek == 2) {
            return "دوشنبه";
        }
        if ($dayOfWeek == 3) {
            return "سه شنبه";
        }
        if ($dayOfWeek == 4) {
            return "چهارشنبه";
        }
        if ($dayOfWeek == 5) {
            return "پنج شنبه";
        }
        if ($dayOfWeek == 6) {
            return "جمعه";
        }
        return "نامشخص";
    }

    public static function getPersianDayOfWeekStringByCode($dayCode)
    {
        $dayOfWeek = $dayCode;
        if ($dayOfWeek == 0) {
            return "شنبه";
        }
        if ($dayOfWeek == 1) {
            return "یکشنبه";
        }
        if ($dayOfWeek == 2) {
            return "دوشنبه";
        }
        if ($dayOfWeek == 3) {
            return "سه شنبه";
        }
        if ($dayOfWeek == 4) {
            return "چهار شنبه";
        }
        if ($dayOfWeek == 5) {
            return "پنج‌شنبه";
        }
        if ($dayOfWeek == 6) {
            return "جمعه";
        }
        return "نامشخص";
    }

    public static function getGeorgianDayOfWeek($date)
    {
        $dayOfWeek = date('w', strtotime($date));
        if ($dayOfWeek == 0) {
            return "Saturday";
        }
        if ($dayOfWeek == 1) {
            return "Sunday";
        }
        if ($dayOfWeek == 2) {
            return "Monday";
        }
        if ($dayOfWeek == 3) {
            return "Tuesday";
        }
        if ($dayOfWeek == 4) {
            return "Wednesday";
        }
        if ($dayOfWeek == 5) {
            return "Thursday";
        }
        if ($dayOfWeek == 6) {
            return "Friday";
        }
        return "";
    }

    public static function getCorrectDayOFWeek($dayOfWeek)
    {
        if ($dayOfWeek == 6)
            return 0;
        if ($dayOfWeek == 0)
            return 1;
        if ($dayOfWeek == 1)
            return 2;
        if ($dayOfWeek == 2)
            return 3;
        if ($dayOfWeek == 3)
            return 4;
        if ($dayOfWeek == 4)
            return 5;
        if ($dayOfWeek == 5)
            return 6;
    }

    public static function checkEmail($email)
    {
        if (strpos($email, '@') !== false) {
            $split = explode('@', $email);
            return (strpos($split['1'], '.') !== false ? true : false);
        } else {
            return false;
        }
    }

    public static function contactExist($number)
    {
        try {

            date_default_timezone_set("Asia/Tehran");

            // your PayamakSefid panel configuration
            $APIKey = Utility::SMSIR_APIKEY;
            $SecretKey = Utility::SMSIR_SECRETKEY;

            $prefix = '';
            $firstname = '';
            $lastname = '';
            $mobile = ltrim($number, '0');
            $groupid = Utility::SMSIR_GROUPID;
            $pageid = 0;

//            Log::info($mobile);

            $PayamakSefid_SearchContacts = new PayamakSefid_SearchContacts($APIKey, $SecretKey);
            $SearchContacts = $PayamakSefid_SearchContacts->SearchContacts($prefix, $firstname, $lastname, $mobile, $groupid, $pageid);

            if (empty($SearchContacts->Contacts)) {
                // list is empty.
//                Log::info('empty');
                return false;
            } else {
//                Log::info('exist');
                return true;
            }
            return false;

//            Log::info('$SearchContacts : ' . var_dump($SearchContacts));
//            Log::info('$SearchContacts : ' . json_encode($SearchContacts));
//            Log::info('$SearchContacts->Contacts : ' . json_encode(var_dump($SearchContacts->Contacts)));

        } catch (Exeption $e) {
//            Log::info('Error $SearchContacts : ' . $e->getMessage());
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            return false;
        }
    }

    public static function addContactSMS($number, $name, $family)
    {
        try {

            date_default_timezone_set("Asia/Tehran");

            // your PayamakSefid panel configuration
            $APIKey = Utility::SMSIR_APIKEY;
            $SecretKey = Utility::SMSIR_SECRETKEY;

            // add contacts data
            $ContactsData = array(
                'ContactsDetails' => array(
                    array(
                        'Prefix' => '',
                        'FirstName' => $name,
                        "LastName" => $family,
                        'Mobile' => $number,
                        'EmojiId' => '1'
                    )
                ),
                'GroupId' => Utility::SMSIR_GROUPID
            );

            $PayamakSefid_AddContacts = new PayamakSefid_AddContacts($APIKey,$SecretKey);
            $AddContacts = $PayamakSefid_AddContacts->AddContacts($ContactsData);
//	echo "<pre>";
//	var_dump($AddContacts);
//	Log::info('$addContact:'.json_encode($AddContacts));
//    Log::info('$addContact->IsSuccessful :'.json_encode($AddContacts->IsSuccessful ));
//    $temp = json_decode($AddContacts);
//    Log::info('$temp :'.json_encode($temp->IsSuccessful ));

            return $AddContacts->IsSuccessful;

        } catch (Exeption $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
        }
    }

    public static function getContactList()
    {
        try {

            date_default_timezone_set("Asia/Tehran");

            // your PayamakSefid panel configuration
            $APIKey = Utility::SMSIR_APIKEY;
            $SecretKey = Utility::SMSIR_SECRETKEY;

            $pageId = 0;

            $PayamakSefid_GetContactsByPageId = new PayamakSefid_GetContactsByPageId($APIKey,$SecretKey);
            $GetContactsByPageId = $PayamakSefid_GetContactsByPageId->GetContactsByPageId($pageId);
//            echo "<pre<";
//            var_dump($GetContactsByPageId);

            Log::info('Contacts:'.json_encode($GetContactsByPageId));

        } catch (Exeption $e) {
            $logEvent = new LogEvent((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
        }
    }

    public static function check_national_code($code)
    {
        $code = self::convert($code);

        if(!preg_match('/^[0-9]{10}$/',$code))
            return false;
        for($i=0;$i<10;$i++)
            if(preg_match('/^'.$i.'{10}$/',$code))
                return false;
        for($i=0,$sum=0;$i<9;$i++)
            $sum+=((10-$i)*intval(substr($code, $i,1)));
        $ret=$sum%11;
        $parity=intval(substr($code, 9,1));
        if(($ret<2 && $ret==$parity) || ($ret>=2 && $ret==11-$parity))
            return true;
        return false;
    }

    public static function check_mobile_number($phoneNumber)
    {
        $phoneNumber = self::convert($phoneNumber);

        if(preg_match("/^09[0-9]{9}$/", $phoneNumber) || preg_match("/^00989[0-9]{9}$/", $phoneNumber)) {
            return true;
        }else{
            return false;
        }
    }

    public static function rescaleImage($file, $minWidth, $minHeight, $imageType){

        list($width, $height) = getimagesize($file);
        if($width > $minWidth)
            $newwidth = $minWidth;
        else
            $newwidth = $width;

//        if($height > $minHeight)
        $newheight = $minHeight;
//        else
//            $newheight = $height;

        $thumb = imagecreatetruecolor($newwidth, $newheight);

        switch($imageType){
            case 'image/png':
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $source = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $source = imagecreatefromgif($file);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($file);
                break;
        }

        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        switch($imageType){
            case 'image/png':
                $image = imagepng($thumb, $file, 0);
                break;
            case 'image/gif':
                $image = imagegif($file);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagejpeg($thumb, $file, 75);
                break;
        }

        return $image;
    }

    public static function addHTTP($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public static function GetFirstNChar($myStr, $N)
    {
        //n is best to be 155
//        $result = "";
//        mb_strlen('سلام', 'UTF-8');
//        mb_substr($myStr, 0, 5);
        if(mb_strlen($myStr, 'UTF-8') < $N)
        {
            $result = $myStr;
        }
        else
        {
            $result = mb_substr($myStr, 0, $N, 'UTF-8')." ...";
        }

        return $result;

    }
}

?>