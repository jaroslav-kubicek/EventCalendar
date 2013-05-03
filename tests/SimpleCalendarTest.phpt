<?php

$configurator = require __DIR__ . '/bootstrap.php';

use EventCalendar\Simple\SimpleCalendar;
use Nette\Application\Request;
use Tester\DomQuery;
use Tester\Assert;

class SimpleCalendarTest extends Tester\TestCase
{

    /**
     * @var EventCalendar\Simple\SimpleCalendar
     */
    private $calendar;

    protected function setUp()
    {
        $this->calendar = new SimpleCalendar();
    }

    public function testBasic()
    {
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        Assert::true($dom->has('.ec-monthTable'));
    }

    /**
     * Check if the month name is called January
     */
    public function testEnglishMonthName()
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('caption');
        Assert::contains('January', $elem[0]->asXML());
    }

    public function testWrongLang()
    {
        $this->calendar->setLanguage('esperanto');
        $control = $this->addToPresenter($this->calendar);
        Assert::exception(callback($control, 'render'), 'LogicException');
    }

    /**
     * Check if the first day of week is called "Pondělí"
     */
    public function testCzechCalendar()
    {
        $this->calendar->setFirstDay(SimpleCalendar::FIRST_MONDAY);
        $this->calendar->setLanguage(SimpleCalendar::LANG_CZ);
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('.ec-monthTable th');
        $mondayName = $elem[0]->asXML();
        Assert::same('Pondělí', utf8_decode(strip_tags($mondayName)));
    }

    public function testDisabledBottomNav()
    {
        $this->calendar->setOptions(array('showBottomNav' => false));
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        Assert::true(!$dom->has('.ec-bottomNavigation'));
    }

    /**
     * Check if the german calendar starting on Monday has wday truncated to three chars
     */
    public function testMaxLenOfWday()
    {
        $this->calendar->setLanguage(SimpleCalendar::LANG_DE);
        $this->calendar->setFirstDay(SimpleCalendar::FIRST_MONDAY);
        $this->calendar->setOptions(array('wdayMaxLen' => 3));
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = $wednesElem[2]->asXML();
        Assert::same('Mit', strip_tags($wednesdayName));
    }

    public function testEvent()
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->setEvents(new TestEvent());
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $noOfEvents = count($dom->find('.ec-event'));
        Assert::equal(2, $noOfEvents);
    }

    public function testEventPosition()
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->setEvents(new TestEvent());
        $html = $this->addToPresenterAndReturnHtml($this->calendar);
        $dom = DomQuery::fromHtml($html);
        $dayElems = $dom->find('.ec-eventDay .ec-dayOfEvents');
        $day = (int) strip_tags($dayElems[0]->asXML());
        Assert::true($dom->has('.ec-eventDay .ec-eventBox') && $day === 2);
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

class TestEvent implements IEventModel
{

    private $events = array();

    public function __construct()
    {
        $this->events['2012-02-02'] = array('Custom event', 'Another event');
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

$testCase = new SimpleCalendarTest();
$testCase->run();
