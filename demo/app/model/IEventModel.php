<?php

interface IEventModel {
    
    /**
     * zjistí, zda pro daný den existuje událost
     * @return boolean
     */
    public function isForDate($year,$month,$day);
    
    /**
     * vrátí pole událostí pro daný den
     * @return array
     */
    public function getForDate($year,$month,$day);
}
