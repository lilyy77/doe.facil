<?php
session_start();
if (!isset($_SESSION['usuario_id'])) die("Faça login.");
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Requisição inválida.");

// Recebe os dados do formulário
$rua = trim($_POST['rua']);
$numero = trim($_POST['numero']);
$bairro = trim($_POST['bairro']);
$cidade = trim($_POST['cidade']);
$cep = trim($_POST['cep']);

// Validação básica (pode melhorar)
if (!$rua || !$numero || !$bairro || !$cidade || !$cep) {
    die("Todos os campos são obrigatórios.");
}

$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

// Atualiza os dados do usuário
$sql = "UPDATE usuarios SET rua = ?, numero = ?, bairro = ?, cidade = ?, cep = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $rua, $numero, $bairro, $cidade, $cep, $usuario_id);

if($stmt->execute()){
    // Redireciona de volta para o carrinho com sucesso
    header("Location: carrinho.php?status=ok");
    exit;
} else {
    die("Erro ao atualizar endereço: " . $stmt->error);
}
?>
<?php
/*
RESUMO DO CÓDIGO (atualizar_endereco.php)

- Inicia a sessão e verifica se o usuário está logado.
- Garante que a requisição seja do tipo POST.
- Recebe os dados de endereço enviados pelo formulário.
- Valida se todos os campos obrigatórios foram preenchidos.
- Conecta ao banco de dados "cadastro".
- Atualiza o endereço do usuário logado na tabela usuarios.
- Em caso de sucesso, redireciona para o carrinho com status=ok.
- Em caso de erro, exibe a mensagem correspondente.
*/
?>
