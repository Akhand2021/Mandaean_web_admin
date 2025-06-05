<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContainerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BaptismController;
use App\Http\Controllers\Api\AdvertismentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\CalenderController;
use App\Http\Controllers\Api\FuneralController;
use App\Http\Controllers\Api\InquiryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('signup', [AuthController::class,'singup']);
Route::post('login', [AuthController::class,'login']);
Route::post('forgot', [AuthController::class,'forgot']);
Route::post('resend-otp', [AuthController::class,'resendOTP']);
Route::post('verify-otp', [AuthController::class,'verifyOTP']);
Route::get('countries/insert', [ContainerController::class,'countriesInsert']);
Route::get('countries', [ContainerController::class,'countries']);

//Content API
Route::get('mandanism-list', [CategoryController::class,'MandanismList']);
Route::get('mandanism-detail/{id}', [CategoryController::class,'MandanismDetail']);

Route::get('latest-news-list', [CategoryController::class,'LatestNewsList']);
Route::get('latest-news-detail/{id}', [CategoryController::class,'LatestNewsDetail']);

Route::get('holy-book-list', [CategoryController::class,'HolyBookList']);

Route::get('rituals-list', [CategoryController::class,'RitualsList']);
Route::get('rituals-detail/{id}', [CategoryController::class,'RitualsDetail']);

Route::get('prayer-list', [CategoryController::class,'PrayerList']);
Route::get('prayer-detail/{id}', [CategoryController::class,'PrayerDetail']);

Route::get('product-list', [ProductController::class,'ProductList']);
Route::get('product-detail/{id}', [ProductController::class,'ProductDetail']);

Route::get('program-list', [CategoryController::class,'ProgramList']);
Route::get('program-detail/{id}', [CategoryController::class,'ProgramDetail']);

Route::get('advertisment-list', [AdvertismentController::class, 'AdvertismentList']);

Route::get('our-history', [CategoryController::class,'OurHistory']);
Route::get('our-history-detail/{id}', [CategoryController::class,'OurHistoryDetail']);    
Route::get('our-culture', [CategoryController::class,'OurCulture']);
Route::get('our-culture-detail/{id}', [CategoryController::class,'OurCultureDetail']);
Route::get('online-articles', [CategoryController::class,'OnlineArticles']);
Route::get('online-article-detail/{id}', [CategoryController::class,'OnlineArticleDetail']);
Route::get('online-videos', [CategoryController::class,'OnlineVideos']);
Route::get('online-video-detail/{id}', [CategoryController::class,'OnlineVideoDetail']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('profile', [UserController::class,'profile']);
    Route::post('update-profile', [UserController::class,'updateProfile']);
    Route::post('change-password', [UserController::class,'changePassword']);
    Route::post('delete-account', [UserController::class,'deleteAccount']);
    
    Route::post('bookmark', [CategoryController::class,'Bookmark']);

    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::get('get-cart', [CartController::class, 'getCart']);
    Route::post('update-item', [CartController::class, 'updateItem']);
    Route::post('delete-item', [CartController::class, 'deleteItem']);
    Route::post('user-address', [CartController::class, 'userAddress']);
    Route::get('order-history', [OrderController::class, 'orderHistory']);
    Route::get('order-detail/{id}', [OrderController::class, 'orderDetail']);
    
    Route::get('baptism-venue', [BaptismController::class, 'BaptismVenue']);
    Route::post('book-baptism', [BaptismController::class, 'BookBaptism']);
    Route::post('place-advertisment', [AdvertismentController::class, 'PlaceAdvertisment']);

    Route::get('notification-list', [NotificationController::class, 'NotificationList']);
    Route::post('read-notification', [NotificationController::class, 'ReadNotification']);
    Route::post('delete-notification', [NotificationController::class, 'DeleteNotification']);
    
    Route::get('donation-event-list', [EventController::class, 'DonationEventList']);
    Route::get('event-detail/{id}', [EventController::class, 'EventDetail']);
    Route::post('donation', [EventController::class, 'Donation']);
    
    Route::post('search', [SearchController::class, 'Search']);

    Route::post('calender-list', [CalenderController::class, 'CalenderList']);
    Route::post('religious-occasions', [CalenderController::class, 'ReligiousOccasions']);
    Route::post('choose-calender', [CalenderController::class, 'ChooseCalender']);
    Route::post('set-event-reminder', [CalenderController::class, 'SetEventReminder']);
    Route::post('delete-all-reminder', [CalenderController::class, 'DeleteAllReminder']);
    Route::get('melvashe', [CalenderController::class, 'Melvashe']);
    Route::post('melvashe-find', [CalenderController::class, 'MelvasheFind']);
    Route::get('funeral', [FuneralController::class, 'Funeral']);
    Route::post('funeral-post', [FuneralController::class, 'FuneralPost']);

    Route::post('inquiry-now', [InquiryController::class, 'InquiryNow']);
});