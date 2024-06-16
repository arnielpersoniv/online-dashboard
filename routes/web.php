<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\DashBoardTaskController;
use App\Http\Controllers\DashBoardWipController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\OpenInfraTaskController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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
Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/home', [PageController::class, 'showHome']);
Route::get('/home', [PageController::class, 'showDashBoardAgent'])->middleware(['auth', 'web','admin.access']);

//Unauthorized Login
Route::GET('unauthorized', function () {
    return view('errors.401');
})->name('unauthorized');

//SSO
Route::group(['middleware' => ['web', 'guest']], function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('connect', [AuthController::class, 'connect'])->name('connect');
});

Auth::routes(['register' => false]);

//Authentication
Route::group(
    ['middleware' => ['verify.access','auth', 'web'],],
    function () {
        Route::post('/upload', [UserController::class, 'upload'])->name('upload');
        Route::group(
            ['prefix' => 'task'],
            function () {
                //Route::get('/my-task', [PageController::class, 'showMyTasks']);
                Route::get('/agent-task', [PageController::class, 'showAgentTasks']);
                Route::post('/all', [ActivityController::class, 'index']);
                Route::post('/agent-all', [OpenInfraTaskController::class, 'index']);
                Route::post('/store', [ActivityController::class, 'store']);
                Route::post('/agent/store', [OpenInfraTaskController::class, 'store']);
                Route::get('/show/{id}', [ActivityController::class, 'show']);
                Route::get('/show/task/{lod_no}', [OpenInfraTaskController::class, 'show']);
                Route::post('/delete/{id}', [ActivityController::class, 'destroy']);
                Route::post('/agent-delete/{id}', [OpenInfraTaskController::class, 'destroy']);
                Route::post('/action', [ActivityController::class, 'action']);
                Route::post('/agent/running-data', [OpenInfraTaskController::class, 'showRunningData']);
            }
        );
        Route::group(
            ['prefix' => 'show'],
            function () {
                Route::get('/all', [CategoryController::class, 'index']);
                Route::get('/edit/{id}', [TaskController::class, 'edit']);
            }
        );

        Route::group(['middleware' => ['admin.access'],'prefix' => 'admin'],
            function () {
                // Route::get('/dashboard/agent', [PageController::class, 'showDashBoardAgent']);
                Route::get('/dashboard/task', [PageController::class, 'showDashBoardTask']);
                Route::get('/dashboard/wip', [PageController::class, 'showDashBoardWip']);
                Route::get('/all/task', [PageController::class, 'showTaskAll']);
                Route::get('/all/agent-task', [PageController::class, 'showAgentTaskAll']);
                Route::get('/attendance', [PageController::class, 'showAttendance']);
                Route::get('/category', [PageController::class, 'showCategory']);
                Route::get('/task', [PageController::class, 'showTasks']);
                Route::get('/status', [PageController::class, 'showStatus']);
                Route::get('/permission', [PageController::class, 'showPermissions']);
                Route::get('/users', [PageController::class, 'showUsers']);
                Route::get('/logs', [PageController::class, 'showLogs']);

                Route::group(
                    ['prefix' => 'agent/report'],
                    function () {
                        Route::post('daily', [DashBoardController::class, 'loadDaily']);
                        Route::post('weekly', [DashBoardController::class, 'loadWeekly']);
                        Route::post('monthly', [DashBoardController::class, 'loadMonthly']);
                        Route::post('yearly', [DashBoardController::class, 'loadYearly']);
                    }
                );
                Route::group(
                    ['prefix' => 'task/report'],
                    function () {
                        Route::post('daily', [DashBoardTaskController::class, 'loadDaily']);
                        Route::post('weekly', [DashBoardTaskController::class, 'loadWeekly']);
                        Route::post('monthly', [DashBoardTaskController::class, 'loadMonthly']);
                        Route::post('yearly', [DashBoardTaskController::class, 'loadYearly']);
                    }
                );

                Route::group(
                    ['prefix' => 'wip/report'],
                    function () {
                        Route::post('load', [DashBoardWipController::class, 'loadAll']);
                    }
                );

                Route::group(
                    ['prefix' => 'attendance'],
                    function () {
                        Route::get('all/{month}', [AttendanceController::class, 'loadAttendance']);
                    }
                );

                Route::group(
                    ['prefix' => 'category'],
                    function () {
                        Route::post('/store', [CategoryController::class, 'store']);
                        Route::get('/show/{id}', [CategoryController::class, 'show']);
                        Route::post('/delete/{id}', [CategoryController::class, 'destroy']);
                    }
                );
                Route::group(
                    ['prefix' => 'task'],
                    function () {
                        Route::get('/all', [TaskController::class, 'index']);
                        Route::post('/store', [TaskController::class, 'store']);
                        Route::get('/show/{id}', [TaskController::class, 'show']);
                        Route::post('/delete/{id}', [TaskController::class, 'destroy']);
                    }
                );
                Route::group(
                    ['prefix' => 'user'],
                    function () {
                        Route::get('/all', [UserController::class, 'index']);
                        Route::post('/store', [UserController::class, 'store']);
                        Route::get('/show/{id}', [UserController::class, 'show']);
                        Route::post('/delete/{id}', [UserController::class, 'destroy']);
                    }
                );
                Route::group(
                    ['prefix' => 'logs'],
                    function () {
                        Route::get('/all', [LogsController::class, 'index']);
                    }
                );
            }

        );
    }
);
