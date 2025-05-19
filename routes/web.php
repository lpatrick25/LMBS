<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionPenaltyController;
use App\Http\Controllers\TransactionStatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
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

Route::get('/', function () {
    return view('index');
})->middleware('guest')->name('loginPage');

Route::get('/register', function () {
    return view(view: 'register');
})->middleware('guest')->name('registrationPage');

Route::prefix('/admin')->middleware('loggedIn')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AppController::class, 'viewDashboard'])->name('viewAdminDashboard');
    // Lab Staff = Create, Read, Update, Delete
    Route::get('/labStaffs', [AppController::class, 'viewLabStaff'])->name('viewLabStaff');
    // Employee = Create, Read, Update, Delete
    Route::get('/employees', [AppController::class, 'viewEmployee'])->name('viewEmployee');
    // Borrower = Create, Read, Update, Delete
    Route::get('/borrowers', [AppController::class, 'viewBorrower'])->name('viewBorrower');
    // Category = Create, Read, Update, Delete
    Route::get('/categories', [AppController::class, 'viewCategory'])->name('viewCategory');
    // Items = Create, Read, Update, Delete
    Route::get('/items', [AppController::class, 'viewItem'])->name('viewItem');
    // Transaction = Create, Read, Update, Delete
    Route::get('/transactions', [AppController::class, 'viewTransactions'])->name('viewTransactions');


    // Borrow = Create, Read, Update, Delete
    Route::get('/borrowed', [AppController::class, 'viewBorrow'])->name('viewBorrow');
    // Reserve = Create, Read, Update, Delete
    Route::get('/reserved', [AppController::class, 'viewReserve'])->name('viewReserve');
    // Inventory = Create, Read
    Route::get('/inventories', [AppController::class, 'viewInventory'])->name('viewInventory');
    // Penalties = Display
    Route::get('/transactionPenalties', [AppController::class, 'viewPenalties'])->name('viewPenalties');
    // Inventory = Generate and Display
    Route::get('/reports', [AppController::class, 'viewReport'])->name('viewReport');
    // User = Update Password and Display
    Route::get('/users', [AppController::class, 'viewUser'])->name('viewUser');
});

Route::prefix('/labStaff')->middleware('loggedIn')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AppController::class, 'viewDashboard'])->name('viewLabStaffDashboard');
    // Category = Create, Read, Update, Delete
    Route::get('/categories', [AppController::class, 'viewCategory'])->name('viewLabStaffCategory');
    // Items = Create, Read, Update, Delete
    Route::get('/items', [AppController::class, 'viewItem'])->name('viewLabStaffItem');
    // Transactions = Create, Read, Update, Delete
    Route::get('/transactions', [AppController::class, 'viewTransactions'])->name('viewLabStaffTransactions');
    // Inventory = Create, Read
    Route::get('/inventories', [AppController::class, 'viewInventory'])->name('viewLabStaffInventory');
    // Penalties = Display
    Route::get('/transactionPenalties', [AppController::class, 'viewPenalties'])->name('viewLabStaffPenalties');
    // Inventory = Generate and Display
    Route::get('/reports', [AppController::class, 'viewReport'])->name('viewLabStaffReport');
});

Route::prefix('/borrower')->middleware('loggedIn')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AppController::class, 'viewDashboard'])->name('viewBorrowerDashboard');
    // Items
    Route::get('/itemList', [AppController::class, 'viewItems'])->name('viewBorrowerItems');
    // Transactions = Create, Read, Update, Delete
    Route::get('/transactions', [AppController::class, 'viewTransactions'])->name('viewBorrowerTransactions');
});

Route::prefix('/employee')->middleware('loggedIn')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AppController::class, 'viewDashboard'])->name('viewEmployeeDashboard');
    // Items
    Route::get('/itemList', [AppController::class, 'viewItems'])->name('viewEmployeeItems');
    // Transactions = Create, Read, Update, Delete
    Route::get('/transactions', [AppController::class, 'viewTransactions'])->name('viewEmployeeTransactions');
});


// Resource Controller

// User Profile Resource
Route::resource('/users', UserController::class);
Route::resource('/userProfiles', UserProfileController::class);
// Categories Resource
Route::resource('/categories', CategoryController::class);
// Item Resource
Route::resource('/items', ItemController::class);
Route::post('/items/updateImage/{itemID}', [ItemController::class, 'updateImage'])->name('updateImage');

// Transaction Resource
Route::resource('/transactions', TransactionController::class);
Route::resource('/transactionStatuses', TransactionStatusController::class);
Route::get('/transactions/getTransactions/{transactionNo}', [TransactionController::class, 'getTransactions'])->name('getTransactions');
Route::put('/transactions/releaseTransaction/{transactionNo}', [TransactionController::class, 'releaseTransaction'])->name('releaseTransaction');
Route::get('/transactions/getTransaction/{transactionNo}', [TransactionController::class, 'getTransaction'])->name('getTransaction');
Route::put('/transactions/returnTransaction/{transactionNo}', [TransactionController::class, 'returnTransaction'])->name('returnTransaction');
Route::get('/transactions/getItemRemarks/{transactionNo}', [TransactionController::class, 'getItemRemarks'])->name('getItemRemarks');

// Inventories
Route::resource('/inventories', InventoryController::class);
// Penalties
Route::resource('/transactionPenalties', TransactionPenaltyController::class);

// Laboratory Items
Route::get('/laboratoryItems/{laboratory}', [AppController::class, 'laboratoryItems'])->name('laboratoryItems');

// Dashboard
Route::get('/chart', [AppController::class, 'getstatusData'])->name('chart');
Route::get('/pendingItems', [AppController::class, 'getPendingItems'])->name('pending');

// User Transaction
Route::get('/getTransactionByUser', [AppController::class, 'getTransactionByUser'])->name('getTransactionByUser');

// Validation
Route::post('/check-email', [UserController::class, 'checkEmail'])->name('checkEmail');
Route::post('/check-contact', [UserController::class, 'checkContact'])->name('checkContact');
Route::post('/check-username', [UserController::class, 'checkUsername'])->name('checkUsername');

// Login, Registration, Change Password, Profile
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/signup', [UserController::class, 'signup'])->name('signup');
Route::get('/profiles', [UserController::class, 'myProfile'])->name('myProfile');
Route::put('/changePassword', [UserController::class, 'changePassword'])->name('changePassword');
