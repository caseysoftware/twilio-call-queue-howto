twilio-queuing
==============

These are two different approaches to do call queuing with Twilio. For example, 
this is often used to keep people on hold until a customer service representative 
is ready to take the call.

The first approach is using a Conference call. As calls come in, you add them to 
a common Conference call muted, probably with hold music. The second approach 
uses our new Queue functionality.


First, here are the differences by the numbers:

Conference
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



Some notes on the Conference-based approach:

-  A Conference is limited to 40 participants. Therefore, as inbound call volume 
grows, you need more Conferences. For example, you need 3 Conferences for 100 
calls and 25 Conferences for 1000 calls.

-  Conferences can't be created in advance and are ended when there are no more 
participants, therefore you need to retrieve a list of active Conference calls 
and iterate over all of them calculating wait times to determine the overall 
average and total participants on hold.

-  Transferring a call out of a Conference requires three steps: first, you must 
retrieve a list of Conferences, get the Participants for the first Conference, 
then use the Call Sid for the first Participant to redirect to the customer 
service agent.

The full Conference documentation is available here:
http://www.twilio.com/docs/api/twiml/conference and http://www.twilio.com/docs/api/rest/conference



Some notes of the Queue-based approach:

-  None of the above limitations apply.

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



The full Queue documentation is available here:
http://www.twilio.com/docs/api/twiml/queue and http://www.twilio.com/docs/api/rest/queue