<?php

class CustomCalendar extends EventCalendar {
    
    public function handleChangeMonth() {
        $this->events->refreshEvents($this->year,  $this->month);
        parent::handleChangeMonth();
    }
    
}
