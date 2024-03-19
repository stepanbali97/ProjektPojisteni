<?php
session_start();
mb_internal_encoding("UTF-8");

function autoloadFunkce(string $trida): void
{
        //Končí název třídy řetězcem kontroler?
    if (preg_match('/Kontroler$/', $trida)) {
        require(__DIR__ . "/kontrolery/" . $trida . ".php");
    } 
    elseif (preg_match('/Exception$/', $trida)) {
        require(__DIR__ . "/vyjimky/" . $trida . ".php");
    } else {
        require(__DIR__ . "/modely/" . $trida . ".php");
    }
}
    
spl_autoload_register("autoloadFunkce");
try {
     

Db::pripoj("127.0.0.1", "root", "", "evidence_pojisteni"); //zachytit chybu a vratit error page a umřít

    $smerovac = new SmerovacKontroler();
    $smerovac->zpracuj(array($_SERVER['REQUEST_URI']));
    $smerovac->vypisPohled();
} catch (DbNepripojenaException $ex) {
    error_log("Db chyba: " . $ex->getMessage() . " Trace: " . $ex->getTraceAsString());    
    ChybaKontroler::vypisChybu(500);
} catch (NenalezenaUrlException $ex) {
    error_log("Nenalezena URL: " . $ex->getMessage() . " Trace: " . $ex->getTraceAsString());    
    ChybaKontroler::vypisChybu(404);
} catch (Exception $ex) {
    error_log("Chyba: " . $ex->getMessage() . " Trace: " . $ex->getTraceAsString());    
    ChybaKontroler::vypisChybu(500);
 }   
