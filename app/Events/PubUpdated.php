<?php

namespace App\Events;

use App\Models\Pub;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PubUpdated
{
    use Dispatchable, SerializesModels;

    public $pub;

    public function __construct(Pub $pub)
    {
        $this->pub = $pub;
    }
}
