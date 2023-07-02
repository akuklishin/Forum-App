<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//index
Route::get('/', [PostsController::class, 'index'])->name('index');

//ssearch
Route::get('/search', [PostsController::class, 'search'])->name('search');

//index sorted by /hot
Route::get('/top', [PostsController::class, 'top'])->name('posts.top');
//index sorted by /new
Route::get('/new', [PostsController::class, 'new'])->name('posts.new');

//display specific user's posts
Route::get('/user/{userName}', [PostsController::class, 'userPosts'])->name('userPosts');
//sort by likes on user's page
Route::get('/user/{userName}/top', [PostsController::class, 'sortTopByUser'])->name('user.top');
//sort by creation time on user's page
Route::get('/user/{userName}/new', [PostsController::class, 'sortNewByUser'])->name('user.new');

//display specific subforum posts
Route::get('/sub/{subforumName}', [PostsController::class, 'subforum'])->name('subforum');
//sort sub posts by likes
Route::get('/sub/{subforumName}/top', [PostsController::class, 'subSortByTop'])->name('subforum.top');
//sort sub posts by creation time
Route::get('/sub/{subforumName}/new', [PostsController::class, 'subSortByNew'])->name('subforum.new');


//create post page
Route::get('/create', [PostsController::class, 'create'])->name('create');
Route::post('/create', [PostsController::class, 'store'])->name('post.store');

//Vote for post
Route::post('/votepost/{postId}', [PostsController::class, 'votepost'])->name('post.votepost');

//Add comment
Route::post('/commentadd/{id}', [CommentsController::class, 'create'])->name('comment.create');
//vote for comment
Route::post('/votecomment/{commentId}', [CommentsController::class, 'votecomment'])->name('comment.vote');
//delete comment
Route::delete('/commentdelete/{id}', [CommentsController::class, 'delete'])->name('comment.delete');

//display specific post by id
Route::get('/post/{id}', [PostsController::class, 'show'])->name('post.show');

//registration
Route::get('/registration', [RegistrationController::class, 'create']);
Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.store');

//delete post
Route::delete('/delete/{id}', [PostsController::class, 'destroy'])->name('post.delete');


//coming with breeze part------------------------------------------------------------------

// Route::get('/', function () {
//     //Debugbar::info('Info');
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
