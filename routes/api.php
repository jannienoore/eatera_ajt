<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodApiController;
use App\Http\Controllers\AdminFoodController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FoodJournalController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\CommunityInteractionController;
use App\Http\Controllers\CommunityReportController;
use App\Http\Controllers\AdminCommunityController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ChatbotController;

use App\Http\Middleware\IsAdmin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('foods')
    ->middleware(['auth:sanctum', IsAdmin::class])
    ->group(function () {
    // USDA
    Route::get('/search', [FoodApiController::class, 'search']);
    Route::post('/import', [FoodApiController::class, 'import']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/foods', [FoodController::class, 'index']);
    Route::get('/foods/{id}', [FoodController::class, 'show']);
});

Route::prefix('admin')
    ->middleware(['auth:sanctum', IsAdmin::class])
    ->group(function () {

    Route::get('/foods', [AdminFoodController::class, 'index']);
    Route::get('/foods/{id}', [AdminFoodController::class, 'show']);
    Route::post('/foods', [AdminFoodController::class, 'store']);
    Route::put('/foods/{id}', [AdminFoodController::class, 'update']);
    Route::get('/stats', [AdminFoodController::class, 'stats']);

    Route::get('/community/posts/pending', [AdminCommunityController::class, 'pendingPosts']);
    Route::post('/community/posts/{id}/approve', [AdminCommunityController::class, 'approvePost']);
    Route::post('/community/posts/{id}/reject', [AdminCommunityController::class, 'rejectPost']);
    Route::delete('/community/posts/{id}', [AdminCommunityController::class, 'deletePost']);
    Route::get('/community/reports/posts', [AdminCommunityController::class, 'postReports']);
    Route::delete('/community/reports/posts/{reportId}', [AdminCommunityController::class, 'deleteReport']);
    Route::get('/community/reports/comments', [AdminCommunityController::class, 'commentReports']);
    Route::delete('/community/reports/comments/{reportId}', [AdminCommunityController::class, 'deleteCommentReport']);
    Route::delete('/community/posts/{postId}/comment/{commentId}', [AdminCommunityController::class, 'deleteComment']);

    Route::get('/articles', [AdminArticleController::class, 'index']);
    Route::post('/articles', [AdminArticleController::class, 'store']);
    Route::get('/articles/{id}', [AdminArticleController::class, 'show']);
    Route::put('/articles/{id}', [AdminArticleController::class, 'update']);
    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/journal', [FoodJournalController::class, 'index']);
    Route::get('/journal/all', [FoodJournalController::class, 'all']);
    Route::post('/journal', [FoodJournalController::class, 'store']);
    Route::put('/journal/{id}', [FoodJournalController::class, 'update']);
    
    Route::get('/dashboard/daily', [DashboardController::class, 'daily']);
    Route::get('/dashboard/weekly', [DashboardController::class, 'weekly']);
    
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::post('/profile', [UserProfileController::class, 'store']);

    Route::get('/community/articles', [ArticleController::class, 'index']);
    Route::get('/community/articles/{id}', [ArticleController::class, 'show']);

    Route::post('/chat', [ChatbotController::class, 'chat']);
});

Route::middleware('auth:sanctum')->get(
    '/recommendations/today',
    [RecommendationController::class, 'today']
);

Route::middleware('auth:sanctum')->prefix('community')->group(function () {
    Route::get('/posts', [CommunityPostController::class, 'index']); // lihat post approved
    Route::post('/posts', [CommunityPostController::class, 'store']); // buat post
    Route::put('/posts/{id}', [CommunityPostController::class, 'update']); // edit post
    Route::delete('/posts/{id}', [CommunityPostController::class, 'destroy']); // hapus post

    Route::post('/posts/{id}/like', [CommunityInteractionController::class, 'toggleLike']);
    Route::post('/posts/{id}/comment', [CommunityInteractionController::class, 'comment']);
    Route::put('/posts/{postId}/comment/{commentId}', [CommunityInteractionController::class, 'updateComment']);
    Route::delete('/posts/{postId}/comment/{commentId}', [CommunityInteractionController::class, 'deleteComment']);
    Route::post('/posts/{postId}/comment/{commentId}/report', [CommunityReportController::class, 'reportComment']);

    Route::post('/posts/{id}/report', [CommunityReportController::class, 'reportPost']);
});


Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
Route::middleware('auth:sanctum')->post('/logout',[LoginController::class,'logout']);
