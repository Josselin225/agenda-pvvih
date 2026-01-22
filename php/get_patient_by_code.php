<?php
// php/get_patient_info.php
require_once '../config/db.php';

$code = $_GET['code'] ?? '';

if ($code) {
    // On cherche le patient et son dernier RDV
    $stmt = $pdo->prepare("SELECT id, nom, prenoms, sexe, code, 
        TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) as age 
        FROM patients WHERE code = ?");
    $stmt->execute([$code]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient) {
        // Chercher la date du dernier RDV
        $stmtRdv = $pdo->prepare("SELECT date_rdv FROM rendez_vous WHERE patient_id = ? ORDER BY date_rdv DESC LIMIT 1");
        $stmtRdv->execute([$patient['id']]);
        $lastRdv = $stmtRdv->fetchColumn();

        echo json_encode([
            'success' => true,
            'id' => $patient['id'],
            'nom' => $patient['nom'],
            'prenoms' => $patient['prenoms'],
            'sexe' => $patient['sexe'],
            'age' => $patient['age'],
            'code' => $patient['code'],
            'dernier_rdv' => $lastRdv ? date('d/m/Y', strtotime($lastRdv)) : 'Aucun'
        ]);
        exit;
    }
}
echo json_encode(['success' => false]);