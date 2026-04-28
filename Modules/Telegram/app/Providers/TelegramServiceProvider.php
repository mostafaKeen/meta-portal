<?php

namespace Modules\Telegram\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class TelegramServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Telegram';

    public function boot(): void
    {
        parent::boot();

        \Modules\Company\Models\TelegramBot::observe(\Modules\Telegram\Observers\TelegramBotObserver::class);
    }

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'telegram';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [
        \Modules\Telegram\Console\RegisterWebhookCommand::class,
    ];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();
        
        $this->app->singleton(\Modules\Telegram\Services\TelegramService::class, function ($app) {
            return new \Modules\Telegram\Services\TelegramService();
        });
    }

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
