<?php

/**
 * Směrovač (router), který podle URL volá jiný kontroler, jenž tvoří pohled, 
 * který je následně vložen do šablony strámky
 */
class SmerovacKontroler extends Kontroler {

    /**
     * @var Kontroler Instalace kontroleru
     */
    protected Kontroler $kontroler;

    /**
     * Parsuje URL podle "/" a vracípole parametrů
     * @param string $url URL pro naparsování
     * @return array Pole URL parametrů
     */
    private function parsujURL(string $url): array {
        $naparsovanaURL = parse_url($url); // vytvoří asociativní pole z naparsované URL
        $naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/"); // osekání "/" z naparsované URL zleva
        $naparsovanaURL["path"] = trim($naparsovanaURL["path"]); // osekání bílých znaků
        $rozdelenaCesta = explode("/", $naparsovanaURL["path"]); // rozbití řetězce podle "/"
        return $rozdelenaCesta;
    }

    /**
     * Naparsuje zadanou URL a vytvoří příslušný kontroler
     * @param array $parametry 
     * @return void
     * @throws NenalezenaUrlException Pokud není URL v seznamu 
     */
    public function zpracuj(array $parametry): void {
        $cesta = $this->parsujURL($parametry[0]);

        if ($cesta[0] === 'novy') {
            $this->kontroler = new NovyPojistenecKontroler;
        } elseif ($cesta[0] === 'pojistenci') {
            $this->kontroler = new PojistenciKontroler;
        } elseif ($cesta[0] === '') {
            $this->kontroler = new PojistenciKontroler;
        } else {
            throw new NenalezenaUrlException("URL nenalezena: " . $cesta[0], 79);
        }


        $this->kontroler->zpracuj($cesta); // volání kontroleru
        // proměnné pro šablonu
        $this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
        $this->data['popis'] = $this->kontroler->hlavicka['popis'];
        $this->data['klicova_slova'] = $this->kontroler->hlavicka['klicova_slova'];
        $this->data['zpravy'] = $this->vratZpravy();

        $this->pohled = 'rozlozeni'; // hlavní šablona
    }

}
