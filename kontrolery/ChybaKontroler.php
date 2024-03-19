<?php

/**
 * Chybový kontroler
 */
class ChybaKontroler {

    /**
     * Zpracuje chybovou stránku
     * @param int $cisloChyby Číslo http chyby, co chceme vrátit
     * @return void
     */
    public static function vypisChybu(int $cisloChyby): void {
        http_response_code($cisloChyby);
        require("pohledy/chyba" . $cisloChyby . ".phtml");
    }

}
