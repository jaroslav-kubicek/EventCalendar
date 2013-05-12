<?php

use EventCalendar\Simple\SimpleCalendar;

class EventPresenter extends Nette\Application\UI\Presenter
{

    public function renderDefault()
    {
        $cal = new SimpleCalendar($this, 'basic');
        $cal->setLanguage(SimpleCalendar::LANG_CZ); // české názvy měsíců a dnů
        $cal->setFirstDay(SimpleCalendar::FIRST_MONDAY); // týden začne pondělkem
    }

}