<?php

$configurator = require __DIR__ . '/bootstrap.php';

use EventCalendar\Simple\EventCalendar;
use Nette\Application\Request;
use Tester\DomQuery;
use Tester\Assert;

class EventCalendarTest extends Tester\TestCase
{

    /**
     * @var EventCalendar\Simple\EventCalendar
     */
    private $calendar;

    protected function setUp()
    {
        $this->calendar = new EventCalendar();
        $this->calendar->setTranslator(new TestTranslator());
    }

    public function testStructure()
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        Assert::true($noOfValidDays === 31 && $noOfEmptyDays === 4);
    }

    public function testMaxLenOfWday()
    {
        $this->calendar->setFirstDay(EventCalendar::FIRST_MONDAY);
        $this->calendar->setOptions(array('wdayMaxLen' => 3));
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = $wednesElem[2]->asXML();
        Assert::same('Wed', strip_tags($wednesdayName));
    }

    public function testDisabledTopNav()
    {
        $this->calendar->setOptions(array('showTopNav' => false));
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        Assert::true(!$dom->has('.ec-monthTable a'));
    }

    public function testTexy()
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->setEvents(new TestEvent());
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $events = $dom->find('.ec-event');
        $texyOn = class_exists('\Texy') && strpos($events[0]->asXML(), 'Custom event with <strong>bold</strong>') !== false;
        $texyOff = !class_exists('\Texy') && strpos($events[0]->asXML(), 'Custom event with **bold** text');
        Assert::true($texyOn || $texyOff);
    }

    private function addToPresenterAndReturnHtml($calendar)
    {
        $control = $this->addToPresenter($calendar);
        ob_start();
        $control->render();
        $html = ob_get_clean();
        return $html;
    }

    private function addToPresenter($calendar)
    {
        global $configurator;
        $presenter = new TestPresenter($configurator);
        $presenter->run(new Request('Test', 'GET', array('action' => 'default')));
        $presenter->addComponent($calendar, 'calendar');
        return $presenter['calendar'];
    }

}

class TestPresenter extends \Nette\Application\UI\Presenter
{

    public function renderDefault()
    {
        $this->terminate();
    }

}

class TestTranslator implements \Nette\Localization\ITranslator
{

    public function translate($message, $count = NULL)
    {
        return $message;
    }

}

class TestEvent implements IEventModel
{

    private $events = array();

    public function __construct()
    {
        $this->events['2012-02-02'] = array('Custom event with **bold** text', 'Another event with "link":http://www.seznam.cz');
    }

    public function getForDate($year, $month, $day)
    {
        return $this->events[$this->formatDate($year, $month, $day)];
    }

    public function isForDate($year, $month, $day)
    {
        return array_key_exists($this->formatDate($year, $month, $day), $this->events);
    }

    private function formatDate($year, $month, $day)
    {
        return sprintf('%d-%02d-%02d', $year, $month, $day);
    }

}

$testCase = new EventCalendarTest();
$testCase->run();
