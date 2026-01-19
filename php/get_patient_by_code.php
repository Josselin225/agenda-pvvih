<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$code = isset($_GET['code']) ? trim($_GET['code']) : '';

if ($code) {
    // Infos patient
    $stmt = $pdo->prepare("SELECT id, nom, prenoms, age, sexe, categorie FROM patients WHERE code = ?");
    $stmt->execute([$code]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient) {
        // Historique complet des RDV
        $stmtH = $pdo->prepare("SELECT date_rdv, motif_rdv, assiduite, date_prochain_rdv FROM rendez_vous WHERE patient_id = ? ORDER BY date_rdv DESC");
        $stmtH->execute([$patient['id']]);
        $historique = $stmtH->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'patient' => $patient,
            'historique' => $historique,
            'last_rdv' => !empty($historique) ? date('d/m/Y', strtotime($historique[0]['date_rdv'])) : null
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}