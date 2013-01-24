<?php

class HomepagePresenter extends Nette\Application\UI\Presenter {

    private $connection;

    public function startup() {
        parent::startup();

        $this->connection = $this->context->getService("database");
    }

    public function actionDefault() {

        $czcal = new EventCalendar($this, "czechCalendar");
        $czcal->setEvents(new EventModel());
        $czcal->setLanguage(EventCalendar::CZECH);
        $czcal->setMode(EventCalendar::FIRST_MONDAY);

        $encal = new EventCalendar($this, "enCalendar");
        $encal->setEvents(new EventModel());

        $spaincal = new EventCalendar($this, "spainCalendar");
        $spaincal->setEvents(new EventModel());
        $spaincal->setMode(EventCalendar::FIRST_MONDAY);
        $spaincal->setLocalNames(array(
            "monthNames" => array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
            "wdays" => array("Lu", "Ma", "Mi", "Ju", "Vi", "Sá", "Do")
        ));
        $spaincal->setOptions(array("showBottomNav" => FALSE));
        
        $customCal = new CustomCalendar($this,'customCal');
        $customCal->setEvents(new ExtendedEventModel($this->connection));
    }

}
