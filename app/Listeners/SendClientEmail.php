<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Events\EmailClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClientEmail implements ShouldQueue
{
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailClient $event): void
    {

        // logger("EVENT: " . $event->client->contacts->toJson() . "");
        // dump($event);
        // The listener receives and uses the dispatched event

        // if the client contract is not active, do nothing 
        if ($event->client->contract_status->isActive()) {
            logger("Active");
        }



        // there is a possibility that there is no primary contact, or more than one primary contact, make sure to cover that scenario when sending email 
        // Scenarios: 0/No primary contact, 1 primary contact, 2 or more primary contacts. 
        // Conditions: ensure that the clients contract is not terminated.

        // 0/No primary contact
        // if there are no primary contacts, choose a random as primary, then cc the rest (cc only if the contact count in greater than 1)

        // 1 primary contact (Ideal situation)
        // if theres one primary contact, send to that contact, cc the rest (cc only if the contact count in greater than 1)
        // if there is only one contact in the collection, make that contact the primary contact regardless of whether the contact is set as primary or not 

        // 2+/more/multiple primary contacts. 
        // if there are more than one primary contacts, send each the message directly, and cc the other contacts that are not primary (cc only if the contact count in greater than 1)

    }
}
