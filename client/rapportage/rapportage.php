<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();

$Main = new Main();
$ClientModel = new ClientModel();

$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = (int)$clientId;

$client = $ClientModel->getById($clientId);
if (!$client) {
    $client = $ClientModel->getClientById($clientId);
}
if (!$client || empty($client)) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['client'] = $client;

$rapportages = [];
$stmt = DatabaseConnection::getConn()->prepare("
    SELECT r.*, vr.id AS verzorgerregel_id, m.naam AS medewerker_naam, m.rol AS medewerker_rol 
    FROM rapport r 
    LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id 
    LEFT JOIN medewerker m ON vr.medewerkerid = m.id
    WHERE vr.clientid = ? 
    ORDER BY r.datumtijd DESC
");
if ($stmt) {
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $rapportages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$isGekoppeld = false;
$stmtKoppeling = DatabaseConnection::getConn()->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? AND is_deleted = 0 LIMIT 1");
if ($stmtKoppeling) {
    $loggedInId = Auth::id();
    $stmtKoppeling->bind_param("ii", $clientId, $loggedInId);
    $stmtKoppeling->execute();
    if ($stmtKoppeling->get_result()->fetch_assoc()) {
        $isGekoppeld = true;
    }
    $stmtKoppeling->close();
}
$magRapporteren = Auth::isAdmin() || $isGekoppeld;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Rapportages van <?= htmlspecialchars($client['naam'] ?? '') ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
</head>

<body>
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">

        <?php
        include '../../includes/n-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <?php if (isset($_SESSION['succes'])) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['succes']) ?>
                    </div>
                    <?php unset($_SESSION['succes']); ?>
                <?php } ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0 text-primary">Rapportages van <?= htmlspecialchars($client['naam']) ?></h1>
                    <?php if ($magRapporteren): ?>
                        <a href='rapportage-nieuw.php?id=<?= (int)$clientId ?>' class='btn btn-primary'>
                            <i class="fa fa-plus me-1"></i> Nieuwe rapportage
                        </a>
                    <?php endif; ?>
                </div>

                <?php
                if (empty($rapportages)) {
                    echo "<p class='text-muted mt-2'>Er zijn nog geen rapportages voor deze cliënt.</p>";
                }

                $maanden = [
                    1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
                    5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
                    9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
                ];

                foreach ($rapportages as $rapport) {
                    $dt = new DateTime($rapport['datumtijd']);
                    $dag = $dt->format('d');
                    $maand = $maanden[(int)$dt->format('n')];
                    $jaar = $dt->format('Y');
                    $tijd = $dt->format('H:i');
                    $auteur = !empty($rapport['medewerker_naam']) ? $rapport['medewerker_naam'] : 'Onbekend';
                    $rol = !empty($rapport['medewerker_rol']) ? ' (' . ucfirst($rapport['medewerker_rol']) . ')' : '';
                ?>
                    <div class="card mb-3 shadow-sm border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 text-primary fw-bold">Rapportage van <?= $dag . ' ' . $maand . ' ' . $jaar ?> om <?= $tijd ?></h5>
                                <small class="text-muted">Geschreven door: <strong><?= htmlspecialchars($auteur) ?></strong><?= htmlspecialchars($rol) ?></small>
                            </div>
                            <a href="rapportage-aanpassen.php?id=<?= (int)$rapport['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-pen me-1"></i> Aanpassen
                            </a>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-dark" style="white-space: pre-line;"><?= htmlspecialchars($rapport['inhoud']) ?></p>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
