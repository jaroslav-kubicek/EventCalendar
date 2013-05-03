<?php

namespace EventCalendar\Simple;

use \EventCalendar\AbstractCalendar;
use \Nette\Utils\Neon;

class SimpleCalendar extends AbstractCalendar
{

    const LANG_EN = 'en', LANG_CZ = 'cz', LANG_SK = 'sk', LANG_DE = 'de';

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

