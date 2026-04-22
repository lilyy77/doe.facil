<?php
session_start();
if (!isset($_SESSION['usuario_id'])) die("Faça login.");
$usuario_id = $_SESSION['usuario_id'];

if (!isset($_POST['produto_id'])) die("Produto não informado.");
$produto_id = intval($_POST['produto_id']);

$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

// Inserir **sempre um novo item**, mesmo que seja o mesmo produto
$sql_insert = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, 1)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("ii", $usuario_id, $produto_id);
$stmt->execute();

echo "ok";


/*
RESUMO DO CÓDIGO

- Inicia a sessão do usuário.
- Verifica se o usuário está logado (usuario_id na sessão).
- Verifica se o produto foi enviado via POST.
- Conecta ao banco de dados MySQL (banco: cadastro).
- Insere o produto no carrinho do usuário.
- Cada inserção cria um novo item, mesmo que seja o mesmo produto.
- Define a quantidade inicial como 1.
- Retorna "ok" após inserir com sucesso.
*/

?>
