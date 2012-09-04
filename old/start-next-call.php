<?php

/* 
 * We would use this to log the end of the call or a hangup, update the CRM record,
 *    and then get the next member of the Queue for the customer service agent (as below).
 */

include 'ConferenceManager.php';

$queue = new ConferenceManager();
$queue->connectNextCaller('http://example.com/redirect-to-agent.xml', 'onHoldConference');
?>
<Response />