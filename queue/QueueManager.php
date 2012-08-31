<?php
/*
 * The creds.php file sets values for these variables:
 *      $AccountSid, $AuthToken
 * 
 * There is no business logic or other code within it.
 */
include '../creds.php';
include '../Services/Twilio.php';

/**
 * This is the Queue-based queuing system.
 * 
 * A few caveats here:
 *   -  This assumes that the Queue Sid you give it is actually active. There's
 *      no validation step.
 *   -   assumes you already have a list of Conferences available and want to
 *      retrieve the data one by one.
 */

class QueueManager {
    protected $twilio = null;
    protected $queue  = null;

    public function __construct() {
        global $AccountSid, $AuthToken;
        
        $this->twilio = new Services_Twilio($AccountSid, $AuthToken);
        $this->loadFirstQueue();
    }

    public function loadFirstQueue() {
        //API call
        $queues = $this->twilio->account->queues;
        foreach($queues as $queue) {
            $this->queue = $queues;
            break;
        }
    }

    public function getMembers() {
        return $this->queue->members;
    }

    public function getCurrentWaitTime() {
        return $this->queue->average_wait_time;
    }

    public function getCurrentOnHoldCount() {
        return $this->queue->current_size;
    }

    /**
     * This gets the next (oldest) caller in the specified (or first) active
     *    Conference and redirects them to their next action.
     * 
     * @return null 
     */
    public function connectNextCaller($destinationUrl) {
        $first = $this->queue->members->front();

        //API call
        $first->dequeue('http://example.com/redirect-to-agent.xml', 'POST');
    }
}