<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ExerciseClassController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\MemberQuickController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MemberStatusController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\EventSeriesController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\PostcodeController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;


// Only administrators can register new users
Auth::routes(['register' => false]);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/diary', [DiaryController::class, 'today'])->name('diary');
    Route::get('/diary/{the_date}/day', [DiaryController::class, 'day'])->name('diary.day');
    // optionally limit the weekly view to one venue
    Route::get('/diary/{the_date}/week/{the_venue?}', [DiaryController::class, 'week'])->name('diary.week');

});


/*
 * Operations
 */
Route::middleware('auth:sanctum', 'can:operate')->group( function() {

    // auto-complete helpers
    Route::get('/search/member', [SearchController::class, 'member'])
        ->name('search.member');

    // Event Schedules
    Route::resource('events', EventScheduleController::class)->except([
        'index'
    ]);
    Route::resource('series', EventSeriesController::class)->only([
        'update', 'edit', 'destroy'
    ]);

    // Event Bookings
    Route::resource('events.bookings', BookingController::class)->shallow();


    /*
     * Club visits (Check Ins)
     */
    Route::get('checkins/{the_date?}', [CheckInController::class, 'day'])->name('checkins');

    // Checkin statistics for today
    Route::get('checkins/totals', [CheckInController::class, 'totals'])->name('checkins.totals');



    /*
     * Club Members
     */

    Route::get('/members/admin', [MainController::class, 'memberAdmin'])->name('members.admin');

    Route::resource('members', MemberController::class);

    Route::resource('members.contracts', ContractController::class)->shallow();

    Route::resource('members.notes', NoteController::class)->shallow();

    Route::resource('members.classes', ExerciseClassController::class)->only([
        'index',
    ]);

    Route::resource('members.checkins', CheckInController::class)->only([
        'index', 'create', 'store'
    ])->shallow();


    // Postcode lookup (requires API key subscription from postcodes.io)
    Route::get('postcode/{postcode}', [PostcodeController::class, 'show'])->name('postcode.show');


});



// Administration
Route::middleware('auth:sanctum', 'can:administer')->group( function() {

    Route::get('/admin', [MainController::class, 'clubAdmin'])->name('club.admin');

    Route::resource('venues', VenueController::class);

    Route::resource('plans', MembershipPlanController::class);

    Route::resource('instructors', InstructorController::class);

    Route::resource('users', UserAdminController::class);

});
