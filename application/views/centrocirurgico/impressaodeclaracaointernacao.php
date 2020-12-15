<?
$sexo = $internacao[0]->sexo;
if ($sexo == "M") {
    $sexopaciente = "Masculino";
} elseif ($sexo == "F") {
    $sexopaciente = "Feminino";
} else {
    $sexopaciente = "Outro";
}
$dataFuturo = date("Y-m-d");
$dataAtual = $paciente['0']->nascimento;
$date_time = new DateTime($dataAtual);
$diff = $date_time->diff(new DateTime($dataFuturo));
$teste = $diff->format('%Ya %mm %dd');
$internacao_id = $internacao[0]->internacao_id;
$dataatualizacao = $internacao[0]->data_autorizacao;
    
$data = $internacao[0]->data;
$MES = substr($internacao[0]->data, 5, 2);

switch ($MES) {
    case 1 : $MES = 'Janeiro';
        break;
    case 2 : $MES = 'Fevereiro';
        break;
    case 3 : $MES = 'Mar&ccedil;o';
        break;
    case 4 : $MES = 'Abril';
        break;
    case 5 : $MES = 'Maio';
        break;
    case 6 : $MES = 'Junho';
        break;
    case 7 : $MES = 'Julho';
        break;
    case 8 : $MES = 'Agosto';
        break;
    case 9 : $MES = 'Setembro';
        break;
    case 10 : $MES = 'Outubro';
        break;
    case 11 : $MES = 'Novembro';
        break;
    case 12 : $MES = 'Dezembro';
        break;
        break;
}
?>

<BODY>
<p><center><u><b><?= $empresa[0]->razao_social; ?></b></u></center></p>
<br>
<br>
<br>
<p><center><font size = 4><b>DECLARA&Ccedil;&Atilde;O</b></font></center></p>
<br>
<br>
<p><?= ($internacao[0]->declaracao); ?>
    <br>
    <br>
    <br>
    <br>
<p><?= $internacao[0]->municipio ?>, <?= substr($internacao[0]->data, 8, 2) . " de " . $MES . " de " . substr($internacao[0]->data, 0, 4); ?></p>
<br>
<br>
<br>
<h4><center>___________________________________________</center></h4>
<h4><center><?= $empresa[0]->razao_social; ?></center></h4>

</BODY>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
