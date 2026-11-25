<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();
$loggedInId = Auth::id();

// Zet deze variabele op true of false om de sjabloon/template knoppen te tonen of te verbergen
$toonTemplateKnoppen = true;

$Main = new Main();
$ClientModel = new ClientModel();

// Controleer of er een client is geselecteerd (via GET of SESSION)
$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = $clientId;

// Haal clientgegevens op
$client = $ClientModel->getById($clientId);
if (!$client) {
    $client = $ClientModel->getClientById($clientId);
}
if (!$client || empty($client)) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['client'] = $client;

$conn = DatabaseConnection::getConn();
$isAdmin = Auth::isAdmin();

// Controleer of deze medewerker gekoppeld is aan de cliënt
$stmtId = $conn->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? AND is_deleted = 0 LIMIT 1");
$stmtId->bind_param("ii", $clientId, $loggedInId);
$stmtId->execute();
$row = $stmtId->get_result()->fetch_assoc();
$stmtId->close();

$verzorgerregelId = $row ? (int)$row['id'] : null;

// 3. Niet gekoppeld én geen admin: direct terugsturen zonder iets te tonen
if (!$verzorgerregelId && !$isAdmin) {
    header("Location: rapportage.php?id=" . $clientId);
    exit;
}

// 2. Admin die nog niet gekoppeld is: automatisch koppelen als verzorger
if (!$verzorgerregelId && $isAdmin) {
    $stmtCheck = $conn->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? LIMIT 1");
    $stmtCheck->bind_param("ii", $clientId, $loggedInId);
    $stmtCheck->execute();
    $existing = $stmtCheck->get_result()->fetch_assoc();
    $stmtCheck->close();

    if ($existing) {
        $verzorgerregelId = (int)$existing['id'];
        $stmtRestore = $conn->prepare("UPDATE verzorgerregel SET is_deleted = 0 WHERE id = ?");
        $stmtRestore->bind_param("i", $verzorgerregelId);
        $stmtRestore->execute();
        $stmtRestore->close();
    } else {
        $stmtInsert = $conn->prepare("INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted) VALUES (?, ?, 0)");
        $stmtInsert->bind_param("ii", $clientId, $loggedInId);
        $stmtInsert->execute();
        $verzorgerregelId = (int)$stmtInsert->insert_id;
        $stmtInsert->close();
    }
}

$error = '';

if (isset($_POST['opslaan'])) {
    $inhoud = trim($_POST['inhoud'] ?? '');

    if ($inhoud === '') {
        $error = 'Rapportage mag niet leeg zijn.';
    } else {
        // Maak de nieuwe rapportage aan
        $tijd = date('Y-m-d H:i:s');
        $stmtRapport = $conn->prepare("INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud) VALUES (?, ?, ?)");
        $stmtRapport->bind_param("iss", $verzorgerregelId, $tijd, $inhoud);
        $stmtRapport->execute();
        $stmtRapport->close();

        $_SESSION['succes'] = 'Nieuwe rapportage succesvol opgeslagen.';
        header("Location: rapportage.php?id=" . $clientId);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <title>Nieuwe rapportage - <?= htmlspecialchars($client['naam']) ?></title>
</head>

<body>
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">
        <?php
        include_once '../../includes/n-sidebar.php';
        ?>
        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3 d-flex flex-column" style="height: 96%; overflow: auto;">
                <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <div class="flex-grow-1 d-flex flex-column">
                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php } ?>

                        <input type="hidden" value="<?= (int)$clientId ?>" name="clientId">
                        <div class="rapportage flex-grow-1">
                            <div class="display-5 text-primary mb-3">Nieuwe rapportage voor <?= htmlspecialchars($client['naam']) ?></div>

                            <?php if ($toonTemplateKnoppen): ?>
                                <!-- Template knoppen -->
                                <div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
                                    <span class="text-muted small fw-bold">Sjablonen:</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('soep')">SOEP</button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="insertTemplate('sbar')">SBAR</button>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="insertTemplate('smart')">SMART</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="wisVeld()">Veld wissen</button>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <textarea name="inhoud" id="rapportageInhoud" rows="16" placeholder="Typ hier de rapportage..." class="form-control" required><?= htmlspecialchars($_POST['inhoud'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="opslaan" class="btn btn-primary w-100 mt-3">Rapportage opslaan</button>
                    <a href="rapportage.php?id=<?= (int)$clientId ?>" class="btn btn-secondary mt-3">Terug</a>
                </form>
            </div>
        </div>
    </div>
    <script src="../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/js/validatie.js"></script>
    <?php if ($toonTemplateKnoppen): ?>
        <script>
            const sjablonen = {
                'soep': "S (Subjectief): \nO (Objectief): \nE (Evaluatie): \nP (Plan): ",
                'sbar': "S (Situatie): \nB (Achtergrond): \nA (Beoordeling): \nR (Aanbeveling): ",
                'smart': "S (Specifiek): \nM (Meetbaar): \nA (Acceptabel): \nR (Realistisch): \nT (Tijdsgebonden): "
            };

            function insertTemplate(type) {
                const textarea = document.getElementById('rapportageInhoud') || document.querySelector('textarea[name="inhoud"]');
                if (!textarea) return;

                const tekst = sjablonen[type] || '';
                if (!tekst) return;

                // Vervangt alle vorige tekst direct door het gekozen sjabloon
                textarea.value = tekst;
                textarea.focus();
            }

            function wisVeld() {
                const textarea = document.getElementById('rapportageInhoud') || document.querySelector('textarea[name="inhoud"]');
                if (!textarea) return;
                textarea.value = '';
                textarea.focus();
            }
        </script>
    <?php endif; ?>
</body>

</html>