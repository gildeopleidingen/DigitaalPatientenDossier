<?php
include_once '../../models/autoload.php';
Auth::requireLogin();
$Main = new Main();
$form = new PatternFormHelper();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 3);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        3,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon04.php");
            exit;
        case 'prev':
            header("Location: patroon02.php");
            exit;
    }
}

$observatieLijst = [
    "Colon-obstipatie",
    "Subjectief ervaren obstipatie",
    "Diarree",
    "Incontinentie van feces",
    "Verstoorde urine-uitscheiding",
    "Functionele urine-incontinentie",
    "Reflex-urine-incontinentie",
    "Stress-urine-incontinentie",
    "Volledige urine-incontinentie",
    "Urineretentie"
];

$content = '';
$content .= $form->yesNoWithText("Heeft u problemen met ontlasting?", "ontlasting_probleem", "op_welke", $antwoorden['ontlasting_probleem'] ?? null, $antwoorden['op_welke'] ?? null, "en wel?");
$content .= $form->textarea("- Wat doet u om deze problemen te bestrijden?", "op_preventie", $antwoorden['op_preventie'] ?? null);
$content .= $form->yesNoWithText("- Gebruikt u iets om uw stoelgang te reguleren?", "op_medicijnen", "op_medicijnen_welke", $antwoorden['op_medicijnen'] ?? null, $antwoorden['op_medicijnen_welke'] ?? null, "en wel?");
$content .= $form->radioGroup("Heeft u problemen ten aanzien van het urineren?", "urineer_probleem", ["1" => "Ja", "0" => "Nee"], $antwoorden['urineer_probleem'] ?? null);
$content .= $form->radioGroup("- Heeft u last van incontinentie?", "up_incontinentie", ["1" => "Ja", "0" => "Nee"], $antwoorden['up_incontinentie'] ?? null);
$content .= $form->yesNoWithText("- Bent u hiervoor in behandeling?", "up_incontinentie_behandeling", "up_incontinentie_behandeling_welke", $antwoorden['up_incontinentie_behandeling'] ?? null, $antwoorden['up_incontinentie_behandeling_welke'] ?? null, "en wel?");
$content .= $form->yesNoWithText("Hebt u last van transpiratie?", "transpiratie", "transpiratie_welke", $antwoorden['transpiratie'] ?? null, $antwoorden['transpiratie_welke'] ?? null, "en wel?");
$content .= $form->observationSection($observatieLijst, $antwoorden['observatie'] ?? '');

echo $form->renderLayout(
    "Anamnese — Uitscheidingspatroon",
    "3. Uitscheidingspatroon",
    $content,
    "patroon02.php",
    "patroon04.php"
);
