<?php

use Yl20181120\DcatExtendTenancy\Http\Controllers;

Route::resource('tenants', Controllers\TenantController::class);
Route::get('tenants/{tenant}/create', Controllers\TenantController::class . '@getAddDomain')->name('tenants.add-domain');
