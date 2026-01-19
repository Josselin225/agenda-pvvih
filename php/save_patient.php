<?php
// On inclut la connexion à la base de données
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Démarrer la transaction
        $pdo->beginTransaction();

        // 2. Insertion du Patient (avec les deux téléphones et le sexe)
        $sqlPatient = "INSERT INTO patients (code, nom, prenoms, sexe, date_naissance, age, quartier, telephone1, telephone2, categorie) 
                       VALUES (:code, :nom, :prenoms, :sexe, :dnais, :age, :quart, :tel1, :tel2, :cat)";
        
        $stmtPatient = $pdo->prepare($sqlPatient);
        $stmtPatient->execute([
            ':code'    => $_POST['code'],
            ':nom'     => $_POST['nom'],
            ':prenoms' => $_POST['prenoms'],
            ':sexe'    => $_POST['sexe'],
            ':dnais'   => $_POST['date_naissance'],
            ':age'     => $_POST['age'],
            ':quart'   => $_POST['quartier'],
            ':tel1'    => $_POST['telephone1'], // Téléphone principal
            ':tel2'    => $_POST['telephone2'], // Téléphone d'urgence
            ':cat'     => $_POST['categorie']
        ]);

        // On récupère l'ID du patient pour l'aval
        $patient_id = $pdo->lastInsertId();

        // 3. Insertion de l'Aval (si rempli)
       // ... après l'insertion du patient ...

        if (!empty($_POST['nom_aval'])) {
            $sqlAval = "INSERT INTO avals (patient_id, nom_complet, lien_parente, telephone, confidentialite) 
                        VALUES (:p_id, :nom_a, :lien, :tel_a, :conf)";
            
            $stmtAval = $pdo->prepare($sqlAval);
            $stmtAval->execute([
                ':p_id'  => $patient_id,
                ':nom_a' => $_POST['nom_aval'],
                ':lien'  => $_POST['lien_parente'], // Nouveau champ ajouté ici
                ':tel_a' => $_POST['tel_aval'],
                ':conf'  => $_POST['confidentialite']
            ]);
        }

// ... suite du code ...

        // 4. Valider tout
        $pdo->commit();

        // Redirection avec succès
        header('Location: ../pages/patients.php?status=success');
        exit();

    } catch (Exception $e) {
        // En cas de problème, on annule les changements
        $pdo->rollBack();
        die("Erreur technique : " . $e->getMessage());
    }
}