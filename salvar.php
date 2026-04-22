<?php
session_start();

// Só admin pode salvar
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: login.php");
    exit;
}

require "conexaobanco.php";

// Recebe ID
$id = $_POST['id'];

// Recebe textos
$titulo1 = $_POST['titulo1'];
$texto1  = $_POST['texto1'];

$titulo2 = $_POST['titulo2'];
$texto2  = $_POST['texto2'];

$titulo3 = $_POST['titulo3'];
$texto3  = $_POST['texto3'];


// ----------------------------
//   PROCESSAR IMAGENS
// ----------------------------

function salvarImagem($inputName, $imagemAntiga) {

    // Se nenhuma imagem nova foi enviada, mantém a antiga
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] == 4) {
        return $imagemAntiga;
    }

    // Pasta onde vai salvar
    $pasta = "uploads/";

    // Se a pasta não existir, cria
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $nomeTemporario = $_FILES[$inputName]['tmp_name'];
    $nomeArquivo = time() . "_" . $_FILES[$inputName]['name'];

    // Move o arquivo
    if (move_uploaded_file($nomeTemporario, $pasta . $nomeArquivo)) {
        return $nomeArquivo; // retorna o nome novo
    }

    return $imagemAntiga; // Se falhar, usa a antiga
}


// Pegando imagens atuais do banco
$sqlImg = "SELECT imagem1, imagem2, imagem3 FROM site_conteudo WHERE id = $id";
$res = $con->query($sqlImg);
$imagens = $res->fetch_assoc();

// Processar cada imagem
$imagem1 = salvarImagem("imagem1", $imagens['imagem1']);
$imagem2 = salvarImagem("imagem2", $imagens['imagem2']);
$imagem3 = salvarImagem("imagem3", $imagens['imagem3']);


// ----------------------------
//   ATUALIZAR NO BANCO
// ----------------------------
$sql = "UPDATE site_conteudo 
        SET 
            titulo1 = '$titulo1',
            texto1  = '$texto1',
            imagem1 = '$imagem1',

            titulo2 = '$titulo2',
            texto2  = '$texto2',
            imagem2 = '$imagem2',

            titulo3 = '$titulo3',
            texto3  = '$texto3',
            imagem3 = '$imagem3'
        WHERE id = $id";

if ($con->query($sql)) {
    echo "<script>alert('Conteúdo atualizado com sucesso!'); window.location='admin.php';</script>";
} else {
    echo "<script>alert('Erro ao salvar!'); window.location='admin.php';</script>";
}

?>
<?php
/*
RESUMO DO CÓDIGO – ATUALIZAÇÃO DE CONTEÚDO DO SITE (ADMIN)

- Inicia a sessão.
- Verifica se o usuário é administrador (isAdmin = 1).
- Redireciona para login se não for admin.
- Conecta ao banco de dados via conexaobanco.php.
- Recebe o ID do conteúdo a ser atualizado.
- Recebe títulos e textos de três seções do site.
- Possui função para upload de imagens:
  - Mantém a imagem antiga se nenhuma nova for enviada.
  - Cria a pasta uploads caso não exista.
  - Salva a imagem com nome único usando timestamp.
- Busca no banco as imagens atuais.
- Processa cada imagem individualmente.
- Atualiza títulos, textos e imagens na tabela site_conteudo.
- Exibe alerta de sucesso ou erro e redireciona para admin.php.
*/
?>
