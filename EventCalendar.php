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
     * @var int jakým dnem začíná týden
     */
    private $mode = self::FIRST_SUNDAY;

    /**
     * @var string jazyk kalendáře
     */
    private $language = self::ENGLISH;

    /**
     * @var array pole různých dalších nastavení kalendáře
     */
    private $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'topNavPrev' => '<<',
        'topNavNext' => '>>',
        'bottomNavPrev' => 'Previous month',
        'bottomNavNext' => 'Next month'
    );

    /**
     * vlastní názvy dní a měsíců
     * @var array
     */
    private $localNames = NULL;

    /**
     * Model akcí pro kalendář implementující rozhraní ICalendarEvent
     * @var ICalendarEvent 
     */
    private $events;

    /**
     * Nastaví kterým dnem má začínat týden
     * @param int $mode 
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     *  Nastaví jazyk kalendáře
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
     * Přenastaví některá další nastavení kalendáře (popisky, navigace apod.)
     * @param array $options 
     */
    public function setOptions($options) {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Možnost nastavení vlastních názvů dní a měsíců
     * Jako parametr je nutné předat asociativní pole, které bude obsahovat:
     * 1) monthNames => pole názvů měsíců číslované 1-12
     * 2) wdays => pole názvů dnů v týdnu číslované 0-6
     * 
     * @param array $localNames
     * @throws InvalidArgumentException 
     */
    public function setLocalNames(array $localNames) {
        if (isset($localNames['monthNames']) && isset($localNames['wdays']) && count($localNames['monthNames']) == 12 && count($localNames['wdays']) == 7) {
            $this->localNames = $localNames;
        } else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * Nastaví model událostí pro kalendář
     * 
     * @param IEventModel $events model implementující IEventModel
     * @throws InvalidArgumentException 
     */
    public function setEvents($events) {
        if ($events instanceof IEventModel) {
            $this->events = $events;
        } else {
            throw new InvalidArgumentException;
        }
    }

    public function render() {
        $this->template->setFile(__DIR__ . '/EventCalendar.latte');

        // zjištění informací o aktuálním měsíci & roku
        if ($this->month == NULL || $this->year == NULL) {
            $date = getdate();
        } else {
            $dateTime = DateTime::createFromFormat('Y-m', $this->year . '-' . $this->month);
            $date = getdate($dateTime->getTimestamp());
        }

        $dateInfo = array();
        $dateInfo['year'] = $date['year']; // aktuální rok
        $dateInfo['month'] = $date['mon']; // aktuální měsíc
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $date['mon'], $date['year']); // počet dnů v měsíci
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($date['year'], $date['mon']); // první den měsíce

        // jaké názvy měsíců použijeme?
        if ($this->localNames != NULL) {
            $names = $this->localNames;
        } else {
            $names = $this->getNames();
        }

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($date['year'], $date['mon']);
        $this->template->prev = $this->getPrevMonth($date['year'], $date['mon']);
        $this->template->names = $names;
        $this->template->options = $this->options;
        $this->template->events = $this->events;

        $this->template->render();
    }

    /** změní aktuálně zobrazený měsíc */
    public function handleChangeMonth() {
        if ($this->presenter->isAjax()) {
            $this->invalidateControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }
    
    /**
     * Zjistí první den měsíce a vrátí jeho pořadí v týdnu dle nastaveného módu
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
     * vrátí pole s názvy měsíců a dnů v týdnu
     * 
     * @return array
     */
    private function getNames() {
        if ($this->language == self::ENGLISH) {
            if ($this->mode == self::FIRST_SUNDAY) {
                $wdays = array(
                    0 => 'Sun',
                    1 => 'Mon',
                    2 => 'Tue',
                    3 => 'Wed',
                    4 => 'Thu',
                    5 => 'Fri',
                    6 => 'Sat'
                );
            } else {
                $wdays = array(
                    0 => 'Mon',
                    1 => 'Tue',
                    2 => 'Wed',
                    3 => 'Thu',
                    4 => 'Fri',
                    5 => 'Sat',
                    6 => 'Sun',
                );
            }
            return array(
                'monthNames' => array(
                    1 => 'January',
                    2 => 'February',
                    3 => 'March',
                    4 => 'April',
                    5 => 'May',
                    6 => 'June',
                    7 => 'July',
                    8 => 'August',
                    9 => 'September',
                    10 => 'October',
                    11 => 'November',
                    12 => 'December'),
                'wdays' => $wdays);
        } else {
            if ($this->mode == self::FIRST_MONDAY) {
                $wdays = array(
                    0 => 'Po',
                    1 => 'Út',
                    2 => 'St',
                    3 => 'Čt',
                    4 => 'Pá',
                    5 => 'So',
                    6 => 'Ne'
                );
            } else {
                $wdays = array(
                    0 => 'Ne',
                    1 => 'Po',
                    2 => 'Út',
                    3 => 'St',
                    4 => 'Čt',
                    5 => 'Pá',
                    6 => 'So'
                );
            }
            return array(
                'monthNames' => array(
                    1 => 'Leden',
                    2 => 'Únor',
                    3 => 'Březen',
                    4 => 'Duben',
                    5 => 'Květen',
                    6 => 'Červen',
                    7 => 'Červenec',
                    8 => 'Srpen',
                    9 => 'Září',
                    10 => 'Říjen',
                    11 => 'Listopad',
                    12 => 'Prosinec'),
                'wdays' => $wdays);
        }
    }

}