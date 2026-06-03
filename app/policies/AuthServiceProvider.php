<?php

namespace App\Providers;

use App\Models\BillingInfo;
use App\Models\Order;
use App\Models\Rank;
use App\Models\Ticket;
use App\Policies\BillingInfoPolicy;
use App\Policies\OrderPolicy;
use App\Policies\RankPolicy;
use App\Policies\TicketPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class       => OrderPolicy::class,
        BillingInfo::class => BillingInfoPolicy::class,
        Ticket::class      => TicketPolicy::class,
        Rank::class        => RankPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
