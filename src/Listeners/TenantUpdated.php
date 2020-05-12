<?php

namespace Yl20181120\Listeners;

use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Tenant;

class TenantUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $tenant
     * @return void
     */
    public function handle(Tenant $tenant)
    {
        Log::error(1);
    }
}
