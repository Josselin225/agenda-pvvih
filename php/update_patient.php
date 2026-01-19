<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    
    // Données Patient (Tout en Majuscules pour la cohérence)
    $nom = strtoupper(trim($_POST['nom']));
    $prenoms = strtoupper(trim($_POST['prenoms']));
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $age = (int)$_POST['age'];
    $quartier = strtoupper(trim($_POST['quartier']));
    $telephone1 = trim($_POST['telephone1']);
    $telephone2 = trim($_POST['telephone2']);
    $categorie = $_POST['categorie'];

    // Données Aval
    $aval_nom = strtoupper(trim($_POST['aval_nom']));
    $lien_parente = $_POST['lien_parente'];
    $aval_tel = trim($_POST['aval_tel']);
    $confidentialite = $_POST['confidentialite'];

    try {
        $pdo->beginTransaction();

        // Mise à jour de TOUS les champs de la table patients
        $sqlPatient = "UPDATE patients SET 
                        nom = :nom, 
                        prenoms = :prenoms, 
                        sexe = :sexe, 
                        date_naissance = :date_naissance,
                        age = :age, 
                        quartier = :quartier, 
                        telephone1 = :tel1,
                        telephone2 = :tel2,
                        categorie = :categorie 
                       WHERE id = :id";
        
        $stmtP = $pdo->prepare($sqlPatient);
        $stmtP->execute([
            ':nom' => $nom,
            ':prenoms' => $prenoms,
            ':sexe' => $sexe,
            ':date_naissance' => $date_naissance,
            ':age' => $age,
            ':quartier' => $quartier,
            ':tel1' => $telephone1,
            ':tel2' => $telephone2,
            ':categorie' => $categorie,
            ':id' => $id
        ]);

        // Mise à jour ou création de l'aval associé
        $sqlAval = "INSERT INTO avals (patient_id, nom_complet, lien_parente, telephone, confidentialite) 
                    VALUES (:p_id, :nom, :lien, :tel, :conf)
                    ON DUPLICATE KEY UPDATE 
                    nom_complet = VALUES(nom_complet),
                    lien_parente = VALUES(lien_parente),
                    telephone = VALUES(telephone),
                    confidentialite = VALUES(confidentialite)";
        
        $stmtA = $pdo->prepare($sqlAval);
        $stmtA->execute([
            ':p_id' => $id,
            ':nom' => $aval_nom,
            ':lien' => $lien_parente,
            ':tel' => $aval_tel,
            ':conf' => $confidentialite
        ]);

        $pdo->commit();
        header("Location: ../pages/patients.php?msg=success");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur Fatale : " . $e->getMessage());
    }
}