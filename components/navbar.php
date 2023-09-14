<?php

$idUsuario = $_SESSION['id'];

$userData = [];

if ($idUsuario) {
  include(__DIR__ . '/../config/conexao.php');

  $query = "SELECT nome, telefone, senha FROM usuario WHERE id_usuario = $idUsuario";
  $result = mysqli_query($conn, $query);
  $userData = mysqli_fetch_assoc($result);

  $userData['senha'] = base64_decode($userData['senha']);
}

?>

<nav>
  <div class="nav-wrapper indigo">
    <ul class="quick-access">
      <li>
        <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large btn-floating btn-flat waves-effect waves-light nopadding"><i class="material-icons">menu</i></a>
      </li>
      <li>
        <a href="/reserva/veiculos.php" class="waves-effect waves-light btn-flat white-text reservar hide-on-med-and-down"><i class="material-icons right">local_shipping</i>Reservar</a>
      </li>
      <li>
        <a href="/reserva/tabela.php" class="waves-effect waves-light btn-flat white-text consultar hide-on-med-and-down"><i class="material-icons right">table_view</i>Consultar Disponibilidade</a>
      </li>
    </ul>
    <div class="usuario hide-on-small-only">
      <span class="nome"><?php echo $_SESSION['nome']; ?></span>
      <i class="material-icons account-circle">account_circle</i>
      <a data-target="nav-dropdown" class="dropdown-trigger">
        <i class="material-icons">keyboard_arrow_down</i>
      </a>
    </div>
  </div>
</nav>

<ul id="slide-out" class="sidenav">
  <div>
    <li class="center top-header">
      <i class="medium material-icons">directions_car</i>
      <i class="small material-icons sidenav-close hide-on-med-and-up">close</i>
    </li>
    <li class="hide-on-med-and-up"><div class="divider"></div></li>
    <li class="hide-on-med-and-up"><a class="subheader">Conta</a></li>
    <li class="hide-on-med-and-up">
      <div class="info-user valign-wrapper">
        <i class="material-icons">account_circle</i>
        <div>
          <span><?php echo $_SESSION['nome']?></span>
          <br>
          <span>
            <?php 
              $telefone = $_SESSION['telefone'];
              $telefone = substr_replace($telefone, '(', 0, 0);
              $telefone = substr_replace($telefone, ')', 3, 0);
              $telefone = substr_replace($telefone, ' ', 4, 0);
              $telefone = substr_replace($telefone, '-', 10, 0);
              echo $telefone;
            ?>
          </span>
        </div>
      </div>
    </li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Acesso Rápido</a></li>
    <li><a href="/reserva/veiculos.php" class="waves-effect"><i class="material-icons">add</i>Reservas</a></li>
    <li><a href="/reserva/tabela.php" class="waves-effect"><i class="material-icons">table_view</i>Consultar Disponibilidade</a></li>
    <li><a href="/reserva/historico.php" class="waves-effect"><i class="material-icons">history</i>Histórico</a></li>
    <?php if ($_SESSION['tipo'] === "A") { ?>
      <li><div class="divider"></div></li>
      <li><a class="subheader">Admin</a></li>
      <li><a href="/reserva/admin/usuarios.php" class="waves-effect"><i class="material-icons">person</i>Usuários</a></li>
      <li><a href="/reserva/admin/veiculos.php" class="waves-effect"><i class="material-icons">local_shipping</i>Veículos</a></li>
      <li><a href="/reserva/admin/departamentos.php" class="waves-effect"><i class="material-icons">business</i>Departamentos</a></li>
    <?php } ?>
  </div>
  <div class="hide-on-med-and-up">
    <li><a class="modal-trigger" href="#edit-profile-modal"><i class="material-icons">account_circle</i>Editar perfil</a></li>
    <li><a href="/reserva/config/sair.php"><i class="material-icons">logout</i>Sair</a></li>
  </div>
</ul>

<!-- Dropdown -->
<ul id="nav-dropdown" class="dropdown-content">
  <li>
    <a class="modal-trigger" href="#edit-profile-modal"><i class="material-icons left">account_circle</i>Editar perfil</a>
  </li>
  <li>
    <a href="/reserva/config/sair.php"><i class="material-icons left">logout</i>Sair</a>
  </li>
</ul>

<!-- Modal perfil -->
<div id="edit-profile-modal" class="modal">
  <div class="modal-content">
    <form action="/tcc/config/edit-perfil.php" method="POST" class="container">
      <h3 class="center">Editar perfil</h3>
      <p class="center"><i class="material-icons medium">account_circle</i></p>
      <div>
        <div class="input-field">
          <input id="edit-profile-nome" type="text" name="edit-profile-nome" value="<?= empty($userData['nome']) ? '' : $userData['nome'] ?>" required>
          <label for="edit-profile-nome">Nome</label>
        </div>
        <div class="input-field">
          <input id="edit-profile-telefone" type="text" name="edit-profile-telefone" data-length="11" minlength="11" maxlength="11" autocomplete="off" value="<?= empty($userData['telefone']) ? '' : $userData['telefone'] ?>" required>
          <label for="edit-profile-telefone">Telefone</label>
          <span id="span-telefone" class="helper-text"></span>
        </div>
        <div class="input-field">
          <input id="edit-profile-senha" type="password" name="edit-profile-senha" value="<?= empty($userData['senha']) ? '' : $userData['senha'] ?>" required>
          <label class="active" for="edit-profile-senha">Senha</label>
        </div>
      </div>
      <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
        <button type="button" class="modal-close btn waves-effect red darken-1">Cancelar
          <i class="material-icons left">cancel</i>
        </button>
        <button type="submit" id="edit-profile-btn-salvar" class="btn waves-effect" type="submit" >
          <span>Salvar</span>
          <i class="material-icons right">send</i>
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
  $('.sidenav').sidenav();
  $('.dropdown-trigger').dropdown();
  $('#edit-profile-telefone').mask("(00) 00000-0000");
});
</script>