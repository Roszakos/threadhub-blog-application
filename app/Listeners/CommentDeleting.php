<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CommentDeleting as CommentDeletingEvent;

class CommentDeleting
{

    /**
     * Handle the event.
     */
    public function handle(CommentDeletingEvent $event): void
    {
        //$event->comment->children()->delete();
        foreach($event->comment->children as $comment) {
            $comment->delete();
        }
    }
}
