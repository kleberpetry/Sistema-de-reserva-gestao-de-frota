<?php
include('./conexao.php');
session_start();

$idUsuario = $_SESSION['id'];

$nome = $_POST['edit-profile-nome'];

preg_match_all('/\d+/', $_POST['edit-profile-telefone'], $matches);
$telefone = implode('', $matches[0]);

$senha = base64_encode($_POST['edit-profile-senha']);

$query = "UPDATE usuario SET nome = '$nome', telefone = '$telefone', senha = '$senha' WHERE id_usuario = $idUsuario";
mysqli_query($conn, $query);
mysqli_close($conn);

$_SESSION['nome'] = $nome;
$_SESSION['telefone'] = $telefone;

$referrer = $_SERVER['HTTP_REFERER'];

header("Location: $referrer");
exit;
?>