<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// return Inertia::render('Dashboard', [
//     'users' => User::query()
//         ->when($request->input('search'), function ($query, $search) {
//             $query->where('name', 'LIKE', "%{$search}%");
//         })
//         ->paginate(10)
//         ->withQueryString()
//         ->through(fn ($user) => [
//             'id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//         ]),
//     'filters' => $request->only(['search']),
//     'conversations' => Conversation::where('user1_id', Auth::id())
//         ->with('messages') // Load the messages relationship
//         ->get()
//         ->map(function ($conversation) {
//             return [
//                 'id' => $conversation->id,
//                 'user1_id' => $conversation->user1_id,
//                 'user2_id' => $conversation->user2_id,
//                 'messages' => $conversation->messages->map(function ($message) {
//                     return [
//                         'id' => $message->id,
//                         'content' => $message->content,
//                         'created_at' => $message->created_at,
//                         'uploadImage' => $message->uploadImage, // Assuming you have this field
//                     ];
//                 }),
//             ];
//         }),
// ]);

Route::get('/dashboard', function (Request $request) {
    return Inertia::render('Dashboard', [
        'users' => User::query()
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString()
        ->through(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]),
        'filters' => $request->only(['search']),
        'conversations' => Conversation::where('user1_id', Auth::id())->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/conversation/{conversationId}', [ConversationController::class, 'create']);
    Route::get('/chat/{conversationId}', [MessageController::class, 'chat']);
    Route::post('/chat/create', [MessageController::class, 'create']);
    Route::post('/chat/create/image', [MessageController::class, 'insertImage']);
    Route::delete('/message/delete/{message}', [MessageController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'image'])->name('profile.image');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
