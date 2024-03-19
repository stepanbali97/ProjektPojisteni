<?php

class NovyPojistenec {

    /**
     * Zkontroluje vstupy a přidá pojištěnce do db
     * @param string $jmeno Jméno nového pojištěnce
     * @param string $prijmeni Příjmení nového pojištěnce
     * @param int $vek Věk nového pojištěnce
     * @param string $telefon Telefonní číslo nového pojištěnce
     * @return bool Zda byl pojištěnec úspěšně přidán
     */
    public static function pridejPojistence(string $jmeno, string $prijmeni, int $vek, string $telefon): bool {

        if (strlen($jmeno) > 70) {
            error_log('Jméno je příliš dlouhé!');
            return false;
        }
        if (strlen($prijmeni) > 70) {
            error_log('Příjmení je příliš dlouhé!');
            return false;
        }
        if ($vek < 0) {
            error_log('Věk nemůže být záporný!');
            return false;
        }
        if (strlen($telefon) > 16) {
            error_log('Telefonní číslo je příliš dlouhé!');
            return false;
        }

        //vložení do db 
        $dotaz = 'INSERT INTO pojistenci (Jmeno, Prijmeni, Telefon, Vek) VALUES (?, ?, ?, ?)';

        $ovlivnenyRadky = Db::dotaz($dotaz, [$jmeno, $prijmeni, $telefon, $vek]);

        if ($ovlivnenyRadky === 1) {
            return true;
        }
        error_log('Insert řádku se nepovedl!');
        return false;
    }

}
