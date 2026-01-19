<?php
// config/db.php
$host = 'localhost';
$dbname = 'agenda_pvvih';
$user = 'root';
$pass = ''; // Par défaut vide sur Laragon

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}