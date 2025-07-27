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
use App\Http\Controllers\Api\StaticContentController;
use App\Http\Controllers\Api\MelvasheController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\AudioController;
use App\Http\Controllers\Api\ReligiousOccasionController;
use App\Http\Controllers\TermsAndConditionsController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AddressController;

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

Route::group(['middleware' => ['apiKey']], function () {


    Route::post('signup', [AuthController::class, 'singup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot', [AuthController::class, 'forgot']);
    Route::post('resend-otp', [AuthController::class, 'resendOTP']);
    Route::post('verify-otp', [AuthController::class, 'verifyOTP']);
    Route::get('countries/insert', [ContainerController::class, 'countriesInsert']);
    Route::get('countries', [ContainerController::class, 'countries']);

    //Content API
    Route::get('mandanism-list', [CategoryController::class, 'MandanismList']);
    Route::get('mandanism-detail/{id}', [CategoryController::class, 'MandanismDetail']);

    Route::get('latest-news-list', [CategoryController::class, 'LatestNewsList']);
    Route::get('latest-news-detail/{id}', [CategoryController::class, 'LatestNewsDetail']);

    Route::get('holy-book-list', [CategoryController::class, 'HolyBookList']);

    Route::get('rituals-list', [CategoryController::class, 'RitualsList']);
    Route::get('rituals-detail/{id}', [CategoryController::class, 'RitualsDetail']);

    Route::get('prayer-list', [CategoryController::class, 'PrayerList']);
    Route::get('prayer-detail/{id}', [CategoryController::class, 'PrayerDetail']);



    Route::get('program-list', [CategoryController::class, 'ProgramList']);
    Route::get('program-detail/{id}', [CategoryController::class, 'ProgramDetail']);

    Route::get('advertisment-list', [AdvertismentController::class, 'AdvertismentList']);

    Route::get('our-history', [CategoryController::class, 'OurHistory']);
    Route::get('our-history-detail/{id}', [CategoryController::class, 'OurHistoryDetail']);
    Route::get('our-culture', [CategoryController::class, 'OurCulture']);
    Route::get('our-culture-detail/{id}', [CategoryController::class, 'OurCultureDetail']);
    Route::get('online-articles', [CategoryController::class, 'OnlineArticles']);
    Route::get('online-article-detail/{id}', [CategoryController::class, 'OnlineArticleDetail']);
    Route::get('online-videos', [CategoryController::class, 'OnlineVideos']);
    Route::get('online-video-detail/{id}', [CategoryController::class, 'OnlineVideoDetail']);

    Route::apiResource('terms-and-conditions', TermsAndConditionsController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/fcm-token', [UserController::class, 'saveToken']);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('profile', [UserController::class, 'profile']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::delete('delete-account', [UserController::class, 'deleteAccount']);

        Route::post('bookmark', [CategoryController::class, 'Bookmark']);
        Route::get('product-list', [ProductController::class, 'ProductList']);
        Route::get('product-detail/{id}', [ProductController::class, 'ProductDetail']);

        Route::post('add-to-cart', [CartController::class, 'addToCart']);
        Route::get('get-cart', [CartController::class, 'getCart']);
        Route::post('update-item', [CartController::class, 'updateItem']);
        Route::post('delete-item', [CartController::class, 'deleteItem']);
        
        Route::get('order-history', [OrderController::class, 'orderHistory']);
        Route::get('order-detail/{id}', [OrderController::class, 'orderDetail']);

        // Add create-order route
        Route::post('create-order', [OrderController::class, 'createOrder']);

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
        Route::get('static-content', [StaticContentController::class, 'index']);
        Route::get('static-content/{id}', [StaticContentController::class, 'show']);

        // Chat routes
        Route::get('chat/active-users', [\App\Http\Controllers\Api\ChatController::class, 'activeUsers']);
        Route::get('chat/last-seen/{userId}', [\App\Http\Controllers\Api\ChatController::class, 'lastSeen']);
        Route::post('chat/send', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage']);
        Route::post('chat/mark-delivered', [\App\Http\Controllers\Api\ChatController::class, 'markDelivered']);
        Route::get('chat/history/{userId}', [\App\Http\Controllers\Api\ChatController::class, 'chatHistory']);
        Route::post('chat/delete', [\App\Http\Controllers\Api\ChatController::class, 'deleteMessages']);
        Route::post('chat/block', [\App\Http\Controllers\Api\ChatController::class, 'blockUser']);
        Route::post('chat/unblock', [\App\Http\Controllers\Api\ChatController::class, 'unblockUser']);
        Route::get('chat/all-users', [\App\Http\Controllers\Api\ChatController::class, 'allUsers']);
        Route::get('chat/chatted-users', [\App\Http\Controllers\Api\ChatController::class, 'chattedUsers']);
        Route::post('chat/mark-read', [\App\Http\Controllers\Api\ChatController::class, 'markRead']);

        // Melvashe CRUD API
        Route::apiResource('melvashe', MelvasheController::class);

        Route::apiResource('posts', PostController::class);
        Route::post('posts/{post}/like', [PostController::class, 'like']);
        Route::post('posts/{post}/comment', [PostController::class, 'comment']);
        Route::post('posts/{post}/share', [PostController::class, 'share']);
        Route::apiResource('friends', FriendController::class)->except(['show']);
        Route::get('my-posts', [PostController::class, 'myPosts']);
        Route::get('audios', [AudioController::class, 'index']);
        // API route for getting all audio files
        Route::get('prayers', [App\Http\Controllers\Api\PrayerController::class, 'index']);
        Route::get('religious-occasions', [ReligiousOccasionController::class, 'index']);
        Route::get('religious-occasions/{id}', [ReligiousOccasionController::class, 'show']);
        Route::get('/stories', [StoryController::class, 'index']);
        Route::post('/stories', [StoryController::class, 'store']);
        Route::get('/stories/{story}', [StoryController::class, 'show']);
        Route::delete('/stories/{story}', [StoryController::class, 'destroy']);
        Route::get('/stories/{story}/viewers', [StoryController::class, 'viewers']);
        Route::get('/my-stories', [StoryController::class, 'myStories']);


        // FAQ Routes
        Route::apiResource('faqs', FaqController::class);

        // Address Routes
        Route::apiResource('addresses', AddressController::class);
    });
});
