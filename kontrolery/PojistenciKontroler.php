<?php

/**
 * Kontroler pro výpis všech registrovaných pojištěnců
 */

class PojistenciKontroler extends Kontroler {

    public function zpracuj(array $parametry): void {
        $pojistenci = Pojistenci::vratPojistence(); // vrací pojištěnce
        $this->data['pojistenci'] = $pojistenci;

        $this->hlavicka = array(
            'titulek' => 'Seznam pojištěnců',
            'klicova_slova' => 'pojištěnec',
            'popis' => 'seznam evidovaných pojištěnců',
        );

        $this->pohled = 'pojistenci';
    }
}
    