<?php

namespace EventCalendar\Simple;

use \Nette\Application\UI;
use \Nette\Utils\Neon;
use \Nette\Utils\Strings;

class SimpleCalendar extends UI\Control
{

    const FIRST_SUNDAY = 0, FIRST_MONDAY = 1;
    const LANG_EN = 'en', LANG_CZ = 'cz', LANG_SK = 'sk', LANG_DE = 'de';

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
     * array of callbacks fn(year, month)
     * @var array
     */
    public $onDateChange;
    
    protected $firstDay = self::FIRST_SUNDAY;
    protected $language = self::LANG_EN;

    /**
     * @var array with options for calendar - you can change defauls by setOptions()
     */
    protected $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'topNavPrev' => '<<',
        'topNavNext' => '>>',
        'bottomNavPrev' => 'Previous month',
        'bottomNavNext' => 'Next month',
        'wdayMaxLen' => null
    );

    /**
     * Model which implements ICalendarEvent
     * @var ICalendarEvent 
     */
    protected $events;

    public function setFirstDay($day)
    {
        $this->firstDay = $day;
    }

    public function setLanguage($lang)
    {
        $this->language = $lang;
    }

    /**
     * Changes default options
     * 
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    public function setEvents(\IEventModel $events)
    {
        $this->events = $events;
    }

    /** change current month */
    public function handleChangeMonth()
    {
        $this->onDateChange($this->year, $this->month);
        if ($this->presenter->isAjax()) {
            $this->invalidateControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/SimpleCalendar.latte');

        // info about current month
        if ($this->month === NULL || $this->year === NULL) {
            $date = new \DateTime();
        } else {
            $date = \DateTime::createFromFormat('Y-m-d', $this->year . '-' . $this->month . '-01');
        }

        $dateInfo = array();
        $dateInfo['year'] = $date->format('Y'); // current year
        $dateInfo['month'] = $date->format('n'); // current month
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $date->format('n'), $date->format('Y')); // count of days in month
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($date->format('Y'), $date->format('n')); // first day of month

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($date->format('Y'), $date->format('n'));
        $this->template->prev = $this->getPrevMonth($date->format('Y'), $date->format('n'));
        $this->template->names = $this->getNames($this->language);
        $this->template->options = $this->options;
        $this->template->events = $this->events;

        $this->template->render();
    }

    private function getFirstDayInMonth($year, $month)
    {
        $day = getdate(mktime(0, 0, 0, $month, 1, $year));
        if ($this->firstDay == self::FIRST_SUNDAY) {
            return $day['wday'];
        } else {
            if ($day['wday'] == 0) {
                return 6;
            } else {
                return $day['wday'] - 1;
            }
        }
    }

    private function getNextMonth($year, $month)
    {
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

    private function getPrevMonth($year, $month)
    {
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

    private function getNames($lang)
    {
        $neon = Neon::decode(file_get_contents(__DIR__ . '/simpleCalData.neon'));
        if (array_key_exists($lang, $neon)) {
            $wdays = $this->truncateWdays($neon[$lang]['wdays']);
            if ($this->firstDay) {
                array_push($wdays, array_shift($wdays));
            }
            return array('monthNames' => $neon[$lang]['monthNames'], 'wdays' => $wdays);
        } else {
            throw new \LogicException('Specified language is not supported.');
        }
    }

    private function truncateWdays($wdays)
    {
        if ($this->options['wdayMaxLen'] > 0) {
            foreach ($wdays as &$value) {
                $value = Strings::substring($value, 0, $this->options['wdayMaxLen']);
            }
        }
        return $wdays;
    }

}
