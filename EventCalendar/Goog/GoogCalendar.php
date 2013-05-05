<?php

namespace EventCalendar\Goog;

use EventCalendar\BasicCalendar;

/**
 * Integration with events from Google Calendar
 * 
 * Experimental
 * 
 * @todo Allow mix of events from Google Calendar with custom events
 */
class GoogCalendar extends BasicCalendar
{
    /**
     * Show top navigation for changing months, default <b>true</b>
     */

    const OPT_SHOW_TOP_NAV = 'showTopNav';
    /**
     * Show bottom navigation for changing months, default <b>true</b>
     */
    const OPT_SHOW_BOTTOM_NAV = 'showBottomNav';
    /**
     * maximum length of wday names, by default, full name is used (<b>null</b>)
     */
    const OPT_WDAY_MAX_LEN = 'wdayMaxLen';
    /**
     * show link to event in Google Calendar, default is <b>true</b>
     */
    const OPT_SHOW_EVENT_LINK = 'showEventLink';
    /**
     * show location if it is set, default is <b>true</b>
     */
    const OPT_SHOW_EVENT_LOCATION = 'showEventLocation';
    /**
     * show description of event if it is set, default is <b>true</b>
     */
    const OPT_SHOW_EVENT_DESCRIPTION = 'showEventDescription';
    /**
     * show start date of event, default is <b>true</b>
     */
    const OPT_SHOW_EVENT_START = 'showEventStart';
    /**
     * show end date of event, default is <b>true</b>
     */
    const OPT_SHOW_EVENT_END = 'showEventEnd';
    /**
     * Datetime format for start and end date, default is <b>F j, Y, g:i a</b>
     */
    const OPT_EVENT_DATEFORMAT = 'eventDateformat';

    /**
     * @var array default options for calendar - you can change defauls by setOptions()
     */
    protected $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'wdayMaxLen' => null,
        'showEventLink' => true,
        'showEventLocation' => true,
        'showEventDescription' => true,
        'showEventStart' => true,
        'showEventEnd' => true,
        'eventDateformat' => 'F j, Y, g:i a'
    );

    /**
     * @var GoogAdapter 
     */
    private $googAdapter;

    protected function getTemplateFile()
    {
        return __DIR__ . '/GoogCalendar.latte';
    }

    /**
     * @param \EventCalendar\Goog\GoogAdapter $googAdapter
     */
    public function setGoogAdapter(GoogAdapter $googAdapter)
    {
        $this->googAdapter = $googAdapter;
    }
    
    /**
     * Do not set events directly, use GoogAdapter. Mix of events from Google with customs events is not implemented yet.
     * @param \EventCalendar\IEventModel $events
     * @throws \LogicException
     */
    public function setEvents(\EventCalendar\IEventModel $events)
    {
        throw new \LogicException('Do not set events directly, use GoogAdapter.');
    }

    /**
     * @throws \EventCalendar\Goog\GoogApiException
     */
    public function render()
    {
        $this->prepareDate();
        $this->googAdapter->setBoundary($this->year, $this->month);
        try {
            $this->events = $this->googAdapter->loadEvents();
        } catch (GoogApiException $e) {
            throw $e;
        }
        parent::render();
    }

}
