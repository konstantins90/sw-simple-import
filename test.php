<?php
try {
    $pdo = new PDO('mysql:host=db;dbname=db', 'user', 'password');
    echo "PDO MySQL Verbindung erfolgreich!";
} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage();
}