<?php

class EventModel extends \Nette\Object implements IEventModel {
    
public function getForDate($year, $month, $day) {
        return array("Nějaká událost","Další tentýž den");
    }
public function isForDate($year, $month, $day) {
        if(($year==2012 || $year == 2013) && ($month==8 || $month == 10 || $month==2) && ($day ==20 || $day == 29)) {
            return TRUE;
        }        
    }
}