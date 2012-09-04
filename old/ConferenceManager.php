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
 * This is the Conference-based queuing system.
 * 
 * A few caveats here:
 *   -  This does not do any polling to see who is still active in the conference
 *      at any given moment. Instead we're abusing the API and making a request out
 *      each and every time we want to do *anything*.
 *   -  This assumes that the Conference Sid you give it is actually active. There's
 *      no validation step.
 *   -  This assumes you already have a list of Conferences available and want to
 *      retrieve the data one by one.
 */
class ConferenceManager
{
    protected $twilio = null;
    protected $conferences = array();
    protected $participants = array();

    public function __construct()
    {
        global $AccountSid, $AuthToken;
        
        $this->twilio = new Services_Twilio($AccountSid, $AuthToken);
    }

    public function getCurrentWaitTime($conferenceSid)
    {
        //API call
        $conference = $this->twilio->account->conferences->get($conferenceSid);
        $participants = $conference->participants;

        $totalTime = 0;
        foreach($participants as $participant) {
            $joinTime = strtotime($participant->date_created);
            $onHoldTime = time() - $joinTime;
            $totalTime += $onHoldTime;
        }

        return $totalTime/count($participants);
    }

    public function getCurrentOnHoldCount($conferenceSid)
    {
        //API call
        $conference = $this->twilio->account->conferences->get($conferenceSid);
        return count($conference->participants);
    }

    /**
     * This gets the next (oldest) caller in the specified (or first) active
     *    Conference and redirects them to their next action.
     * 
     * @return null 
     */
    public function connectNextCaller($destinationUrl, $conferenceSid)
    {
        //API call
        $conference = $this->twilio->account->conferences->get($conferenceSid);

        if (is_array($conference->participants)) {
            $participant = $conference->participants[0];
            
            //API call
            $firstCall = $this->twilio->account->calls->get($participant->call_sid);
            //API call
            $firstCall->update(array('Url' => $destinationUrl, 'Method' => 'POST'));
        } else {
            //No one else is on hold 
        }
    }
}