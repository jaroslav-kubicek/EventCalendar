<?php

namespace EventCalendar\Goog;

/**
 * Represent single event from Google Calendar
 */
class GoogleEvent extends \Nette\Object
{
    private $id;
    private $status;
    private $htmlLink;
    private $created;
    private $updated;
    private $summary;
    private $location = null;
    private $description = null;
    private $creator;
    private $start;
    private $end;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHtmlLink()
    {
        return $this->htmlLink;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getLocation()
    {
        return $this->location;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $htmlLink
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setHtmlLink($htmlLink)
    {
        $this->htmlLink = $htmlLink;
        return $this;
    }

    /**
     * @param string|\DateTime $created
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setCreated($created)
    {
        if (!$created instanceof \DateTime) {
            $created = new \DateTime($created);
        }
        $this->created = $created;
        return $this;
    }

    /**
     * @param string|\DateTime $updated
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setUpdated($updated)
    {
        if (!$updated instanceof \DateTime) {
            $updated = new \DateTime($updated);
        }
        $this->updated = $updated;
        return $this;
    }

    /**
     * @param string $summary
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $location
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }
    
    /**
     * @param string $description
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string|\DateTime $start
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setStart($start)
    {
        if (!($start instanceof \DateTime)) {
            $start = new \DateTime($start);
        }
        $this->start = $start;
        return $this;
    }

    /**
     * @param string|\DateTime $end
     * @return \EventCalendar\Goog\GoogleEvent
     */
    public function setEnd($end)
    {
        if (!$end instanceof \DateTime) {
            $end = new \DateTime($end);
        }
        $this->end = $end;
        return $this;
    }

}
