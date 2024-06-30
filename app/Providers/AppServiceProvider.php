<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Channel;
use App\Events\PubUpdated;
use App\Listeners\UpdateBlocPubDuration;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PubUpdated::class => [
            UpdateBlocPubDuration::class,
        ],
    ];

    public function boot()
    {
    
       // Partager le canal actuel avec toutes les vues
       View::composer('*', function ($view) {
        $currentChannelId = Session::get('current_channel_id');
        $currentChannel = $currentChannelId ? Channel::find($currentChannelId) : null;
        $view->with('currentChannel', $currentChannel);
    });
}
  
}
