<?php
require_once 'login_bd.php';

date_default_timezone_set('Europe/Lisbon'); 

$id = filter_input(INPUT_POST, 'id');
$novonome = filter_input(INPUT_POST, 'nome');
$novoquantidade = filter_input(INPUT_POST, 'quantidade');
$novoutilidade = filter_input(INPUT_POST, 'utilidade');
$novodetalhes = filter_input(INPUT_POST, 'detalhes');

// Obter a hora atual
$novoitem_creat = date('Y-m-d H:i:s'); // Formato: YYYY-MM-DD HH:MM:SS

$ligacao = new mysqli($hn, $un, $pw, $db);
mysqli_set_charset($ligacao, "utf8");

if (!$ligacao) {
    header("Location:inventario.php?alteracao=false");
    exit;
}

// Update à tabela
$sql = "UPDATE item SET nome=?, quantidade=?, utilidade=?, detalhes=?, item_creat=? WHERE id=?";
$update = $ligacao->prepare($sql);
$update->bind_param('sssssi', $novonome, $novoquantidade, $novoutilidade, $novodetalhes, $novoitem_creat, $id);

if (!$update->execute()) {
    header("Location:inventario.php?alter=false");
    exit;
}

header("Location:inventario.php?alter=true");

$update->close();
$ligacao->close();
?>
