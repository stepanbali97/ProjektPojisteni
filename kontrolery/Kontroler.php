<?php

/**
 * Výchozí kontroler pro evidenci pojištění
 */
abstract class Kontroler {

    /**
     * @var array Prázdné pole, indexy jsou vidět v šabloně jako běžné proměnné
     */
    protected array $data = array();

    /**
     * @var string Název příslušné šablony bez přípony
     */
    protected string $pohled = "";

    /**
     * @var array|string Hlavička příslušné HTML stránky
     */
    protected array $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');

    /**
     * Funkce ošetřující proměnnou pro výpis do HTML stránky
     * @param mixed|null $x Je proměnná pro ošetření
     * @return mixed ošetřená proměnná
     */
    private function osetri(mixed $x = null): mixed {
        if (!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->osetri($v);
            }
            return $x;
        } else
            return $x;
    }

    /**
     * Vypíše příslušný pohled
     * @return void
     */
    public function vypisPohled(): void {
        if ($this->pohled) {
            extract($this->osetri($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }

    /**
     * Přidá zprávu pro uživatele webu
     * @param string $zprava zpráva pro zobrazení př. Úspěšné přidání pojištěnce
     * @return void
     */
    public function pridejZpravu(string $zprava): void {
        if (isset($_SESSION['zpravy']))
            $_SESSION['zpravy'][] = $zprava;
        else
            $_SESSION['zpravy'] = array($zprava);
    }

    /**
     * Vrací zprávy určené pro uživatele evidence pojištění
     * @return array Pole uložených hlášek pro zobrazování
     */
    public function vratZpravy(): array {
        if (isset($_SESSION['zpravy'])) {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
        } else
            return array();
    }

    /**
     * Přesměruje na příslušné URL
     * @param string $url URL adresa, kam má přesměrovat
     * @return never
     */
    public function presmeruj(string $url): never {
        header("Location: $url");
        header("Connection: close");
        exit;
    }

    /**
     * Hlavní metoda kontroleru
     * @param array $parametry Pole parametrů
     * @return void 
     */
    abstract function zpracuj(array $parametry): void;
}
