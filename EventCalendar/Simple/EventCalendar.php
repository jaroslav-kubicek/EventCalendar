<?php

namespace EventCalendar\Simple;

class EventCalendar extends \EventCalendar\AbstractCalendar
{

    /**
     * @var \Nette\Localization\ITranslator
     */
    private $translator;

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
