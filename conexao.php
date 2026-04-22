<?php
session_start();

/* ================= CONEXÃO ================= */
$con = new mysqli("localhost", "root", "", "cadastro");
if ($con->connect_error) {
    die("Erro de conexão: " . $con->connect_error);
}

/* ================= AÇÃO ================= */
$acao = $_POST['acao'] ?? '';

/* ================= LOGIN ================= */
if ($acao === "login") {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        echo "<script>alert('Preencha todos os campos'); window.location='conecte.php';</script>";
        exit;
    }

    $stmt = $con->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $usuario = $res->fetch_assoc();

        if ($senha === $usuario['senha']) {

            // 🔐 ADMIN PELO EMAIL
            if (substr($email, -11) === "@doe.com.br") {
                $_SESSION['isAdmin'] = 1;
            } else {
                $_SESSION['isAdmin'] = 0;
            }

            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];

            // 🔁 REDIRECIONAMENTO
            if ($_SESSION['isAdmin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit;

        } else {
            echo "<script>alert('Senha incorreta'); window.location='conecte.php';</script>";
            exit;
        }

    } else {
        echo "<script>alert('Email não encontrado'); window.location='conecte.php';</script>";
        exit;
    }
}

/* ================= CADASTRO ================= */
elseif ($acao === "cadastro") {

    $nome  = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        echo "<script>alert('Preencha todos os campos'); window.location='cadastro.html';</script>";
        exit;
    }

    $check = $con->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "<script>alert('Email já cadastrado'); window.location='cadastro.html';</script>";
        exit;
    }

    $stmt = $con->prepare(
        "INSERT INTO usuarios (nome, email, senha, isAdmin) VALUES (?, ?, ?, 0)"
    );
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso'); window.location='conecte.php';</script>";
        exit;
    } else {
        echo "Erro ao cadastrar";
        exit;
    }
}

$con->close();
?>
