<?php

namespace EventCalendar\Simple;

/**
 * Calendar control with the need to define a translator
 * 
 * Also, you can use Texy! syntax in your events, just install Texy! into your project and use it.
 * 
 */
class EventCalendar extends \EventCalendar\AbstractCalendar
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
     * @var array default options for calendar - you can change defauls by setOptions()
     */
    protected $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'wdayMaxLen' => null
    );

    /**
     * @var \Nette\Localization\ITranslator
     */
    private $translator;

    /**
     * set translator for calendar control
     * @param \Nette\Localization\ITranslator $translator
     */
    public function setTranslator(\Nette\Localization\ITranslator $translator)
    {
        $this->translator = $translator;
    }

    public function render()
    {
        $this->template->setTranslator($this->translator);
        $this->template->wdays = $this->getWdays();
        $this->template->monthNames = $this->getMonthNames();
        parent::render();
    }

    protected function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);
        if (class_exists('\Texy')) {
            $texy = new \Texy();
            $template->registerHelper('texy', callback($texy, 'process'));
        } else {
            $template->registerHelper('texy', function($string) {
                        return $string;
                    });
        }

        return $template;
    }

    protected function getTemplateFile()
    {
        return __DIR__ . '/EventCalendar.latte';
    }

    protected function getWdays()
    {
        $wdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        if ($this->firstDay == self::FIRST_MONDAY) {
            array_push($wdays, array_shift($wdays));
        }
        return $this->truncateWdays($wdays);
    }

    protected function getMonthNames()
    {
        $month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        return $month;
    }

}
