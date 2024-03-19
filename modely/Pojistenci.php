<?php

class Pojistenci {

    /**
     * Vrací všechny registrované pojištěnce
     * @return array Výpis pojištěnců z databáze
     */
    public static function vratPojistence(): array {

        $dotaz = "SELECT * FROM pojistenci ";
        $pojistenci = Db::dotazVsechny($dotaz);
        return $pojistenci;
    }

}
