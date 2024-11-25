<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
////    Log::info('hi');
////    $message = trans('messages.msgErrorItemNotExist');
////    $messages = [$message];
////    return view('welcome')->with('messages', $messages);
//    return view('welcome');
//});

//->middleware('first', 'second');
//->middleware(['auth.admin', 'auth.operator','auth.owner']);

Route::get('/','HomeController@home');


Route::get('/termsOfUse','HomeController@termsOfUse');
Route::get('/refreshCaptcha', 'HomeController@refreshCaptcha');
Route::get('/faq', 'HomeController@faq');

//////////////////////////Feedback realated ///////////////////////
Route::get('/feedbacks/create','FeedBackController@create');
Route::get('/feedbacks/index','FeedBackController@index')->middleware('auth.operator');
Route::get('/feedbacks/detail/{feedback_id}/{feedback_guid}','FeedBackController@detail')->middleware('auth.operator');
Route::get('/feedbacks/remove/{feedback_id}/{feedback_guid}','FeedBackController@remove')->middleware('auth.operator');

Route::post('/feedbacks/store' , 'FeedBackController@store');
//////////////////////////end of Feedback related ///////////////////////////////////////////////////////


//////////////////////////Transactions realated ///////////////////////
Route::get('/transactions/order/{garden_id}', 'TransactionController@order');
Route::get('/transactions/tariff', 'TransactionController@tariff');
Route::get('/transactions/verification','TransactionController@verification');
Route::get('/transactions/verificationForFree','TransactionController@verificationForFree');
Route::get('/transactions/indexAdmin','TransactionController@indexAdmin')->middleware('auth.admin');//لیست تمامی پرداخت ها برای مدیر
//Route::get('/transactions/index','TransactionController@index')->middleware('auth.basic');//پرداخت ها
Route::get('/transactions/list/{user_id}','TransactionController@listShow')->middleware('auth.leader');//پرداخت های کاربر لاگین شده
Route::get('/transactions/show/{transaction_id}/{transaction_guid}','TransactionController@show');
Route::get('/transactions/income/{user_id}', 'TransactionController@income')->middleware('auth.owner');
Route::get('/transactions/settle/{transaction_id}/{transaction_guid}','TransactionController@settle')->middleware('auth.operator');

Route::post('/transactions/storeGarden' , 'TransactionController@storeGarden');
Route::post('/transactions/storeTour' , 'TransactionController@storeTour');
Route::post('/transactions/search','TransactionController@search')->middleware('auth.leader');

///////////////////////////end of Transactions related ///////////////////////////////////////////////////////

/// //////////////////////////Users realated ///////////////////////
Route::get('/users/login', 'UserController@login');
Route::get('/users/create', 'UserController@create')->middleware('auth.operator');
Route::get('/users/index', 'UserController@index')->middleware('auth.operator');
Route::get('/users/reset', 'UserController@reset');
Route::get('/users/logout', 'UserController@logout')->middleware('auth.basic');
Route::get('/users/edit/{user_id}/{user_guid}','UserController@edit')->middleware('auth.basic');
Route::get('/users/remove/{user_id}/{user_guid}','UserController@remove')->middleware('auth.admin');
Route::get('/users/recover', 'UserController@recover');
Route::get('/users/getFile/{user_id}/{user_guid}/{tag}', 'UserController@getFile');
Route::get('/users/downloadFile/{user_id}/{user_guid}/{tag}', 'UserController@downloadFile');
Route::get('/users/removeFile/{user_id}/{user_guid}/{tag}', 'UserController@removeFile');
Route::get('/users/dashboard', 'UserController@dashboard')->middleware('auth.basic');
Route::get('/users/detail/{user_id}/{user_guid}','UserController@detail')->middleware('auth.operator');
Route::get('/users/removeUserAvatar/{user_id}/{user_guid}','UserController@removeUserAvatar')->middleware('auth.basic');

Route::post('/users/forget', 'UserController@forget');
Route::post('/users/register' , 'UserController@register')->middleware('auth.operator');
Route::post('/users/authenticate' , 'UserController@authenticate');
Route::post('/users/update','UserController@update')->middleware('auth.basic');
//Route::post('/users/jsonAuthenticate' , 'UserController@jsonAuthenticate');
Route::post('/users/jsonRemove','UserController@jsonRemove')->middleware('auth.admin');

/////////////////////////////end of Users related ///////////////////////////////////////////////////////

//////////////////////////Garden realated ///////////////////////
Route::get('/gardens/index','GardenController@index')->middleware('auth.basic');
Route::get('/gardens/list/{user_id}','GardenController@listShow')->middleware('auth.basic');
Route::get('/gardens/listAll','GardenController@listAll');
Route::get('/gardens/edit/{garden_id}/{garden_guid}','GardenController@edit')->middleware('auth.basic');
Route::get('/gardens/remove/{garden_id}/{garden_guid}','GardenController@remove')->middleware('auth.basic');
Route::get('/gardens/detail/{garden_id}','GardenController@detail');
Route::get('/gardens/code/{garden_id}/{garden_guid}','GardenController@code')->middleware('auth.basic');
Route::get('/gardens/reservs/{garden_id}/{garden_guid}/{offset}/{limit}','GardenController@reservs')->middleware('auth.basic');
Route::get('/gardens/transactions/{garden_id}/{garden_guid}','GardenController@transactions')->middleware('auth.basic');
Route::get('/gardens/getFile/{garden_id}/{garden_guid}/{media_id?}/{tag}', 'GardenController@getFile');
Route::get('/gardens/getFileStream/{garden_id}/{garden_guid}/{media_id?}/{tag}', 'GardenController@getFileStream');
Route::get('/gardens/search','GardenController@search');
Route::get('/gardens/{garden_id}','GardenController@show');

Route::post('/gardens/update' , 'GardenController@update')->middleware('auth.basic');
Route::post('/gardens/saveFile', 'GardenController@saveFile')->middleware('auth.basic');
Route::post('/gardens/removeFile', 'GardenController@removeFile')->middleware('auth.basic');
Route::post('/gardens/searchGarden', 'GardenController@searchGarden');
//////////////////////////end of Garden related ///////////////////////////////////////////////////////

//////////////////////////Reserve realated ///////////////////////
Route::get('/reservs/index/{offset}/{limit}','ReserveController@index')->middleware('auth.basic');
Route::get('/reservs/list/{garden_id}/{offset}/{limit}','ReserveController@listShow')->middleware('auth.basic');
Route::get('/reservs/edit/{reserve_id}/{reserve_guid}','ReserveController@edit')->middleware('auth.basic');
Route::get('/reservs/remove/{reserve_id}/{reserve_guid}','ReserveController@remove')->middleware('auth.basic');
Route::get('/reservs/detail/{reserve_id}/{reserve_guid}','ReserveController@detail');

Route::post('/reservs/update' , 'ReserveController@update')->middleware('auth.basic');
Route::post('/reservs/store' , 'ReserveController@store');
//////////////////////////end of Reserve related ///////////////////////////////////////////////////////


//////////////////////////AboutUs realated ///////////////////////
Route::get('/home/about','HomeController@about');
//////////////////////////end of AboutUs related ///////////////////////////////////////////////////////

//////////////////////////Tour realated ///////////////////////
Route::get('/tours/index','TourController@index')->middleware('auth.owner');
Route::get('/tours/list/{user_id}','TourController@listShow')->middleware('auth.leader');
Route::get('/tours/listAll','TourController@listAll');
Route::get('/tours/edit/{tour_id}/{tour_guid}','TourController@edit')->middleware('auth.owner');
Route::get('/tours/remove/{tour_id}/{tour_guid}','TourController@remove')->middleware('auth.operator');
Route::get('/tours/detail/{tour_id}','TourController@detail');
Route::get('/tours/code/{tour_id}/{tour_guid}','TourController@code')->middleware('auth.leader');
Route::get('/tours/reservs/{tour_id}/{tour_guid}/{offset}/{limit}','TourController@reservs')->middleware('auth.leader');
Route::get('/tours/transactions/{tour_id}/{tour_guid}','TourController@transactions')->middleware('auth.leader');
Route::get('/tours/getFile/{tour_id}/{tour_guid}/{media_id?}/{tag}', 'TourController@getFile');
Route::get('/tours/getFileStream/{tour_id}/{tour_guid}/{media_id?}/{tag}', 'TourController@getFileStream');
Route::get('/tours/search','TourController@search');
Route::get('/tours/create','TourController@create')->middleware('auth.owner');
Route::get('/tours/removeTourLeader/{tour_leader_id}/{tour_leader_guid}/{tour_id}/{tour_guid}','TourController@removeTourLeader')->middleware('auth.owner');
Route::get('/tours/removeTourFeature/{tour_feature_id}/{tour_feature_guid}/{tour_id}/{tour_guid}','TourController@removeTourFeature')->middleware('auth.owner');
Route::get('/tours/removeTourBanner/{tour_id}/{tour_guid}','TourController@removeTourBanner')->middleware('auth.owner');
Route::get('/tours/removeTourPicture/{tour_id}/{tour_guid}/{media_id}/{media_guid}','TourController@removeTourPicture')->middleware('auth.owner');
Route::get('/tours/getFileStream/{tour_id}/{tour_guid}/{media_id?}/{tag}', 'TourController@getFileStream');
Route::get('/tours/removeTourMedia/{tour_id}/{tour_guid}/{media_id}/{media_guid}','TourController@removeTourMedia')->middleware('auth.owner');
Route::get('/tours/{tour_id}','TourController@detail');

Route::post('/tours/update' , 'TourController@update')->middleware('auth.owner');
Route::post('/tours/saveFile', 'TourController@saveFile')->middleware('auth.owner');
Route::post('/tours/removeFile', 'TourController@removeFile')->middleware('auth.owner');
Route::post('/tours/searchTour', 'TourController@searchTour');
Route::post('/tours/store', 'TourController@store')->middleware('auth.owner');
//////////////////////////end of Tour related ///////////////////////////////////////////////////////

//////////////////////////Feature realated ///////////////////////
Route::get('/features/index','FeatureController@index')->middleware('auth.admin');
Route::get('/features/edit/{feature_id}/{feature_guid}','FeatureController@edit')->middleware('auth.admin');
Route::get('/features/remove/{feature_id}/{feature_guid}','FeatureController@remove')->middleware('auth.admin');
Route::get('/features/detail/{feature_id}/{feature_guid}','FeatureController@detail')->middleware('auth.operator');
Route::get('/features/create','FeatureController@create')->middleware('auth.admin');
Route::get('/features/getFile/{feature_id}/{feature_guid}/{tag}', 'FeatureController@getFile');

Route::post('/features/update' , 'FeatureController@update')->middleware('auth.admin');
Route::post('/features/store' , 'FeatureController@store')->middleware('auth.admin');

//////////////////////////end of Feature related ///////////////////////////////////////////////////////

//////////////////////////Report realated ///////////////////////
Route::get('/reports/index','ReportController@index')->middleware('auth.admin');
Route::get('/reports/edit/{report_id}/{report_guid}','ReportController@edit')->middleware('auth.admin');
Route::get('/reports/remove/{report_id}/{report_guid}','ReportController@remove')->middleware('auth.admin');
Route::get('/reports/detail/{report_id}/{report_guid}','ReportController@detail')->middleware('auth.operator');
Route::get('/reports/create','ReportController@create')->middleware('auth.admin');

Route::post('/reports/update' , 'ReportController@update')->middleware('auth.admin');
Route::post('/reports/store' , 'ReportController@store')->middleware('auth.admin');

//////////////////////////end of Report related ///////////////////////////////////////////////////////

//////////////////////////Blogs realated ///////////////////////
Route::get('/blogs/create', 'BlogsController@create')->middleware('auth.operator');
Route::get('/blogs/index','BlogsController@index')->middleware('auth.operator');
Route::get('/blogs/detail/{blog_id}','BlogsController@detail');
Route::get('/blogs/list/{type?}','BlogsController@showList');
Route::get('/blogs/remove/{blog_id}/{blog_guid}','BlogsController@remove')->middleware('auth.operator');
Route::get('/blogs/edit/{blog_id}/{blog_guid}','BlogsController@edit')->middleware('auth.operator');
Route::get('/blogs/getFile/{blog_id}/{blog_guid}/{media_id?}/{tag}', 'BlogsController@getFile');
Route::get('/blogs/downloadFile/{blog_id}/{blog_guid}/{tag}', 'BlogsController@downloadFile');
Route::get('/blogs/removeFile/{blog_id}/{blog_guid}/{tag}', 'BlogsController@removeFile')->middleware('auth.operator');
Route::get('/blogs/getFileStream/{blog_id}/{blog_guid}/{blog_media_id?}/{tag}', 'BlogsController@getFileStream');
Route::get('/blogs/removeBlogBanner/{blog_id}/{blog_guid}','BlogsController@removeBlogBanner')->middleware('auth.operator');
Route::get('/blogs/removeBlogMedia/{blog_id}/{blog_guid}/{media_id}/{media_guid}','BlogsController@removeBlogMedia')->middleware('auth.operator');
Route::get('/blogs/catalog','BlogsController@catalog');
Route::get('/blogs/{blog_id}','BlogsController@detail');


Route::post('/blogs/store' , 'BlogsController@store')->middleware('auth.operator');
Route::post('/blogs/update' , 'BlogsController@update')->middleware('auth.operator');
//////////////////////////end of Blogs related ///////////////////////////////////////////////////////