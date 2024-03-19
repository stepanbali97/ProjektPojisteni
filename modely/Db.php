<?php

class Db {

    /**
     * @var PDO Databázové spojení
     */
    private static PDO $spojeni;

    /**
     * @var array Výchozí nastavení ovládače
     */
    private static array $nastaveni = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Připojení k db evidence_pojisteni
     * @param string $host Hostitel db
     * @param string $uzivatel Přihlašovací jméno
     * @param string $heslo Přihlašovací heslo
     * @param string $databaze Název dd
     * @return void
     * @throws DbNepripojenaException Pokud není připojena db
     */
    public static function pripoj(string $host, string $uzivatel, string $heslo, string $databaze): void {
        if (!isset(self::$spojeni)) {
            try {
                self::$spojeni = new PDO(
                        "mysql:host=$host;dbname=$databaze",
                        $uzivatel,
                        $heslo,
                        self::$nastaveni
                );
            } catch (PDOException $ex) {
                throw new DbNepripojenaException("Nepřipojil jsem databázi", 97, $ex);
            }
        }
    }

    /**
     * Provede dotaz a získá všechny výsledky 
     * @param string $dotaz SQL dotaz který se připraví - s otazníky
     * @param array $parametry Těmito se nahradí otazníky
     * @return array|bool Pole všech výsledků asociační i číselné indexy 
     */
    public static function dotazVsechny(string $dotaz, array $parametry = array()): array|bool {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll();
    }

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků. Pro insert nebo update.
     * @param string $dotaz SQL dotaz s parametry nahrazující otazníky
     * @param array $parametry Parametry které budou doplněny do připraveného SQL dotazu na místo otazníků
     * @return int Počet ovlivněných řádků
     */
    public static function dotaz(string $dotaz, array $parametry = array()): int {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }

}
