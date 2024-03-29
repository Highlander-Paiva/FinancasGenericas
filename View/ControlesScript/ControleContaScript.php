<?php

include "../../ScriptLogin.php";
/* comando */
$comando = $_REQUEST['comando'];
$controleConta = new ControleConta($conexao);


if ($comando == "inserir") {
    $idConta = $controleConta->insereConta($_POST);
    header('Location: ../VerConta.php?id=' . $idConta);
} else if ($comando == "usuarioPagando") {
    $idRemetente = $_POST['idPagador'];
    $idDestinatario = $_POST['idRecebidor'];
    $idConta = $_POST['idConta'];
    $pagamento = $_POST['valorPago'];
    $controleConta->atualizaConta($idRemetente, $idDestinatario, $pagamento, $idConta);
    header('Location: ../VerConta.php?id=' . $idConta);
} else if ($comando == "usuarioPagandoMuitasContas") {
    $idRemetente = $_POST['idUsuarioPagando'];
    $idDestinatario = $_POST['idUsuarioRecebendo'];
    $pagamentos = $_POST['pagar'];
    $idContas = $_POST['idConta'];
    $idRequerimento = $_POST['idRequerimento'];
    $controleConta->atualizaDiversasContas($idRemetente, $idDestinatario, $pagamentos, $idContas, $idRequerimento);
    header('Location: ../Pagamento.php');
} else if ($comando == "alterarConta") {
    $idConta = $controleConta->alteraConta($_POST);
    header('Location: ../VerConta.php?id=' . $idConta);
} else if ($comando == "usuarioRequerimento") {
    $idDestinatario = $_POST['idDestinatario'];
    $idRemetente = $_POST['idRemetente'];
    $valor = $_POST['valorRequerimento'];
    $requerimentoControle = new ControleRequerimento($conexao);
    $requerimentoControle->insereRequerimento($idRemetente, $idDestinatario, $valor);
    header('Location: ../Perfil.php');
} else if ($comando == 'requerimentoRejeitado') {
    $idRequerimento = $_REQUEST['idRequerimento'];
    $requerimentoControle = new ControleRequerimento($conexao);
    $requerimentoControle->rejeitaRequerimento($idRequerimento);
    header('Location: ../Perfil.php');
}
?>


