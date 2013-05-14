<?php

use EventCalendar\Simple\SimpleCalendar;
use EventCalendar\Simple\EventCalendar;

class EventPresenter extends Nette\Application\UI\Presenter
{

    /** @persistent */
    public $calendar = 'basic';
    private $connection;

    public function injectConnection(Nette\Database\Connection $connection)
    {
        $this->connection = $connection;
    }

    public function actionDefault()
    {
        $this->getCalendar($this->calendar);
    }

    private function getCalendar($type)
    {
        switch ($type) {
            case 'basic':
                return new SimpleCalendar($this, 'calendar');
                break;
            case 'czech':
                $czcal = new SimpleCalendar($this, 'calendar');
                $czcal->setLanguage(SimpleCalendar::LANG_CZ);
                $czcal->setFirstDay(SimpleCalendar::FIRST_MONDAY);
                $czcal->setOptions(array(SimpleCalendar::OPT_SHOW_BOTTOM_NAV => false, SimpleCalendar::OPT_WDAY_MAX_LEN => '2'));
                return $czcal;
                break;
            case 'translator':
                $withTranslator = new EventCalendar($this, 'calendar');
                $withTranslator->setTranslator(new Translator($this->connection));
                $withTranslator->setEvents(new EventModel($this->connection));
                return $withTranslator;
                break;
            default:
                throw new InvalidArgumentException('Unknown type of calendar');
                break;
        }
    }

}
