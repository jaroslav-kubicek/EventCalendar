<?php

namespace EventCalendar\Simple;

/**
 * Calendar control with the need to define a translator
 * 
 * Also, you can use Texy! syntax in your events, just install Texy! into your project and use it.
 * 
 */
class EventCalendar extends \EventCalendar\BasicCalendar
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

}
