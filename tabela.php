<?php
session_start();

if ($_SESSION["status"] != "ok") {
    header('location: ./index.php');
}

$searchDate = isset($_GET['data']) ? $_GET['data'] . ' 00:00:00' : '';

include "./config/conexao.php";
date_default_timezone_set('America/Sao_Paulo');

$currentDate = date("Y-m-d");

$sql = "SELECT id_reserva, data_saida, data_retorno, nome, modelo, placa, destino FROM reserva JOIN usuario ON reserva.fk_id_usuario = usuario.id_usuario JOIN veiculo ON reserva.fk_id_veiculo = veiculo.id_veiculo";
$res = mysqli_query($conn, $sql);
$reservas = mysqli_fetch_all($res, MYSQLI_ASSOC);
mysqli_free_result($res);

$today = empty($searchDate)
    ? new DateTime('today', new DateTimeZone('America/Sao_Paulo'))
    : DateTime::createFromFormat('Y-m-d H:i:s', $searchDate, new DateTimeZone('America/Sao_Paulo'));

$startTime = clone $today;
$endTime = clone $today;
$startTime->setTime(0, 1, 0);
$endTime->setTime(23, 59, 0);

$reservasFiltradas = [];

foreach ($reservas as $reserva) {
    $reservationStart = new DateTime($reserva['data_saida'], new DateTimeZone('America/Sao_Paulo'));
    $reservationEnd = new DateTime($reserva['data_retorno'], new DateTimeZone('America/Sao_Paulo'));

    if (
        ($startTime >= $reservationStart && $startTime < $reservationEnd) ||
        ($endTime > $reservationStart && $endTime <= $reservationEnd) ||
        ($startTime <= $reservationStart && $endTime >= $reservationEnd)
    ) {
        $reservasFiltradas[] = $reserva;
    }
}

$reservas = $reservasFiltradas;

$sql = "SELECT id_veiculo, modelo, placa FROM veiculo WHERE status_veiculo = 'A'";
$res = mysqli_query($conn, $sql);
$veiculos = mysqli_fetch_all($res, MYSQLI_ASSOC);

mysqli_free_result($res);
mysqli_close($conn);

function isReserved($time, $placa) {
    global $reservas;
    global $searchDate;

    $time_to_check = empty($searchDate)
        ? new DateTime($time, new DateTimeZone('America/Sao_Paulo'))
        : DateTime::createFromFormat('Y-m-d H:i', $_GET['data'] . $time, new DateTimeZone('America/Sao_Paulo'));

    foreach ($reservas as $reserva) {
        if ($reserva['placa'] === $placa) {
            $data_saida = new DateTime($reserva['data_saida'], new DateTimeZone('America/Sao_Paulo'));
            $data_retorno = new DateTime($reserva['data_retorno'], new DateTimeZone('America/Sao_Paulo'));
            
            if ($time_to_check >= $data_saida && $time_to_check <= $data_retorno) {
                return true;
            }
        }
    }

    return false;
}


?>

<!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <?php include "./components/head.php" ?>
        <link rel="stylesheet" href="./styles/navbar.css">
        <title>Tabela</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/reserva/styles/tabela.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </head>
    <body>
        <?php include "./components/navbar.php" ?>
        <table class="scroll">
            <thead>
                <tr>
                    <td>
                        <input id="date-selector" type="text" class="datepicker datetime-input" placeholder="<?= $today->format('Y-m-d') ?>" autocomplete="off" value="" />
                    </td>
                    <?php foreach ($veiculos as $veiculo) { ?>
                        <td><?= $veiculo['modelo'] ?> <?= $veiculo['placa'] ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <?php
                $timeSlots = array();
                $currentTime = clone $today;
                for ($i = 0; $i < 96; $i++) {
                    $timeSlots[] = $currentTime->format('H:i');
                    $currentTime->modify('+15 minutes');
                }
            ?>
            <tbody>
                <?php foreach ($timeSlots as $time) { ?>
                    <tr>
                        <td><?= $time ?></td>
                        <?php foreach ($veiculos as $veiculo) { ?>
                            <td style="background-color: <?= isReserved($time, $veiculo['placa']) ? '#3f51b5' : 'unset' ?>;"></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <script src="./scripts/tabela.js"></script>
    </body>
</html>