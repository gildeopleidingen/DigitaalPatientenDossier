<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();
$form = new PatternFormHelper();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        2,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon03.php");
            exit;
        case 'prev':
            header("Location: patroon01.php");
            exit;
    }
}

$observatieLijst = [
    "(Dreigend) voedingsteveel (zwaarlijvigheid)",
    "Voedingstekort",
    "(Dreigend) vochttekort",
    "Falende warmteregulatie",
    "Aspiratiegevaar",
    "(Dreigende) huiddefect"
];

$content = '';
$content .= $form->radioGroup("Hoe is uw eetlust nu?", "eetlust", ["0" => "Normaal", "1" => "Slecht", "2" => "Overmatig"], $antwoorden['eetlust'] ?? null);
$content .= $form->yesNoWithText("- Heeft u een dieet?", "dieet", "dieet_welk", $antwoorden['dieet'] ?? null, $antwoorden['dieet_welk'] ?? null, "en wel?");
$content .= $form->radioGroup("- Is uw gewicht de laatste tijd veranderd?", "gewicht_verandert", ["1" => "Ja", "0" => "Nee"], $antwoorden['gewicht_verandert'] ?? null);
$content .= $form->radioGroup("Heeft u moeite met slikken?", "moeilijk_slikken", ["1" => "Ja", "0" => "Nee"], $antwoorden['moeilijk_slikken'] ?? null);
$content .= $form->radioGroup("Heeft u gebitsproblemen?", "gebitsproblemen", ["1" => "Ja", "0" => "Nee"], $antwoorden['gebitsproblemen'] ?? null);
$content .= $form->radioGroup("- Heeft u een gebitsprothese?", "gebitsprothese", ["1" => "Ja", "0" => "Nee"], $antwoorden['gebitsprothese'] ?? null);
$content .= $form->radioGroup("Heeft u huidproblemen?", "huidproblemen", ["1" => "Ja", "0" => "Nee"], $antwoorden['huidproblemen'] ?? null);
$content .= $form->radioGroup("Heeft u het koud of warm?", "gevoel", ["0" => "Normaal", "1" => "Koud", "2" => "Warm"], $antwoorden['gevoel'] ?? null);
$content .= $form->observationSection($observatieLijst, $antwoorden['observatie'] ?? '');

echo $form->renderLayout(
    "Anamnese — Voedings- en Stofwisselingspatroon",
    "2. Voedings- en stofwisselingspatroon",
    $content,
    "patroon01.php",
    "patroon03.php"
);
