protected $listen = [
    \App\Events\OrderCreated::class => [
        \App\Listeners\SendOrderNotificationToAdmin::class,
    ],
];