<?php

namespace EventCalendar\Goog;

use \Nette\Caching\Cache;

/**
 * Class for accessing data in Google Calendar by its API
 * 
 * Currently, only public calendars are allowed 
 * 
 * See API doc for Google Calendar events: https://developers.google.com/google-apps/calendar/v3/reference/events/list
 * 
 */
class GoogAdapter extends \Nette\Object
{

    private $calendarId;
    private $apiKey;

    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var \DateTime
     */
    private $cacheExpiration;
    private $searchTerm;
    private $showDeleted = false;
    private $expandRecurringEvents = false;
    private $timeMax;
    private $timeMin;
    private $year;
    private $month;

    /**
     * @var \DateTimeZone
     */
    private $timeZone;

    public function __construct($calendarId, $apiKey)
    {
        $this->calendarId = $calendarId;
        $this->apiKey = $apiKey;
    }

    /**
     * @param \Nette\Caching\Cache $cache
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * set expiration for cache
     * @param \DateTime $dateTime
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function setCacheExpiration(\DateTime $dateTime)
    {
        $this->cacheExpiration = $dateTime;
        return $this;
    }

    /**
     * filter events by search term
     * @param string $searchTerm
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }

    /**
     * @param boolean $boolean
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function showDeleted($boolean)
    {
        $this->showDeleted = $boolean;
        return $this;
    }

    /**
     * Return recurring events one by one
     * @param boolean $boolean
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function expandRecurringEvents($boolean)
    {
        $this->expandRecurringEvents = $boolean;
        return $this;
    }

    /**
     * Set timezone in which results are returned
     * @param \DateTimeZone $timeZone
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function setTimeZone(\DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * Time constraint for events from Google Calendar. Used by GoogleCalendar for getting events only for current month.
     * @param int $year
     * @param int $month
     * @return \EventCalendar\Goog\GoogAdapter
     */
    public function setBoundary($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
        $noOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if (isset($this->timeZone)) {
            $min = $year . '-' . $month . '-01T00:00:00Z';
            $max = $year . '-' . $month . '-' . $noOfDays . 'T23:59:59Z';
        } else {
            $min = $year . '-' . $month . '-01T00:00:00Z';
            $max = $year . '-' . $month . '-' . $noOfDays . 'T23:59:59Z';
        }
        $this->timeMax = $max;
        $this->timeMin = $min;
        return $this;
    }

    /**
     * Load events from Google Calendar via API or from cache
     * @return \EventCalendar\Goog\GoogData
     * @throws GoogApiException
     */
    public function loadEvents()
    {
        // return from cache
        if (isset($this->cache)) {
            $alreadySaved = $this->cache->load($this->year . '-' . $this->month);
            if ($alreadySaved) {
                return $alreadySaved;
            }
        }
        // load via api
        $json = json_decode($this->loadByApi());
        if (isset($json->error)) {
            throw new GoogApiException($json->error->message, $json->error->code);
        }
        $googData = new GoogData();
        $googData->setName($json->summary);
        $googData->setDescription($json->description);
        if (isset($json->items)) {
            foreach ($json->items as $item) {
                $event = new GoogleEvent($item->id);
                $event->setStatus($item->status)
                        ->setSummary($item->summary)
                        ->setCreated($item->created)
                        ->setUpdated($item->updated)
                        ->setHtmlLink($item->htmlLink)
                        ->setStart($item->start->dateTime)
                        ->setEnd($item->end->dateTime);
                if (isset($item->location)) {
                    $event->setLocation($item->location);
                } 
                if (isset($item->description)) {
                    $event->setDescription($item->description);
                }
                
                $googData->addEvent($event);
            }
        }
        // save loaded data to cache
        if (isset($this->cache)) {
            if (isset($this->cacheExpiration)) {
                $dependencies = array(Cache::EXPIRATION => $this->cacheExpiration->getTimestamp());
            } else {
                $dependencies = null;
            }
            $this->cache->save($this->year . '-' . $this->month, $googData, $dependencies);
        }
        return $googData;
    }

    private function loadByApi()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->prepareUrl());
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function prepareUrl()
    {
        $url = 'https://www.googleapis.com/calendar/v3/calendars/';
        $url .= urlencode($this->calendarId);
        $url .= '/events?key=' . $this->apiKey;
        $url .= '&timeMin=' . urlencode($this->timeMin);
        $url .= '&timeMax=' . urlencode($this->timeMax);
        if (!empty($this->expandRecurringEvents)) {
            $url .= '&singleEvents=true';
        }
        if (isset($this->timeZone)) {
            $url .= '&timeZone=' . urlencode($this->timeZone->getName());
        }
        if (isset($this->searchTerm)) {
            $url .= '&q=' . urlencode($this->searchTerm);
        }
        if (!empty($this->showDeleted)) {
            $url .= '&showDeleted=true';
        }
        return $url;
    }

}
