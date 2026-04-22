<?php
include_once("nova_conexao.php");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

/* CURTIR */
if(isset($_POST['curtir'])){
    $post_id = $_POST['post_id'];
    $conn->query("INSERT INTO curtidas (post_id) VALUES ($post_id)");
}

/* COMENTAR */
if(isset($_POST['comentar'])){
    $post_id = $_POST['post_id'];
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $conn->query("INSERT INTO comentarios (post_id, comentario) VALUES ($post_id, '$comentario')");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doe.Fácil</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="barra.css">
  <link rel="stylesheet" href="sub.barra.css">

  <!-- jQuery e Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/e631c8149a.js"></script>

  <style>
    .container-principal {
      margin-top: 140px;
    }

    /* SIDEBAR DIREITA */
    .sidebar-direita {
      position: fixed;
      top: 0;
      right: -350px;
      width: 350px;
      height: 100%;
      background: #111;
      color: #fff;
      overflow-y: auto;
      transition: 0.4s;
      padding: 20px;
      z-index: 9999;
    }

    .sidebar-direita.open {
      right: 0;
    }

    /* SIDEBAR ESQUERDA */
    .sidebar-esquerda {
      position: fixed;
      top: 0;
      left: -350px;
      width: 350px;
      height: 100%;
      background: #111;
      color: #fff;
      overflow-y: auto;
      transition: 0.4s;
      padding: 20px;
      z-index: 9999;
    }

    .sidebar-esquerda.open {
      left: 0;
    }

    .close-btn,
    .close-btn-esquerda {
      color: white;
      font-size: 22px;
      cursor: pointer;
      position: absolute;
      top: 10px;
    }

    .close-btn {
      left: 10px;
    }

    .close-btn-esquerda {
      right: 10px;
    }

    .friend-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 8px 0;
      padding: 6px;
      background: #222;
      border-radius: 6px;
    }

    .friend-item img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 8px;
    }

    .edit-input {
      display: none;
      background: #222;
      border: 1px solid #444;
      color: white;
      width: 100%;
      margin-top: 5px;
      padding: 5px;
      border-radius: 5px;
    }

     

  </style>
</head>

<body>

  <header>
    <div class="container-fluid" id="nav-container">
      <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <a class="navbar-brand">
          <img id="logo" src="img/logo.jpg" width="40" height="40">
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbar-link">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbar-link">
          <div class="navbar-nav">
            <a href="home.php" class="nav-item nav-link">Home</a>
            <a href="rede.php" class="nav-item nav-link active">Mídia</a>
            <a href="viva.php" class="nav-item nav-link">Doe</a>
            <a href="conecte.php" class="nav-item nav-link">Conecte</a>
            <a href="contato.php" class="nav-item nav-link">Contato</a>
          </div>
        </div>
      </nav>
    </div>

    <div class="sub-navbar">
      <div class="left-links">
        <!-- agora cada link passa um tipo diferente -->
        <a href="javascript:void(0);" onclick="abrirEsquerda('sobre')">Sobre</a>
        <a href="javascript:void(0);" onclick="abrirEsquerda('ajuda')">Ajuda</a>
        <a href="javascript:void(0);" onclick="abrirEsquerda('doacoes')">Doações</a>
      </div>

      <div class="center-text">“Doe com amor, transforme vidas.”</div>

      <div class="right-links">
        <a href="javascript:void(0);" onclick="abrirDireita()"> <i class="fas fa-user"></i> </a>
        <a href="javascript:void(0);" onclick="abrirDireita()"> <i class="fas fa-bell"></i> </a>
        <a href="javascript:void(0);" onclick="abrirDireita()"> <i class="fas fa-comment-dots"></i> </a>
      </div>
    </div>
  </header>

  <!-- SIDEBAR ESQUERDA -->
  <div id="sidebarEsquerda" class="sidebar-esquerda">
    <span class="close-btn-esquerda" onclick="fecharEsquerda()">✕</span>

    <!-- título dinâmico -->
    <h3 id="esquerdaTitulo">Informações</h3>

    <!-- área de conteúdo dinâmico -->
    <div id="esquerdaConteudo">
      <p>Sem ideias ok</p>
    </div>
  </div>

  <!-- SIDEBAR DIREITA -->
  <div id="sidebarDireita" class="sidebar-direita">
    <span class="close-btn" onclick="fecharDireita()">✕</span>

    <h4 style="text-align:center;">Perfil</h4>
    <img id="userFoto" src="img/perfil.jpg" alt="Foto do usuário" style="width:100px;height:100px;border-radius:50%;display:block;margin:auto;">
    <input type="file" id="inputFoto" accept="image/*" style="display:none;">

    <p id="userNome" style="text-align:center; font-weight:bold;">Lilian Rodrigues</p>
    <input type="text" id="editNome" class="edit-input">

    <p id="userBio" style="text-align:center; font-size:14px;">Minha bio aqui...</p>
    <textarea id="editBio" class="edit-input" rows="3"></textarea>

    <button class="btn btn-light btn-sm btn-block mt-2" onclick="editarPerfil()">Editar</button>
    <button class="btn btn-success btn-sm btn-block mt-2 edit-input" id="btnSalvar" onclick="salvarPerfil()">Salvar</button>

    <hr>

    <h5>Amigos</h5>
    <div id="listaAmigos">
      <div class="friend-item">
        <div style="display:flex; align-items:center;">
          <img src="img/amigo1.jpg"><span>Maria Souza</span>
        </div>
        <i class="fas fa-trash" style="cursor:pointer;" onclick="removerAmigo(this)"></i>
      </div>

      <div class="friend-item">
        <div style="display:flex; align-items:center;">
          <img src="img/amigo2.jpg"><span>João Pedro</span>
        </div>
        <i class="fas fa-trash" style="cursor:pointer;" onclick="removerAmigo(this)"></i>
      </div>
    </div>
  </div> 
  
<div class="container container-principal" style="max-width:750px;">

<?php
$result = $conn->query("SELECT * FROM publicacoes ORDER BY id DESC");


while($row = $result->fetch_assoc()){
$post_id = $row['id'];


/* Contar curtidas */
$c = $conn->query("SELECT COUNT(*) as total FROM curtidas WHERE post_id=$post_id");
$totalCurtidas = $c->fetch_assoc()['total'];


/* Buscar comentários */
$comentarios = $conn->query("SELECT * FROM comentarios WHERE post_id=$post_id ORDER BY id DESC");
?>


<div class="card mb-4 shadow" style="border-radius:15px;">


    <div class="card-body">


        <h5><?php echo $row['titulo']; ?></h5>
        <p><?php echo $row['descricao']; ?></p>


        <?php if($row['midia']){ ?>
            <img src="<?php echo $row['midia']; ?>" style="width:100%; border-radius:10px;">
        <?php } ?>


        <hr>


        <!-- Curtidas -->
        <form method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <button name="curtir" class="btn btn-outline-danger btn-sm">
                ❤️ Curtir (<?php echo $totalCurtidas; ?>)
            </button>
        </form>


        <hr>


        <!-- Comentários -->
        <form method="POST" class="mb-3">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="text" name="comentario" class="form-control mb-2" placeholder="Escreva um comentário..." required>
            <button name="comentar" class="btn btn-primary btn-sm">Comentar</button>
        </form>


        <?php while($com = $comentarios->fetch_assoc()){ ?>
            <div style="background:#f0f0f0; padding:8px; border-radius:8px; margin-bottom:5px;">
                <?php echo $com['comentario']; ?>
                <br>
                <small style="color:gray;"><?php echo $com['data']; ?></small>
           </div>
        <?php } ?>


        <hr>


        <!-- Compartilhar -->
        <button onclick="compartilhar()" class="btn btn-outline-secondary btn-sm">
            🔗 Compartilhar
        </button>


    </div>


</div>


<?php } ?>


</div>

  <!-- ABAS -->
  <script>
    function abrirDireita() {
      document.getElementById("sidebarDireita").classList.add("open");
    }
    function fecharDireita() {
      document.getElementById("sidebarDireita").classList.remove("open");
    }

    function abrirEsquerda(tipo) {
      const titulo = document.getElementById('esquerdaTitulo');
      const conteudo = document.getElementById('esquerdaConteudo');

      if (tipo === 'sobre') {
        titulo.textContent = 'Sobre';
        conteudo.innerHTML = `
          <p><strong>Doe.Fácil</strong> é uma site para conectar quem quer ajudar com quem realmente precisa, e poder dar uma vida digna a essas pessoas.</p>
          <p>Missão: facilitar doações, promover transparência e dar oportunidades a moradores de rua.</p>
          <p><a href="sobre.html" style="color:#9ad;">Saiba mais( disponível em breve )</a></p>
        `;
      } else if (tipo === 'ajuda') {
        titulo.textContent = 'Ajuda';
        conteudo.innerHTML = `
          <p>Precisa de ajuda com o site? Veja as opções abaixo:</p>
          <ul>
            <li></li>
            <li>sem ideias mais ok</li>
            <li></li>
          </ul>
          <p><a href="contato.html" style="color:#9ad;">Fale com o suporte</a></p>
        `;
      } else if (tipo === 'doacoes') {
        titulo.textContent = 'Doações';
        conteudo.innerHTML = `
          <p>Aqui estão formas rápidas de doar:</p>
          <ol>
            <li>Doar itens (alimentos, roupas e muito mais )</li>
            <p><a href="viva.html" style="color:#9ad;">Veja Itens Emergencias</a></p>
            <li>Doar dinheiro por transferência ( Ainda não é possível no site ) </li>
            <li>Participar de campanhas locais</li>
          </ol>
          <p><a href="rede.html" style="color:#9ad;">Ver campanhas</a></p>
        `;
      } else {
        titulo.textContent = 'Informações';
        conteudo.innerHTML = `<p>Sem ideias ok</p>`;
      }

      // abre a sidebar esquerda
      document.getElementById("sidebarEsquerda").classList.add("open");
    }

    function fecharEsquerda() {
      document.getElementById("sidebarEsquerda").classList.remove("open");
    }

    function editarPerfil() {
      $("#userNome, #userBio").hide();
      $("#editNome, #editBio, #btnSalvar").show();
      $("#editNome").val($("#userNome").text());
      $("#editBio").val($("#userBio").text());
    }

    function salvarPerfil() {
      $("#userNome").text($("#editNome").val());
      $("#userBio").text($("#editBio").val());
      $("#userNome, #userBio").show();
      $("#editNome, #editBio, #btnSalvar").hide();
    }

    document.getElementById("userFoto").onclick = function () {
      document.getElementById("inputFoto").click();
    };

    document.getElementById("inputFoto").addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        document.getElementById("userFoto").src = URL.createObjectURL(file);
      }
    });

    function removerAmigo(icon) {
      icon.parentElement.remove();
    }

    function compartilhar(id) {
  const link = window.location.origin + "/rede.php?post=" + id;
  navigator.clipboard.writeText(link);
  alert("Link do post copiado!");
}
  </script>

</body>

</html>

<!--
RESUMO DA PÁGINA - REDE DOE.FÁCIL

- Página principal da área "Rede / Mídia" do site Doe.Fácil.
- Utiliza Bootstrap 4 para layout e responsividade.
- Possui duas sidebars:
  - Sidebar esquerda: informações dinâmicas (Sobre, Ajuda, Doações).
  - Sidebar direita: perfil do usuário e lista de amigos.
- Navbar fixa no topo com links principais do site.
- Sub-navbar com frases, atalhos e ícones de interação.
- Sidebar direita permite:
  - Visualizar e editar nome, bio e foto do perfil.
  - Listar e remover amigos (simulação).
- Sidebar esquerda muda conteúdo conforme o botão clicado.
- Usa JavaScript e jQuery para:
  - Abrir e fechar sidebars.
  - Trocar conteúdo dinamicamente.
  - Editar perfil em tempo real (front-end).
- Estrutura preparada para futuras integrações com backend (PHP / banco).
- Página focada em interação social e navegação do usuário.
-->
