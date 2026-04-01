<?php
include("php/config.php");

$id = $_POST['id'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT senha FROM mensagens WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res && password_verify($senha, $res['senha'])) {
    echo "ok";
} else {
    echo "erro";
}