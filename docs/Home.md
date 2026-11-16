# Digitaal Patiënten Dossier (DPD)

Welkom bij de documentatie van het Digitaal Patiënten Dossier (DPD). Dit is een webapplicatie waarmee zorgmedewerkers patiëntgegevens kunnen bijhouden, de 11 gezondheidspatronen van Gordon kunnen invullen en medische rapportages kunnen opstellen.

---

## Rollen in het systeem

Het systeem heeft twee soorten gebruikers:
1. **Zorgverlener (Verpleegkundige / Verzorgende):** Kan cliëntendossiers inzien van gekoppelde cliënten, patronen invullen en rapportages schrijven.
2. **Beheerder (Admin):** Heeft volledige toegang tot alle dossiers en kan ook medewerkers toevoegen, bewerken en koppelen aan cliënten.

---

## Installatie & Lokaal Draaien

Om het project lokaal op te starten (bijvoorbeeld met XAMPP):

1. **Repository clonen of downloaden:**
   Zorg dat het project in de `htdocs` map van XAMPP staat (of in je Apache webroot).

2. **Database aanmaken:**
   Maak in phpMyAdmin een nieuwe database aan genaamd `dpd_dev`.

3. **SQL scripts importeren:**
   Importeer de SQL-bestanden uit de map `SQLscripts/`:
   - Eerst `SQLscripts/dpd_empty.sql` (de tabellen).
   - Daarna `SQLscripts/dpd_testdata01.sql` (de testdata).
   - Zorg dat migratiescripts `dpd_testdata20` en `dpd_testdata21` zijn uitgevoerd voor de soft-delete kolommen (`is_deleted`).

4. **Configuratie controleren (`.env`):**
   Maak eventueel een `.env` bestand aan in de hoofdmap met:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=dpd_dev
   ```
   *(Als je geen `.env` bestand aanmaakt, gebruikt het systeem standaard `localhost`, `root`, leeg wachtwoord en `dpd_dev`).*

5. **Openen in de browser:**
   Ga naar `http://localhost/DigitaalPatientenDossier/` (of jouw lokale mapnaam).

---

## Standaard Inloggegevens

Voor het testen zijn de volgende accounts beschikbaar:

| Rol | E-mailadres | Wachtwoord |
| :--- | :--- | :--- |
| **Beheerder** | `medewerker@apotheek.nl` | `admin` |
| **Zorgverlener** | `verpleegkundige@apotheek.nl` | `admin` |

