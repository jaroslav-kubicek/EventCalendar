<?php

namespace EventCalendar;

abstract class BasicCalendar extends AbstractCalendar
{

    /**
     * @var \Nette\Localization\ITranslator
     */
    protected $translator;

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
