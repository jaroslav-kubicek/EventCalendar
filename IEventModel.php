<?php

interface IEventModel {
    
    /**
     * zjistí, zda pro daný den existuje událost
     */
    public function isForDate($year,$month,$day);
    
    /**
     * vrátí pole událostí pro daný den
     */
    public function getForDate($year,$month,$day);
}
