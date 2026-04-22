<?php
session_start();
if (!isset($_SESSION['usuario_id'])) die("Faça login.");
$usuario_id = $_SESSION['usuario_id'];

if (!isset($_POST['carrinho_id'])) die("Item não informado.");
$carrinho_id = intval($_POST['carrinho_id']);

$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

$sql = "DELETE FROM carrinho WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $carrinho_id, $usuario_id);
$stmt->execute();

echo "ok";
?>
<?php
/*
RESUMO DO CÓDIGO – REMOVER ITEM DO CARRINHO

- Inicia a sessão do usuário.
- Verifica se o usuário está logado através da sessão.
- Obtém o ID do usuário logado.
- Verifica se o ID do item do carrinho foi enviado via POST.
- Conecta ao banco de dados "cadastro".
- Remove do banco o item do carrinho correspondente:
  - Apenas se o item pertencer ao usuário logado.
- Usa prepared statement para segurança (evita SQL Injection).
- Retorna "ok" como resposta para indicar sucesso.
- Arquivo ideal para ser chamado via AJAX.
*/
?>
