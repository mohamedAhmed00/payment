<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Repositories\Interfaces\IPermissionRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class Permission extends Command
{

    protected $signature = 'permission:seed';

    protected $description = 'Seed Permission To database using reflection class';

    private array $availableMethodsNames = [
        'index',
        'store',
        'show',
        'create',
        'update',
        'destroy',
        'paymentSettings',
        'savePaymentSettings',
    ];

    private array $skipFiles = ['.', '..', 'Controller.php', 'Api', 'Auth', 'PaymentController.php'];

    private array | bool $files;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(IPermissionRepository $permissionRepository) : int
    {
        $this->files = array_filter(scandir(app_path('Http'.\DIRECTORY_SEPARATOR.'Controllers')), fn ($file) => ! \in_array($file, $this->skipFiles));
        foreach ($this->files as $file) {
            foreach ($this->getMethods($file) as $availableMethod) {
                $permission = Str::snake(Str::remove('Controller.php', $file)).'-'.$availableMethod->name;
                $permissionRepository->updateOrCreate(['key' => $permission]);
            }
        }

        return 1;
    }

    private function getMethods(string $className) : array
    {
        $reflect = new ReflectionClass('App\Http\Controllers\\'.Str::remove('.php', $className));
        $methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);

        return array_filter($methods, fn ($item) => \in_array($item->name, $this->availableMethodsNames));
    }
}
