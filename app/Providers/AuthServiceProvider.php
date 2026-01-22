<?php

namespace App\Providers;

use App\Auth\ApiSessionUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Csoapproval::class => \App\Policies\CsoapprovalPolicy::class,
        \App\Models\EquipmentTypes::class => \App\Policies\EquipmentTypesPolicy::class,
        \App\Models\Establishment::class => \App\Policies\EstablishmentPolicy::class,
        \App\Models\IctCategories::class => \App\Policies\IctCategoriesPolicy::class,
        \App\Models\IssueItem::class => \App\Policies\IssueItemPolicy::class,
        \App\Models\Items::class => \App\Policies\ItemsPolicy::class,
        \App\Models\SerialNumbers::class => \App\Policies\ScanPolicy::class,
        \App\Models\ReciveItems::class => \App\Policies\RecieveItemPolicy::class,
        \App\Models\IssuePlaces::class => \App\Policies\IssuePlacesPolicy::class,
        \App\Models\Permissions::class => \App\Policies\PermissionPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Votes::class => \App\Policies\VotesPolicy::class,
        \App\Models\UserModule::class => \App\Policies\UserModulePolicy::class,
        \App\Models\Supplier::class => \App\Policies\SupplierPolicy::class,
        \App\Models\Titlenames::class => \App\Policies\TitlenamesPolicy::class,
        \App\Models\Store::class => \App\Policies\StorePolicy::class,
        \App\Models\StroopIssued::class => \App\Policies\StroopIssuedPolicy::class,
        \App\Models\SignalUnit::class => \App\Policies\SignalUnitPolicy::class,
        \App\Models\Serial::class => \App\Policies\SerialPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\StroopRequest::class => \App\Policies\StroopRequestPolicy::class,
        \App\Models\RecPlaces::class => \App\Policies\RecPlacesPolicy::class,
        \App\Models\Quantities::class => \App\Policies\QuantitiesPolicy::class,
        \App\Models\PurchaseOrderNos::class => \App\Policies\PurchaseOrderNosPolicy::class,
        \App\Models\Module::class => \App\Policies\ModulePolicy::class,
        \App\Models\ModelName::class => \App\Policies\ModelNamePolicy::class,
        \App\Models\Measures::class => \App\Policies\MeasuresPolicy::class,
        \App\Models\IssuingType::class => \App\Policies\IssuingTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Auth::provider('api_session', function ($app, array $config) {
        //     return new ApiSessionUserProvider($app['session.store']);
        // });

        // Gate::before(function ($user, $ability) {
        //     if (! $user) {
        //         return false;
        //     }

        //     // if ($user instanceof \App\Auth\ApiUser) {
        //     //     return true;
        //     // }

        //     return null;
        // });

        /**
         * Optional custom Gates
         * උදා: user එක Admin role එකේද බලන්න
         */

    }
}
