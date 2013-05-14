<?php

class EventModel extends Nette\Object implements EventCalendar\IEventModel
{

    private $connection;
    private $events;

    public function __construct(Nette\Database\Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getForDate($year, $month, $day)
    {
        $this->find($year, $month); // may be omitted
        $date = sprintf('%d-%02d-%02d', $year, $month, $day);
        return $this->events[$date];
    }

    public function isForDate($year, $month, $day)
    {
        $this->find($year, $month);
        $date = sprintf('%d-%02d-%02d', $year, $month, $day);
        return (isset($this->events[$date])) ? true : false;
    }

    private function find($year, $month)
    {
        if (!isset($this->events)) {
            $lastday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $start = sprintf('%d-%02d-00', $year, $month);
            $end = sprintf('%d-%02d-%02d', $year, $month, $lastday);
            $query = $this->connection->table('events')->where('when BETWEEN ? AND ?', $start, $end);
            $this->events = array();
            foreach ($query as $row) {
                $this->events[$row->when->format('Y-m-d')][] = $row->desc;
            }
        }
    }

}
