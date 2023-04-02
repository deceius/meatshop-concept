<?php

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
    return redirect('/app/login');
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('branches')->name('branches/')->group(static function() {
            Route::get('/',                                             'BranchesController@index')->name('index');
            Route::get('/create',                                       'BranchesController@create')->name('create');
            Route::post('/',                                            'BranchesController@store')->name('store');
            Route::get('/{branch}/edit',                                'BranchesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'BranchesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{branch}',                                    'BranchesController@update')->name('update');
            Route::delete('/{branch}',                                  'BranchesController@destroy')->name('destroy');
        });
    });
});



/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('types')->name('types/')->group(static function() {
            Route::get('/',                                             'TypesController@index')->name('index');
            Route::get('/create',                                       'TypesController@create')->name('create');
            Route::post('/',                                            'TypesController@store')->name('store');
            Route::get('/{type}/edit',                                  'TypesController@edit')->name('edit');
            Route::post('/{type}',                                      'TypesController@update')->name('update');
            Route::delete('/{type}',                                    'TypesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('brands')->name('brands/')->group(static function() {
            Route::get('/',                                             'BrandsController@index')->name('index');
            Route::get('/create',                                       'BrandsController@create')->name('create');
            Route::post('/',                                            'BrandsController@store')->name('store');
            Route::get('/{brand}/edit',                                 'BrandsController@edit')->name('edit');
            Route::post('/{brand}',                                     'BrandsController@update')->name('update');
            Route::delete('/{brand}',                                   'BrandsController@destroy')->name('destroy');
        });
    });
});





/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('items')->name('items/')->group(static function() {
            Route::get('/',                                             'ItemsController@index')->name('index');
            Route::get('/create',                                       'ItemsController@create')->name('create');
            Route::post('/',                                            'ItemsController@store')->name('store');
            Route::get('/{item}/edit',                                  'ItemsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ItemsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{item}',                                      'ItemsController@update')->name('update');
            Route::delete('/{item}',                                    'ItemsController@destroy')->name('destroy');
            Route::get('/export',                                       'ItemsController@export')->name('export');
        });
    });
});





/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('users')->name('users/')->group(static function() {
            Route::get('/',                                             'UsersController@index')->name('index');
            Route::get('/create',                                       'UsersController@create')->name('create');
            Route::post('/',                                            'UsersController@store')->name('store');
            Route::get('/{user}/edit',                                  'UsersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'UsersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{user}',                                      'UsersController@update')->name('update');
            Route::delete('/{user}',                                    'UsersController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('permissions')->name('permissions/')->group(static function() {
            Route::get('/',                                             'PermissionsController@index')->name('index');
            Route::get('/create',                                       'PermissionsController@create')->name('create');
            Route::post('/',                                            'PermissionsController@store')->name('store');
            Route::get('/{permission}/edit',                            'PermissionsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PermissionsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{permission}',                                'PermissionsController@update')->name('update');
            Route::delete('/{permission}',                              'PermissionsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('roles')->name('roles/')->group(static function() {
            Route::get('/',                                             'RolesController@index')->name('index');
            Route::get('/create',                                       'RolesController@create')->name('create');
            Route::post('/',                                            'RolesController@store')->name('store');
            Route::get('/{role}/edit',                                  'RolesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'RolesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{role}',                                      'RolesController@update')->name('update');
            Route::delete('/{role}',                                    'RolesController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('prices')->name('prices/')->group(static function() {
            Route::get('/',                                             'PricesController@index')->name('index');
            Route::get('/create',                                       'PricesController@create')->name('create');
            Route::post('/',                                            'PricesController@store')->name('store');
            Route::get('/{price}/edit',                                 'PricesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PricesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{price}',                                     'PricesController@update')->name('update');
            Route::delete('/{price}',                                   'PricesController@destroy')->name('destroy');
            Route::get('/export',                                       'PricesController@export')->name('export');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('transaction-details')->name('transaction-details/')->group(static function() {
            Route::get('/',                                             'TransactionDetailsController@index')->name('index');
            Route::get('/list/{transactionHeaderId}',                   'TransactionDetailsController@headerDetails')->name('header-details');
            Route::get('/create',                                       'TransactionDetailsController@create')->name('create');
            Route::post('/',                                            'TransactionDetailsController@store')->name('store');
            Route::get('/get-qr',                                       'TransactionDetailsController@getFromQr')->name('get-from-qr');
            Route::get('/compute-inventory',                            'TransactionDetailsController@computeInventory')->name('compute-inventory');
            Route::get('/{transactionDetail}/edit',                     'TransactionDetailsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'TransactionDetailsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{transactionDetail}',                         'TransactionDetailsController@update')->name('update');
            Route::delete('/{transactionDetail}',                       'TransactionDetailsController@destroy')->name('destroy');
            Route::get('/export',                                       'TransactionDetailsController@export')->name('export');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('transaction-headers')->name('transaction-headers/')->group(static function() {
            Route::get('/{type}',                                             'TransactionHeadersController@index')->name('index');
            Route::get('/create/{type}',                                       'TransactionHeadersController@create')->name('create');
            Route::post('/',                                            'TransactionHeadersController@store')->name('store');
            Route::get('/{transactionHeader}/edit',                     'TransactionHeadersController@edit')->name('edit');
            Route::post('/{transactionHeader}/update-status',           'TransactionHeadersController@updateStatus')->name('update-status');
            Route::post('/{transactionHeader}/initiate-transfer',           'TransactionHeadersController@initiateTransfer')->name('initiate-transfer');
            Route::post('/bulk-destroy',                                'TransactionHeadersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{transactionHeader}',                         'TransactionHeadersController@update')->name('update');
            Route::delete('/{transactionHeader}',                       'TransactionHeadersController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('access-tiers')->name('access-tiers/')->group(static function() {
            Route::get('/',                                             'AccessTiersController@index')->name('index');
            Route::get('/create',                                       'AccessTiersController@create')->name('create');
            Route::post('/',                                            'AccessTiersController@store')->name('store');
            Route::get('/{accessTier}/edit',                            'AccessTiersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'AccessTiersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{accessTier}',                                'AccessTiersController@update')->name('update');
            Route::delete('/{accessTier}',                              'AccessTiersController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('customers')->name('customers/')->group(static function() {
            Route::get('/',                                             'CustomersController@index')->name('index');
            Route::get('/create',                                       'CustomersController@create')->name('create');
            Route::post('/',                                            'CustomersController@store')->name('store');
            Route::get('/{customer}/edit',                              'CustomersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CustomersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{customer}',                                  'CustomersController@update')->name('update');
            Route::delete('/{customer}',                                'CustomersController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('transfers')->name('transfers/')->group(static function() {
            Route::get('/',                                             'TransfersController@index')->name('index');
            Route::get('/create',                                       'TransfersController@create')->name('create');
            Route::post('/',                                            'TransfersController@store')->name('store');
            Route::get('/{transfer}/edit',                              'TransfersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'TransfersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{transfer}',                                  'TransfersController@update')->name('update');
            Route::delete('/{transfer}',                                'TransfersController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('traders')->name('traders/')->group(static function() {
            Route::get('/',                                             'TradersController@index')->name('index');
            Route::get('/create',                                       'TradersController@create')->name('create');
            Route::post('/',                                            'TradersController@store')->name('store');
            Route::get('/{trader}/edit',                                'TradersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'TradersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{trader}',                                    'TradersController@update')->name('update');
            Route::delete('/{trader}',                                  'TradersController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('app')->namespace('App\Http\Controllers\Admin')->name('app/')->group(static function() {
        Route::prefix('expenses')->name('expenses/')->group(static function() {
            Route::get('/',                                             'ExpensesController@index')->name('index');
            Route::get('/create',                                       'ExpensesController@create')->name('create');
            Route::post('/',                                            'ExpensesController@store')->name('store');
            Route::get('/{expense}/edit',                               'ExpensesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ExpensesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{expense}',                                   'ExpensesController@update')->name('update');
            Route::delete('/{expense}',                                 'ExpensesController@destroy')->name('destroy');
        });
    });
});
