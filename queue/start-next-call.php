<?php

/* 
 * We would use this to log the end of the call or a hangup, update the CRM record,
 *    and then get the next member of the Queue for the customer service agent (as below).
 * 
 * We don't need to update the Queue directly as any hangups are automatically
 *    removed on the Twilio side.
 */

include 'QueueManager.php';

$queue = new QueueManager();
$queue->connectNextCaller('http://example.com/redirect-to-agent.xml');

?>
<Response />