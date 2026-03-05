<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   
    header('Location: index.php?delete=invalid');
    exit;
}

require_once __DIR__ . '/includes/config.php'; 
require_once __DIR__ . '/includes/donnees.php'; 

$id_plat = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id_plat === null || $id_plat === false || $id_plat <= 0) {
    header('Location: index.php?delete=invalid');
    exit;
}

$succes = false; 
try {
    $succes = Plat::supprimerParId($pdo, $id_plat);
} catch (PDOException $e) {
    error_log("Erreur PDO lors de la suppression du plat ID $id_plat: " . $e->getMessage());
    $succes = false;
} catch (Exception $e) {
     error_log("Erreur générale lors de la suppression du plat ID $id_plat: " . $e->getMessage());
     $succes = false;
}

if ($succes) {
    header('Location: index.php?delete=succes');
    exit;
} else {
    header('Location: index.php?delete=echec');
    exit;
}

?>
