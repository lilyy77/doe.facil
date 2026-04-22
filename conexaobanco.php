<?php
$host = "localhost3307";
$usuario = "root";
$senha = "";
$banco = "cadastro";

$con = new mysqli($host, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Erro ao conectar no banco: " . $con->connect_error);
}

?>
<?php
/*
RESUMO DO CÓDIGO

- Define os dados de conexão com o banco de dados:
  host, usuário, senha e nome do banco.
- Cria a conexão usando mysqli.
- Verifica se ocorreu erro na conexão.
- Se houver erro, interrompe o sistema e exibe a mensagem.
- Se não houver erro, a conexão fica pronta para uso.
*/
?>
