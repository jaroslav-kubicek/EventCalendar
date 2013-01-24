<?php

/**
 * @copyright  Copyright (c) 2012 Jaroslav Kubíček
 * @license    New BSD License
 * @version    0.1 2012-08-18
 */
use \Nette\Application\UI;

class EventCalendar extends UI\Control {

    const FIRST_SUNDAY = 0, FIRST_MONDAY = 1, CZECH = 'cz', ENGLISH = 'en';

    /**
     * @var int
     * @persistent 
     */
    public $year = NULL;

    /**
     * @var int
     * @persistent
     */
    public $month = NULL;

    /**
     * @var int first day of week
     */
    protected $mode = self::FIRST_SUNDAY;

    /**
     * @var string language
     */
    protected $language = self::ENGLISH;

    /**
     * @var array with options for calendar - you can change defauls by setOptions()
     */
    protected $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'topNavPrev' => '<<',
        'topNavNext' => '>>',
        'bottomNavPrev' => 'Previous month',
        'bottomNavNext' => 'Next month'
    );

    /**
     * @var array
     */
    protected $localNames = NULL;

    /**
     * Model which implements ICalendarEvent
     * @var ICalendarEvent 
     */
    protected $events;

    /**
     * set first day of week
     * @param int $mode 
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     *  set language for calendar
     * @param string $language 
     */
    public function setLanguage($language) {
        if ($language == self::ENGLISH) {
            $this->language = self::ENGLISH;
            $this->options['bottomNavPrev'] = 'Previous month';
            $this->options['bottomNavNext'] = 'Next month';
        } else {
            $this->language = self::CZECH;
            $this->options['bottomNavPrev'] = 'Předchozí měsíc';
            $this->options['bottomNavNext'] = 'Následující měsíc';
        }
    }

    /**
     * Change some other defauls options - check demo or doc
     * @param array $options 
     */
    public function setOptions($options) {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Possibility to set custom names for months and days
     * 
     * 1) monthNames => array of names for each month indexed 1-12
     * 2) wdays => array of names for each day of week indexed 0-6
     * 
     * @param array $localNames
     * @throws InvalidArgumentException 
     */
    public function setLocalNames(array $localNames) {
        if (isset($localNames['monthNames']) && isset($localNames['wdays']) && count($localNames['monthNames']) === 12 && count($localNames['wdays']) === 7) {
            $this->localNames = $localNames;
        } else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * Set model for calendar
     * 
     * @param IEventModel $events model 
     */
    public function setEvents(IEventModel $events) {
        $this->events = $events;
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/EventCalendar.latte');

        // info about current month
        if ($this->month === NULL || $this->year === NULL) {
            $date = new DateTime();
        } else {
            $date = DateTime::createFromFormat('Y-m-d', $this->year . '-' . $this->month . '-01');
        }

        $dateInfo = array();
        $dateInfo['year'] = $date->format('Y'); // current year
        $dateInfo['month'] = $date->format('n'); // current month
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $date->format('n'), $date->format('Y')); // count of days in month
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($date->format('Y'), $date->format('n')); // first day of month
        // which names shold we use?
        if ($this->localNames !== NULL) {
            $names = $this->localNames;
        } else {
            $names = $this->getNames();
        }

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($date->format('Y'), $date->format('n'));
        $this->template->prev = $this->getPrevMonth($date->format('Y'), $date->format('n'));
        $this->template->names = $names;
        $this->template->options = $this->options;
        $this->template->events = $this->events;

        $this->template->render();
    }

    /** change current month */
    public function handleChangeMonth() {
        if ($this->presenter->isAjax()) {
            $this->invalidateControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }

    /**
     * find first day of month and return its position in week
     * 
     * @param int $year
     * @param int $month
     * @return int 
     */
    private function getFirstDayInMonth($year, $month) {
        $day = getdate(mktime(0, 0, 0, $month, 1, $year));
        if ($this->mode == self::FIRST_SUNDAY) {
            return $day['wday'];
        } else {
            if ($day['wday'] == 0) {
                return 6;
            } else {
                return $day['wday'] - 1;
            }
        }
    }

    private function getNextMonth($year, $month) {
        $next = array();
        if ($month == 12) {
            $next['month'] = 1;
            $next['year'] = $year + 1;
        } else {
            $next['month'] = $month + 1;
            $next['year'] = $year;
        }
        return $next;
    }

    private function getPrevMonth($year, $month) {
        $prev = array();
        if ($month == 1) {
            $prev['month'] = 12;
            $prev['year'] = $year - 1;
        } else {
            $prev['month'] = $month - 1;
            $prev['year'] = $year;
        }
        return $prev;
    }

    /**
     * return array with names for months and days in week
     * @return array
     */
    private function getNames() {
        if ($this->language == self::ENGLISH) {
            if ($this->mode == self::FIRST_SUNDAY) {
                $wdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
            } else {
                $wdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
            }
            return array(
                'monthNames' => array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                'wdays' => $wdays
            );
        } else {
            if ($this->mode == self::FIRST_MONDAY) {
                $wdays = array('Po','Út','St','Čt', 'Pá','So','Ne');
            } else {
                $wdays = array('Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So');
            }
            return array(
                'monthNames' => array(1 => 'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'),
                'wdays' => $wdays);
        }
    }

}