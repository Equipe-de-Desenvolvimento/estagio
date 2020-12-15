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
@$dataAtual = $paciente['0']->nascimento;
$date_time = new DateTime($dataAtual);
$diff = $date_time->diff(new DateTime($dataFuturo));
$teste = $diff->format('%Ya %mm %dd');
$internacao_id = $internacao[0]->internacao_id;
$dataatualizacao = $internacao[0]->data_saida; 
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

<?
$corpo = $internacao[0]->declaracao;
$corpo = str_replace("_paciente_", $internacao['0']->paciente, $corpo);
$corpo = str_replace("_sexo_", $internacao['0']->sexo, $corpo);
$corpo = str_replace("_nascimento_",date("d/m/Y",strtotime($internacao['0']->nascimento)), $corpo);
$corpo = str_replace("_convenio_", $internacao['0']->convenio, $corpo); 
$corpo = str_replace("_CPF_", $internacao['0']->cpf, $corpo); 
$corpo = str_replace("_data_", date( "d/m/Y",strtotime($internacao['0']->data)), $corpo); 
$corpo = str_replace("_procedimento_", $internacao['0']->procedimento, $corpo);
    
$corpo = str_replace("_assinatura_","<img src='".base_url() ."upload/1ASSINATURAS/".@$internacao['0']->operador_id.".jpg' >", $corpo);

?>
<p><?= $corpo; ?>

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
