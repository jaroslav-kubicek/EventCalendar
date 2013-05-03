<?php

namespace EventCalendar\Simple;

use \EventCalendar\AbstractCalendar;
use \Nette\Utils\Neon;

/**
 * Simple alternative for calendar control if you don't want to use translator.
 * 
 * Specify your language by calling setLanguage($lang)
 */
class SimpleCalendar extends AbstractCalendar
{

    const LANG_EN = 'en', LANG_CZ = 'cz', LANG_SK = 'sk', LANG_DE = 'de';
    
    /**
     * Show top navigation for changing months, default <b>true</b>
     */
    const OPT_SHOW_TOP_NAV = 'showTopNav';
    /**
     * Show bottom navigation for changing months, default <b>true</b>
     */
    const OPT_SHOW_BOTTOM_NAV = 'showBottomNav';
    /**
     * text for top link to previous month, default <b><</b>
     */
    const OPT_TOP_NAV_PREV = 'topNavPrev';
    /**
     * text for top link to next month, default <b>></b>
     */
    const OPT_TOP_NAV_NEXT = 'topNavNext';
    /**
     * text for bottom link to previous month, default <b>Previous month</b>
     */
    const OPT_BOTTOM_NAV_PREV = 'bottomNavPrev';
    /**
     * text for bottom link to next month, default <b>Next month</b>
     */
    const OPT_BOTTOM_NAV_NEXT = 'bottomNavNext';
    /**
     * maximum length of wday names, by default, full name is used (<b>null</b>)
     */
    const OPT_WDAY_MAX_LEN = 'wdayMaxLen';

    protected $language = self::LANG_EN;

    /**
     * @var array default options for calendar - you can change defauls by setOptions()
     */
    protected $options = array(
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'topNavPrev' => '<',
        'topNavNext' => '>',
        'bottomNavPrev' => 'Previous month',
        'bottomNavNext' => 'Next month',
        'wdayMaxLen' => null
    );

    public function setLanguage($lang)
    {
        $this->language = $lang;
    }

    public function render()
    {
        $this->template->names = $this->getNames($this->language);
        parent::render();
    }

    protected function getNames($lang)
    {
        $neon = Neon::decode(file_get_contents(__DIR__ . '/simpleCalData.neon'));
        if (array_key_exists($lang, $neon)) {
            $wdays = $this->truncateWdays($neon[$lang]['wdays']);
            if ($this->firstDay === self::FIRST_MONDAY) {
                array_push($wdays, array_shift($wdays));
            }
            return array('monthNames' => $neon[$lang]['monthNames'], 'wdays' => $wdays);
        } else {
            throw new \LogicException('Specified language is not supported.');
        }
    }

    protected function getTemplateFile()
    {
        return __DIR__ . '/SimpleCalendar.latte';
    }

}

