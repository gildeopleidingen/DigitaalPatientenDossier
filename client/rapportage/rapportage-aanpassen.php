<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();

$rapportageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($rapportageId <= 0) {
    header("Location: ../client.php");
    exit;
}

$stmt = DatabaseConnection::getConn()->prepare("
    SELECT r.*, vr.clientid, vr.medewerkerid 
    FROM rapport r 
    LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id 
    WHERE r.id = ?
");
$stmt->bind_param("i", $rapportageId);
$stmt->execute();
$rapportage = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($rapportage == null) {
    header("Location: ../client.php");
    exit;
}

// Zorg dat het echte client-ID in de sessie staat
$clientId = !empty($rapportage['clientid']) ? (int)$rapportage['clientid'] : ($_SESSION['clientId'] ?? null);
if ($clientId) {
    $_SESSION['clientId'] = $clientId;
}

$loggedInId = Auth::id();
$isAdmin = Auth::isAdmin();
$isGekoppeld = false;
$stmtKoppeling = DatabaseConnection::getConn()->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? AND is_deleted = 0 LIMIT 1");
if ($stmtKoppeling) {
    $stmtKoppeling->bind_param("ii", $clientId, $loggedInId);
    $stmtKoppeling->execute();
    if ($stmtKoppeling->get_result()->fetch_assoc()) {
        $isGekoppeld = true;
    }
    $stmtKoppeling->close();
}

$isAuteur = (int)($rapportage['medewerkerid'] ?? 0) === $loggedInId;
$magAanpassen = $isAdmin || ($isGekoppeld && $isAuteur);

// Alleen de auteur of een admin mag aanpassen
if (!$magAanpassen) {
    header("Location: ./rapportage.php?id=" . $clientId);
    exit;
}

$error = '';

if (isset($_POST['aanpassen'])) {
    $rapportageInhoud = trim($_POST['inhoud'] ?? '');
    $postRapportageId = (int)($_POST['rapportageId'] ?? 0);

    if ($rapportageInhoud === '') {
        $error = 'Rapportage mag niet leeg zijn.';
    } else {
        if ($postRapportageId === (int)$rapportage['id']) {
            $stmt = DatabaseConnection::getConn()->prepare("UPDATE rapport SET inhoud = ? WHERE id = ?");
            $stmt->bind_param("si", $rapportageInhoud, $postRapportageId);
            $stmt->execute();
            $stmt->close();

            $_SESSION['succes'] = 'Rapportage succesvol aangepast.';
        }
        header("Location: ./rapportage.php?id=" . $clientId);
        exit();
    }
}

$inhoud = isset($_POST['inhoud']) ? $_POST['inhoud'] : ($rapportage['inhoud'] ?? '');
// Oude tags herstellen naar newlines
$inhoud = preg_replace('/<br\s*\/?>\r?\n?/i', "\n", $inhoud);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <title>Rapportage aanpassen</title>
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
                        <?php if (isset($_SESSION['succes'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($_SESSION['succes']) ?>
                            </div>
                            <?php unset($_SESSION['succes']); ?>
                        <?php } ?>
                        <input type="hidden" value="<?= (int)$rapportage['id'] ?>" name="rapportageId">
                        <div class="rapportage flex-grow-1">
                            <div class="display-5 text-primary mb-3">Rapportage aanpassen van <?= htmlspecialchars($rapportage['datumtijd']) ?></div>
                            <div class="mb-3">
                                <textarea name="inhoud" id="rapportageInhoud" rows="16" placeholder="Rapportage" class="form-control" required><?= htmlspecialchars($inhoud) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="aanpassen" class="btn btn-primary w-100 mt-3">Aanpassen</button>
                    <a href="rapportage.php?id=<?= (int)$clientId ?>" class="btn btn-secondary mt-3">Terug</a>
                </form>
            </div>
        </div>
    </div>
    <script src="../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/js/validatie.js"></script>
</body>

</html>