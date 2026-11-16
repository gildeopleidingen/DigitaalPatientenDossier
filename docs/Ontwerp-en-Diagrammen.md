# Ontwerp & Diagrammen

Hier staan de ontwerpen, klassendiagrammen en wireframes van het Digitaal Patiënten Dossier.

---

## 1. Wireframes (Figma)

Het interactieve prototype en de schermontwerpen zijn gemaakt in Figma:

🔗 **[Bekijk het Figma ontwerp](https://www.figma.com/file/G5Msuj8yncuQoh6jHlPkog/DigitaalPatientenDossier?type=design&node-id=0%3A1&mode=design&t=B6TTxxfovbMHaNwE-1)**

Hierin staan onder andere de ontwerpen voor:
- Het inlogscherm
- Het dashboard voor zorgverleners en beheerders
- Het cliëntendossier en de 11 patronen van Gordon
- De rapportage-invoer

---

## 2. Visual Paradigm UML Diagrammen

De UML-diagrammen zijn gemodelleerd in Visual Paradigm.

> **Projectbestand:**  
> Het complete `.vpp` bronbestand staat in de repository onder:  
> `docs/diagrams/DigitaalPatientenDossier.vpp`

### A. Volledig Klassendiagram
Hieronder zie je het complete klassendiagram met de OOP-structuur van de modellen (`ClientModel`, `MedewerkerModel`, database connectie en formulieren):

![Volledig Klassendiagram](images/Digitaal%20Patienten%20Dossier%20-%20Volledig%20Klassendiagram.png)

### B. Domeinmodellen van Cliënt en Medewerker
De relatie tussen medewerkers, cliënten en de koppeltabel `verzorgerregel`:

![Domeinmodellen](images/Domeinmodellen%20-%20Client%20en%20Medewerker.png)

### C. Intake Engine (Gezondheidspatronen van Gordon)
Hoe de 11 patronen van Gordon zijn opgebouwd in de databasemodellen:

![Gordon Patronen](images/Intake%20Engine%20-%20Gordon%20Patronen.png)

### D. Systeemfundering & Authenticatie
De authenticatie en database-opzet van het systeem:

![Database en Authenticatie](images/Systeemfundering%20-%20Database%20en%20Authenticatie.png)

---

## 3. Database & Soft-delete

In plaats van gegevens definitief te verwijderen met `DELETE`, gebruikt het systeem **soft-delete** via de kolom `is_deleted`:
- `is_deleted = 0`: Het record is actief en zichtbaar.
- `is_deleted = 1`: Het record is gedeactiveerd (bijv. een medewerker die uit dienst is of een cliënt die gearchiveerd is).

Hierdoor blijven medische gegevens bewaard voor de wettelijke bewaarplicht, maar kunnen gedeactiveerde medewerkers niet meer inloggen.

