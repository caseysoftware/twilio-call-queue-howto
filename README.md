
# Call Queuing with Twilio

A comparison of two approaches to perform call queuing with Twilio. This is often 
used to keep people on hold until a customer service representative is ready to 
take the call.

Built for explanation & demo purposes, September 2012.

The full Queue documentation is available here:
http://www.twilio.com/docs/api/twiml/queue and http://www.twilio.com/docs/api/rest/queue


## Summary

The primary approach for building and using call queues with Twilio used to be via 
muted Conference calls. While it worked, the new Queue functionality makes call 
queuing faster and more reliable with a fraction of the code.

The old implementation is available in conf/ConferenceManager.php

The new implementation is available in queue/QueueManager.php

How it works:

1.  A phone call comes in via your Twilio phone number and is added to an On 
Hold queue waiting for the next request.
1.  As needed, you can retrieve statistics like Average Wait Time and Current 
Queue Size.
1.  When the next customer service representative is ready, you request the next caller.

Some notes of the Queue-based approach:

-  A single Queue allows 100 participants by default but can support up to 1000 
participants.

-  Queues can be created in advance and remain even when there are zero 
participants so you can create it, store the Queue Sid locally, and never need 
to request the active Queues again.

-  Once you have the Queue Sid (available at creation, as noted above), you can 
retrieve both the total participants on hold and the average waiting time in a 
single request no matter how many people are waiting.

-  Transferring a call out of a Queue requires one step: use the Queue Sid to 
request the next call on hold.


### Detailed Usage:

1.  Set the Voice URL for one of your Twilio phone numbers to the url for 
add-to-queue.xml This simply puts the call on hold and waits.
1.  As needed, you can retrieve statistics like Average Wait Time and Current 
Queue Size. In the old implementation, the code must loop over all conferences 
and all participants to calculate both values. In the new implementation, a 
request to the Queue resource simply returns both without further effort.
1.  To transfer the next caller to a customer service representative, you simply 
load start-next-call.php which makes a single API request to 
Queue/QUxxxx/Members/Front resource with the redirect-to-agent.xml file.

### Differences by the numbers:

While the implementations are interchangable, behind the scenes using Queue 
reduces the overall code by 1/3, the number of API requests by 50-75%, and gives 
you more flexibility in general.

Old way (using Conference):
-  creating in advance:                     not possible
-  retrieving stats for 10 participants:    1+ 1 API requests, 10 item loop
-  retrieving stats for 100 participants:   1+ 3 API requests, 100 item loop
-  retrieving stats for 1000 participants:  1+25 API requests, 1000 item loop
-  transferring a caller to an agent:       3 API requests


Queue:
-  creating in advance:                      1 API request
-  retrieving stats for 10 participants:     1 API request, display results
-  retrieving stats for 100 participants:    1 API request, display results
-  retrieving stats for 1000 participants:   1 API request, display results
-  transferring a caller to an agent:        1 API request

The full Queue documentation is available here:
http://www.twilio.com/docs/api/twiml/queue and http://www.twilio.com/docs/api/rest/queue