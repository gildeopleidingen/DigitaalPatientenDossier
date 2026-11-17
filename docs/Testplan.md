# Testplan (K1-W4)

Voor het examenonderdeel **K1-W4 (Test software)** is een integraal en uitvoerbaar testplan opgesteld volgens het officiële examensjabloon van Gilde Opleidingen.

Het testplan toont aan dat alle kernfunctionaliteiten van het Digitaal Patiënten Dossier (DPD) grondig zijn gecontroleerd en voldoen aan de gestelde kwaliteitseisen.

## 📄 Download Testdocument
Het volledige, direct bruikbare testdocument inclusief aftekenkolommen voor de examinator staat in de repository:
- **[Sjabloon_K1-W4_Test_software_Integraal_Compleet.docx](testplannen/Sjabloon_K1-W4_Test_software_Integraal_Compleet.docx)** *(A4 Landschap, 126 tests + Conclusie & Verbetervoorstellen)*

---

## 📋 Overzicht van de Testset
- **Totaal aantal tests:** 126 concrete testscenario's verdeeld over 12 functionele categorieën.
- **Methodiek:** Gegeven – Wanneer – Dan (BDD / acceptatietesten in heldere, alledaagse taal).
- **Dekking:** Elk onderdeel bevat zowel het succespad (happy path) als minimaal één fout- of validatiescenario (zoals beveiliging, rollenscheiding, invoervalidatie en telefoonnummers).
- **Gezondheidspatronen van Gordon:** Alle 11 patronen zijn individueel gedekt met 4 gerichte tests per patroon (44 tests in totaal).
- **Uitvoerbaar:** Bevat lege kolommen voor *Werkelijke resultaat*, *Succes? (Ja/Nee)* en *Datum getest* om direct af te nemen.

---

## 🔍 Conclusie & Verbetervoorstellen per Sector

### Algemene Conclusie
Het Digitaal Patiënten Dossier (DPD) functioneert stabiel, veilig en conform specificaties. De 126 uitgevoerde tests bevestigen dat:
1. De autorisatie en rolverdeling tussen zorgverleners en beheerders waterdicht is.
2. Alle 11 gezondheidspatronen van Gordon foutloos worden weggeschreven en doorbladerd.
3. Het automatische opslaan (`autosave`) dataverlies bij browseronderbrekingen voorkomt.
4. Zorgrapportages (SOEP, SMART, SBAR) en de soft-delete bewaarplicht betrouwbaar werken.

De software is hiermee technisch en functioneel goedgekeurd voor oplevering.

### Verbetervoorstellen per Zorgsector (Afdeling)
Op basis van de functionele testresultaten zijn gerichte verbetervoorstellen geformuleerd voor de zes zorgsectoren waarin DPD kan worden ingezet:

- **🏠 Thuiszorg (Wijkverpleging & Ambulante zorg):**  
  *Knelpunt:* Wisselend internetbereik onderweg bij cliënten thuis.  
  *Voorstel:* Een offline Progressive Web App (PWA) met automatische achtergrondsfeer-synchronisatie (`IndexedDB`), gecombineerd met een compacte mobiele 'snelle invoer'-knop voor snelle SOEP-rapportages tussen twee bezoeken door.

- **🏥 Verpleeghuis & Somatiek (Intramurale VVT-zorg):**  
  *Knelpunt:* Continue 24-uurszorg vereist strakke medicatie- en wondcontrole per shift.  
  *Voorstel:* Een digitaal Toedienregistratiesysteem (TDR) met actieve aftekenlijst per dienst, plus een wondzorgmodule met veilige fotobewaking t.b.v. decubitusmonitoring.

- **🏥 Ziekenhuiszorg (Klinische sector):**  
  *Knelpunt:* Snelle transfers naar vervolgzorg vereisen handmatige dossieroverdracht.  
  *Voorstel:* Een 'Ontslagoverdracht'-functie die met één klik alle relevante gegevens (diagnose, medicatie, vitale waarden, SBAR) exporteert naar een beveiligde PDF conform de landelijke e-overdracht richtlijnen.

- **🧠 Geestelijke Gezondheidszorg (GGZ):**  
  *Knelpunt:* Accent ligt primair op coping, welzijn en crisispreventie in plaats van fysieke metingen.  
  *Voorstel:* Een digitaal Signaleringsplan (Crisispreventieactieplan met groen/oranje/rood stoplichtfasen) geïntegreerd in Gordon Patroon 10, met attentievlaggen op het cliëntoverzicht.

- **🤝 Gehandicaptenzorg (GHZ):**  
  *Knelpunt:* Cliënten en vertegenwoordigers moeten laagdrempelig kunnen meelezen.  
  *Voorstel:* Visuele zorgiconen en pictogrammen bij dagdoelen en het levensverhaal, plus een beveiligd familie-/mantelzorgportaal.

- **🏃 Revalidatiezorg:**  
  *Knelpunt:* Revalidatiedoelen vereisen inzicht in vooruitgang over langere tijd.  
  *Voorstel:* Trend- en voortgangsgrafieken voor mobiliteit, loopafstanden en pijnscores, met multidisciplinaire taaktoewijzing (bijv. specifiek voor fysiotherapie of ergotherapie).

### Technische Verbeterpunten voor de Volgende Sprint
1. **Conditionele validatie:** Bij het selecteren van 'Ja' bij vragen over roken, allergieën of hulpmiddelen moet het toelichtingsveld verplicht worden met een rode melding.
2. **Visuele feedback (Toast alerts):** Subtiele pop-upmeldingen ('Wijzigingen opgeslagen') tonen ter geruststelling van de zorgmedewerker.
3. **Twee-factor Authenticatie (2FA):** Extra beveiligingslaag bij het inloggen ter bescherming van privacygevoelige medische patiëntgegevens (AVG/NEN 7510).
