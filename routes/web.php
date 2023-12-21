<?php

use App\Http\Controllers\Ajax\Dashboard as AjaxDashboard;

use App\Http\Controllers\Ajax\FacilitiesManager as AjaxFacilitiesManager;
use App\Http\Controllers\Datatables\FacilitiesManager as DtFacilitiesManager;

use App\Http\Controllers\Ajax\UserManager as AjaxUserManager;
use App\Http\Controllers\Datatables\UserManager as DtUserManager;

use App\Http\Controllers\Ajax\KosManager as AjaxKosManager;
use App\Http\Controllers\Datatables\KosManager as DtKosManager;

use App\Http\Controllers\Ajax\Comment as AjaxComments;
use App\Http\Controllers\Datatables\Comment as DtComment;

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\FrontController;
use App\Http\Controllers\Web\UserManager;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/greeting', [FrontController::class, 'greeting'])->name('front.greeting');
Route::get('/home', [FrontController::class, 'home'])->name('front.home');
Route::get('/detail/{id}', [FrontController::class, 'detail'])->name('front.detail');
Route::get('/route-directions', [FrontController::class, 'routeDirections'])->name('front.route_directions');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    Route::get('/document/verify', function () {
        return view('auth.verify-document');
    })->name('verification.notice');

    // Verified User Middleware
    Route::group(['middleware' => 'verified'], function () {
    });

    Route::group(['middleware' => ['role:superadmin|admin']], function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::group(['prefix' => 'kos-manager'], function () {
            Route::get('/', [AdminController::class, 'kosManagerIndex'])->name('admin.kos_manager.list');
            Route::get('/edit/{id}', [AdminController::class, 'kosManagerEdit'])->name('admin.kos_manager.edit');
        });
    });

    Route::group(['middleware' => ['role:superadmin']], function () {
        Route::get('/users', [UserManager::class, 'index'])->name('admin.user_manager.list');
        Route::get('/facilities', [AdminController::class, 'facilitiesManagerIndex'])->name('admin.facilities_manager.list');
    });
});

Route::group(['prefix' => 'ajax'], function () {
    Route::group(['prefix' => 'boarding-houses'], function () {
        Route::post('/get-data', [AjaxKosManager::class, 'getAll'])->name('ajax.boarding_houses.get');
        Route::get('/get-facilities/{id}', [AjaxKosManager::class, 'getFacilities'])->name('ajax.boarding_houses.get_facilities');
        Route::post('/facilities/update/{id}', [AjaxKosManager::class, 'updateFacilities'])->name('ajax.boarding_houses.update_facilities');
    });

    Route::group(['prefix' => 'comments'], function () {
        Route::post('/store', [AjaxComments::class, 'store'])->name('ajax.comments.store');
        Route::post('/get-summary/{boarding_id}', [AjaxComments::class, 'getSummary'])->name('ajax.comments.get_summary');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => ['role:superadmin']], function () {
            Route::group(['prefix' => 'user-manager'], function () {
                Route::get('/get/{id}', [AjaxUserManager::class, 'get'])->name('ajax.user_manager.get');
                Route::post('/create', [AjaxUserManager::class, 'store'])->name('ajax.user_manager.create');
                Route::post('/update/{id}', [AjaxUserManager::class, 'update'])->name('ajax.user_manager.update');
                Route::post('/delete/{id}', [AjaxUserManager::class, 'destroy'])->name('ajax.user_manager.delete');
            });

            Route::group(['prefix' => 'facilities-manager'], function () {
                Route::get('/get/{id?}', [AjaxFacilitiesManager::class, 'get'])->name('ajax.facilities_manager.get');
                Route::post('/create', [AjaxFacilitiesManager::class, 'store'])->name('ajax.facilities_manager.create');
                Route::post('/update/{id}', [AjaxFacilitiesManager::class, 'update'])->name('ajax.facilities_manager.update');
                Route::post('/delete/{id}', [AjaxFacilitiesManager::class, 'destroy'])->name('ajax.facilities_manager.delete');
            });

            Route::group(['prefix' => 'kos-manager'], function () {
                Route::get('/document/{id}', [AjaxKosManager::class, 'getDocument'])->name('ajax.kos_manager.document');
                Route::post('/document/verify/{id}', [AjaxKosManager::class, 'verifyDocument'])->name('ajax.kos_manager.document.verify');
            });
        });
        
        Route::group(['middleware' => ['role:superadmin|admin']], function () {
            Route::group(['prefix' => 'summary'], function () {
                Route::post('/admin-dashboard', [AjaxDashboard::class, 'getAdminSummaryData'])->name('ajax.summary.admin_dashboard');
            });

            Route::group(['prefix' => 'chart'], function () {
                Route::post('/boarding-house-total', [AjaxDashboard::class, 'getBoardingHouseTotal'])->name('ajax.chart.boarding_house_total');
                Route::post('/room-filled', [AjaxDashboard::class, 'getRoomFilled'])->name('ajax.chart.room_filled');
            });

            Route::group(['prefix' => 'user-manager'], function () {
                Route::post('/document/upload/{id}', [AjaxUserManager::class, 'uploadDocument'])->name('ajax.user_manager.document.upload');
            });

            Route::group(['prefix' => 'kos-manager'], function () {
                Route::get('/get/{id}', [AjaxKosManager::class, 'get'])->name('ajax.kos_manager.get');
                Route::post('/create', [AjaxKosManager::class, 'store'])->name('ajax.kos_manager.create');
                Route::post('/update/{id}', [AjaxKosManager::class, 'update'])->name('ajax.kos_manager.update');
                Route::post('/delete/{id}', [AjaxKosManager::class, 'destroy'])->name('ajax.kos_manager.delete');
                Route::post('/update-status/{id}', [AjaxKosManager::class, 'updateStatus'])->name('ajax.kos_manager.update_status');

                Route::post('/images/upload', [AjaxKosManager::class, 'imageUploader'])->name('ajax.kos_manager.images.upload');
                Route::post('/update-document', [AjaxKosManager::class, 'updateDocument'])->name('ajax.kos_manager.update_document');
                Route::post('/delete-cover-image/{id}', [AjaxKosManager::class, 'deleteCoverImage'])->name('ajax.kos_manager.delete_cover_image');
            });
        });
    });
});

Route::group(['prefix' => 'dt-ajax'], function () {
    Route::get('/comments/{boarding_id}', [DtComment::class, 'getData'])->name('dt-ajax.comments.get');

    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => ['role:superadmin|admin']], function () {
            Route::get('/user-manager/get', [DtUserManager::class, 'getAll'])->name('dt-ajax.user_manager.get');
    
    
            Route::group(['prefix' => 'kos-manager'], function () {
                Route::get('/get', [DtKosManager::class, 'getData'])->name('dt-ajax.kos_manager.get');
                Route::get('/cover-images', [DtKosManager::class, 'getCoverImages'])->name('dt-ajax.kos_manager.cover_images');
            });
    
            Route::get('/facilities-manager/get', [DtFacilitiesManager::class, 'getAll'])->name('dt-ajax.facilities_manager.get');
        });
    });
});

require __DIR__.'/auth.php';
