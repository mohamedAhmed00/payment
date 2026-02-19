<?php

namespace App\Domain\Injector;

use App\Domain\Factory\INotificationFactory;
use App\Domain\Factory\IPaymentSupplierFactory;
use App\Domain\Factory\IPaymentTypeFactory;
use App\Domain\Factory\NotificationFactory;
use App\Domain\Factory\PaymentSupplierFactory;
use App\Domain\Factory\PaymentTypeFactory;
use App\Domain\Responder\Classes\ApiHttpResponder;
use App\Domain\Responder\Classes\WebHttpResponder;
use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use App\Domain\Services\Classes\ActivityLogsService;
use App\Domain\Services\Classes\GroupService;
use App\Domain\Services\Classes\NotifyService;
use App\Domain\Services\Classes\OrganizationService;
use App\Domain\Services\Classes\PaymentService;
use App\Domain\Services\Classes\PermissionService;
use App\Domain\Services\Classes\TelegramService;
use App\Domain\Services\Classes\TransactionService;
use App\Domain\Services\Classes\UserPaymentSettingsService;
use App\Domain\Services\Classes\UserService;
use App\Domain\Services\Interfaces\IActivityLogsService;
use App\Domain\Services\Interfaces\IGroupService;
use App\Domain\Services\Interfaces\INotifyService;
use App\Domain\Services\Interfaces\IOrganizationService;
use App\Domain\Services\Interfaces\IPaymentService;
use App\Domain\Services\Interfaces\IPermissionService;
use App\Domain\Services\Interfaces\ITelegramService;
use App\Domain\Services\Interfaces\ITransactionService;
use App\Domain\Services\Interfaces\IUserPaymentSettingsService;
use App\Domain\Services\Interfaces\IUserService;
use App\Providers\AppServiceProvider;

class ServiceInjector extends AppServiceProvider
{
    public function boot()
    {
        $this->app->scoped(IHttpRedirectResponder::class, WebHttpResponder::class);
        $this->app->scoped(IApiHttpResponder::class, ApiHttpResponder::class);
        $this->app->scoped(IGroupService::class, GroupService::class);
        $this->app->scoped(IOrganizationService::class, OrganizationService::class);
        $this->app->scoped(ITelegramService::class, TelegramService::class);
        $this->app->scoped(IPermissionService::class, PermissionService::class);
        $this->app->scoped(IActivityLogsService::class, ActivityLogsService::class);
        $this->app->scoped(IUserService::class, UserService::class);
        $this->app->scoped(IPaymentService::class, PaymentService::class);
        $this->app->scoped(IUserPaymentSettingsService::class, UserPaymentSettingsService::class);
        $this->app->scoped(IPaymentSupplierFactory::class, PaymentSupplierFactory::class);
        $this->app->scoped(IPaymentTypeFactory::class, PaymentTypeFactory::class);
        $this->app->scoped(ITransactionService::class, TransactionService::class);
        $this->app->scoped(INotifyService::class, NotifyService::class);
        $this->app->scoped(INotificationFactory::class, NotificationFactory::class);
    }
}
