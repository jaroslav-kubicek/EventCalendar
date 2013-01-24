<?php

interface IEventModel {
    
    /**
     * exists some event for that day?
     * @return boolean
     */
    public function isForDate($year,$month,$day);
    
    /**
     * return array with events - output is NOT escaped (you can use html)
     * @return array
     */
    public function getForDate($year,$month,$day);
}