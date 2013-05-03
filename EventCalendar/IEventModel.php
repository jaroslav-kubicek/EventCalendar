<?php


namespace EventCalendar;
/**
 * <i>Tip:</i> For max performace you can load events only for current month. If user change month, handle this by event onDateChange.
 */
interface IEventModel
{

    /**
     * exists some event for that day?
     * @return boolean
     */
    public function isForDate($year, $month, $day);

    /**
     * return array with events - output is NOT escaped (you can use html)
     * @return array
     */
    public function getForDate($year, $month, $day);
}
