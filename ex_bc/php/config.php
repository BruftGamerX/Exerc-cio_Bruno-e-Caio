<?php
$host = "localhost";
$db = "mensagens_site";
$user = "root";
$pass = "&tec77@info!";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}


session_start();


function mostrarMensagem() {
    if (isset($_SESSION['msg'])) {
        $cor = $_SESSION['msg_tipo'] ?? 'red'; 
        echo "<p style='color:$cor'>" . $_SESSION['msg'] . "</p>";
        unset($_SESSION['msg']);
        unset($_SESSION['msg_tipo']);
    }
}
?>