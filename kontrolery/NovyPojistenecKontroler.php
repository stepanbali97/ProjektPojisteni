<?php

/**
 * Kontroler pro evidenci nového pojištěnce
 */
class NovyPojistenecKontroler extends Kontroler {

    private bool $formularOk = true; // jestli je formulář spravně vyplněný

    /**
     * Vytvoření struktury pro další použití v ostatních metodách
     * @return void
     */
    private function nastavVychoziHodnotyFormulare(): void {
        $this->data['formular'] = [];

        $this->data['formular']['jmeno'] = [];
        $this->data['formular']['jmeno']['chybovaZprava'] = '';
        $this->data['formular']['jmeno']['ok'] = true;
        $this->data['formular']['jmeno']['poslanaHodnota'] = '';

        $this->data['formular']['prijmeni'] = [];
        $this->data['formular']['prijmeni']['chybovaZprava'] = '';
        $this->data['formular']['prijmeni']['ok'] = true;
        $this->data['formular']['prijmeni']['poslanaHodnota'] = '';

        $this->data['formular']['vek'] = [];
        $this->data['formular']['vek']['chybovaZprava'] = '';
        $this->data['formular']['vek']['ok'] = true;
        $this->data['formular']['vek']['poslanaHodnota'] = '';

        $this->data['formular']['telefon'] = [];
        $this->data['formular']['telefon']['chybovaZprava'] = '';
        $this->data['formular']['telefon']['ok'] = true;
        $this->data['formular']['telefon']['poslanaHodnota'] = '';
    }

    /**
     * Ověření správně zadaného pole formuláře pro jméno
     * Pracuje s instančními proměnnými $this->formularOK a $this->data['formular']['jmeno']
     * @return string Ošetřené jméno
     */
    private function validaceJmena(): string {
        if (!array_key_exists('jmeno', $_POST)) {
            $_POST['jmeno'] = '';
        }
        $jmenoPost = (string) $_POST ['jmeno']; // přetypuji na string
        $jmeno = trim($jmenoPost);
        $this->data['formular']['jmeno']['poslanaHodnota'] = $jmeno;
        if (strlen($jmeno) === 0) { // je něco vyplněné?
            $this->data['formular']['jmeno']['chybovaZprava'] = 'Jméno nemůže být prázdné.';
            $this->data['formular']['jmeno']['ok'] = false;
            $this->formularOk = false;
        } elseif (strlen($jmeno) > 60) { // víc než 60 znaků se nemusí vejít do db
            $this->data['formular']['jmeno']['chybovaZprava'] = 'Jméno je příliš dlouhé.';
            $this->data['formular']['jmeno']['ok'] = false;
            $this->formularOk = false;
        }
        return $jmeno;
    }

    /**
     * Ověření správně zadaného pole formuláře pro příjmení
     * Pracuje s instančními proměnnými $this->formularOK a $this->data['formular']['prijmeni']
     * @return string Ošetřené příjmení
     */
    private function validacePrijmeni(): string {
        if (!array_key_exists('prijmeni', $_POST)) {
            $_POST['prijmeni'] = '';
        }
        $prijmeniPost = (string) $_POST['prijmeni'];
        $prijmeni = trim($prijmeniPost);
        $this->data['formular']['prijmeni']['poslanaHodnota'] = $prijmeni;
        if (strlen($prijmeni) === 0) { // je vyplněné?
            $this->data['formular']['prijmeni']['chybovaZprava'] = 'Příjmení nemůže být prázdné.';
            $this->data['formular']['prijmeni']['ok'] = false;
            $this->formularOk = false;
        } elseif (strlen($prijmeni) > 65) { // delší příjmení než 65 znaků se nemusí vejít do db
            $this->data['formular']['prijmeni']['chybovaZprava'] = 'Příjmení je příliš dlouhé.';
            $this->data['formular']['prijmeni']['ok'] = false;
            $this->formularOk = false;
        }
        return $prijmeni;
    }

    /**
     * Ověření správně zadaného pole formuláře pro věk
     * Pracuje s instančními proměnnými $this->formularOK a $this->data['formular']['vek']
     * @return string Ošetřený věk
     */
    private function validaceVek(): int {
        if (!array_key_exists('vek', $_POST)) {
            $_POST['vek'] = '';
        }
        $vekPost = trim((string) $_POST['vek']);
        $vek = (int) $vekPost;

        $this->data['formular']['vek']['poslanaHodnota'] = $vekPost;
        if ($vekPost !== (string) $vek) { // věk není zadaný čísly
            $this->data['formular']['vek']['chybovaZprava'] = 'Věk zadávejte pouze číslicemi.';
            $this->data['formular']['vek']['ok'] = false;
            $this->formularOk = false;
        } elseif ($vek < 0) { // nesmí být záporný
            $this->data['formular']['vek']['chybovaZprava'] = 'Věk nesmí být záporný.';
            $this->data['formular']['vek']['ok'] = false;
            $this->formularOk = false;
        } elseif ($vek > 100) { // příliš starý pojištěnec
            $this->data['formular']['vek']['chybovaZprava'] = 'Věk je příliš vysoký, takhle starého člověka nelze pojistit.';
            $this->data['formular']['vek']['ok'] = false;
            $this->formularOk = false;
        }
        return $vek;
    }

    /**
     * Ověření správně zadaného pole formuláře pro telefonní číslo
     * Pracuje s instančními proměnnými $this->formularOK a $this->data['formular']['telefon']
     * @return string Ošetřené telefonní číslo
     */
    private function validaceTelefon(): string {
        if (!array_key_exists('telefon', $_POST)) {
            $_POST['telefon'] = '';
        }
        $telefonPost = (string) $_POST['telefon'];
        $telefon = preg_replace('/\s+/', '', $telefonPost);
        $this->data['formular']['telefon']['poslanaHodnota'] = $telefon;
        if (strlen($telefon) > 16) { // validní číslo nemá více než 16 znaků
            $this->data['formular']['telefon']['chybovaZprava'] = 'Uvedené číslo neexistuje, je příliš dlouhé.';
            $this->data['formular']['telefon']['ok'] = false;
            $this->formularOk = false;
        } elseif (!preg_match('/^\+\d\d\d+$/', $telefon)) { // nejkratší možné zadané číslo je +123
            $this->data['formular']['telefon']['chybovaZprava'] = 'Telefonní číslo musí obsahovat předvolbu ve tvaru "+" a číselná hodnota Vašeho telefonního čísla.';
            $this->data['formular']['telefon']['ok'] = false;
            $this->formularOk = false;
        }
        return $telefon;
    }

    /**
     * Zobrazení a zpracování formuláře, po úspěšném zpracování přesměrování na výpis pojištenců
     * @param array $parametry
     * @return void
     */
    public function zpracuj(array $parametry): void {


        $this->hlavicka = array(
            'titulek' => 'Registrace nového pojištěnce',
            'klicova_slova' => 'nový pojištěnec',
            'popis' => 'formulář pro registraci nového pojištěnce',
        );
        $this->pohled = 'registrace';

        $this->nastavVychoziHodnotyFormulare();

        if ($_POST) { // zpracování odeslaného formuláře ze strany uživatele
            $jmeno = $this->validaceJmena();
            $prijmeni = $this->validacePrijmeni();
            $vek = $this->validaceVek();
            $telefon = $this->validaceTelefon();

            if ($this->formularOk) { // ve formuláři nebyla chyba, pojištěnce přidáme do db
                $vysledekPridani = NovyPojistenec::pridejPojistence($jmeno, $prijmeni, $vek, $telefon);
                if ($vysledekPridani === true) {
                    $this->pridejZpravu('Nový pojištěnec byl úspěšně přidán.');
                    $this->presmeruj('/');
                }
            }
        }
    }

}
