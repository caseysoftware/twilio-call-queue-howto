
# Call Queuing Comparison - using Twilio

Whether you're running a call center, just a small office, or have to deal with 
all of the incoming calls for that event tonight, putting people on hold (aka 
call queuing) is an important but often difficult process.

The old approach was multiple phone lines and placing a call on hold meant 
tying up a line and busy signals.

With Twilio, one solution was to put the caller into a Conference call on mute. 
While it worked, "managing" the individual conferences, connecting to the person 
who has waited the longest, etc requires an extra layer of business logic.

Since the launch of our new Queue functionality, there's an easier way..


## Summary

Using Queue, "managing" a call queue is as simple as three steps:

1.  Incoming calls are directed to the Queue using the Dial/Queue TwiML combination.
1.  (optional) You can retrieve statistics like Average Wait Time and Current 
Queue Size using the REST Queue resource.
1.  Connecting to the next caller is a a single API request to the Queue.

Some notes of the Queue-based approach:

-  A single Queue allows 100 participants by default but can support up to 1000 
participants.

-  Queues can be created in advance and continue to exist even when there are zero 
participants. This allows you to create it once, store the Queue Sid locally, and 
never need to request the active Queues again.

-  Once you have the Queue Sid (available at creation, as noted above), you can 
retrieve both the total participants on hold and the average waiting time in a 
single request no matter how many people are waiting.

-  Transferring a call out of a Queue requires one step: use the Queue Sid to 
request the next call on hold.

For an example in action, be sure to check out [Jon Gottfried's
screencast](https://www.youtube.com/watch?v=AICLFi2djbs).

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

The full Queue documentation is available here:
http://www.twilio.com/docs/api/twiml/queue and http://www.twilio.com/docs/api/rest/queue

## Call Queuing Comparison

While the implementations are interchangable, behind the scenes using Queue 
reduces the overall code by 1/3, the number of API requests by 50-75%, and gives 
you more flexibility in general.

The old implementation is available in [old/ConferenceManager.php](https://github.com/caseysoftware/twilio-call-queue-howto/blob/master/old/ConferenceManager.php)

The new implementation is available in [queue/QueueManager.php](https://github.com/caseysoftware/twilio-call-queue-howto/blob/master/queue/QueueManager.php)

### By the numbers:

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


If you prefer Python, check out [Rob Spectre's Twilio Queue Example](https://github.com/RobSpectre/Twilio-Queue-Example).

Built for explanation & demo purposes, September 2012.
