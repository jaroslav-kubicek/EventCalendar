<?php

class ExtendedEventModel implements IEventModel {
    
    private $connection;
    private $monthEvents;
    
    public function __construct(\Nette\Database\Connection $connection,  \DateTime $dateTime=null) {
        $this->connection = $connection;
        $dateTime = ($dateTime === null) ? new \Nette\DateTime() : $dateTime;
        $this->findEvents($dateTime);
    }

    public function getForDate($year, $month, $day) {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day);
        return $this->monthEvents[$dateTime->format('Y-m-d')];
    }

    public function isForDate($year, $month, $day) {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day); 
        return array_key_exists($dateTime->format('Y-m-d'), $this->monthEvents);
    }
    
    public function refreshEvents($year,$month) {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-01');
        $this->findEvents($dateTime);        
    }
    
    private function findEvents(\DateTime $dateTime) {
        $this->monthEvents = array();
        $result = $this->connection->table('events')->select('when, desc')->where('when BETWEEN ? AND ?',$dateTime->format('Y-m-').'01',$dateTime->format('Y-m-').'31');
        foreach ($result as $event) {
            $this->monthEvents[$event->when->format('Y-m-d')][] = $event->desc;
        }
    }
    
}
