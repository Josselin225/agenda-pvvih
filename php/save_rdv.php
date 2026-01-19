<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Transformer les tableaux de motifs en texte (ex: "PCR, CO/ETP")
    $motif_rdv = isset($_POST['motif_rdv']) ? implode(', ', $_POST['motif_rdv']) : 'SANS MOTIF';
    $motif_prochain = isset($_POST['motif_prochain_rdv']) ? implode(', ', $_POST['motif_prochain_rdv']) : 'SANS MOTIF';

    $sql = "INSERT INTO rendez_vous (
                patient_id, date_rdv, motif_rdv, molecule, duree_traitement, 
                assiduite, date_prochain_rdv, motif_prochain_rdv
            ) VALUES (
                :p_id, :d_rdv, :m_rdv, :mole, :duree, 
                :assid, :d_prox, :m_prox
            )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':p_id'  => $_POST['patient_id'],
        ':d_rdv' => $_POST['date_rdv'],
        ':m_rdv' => $motif_rdv,
        ':mole'  => strtoupper($_POST['molecule']),
        ':duree' => $_POST['duree_traitement'],
        ':assid' => $_POST['assiduite'],
        ':d_prox'=> $_POST['date_prochain_rdv'],
        ':m_prox'=> $motif_prochain
    ]);

    header("Location: ../pages/rendez_vous.php?msg=success");
}