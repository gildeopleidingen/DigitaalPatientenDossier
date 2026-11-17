# Testplan (K1-W4)

Voor het examenonderdeel **K1-W4 (Test software)** is een integraal en uitvoerbaar testplan opgesteld volgens het officiële examensjabloon van Gilde Opleidingen.

Het testplan toont aan dat alle kernfunctionaliteiten van het Digitaal Patiënten Dossier (DPD) grondig zijn gecontroleerd en voldoen aan de gestelde kwaliteitseisen.

## 📄 Download Testdocument
Het volledige, direct bruikbare testdocument inclusief aftekenkolommen voor de examinator staat in de repository:
- **[Sjabloon_K1-W4_Test_software_Integraal_Compleet.docx](testplannen/Sjabloon_K1-W4_Test_software_Integraal_Compleet.docx)** *(A4 Landschap, 126 tests + lege invultabellen voor Conclusie & Verbetervoorstellen)*

---

## 📋 Overzicht van de Testset
- **Totaal aantal tests:** 126 concrete testscenario's verdeeld over 12 functionele categorieën.
- **Methodiek:** Gegeven – Wanneer – Dan (BDD / acceptatietesten in heldere, alledaagse taal).
- **Dekking:** Elk onderdeel bevat zowel het succespad (happy path) als minimaal één fout- of validatiescenario (zoals beveiliging, rollenscheiding, invoervalidatie en telefoonnummers).
- **Gezondheidspatronen van Gordon:** Alle 11 patronen zijn individueel gedekt met 4 gerichte tests per patroon (44 tests in totaal).
- **Uitvoerbaar:** Bevat lege kolommen voor *Werkelijke resultaat*, *Succes? (Ja/Nee)* en *Datum getest* om direct fysiek of digitaal af te nemen.

---

## 📝 Conclusie & Verbetervoorstellen per Sector (Invulformat)

In **hoofdstuk 13** van het officiële Word-testdocument zijn gestructureerde invultabellen opgenomen. Na afloop van het testen vult het testteam hier de definitieve bevindingen en verbeteringen in op basis van de behaalde testresultaten:

### 1. Testresultaten & Conclusie (Invultabel 13.1)
Beoordelingskader om in te vullen na afronding van de testscenario's:
- **Datum testronde & Naam van de tester**
- **Totaal aantal tests uitgevoerd** *(van de 126 tests)*
- **Aantal geslaagde tests (Pass [Ja])**
- **Aantal afgekeurde tests (Fail [Nee])**
- **Algemeen eindoordeel & toelichting op de softwarekwaliteit** *(ruimte voor synthese over stabiliteit, de 11 patronen van Gordon, autorisatie en gebruiksvriendelijkheid)*

### 2. Verbetervoorstellen per Zorgsector (Invultabel 13.2)
Een gestructureerde invultabel met per sector kolommen voor *Geconstateerde knelpunten / testbevindingen* en *Concreet verbetervoorstel voor de volgende versie*:
1. **🏠 Thuiszorg** *(Wijkverpleging & extramurale zorg)*
2. **🏥 Verpleeghuis** *(VVT & somatische zorg)*
3. **🏥 Ziekenhuis** *(Klinische sector & ontslag)*
4. **🧠 Geestelijke Gezondheidszorg (GGZ)** *(Psychiatrie & begeleid wonen)*
5. **🤝 Gehandicaptenzorg (GHZ)** *(Verstandelijk / meervoudig beperkt)*
6. **🏃 Revalidatiezorg** *(Paramedisch & klinisch herstel)*
7. **⚙️ Algemeen & Technisch** *(Beveiliging, formulieren, autosave, 2FA)*
