EventCalendar
============

- add-on component for Nette framework - http://nette.org/
- enable displaying various events in calendar
- provide methods for localisation & customization
- you can also use html and Texy! in your event texts
- http://addons.nette.org/cs/eventcalendar

Installing
============

Install component to your project via Composer:

    "require": {
        ...
        "jaroslav-kubicek/event-calendar": "0.2.0"
    }

Quick start
============

Add to your code (in presenter/control):

    public function createComponentCalendar() {
        $cal = new EventCalendar\Simple\SimpleCalendar();
        return $cal;
    }

and in template:

    {control calendar}

API Docs
============

http://www.nimral.cz/calendar/docs/index.html
