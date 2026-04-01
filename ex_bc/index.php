<?php
include("php/config.php");

if (isset($_POST['excluir'])) {
    $id = $_POST['id'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT senha FROM mensagens WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($senha, $res['senha'])) {
        $del = $conn->prepare("DELETE FROM mensagens WHERE id=?");
        $del->bind_param("i", $id);
        $del->execute();

        $_SESSION['msg'] = "Mensagem excluída!";
    } else {
        $_SESSION['msg'] = "Senha incorreta!";
    }

    header("Location:index.php");
    exit;
}

if (isset($_POST['salvar_edicao'])) {
    $id = $_POST['id'];
    $mensagem = $_POST['mensagem'];

    $stmt = $conn->prepare("UPDATE mensagens SET mensagem=? WHERE id=?");
    $stmt->bind_param("si", $mensagem, $id);
    $stmt->execute();

    $_SESSION['msg'] = "Mensagem atualizada!";
    header("Location:index.php");
    exit;
}

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO mensagens(nome,email,mensagem,senha,data_criacao) VALUES(?,?,?,?,NOW())");
    $stmt->bind_param("ssss", $nome, $email, $mensagem, $senha);
    $stmt->execute();

    $_SESSION['msg'] = "Mensagem enviada!";
    header("Location:index.php");
    exit;
}

$result = $conn->query("SELECT * FROM mensagens ORDER BY data_criacao DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mensagens</title>

<link rel="stylesheet" href="css/style.css">

<script>
function abrirModal(id, mensagem) {
    document.getElementById("modal").style.display = "flex";
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_mensagem").value = mensagem;
}

function fecharModal() {
    document.getElementById("modal").style.display = "none";
}

function verificarSenhaEditar(id, mensagem) {
    let senha = document.getElementById("senha_edit_" + id).value;

    fetch("verificar_senha.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id=" + id + "&senha=" + senha
    })
    .then(res => res.text())
    .then(resp => {
        if (resp === "ok") {
            abrirModal(id, mensagem);
        } else {
            alert("Senha incorreta!");
        }
    });
}
</script>

</head>
<body>
<div class="container">

<h2>Nova Mensagem</h2>
<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required placeholder="ex: nome@example.com"><br>
    Senha: <input type="password" name="senha" required><br>
    <textarea name="mensagem" required maxlength="250" placeholder="Insira uma mensagem (max 250)"></textarea><br>
    <button>Enviar</button>
</form>

<h2>Mensagens</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="message-card">
            <b><?= htmlspecialchars($row['nome']) ?></b><br>
            <small><?= htmlspecialchars($row['email']) ?> - <?= $row['data_criacao'] ?></small>
            <p><?= htmlspecialchars($row['mensagem']) ?></p>

            
            <form onsubmit="event.preventDefault(); verificarSenhaEditar(<?= $row['id'] ?>, '<?= htmlspecialchars($row['mensagem'], ENT_QUOTES) ?>')">
                <input type="password" id="senha_edit_<?= $row['id'] ?>" placeholder="Senha">
                <button>Editar</button>
            </form>

            
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="password" name="senha" placeholder="Senha">
                <button name="excluir">Excluir</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Nenhuma mensagem disponível.</p>
<?php endif; ?>

<div id="modal" class="modal">
    <div class="modal-content">
        <h3>Editar Mensagem</h3>
        <form method="POST" onsubmit="fecharModal()">
            <input type="hidden" name="id" id="edit_id">
            <textarea name="mensagem" id="edit_mensagem" required></textarea><br>
            <button name="salvar_edicao">Salvar</button>
            <button type="button" onclick="fecharModal()">Cancelar</button>
        </form>
    </div>
</div>

</div>
</body>
</html>