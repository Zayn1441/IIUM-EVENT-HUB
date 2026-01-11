<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Models\Event;
use App\Http\Controllers\EventController; // Added this use statement for EventController

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_events' => \App\Models\Event::count(),
            'upcoming_events' => \App\Models\Event::where('date', '>=', now())->count(),
            // Add logic for 'my_events' count if needed
        ];
        $upcomingEvents = \App\Models\Event::where('date', '>=', now())
            ->withCount('registrations')
            ->orderBy('date')
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'upcomingEvents'));
    })->name('dashboard');

    Route::get('/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.my-events');
    Route::get('/saved-events', [EventController::class, 'savedEvents'])->name('events.saved-events');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/save', [EventController::class, 'save'])->name('events.save');
    Route::post('/events/{event}/toggle-reminder', [EventController::class, 'toggleReminder'])->name('events.toggle-reminder');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('events', \App\Http\Controllers\EventController::class);

    // Reporting
    Route::post('/events/{event}/report', [App\Http\Controllers\ReportController::class, 'store'])->name('events.report');

    // Admin Routes
    Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::delete('/reports/{report}', [App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');
    });
});

require __DIR__ . '/auth.php';
