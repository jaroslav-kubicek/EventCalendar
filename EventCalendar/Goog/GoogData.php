<?php

namespace EventCalendar\Goog;

use \EventCalendar\IEventModel;

class GoogData extends \Nette\Object implements IEventModel
{
    
    const DATE_FORMAT = 'Y-m-d';

    private $events = array();
    private $name;
    private $description;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addEvent(GoogleEvent $event)
    {
        $this->events[$event->getStart()->format(self::DATE_FORMAT)][$event->id] = $event;
        $this->events[$event->getEnd()->format(self::DATE_FORMAT)][$event->id] = $event;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getForDate($year, $month, $day)
    {
        return $this->events[$this->numbersToDate($year, $month, $day)];
    }

    public function isForDate($year, $month, $day)
    {
        return isset($this->events[$this->numbersToDate($year, $month, $day)]);
    }

    private function numbersToDate($year, $month, $day)
    {
        $dateTime = \DateTime::createFromFormat(self::DATE_FORMAT, $year . '-' . $month . '-' . $day);
        return $dateTime->format(self::DATE_FORMAT);
    }

}
