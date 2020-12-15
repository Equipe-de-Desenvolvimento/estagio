<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />


<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

<head>
    <title>Laudo Consulta</title>
</head>
<div> 
    <?
    $dataFuturo = date("Y-m-d");
    $dataAtual = @$obj->_nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');
//    var_dump(isset($obj->_peso), isset($obj->_altura)); die;
    if (isset($obj->_peso)) {
        $peso = @$obj->_peso;
    } else {
        $peso = @$laudo_peso[0]->peso;
    }
    if (isset($obj->_altura)) {
        $altura = @$obj->_altura;
    } else {
        $altura = @$laudo_peso[0]->altura;
    }
    if (isset($obj->_pulso)) {
        $pulso = @$obj->_pulso;
    } else {
        $pulso = @$laudo_peso[0]->pulso;
    }
    if (isset($obj->_temperatura)) {
        $temperatura = @$obj->_temperatura;
    } else {
        $temperatura = @$laudo_peso[0]->temperatura;
    }
    if (isset($obj->_pressao_arterial)) {
        $pressao_arterial = @$obj->_pressao_arterial;
    } else {
        $pressao_arterial = @$laudo_peso[0]->pressao_arterial;
    }
    if (isset($obj->_f_respiratoria)) {
        $f_respiratoria = @$obj->_f_respiratoria;
    } else {
        $f_respiratoria = @$laudo_peso[0]->f_respiratoria;
    }
    if (isset($obj->_spo2)) {
        $spo2 = @$obj->_spo2;
    } else {
        $spo2 = @$laudo_peso[0]->spo2;
    }


    if ($obj->_hipertensao != '') {
        $hipertensao = @$obj->_hipertensao;
    } else {
        $hipertensao = @$laudo_peso[0]->hipertensao;
    }

    if ($obj->_diabetes != '') {
        $diabetes = @$obj->_diabetes;
    } else {
        $diabetes = @$laudo_peso[0]->diabetes;
    }

    if ($obj->_medicacao != '') {
        $medicacao = @$obj->_medicacao;
    } else {
        $medicacao = @$laudo_peso[0]->medicacao;
    }


    if (@$empresapermissao[0]->campos_atendimentomed != '') {
        $opc_telatendimento = json_decode(@$empresapermissao[0]->campos_atendimentomed);
    } else {
        $opc_telatendimento = array();
    }
//    var_dump($empresapermissao[0]->dados_atendimentomed); die;
    if (@$empresapermissao[0]->dados_atendimentomed != '') {
        $opc_dadospaciente = json_decode(@$empresapermissao[0]->dados_atendimentomed);
//        echo "<pre>";
//        print_r( $opc_dadospaciente );
    } else {
        $opc_dadospaciente = array();
    }



    $operador_id = $this->session->userdata('operador_id');
    if (@$obj->_status == 'FINALIZADO' && $laudo_sigiloso == 't' && $operador_id != 1) {
        $readonly = 1;
    } else {
        $readonly = 0;
    }
    if (@$obj->_status == 'FINALIZADO' && $laudo_sigiloso == 't') {
        $adendo = true;
        $readonlyadendo = 0;
    } else {
        $adendo = false;
          $readonlyadendo = 1;
    }
    if (@$obj->_estado_civil == 1):$estado_civil = 'Solteiro';
    endif;
    if (@$obj->_estado_civil == 2):$estado_civil = 'Casado';
    endif;
    if (@$obj->_estado_civil == 3):$estado_civil = 'Divorciado';
    endif;
    if (@$obj->_estado_civil == 4):$estado_civil = 'Viuvo';
    endif;
    if (@$obj->_estado_civil == 5):$estado_civil = 'Outros';
    endif;
//    var_dump($laudo_sigiloso); die;
    ?>
<style>
.row{
    display: flex;
}
.col{
    flex: 1;
    align-self: center;
}
.top{
  position: absolute;
  top: 100px;
  right: 0;
  width: 500px;
  /* height: 200px; */
}

.top2{
  position: absolute;
  top: 40px;
  right: 0;
  width: 500px;
  /* height: 200px; */
}

.nav-container{ background: url('images/nav_bg.jpg') repeat-x 0 0;}
    .f-nav{ z-index: 9999; position: fixed; left: 0; top: 0; width: 100%;} /* this make our menu fixed top */
    
.nav { height: 42px;}
    .nav ul { list-style: none; }
    .nav ul li{float: left; margin-top: 6px; padding: 6px; border-right: 1px solid #ACACAC;}
    .nav ul li:first-child{ padding-left: 0;}
    .nav ul li a { }
    .nav ul li a:hover{ text-decoration: underline;
    }

.fundo_cinza{
    background: rgb(236, 232, 232);
}

.btn {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 135px;
  height: 35px;
  background-color: #0066b8;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_medio{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 260px;
  height: 35px;
  background-color: #0066b8;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_pequeno {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 60px;
  height: 5px;
  background-color: #0066b8;
  padding: 2px 10px;
  border: none;
  color: white;
}
.btn_renomear{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 100px;
  height: 35px;
  background-color: #DC143C;
  padding: 4px 20px;
  border: none;
  color: white; 
}

.btn_renomear_2{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 200px;
  height: 35px;
  background-color: #DC143C;
  padding: 4px 20px;
  border: none;
  color: white; 
}
.btn_verde {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 135px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_verde_2{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 220px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_verde_3{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 260px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_verde_4{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 260px;
  height: 35px;
  background-color: #DAA520;
  padding: 9px 50px;
  border: none;
  color: white;
}
.btn_disabled{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 260px;
  height: 35px;
  background-color: #808080;
  padding: 9px 50px;
  border: none;
  color: white;
}

.btn_verde_grande{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 160px;
  height: 35px;
  background-color: #228B22;
  padding: 9px 50px;
  border: none;
  color: white;
}

.btn_grande{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 420px;
  height: 35px;
  background-color: #0066b8;
  padding: 9px 50px;
  border: none;
  color: white;
}

.btn_rosa {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13;
  width: 135px;
  height: 35px;
  background-color: #DC143C;
  padding: 9px 50px;
  border: none;
  color: white;
}

.area-upload{
	box-shadow: 0 5px 20px rgba(0,0,0,.2);
	/* margin: 20px auto; */
	padding: 10px;
	box-sizing: border-box;
	
	width: 100%;
	max-width: 500px;
    height: 200px;
	position: relative;
}

.area-upload label.label-upload{
	border: 2px dashed #0d8acd;
	min-height: 30px;
	text-align: center;
	width: 100%;
	
	display: flex;
	justify-content: center;
	flex-direction: column;
	color: #0d8acd;
	position: relative;
	
	-webkit-transition: .3s all;
	-moz-transition: .3s all;
	-o-transition: .3s all;
	transition: .3s all;
}

.area-upload label.label-upload.highlight{
	background-color: #fffdaa;
}

.area-upload label.label-upload *{
	pointer-events: none;
}

.area-upload input{
	position: absolute;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	-webkit-appearance: none;
	opacity: 0;
}

.area-upload .lista-uploads {
	
}

.area-upload .lista-uploads .barra{
	background-color: #e6e6e6;
	margin: 5px 0;
	width: 100%;
	position: relative;
}

.area-upload .lista-uploads .barra .fill{
	background-color: #a1f7ff;
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	min-width: 0;
	
	-webkit-transition: .2s all;
	-moz-transition: .2s all;
	-o-transition: .2s all;
	transition: .2s all;
}

.area-upload .lista-uploads .barra.complete .fill{
	background-color: #bcffdf;
}

.area-upload .lista-uploads .barra .text{
	z-index: 10;
	text-align: center;
	/* padding: 3px 5px; */
	box-sizing: border-box;
	position: relative;
	width: 100%;
	color: black;
	font-size: 11px;
}
.area-upload .lista-uploads .barra .text a{
	color: black;
	font-weight: bold;
}

.area-upload .lista-uploads .barra.error .fill{	
	background-color: #c02929;
	color: white;
	min-width: 100%;
}

.area-upload .lista-uploads .barra.error .text{
	color: white;
}





.area-upload2{
	box-shadow: 0 5px 20px rgba(0,0,0,.2);
	/* margin: 20px auto; */
	padding: 10px;
	box-sizing: border-box;
	
	width: 100%;
	max-width: 700px;
    height: auto;
	position: relative;
}

.area-upload2 .lista-uploads {
	
}

.area-upload2 .lista-uploads .barra{
	background-color: #e6e6e6;
	margin: 5px 0;
	width: 100%;
	position: relative;
}

.area-upload2 .lista-uploads .barra .fill{
	background-color: #a1f7ff;
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	min-width: 0;
	
	-webkit-transition: .2s all;
	-moz-transition: .2s all;
	-o-transition: .2s all;
	transition: .2s all;
}

.area-upload2 .lista-uploads .barra.complete .fill{
	background-color: #bcffdf;
}

.area-upload2 .lista-uploads .barra .text{
	z-index: 10;
	text-align: center;
	/* padding: 3px 5px; */
	box-sizing: border-box;
	position: relative;
	width: 100%;
	color: black;
	font-size: 11px;
}
.area-upload2 .lista-uploads .barra .text a{
	color: black;
	font-weight: bold;
}

.area-upload2 .lista-uploads .barra.error .fill{	
	background-color: #FF0000;
	color: white;
	min-width: 100%;
}

.area-upload2 .lista-uploads .barra.error .text{
	color: white;
}

.area-upload2 .lista-uploads .barra.rename .fill{	
	background-color: #0066b8;
	color: white;
	min-width: 100%;
}

.area-upload2 .lista-uploads .barra.rename .text{
	color: white;
}

.edicao_rapida {
  position: relative;
  width: 600px;
  box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.2);
  text-align: left;
  color: #626262;
  font-size: 14px;
  padding: 20px;
  background-color: #F0E68C;
}
.edicao_rapida *[contentEditable="true"]:hover {
  outline: 1px solid #2276d2;
}
.destaque{
    color: red;
    font-size: 30px;
    font-weight: bold;
    text-decoration: underline red;
}
.fieldset_maior{
    /* width: auto; */
    width: 400px;
    height: auto;
    /* min-height: 250px; */
}
.transparente {
    opacity: 0.4;
} 


/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 10000; /* Sit on top */
  padding-top: 60px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 80%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  /* background-color: #0066b8; */
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  /* background-color: #0066b8; */
  color: white;
}


table.no-spacing {
  border-spacing:0; /* Removes the cell spacing via CSS */
  border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
}
</style>


<div class="nav-container">
    <div class="nav">
    <fieldset class="fundo_cinza">
                    <!-- <legend>Dados</legend> -->

                    <table border="0" class="no-spacing" cellspacing="0" style=" width: 100%;"> 
                        <tr>
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                <td colspan="5" ><b>Paciente:</b><?= @$obj->_nome ?></td>
                            <? } ?>
                            <? if (in_array('cpf', $opc_dadospaciente)) { ?>
                                <td colspan="1" ><b>CPF:</b> <?= @$obj->_cpf ?></td>
                            <? } ?>
                            <? if (in_array('nascimento', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Nascimento:</b><?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?></td>
                            <? } ?>
                            <? if (in_array('idade', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Idade: </b><?= $teste ?></td>
                            <? } ?>
                            <? if (in_array('sexo', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Sexo: </b><?= @$obj->_sexo ?></td>
                            <? } ?>


                            <td rowspan="6"><img src="<?= base_url() ?>upload/webcam/pacientes/<?= $paciente_id ?>.jpg" width="100" height="100" /></td>
                        </tr>

                        <tr>
                            <? if (in_array('endereco', $opc_dadospaciente)) { ?>
                                <td colspan="6"><b>Endereco:</b> <?= @$obj->_logradouro ?>, <?= @$obj->_numero . ' ' . @$obj->_bairro ?> <?= (in_array('cidade', $opc_dadospaciente)) ? ' , ' . @$obj->_cidade : ''; ?> - <?= @$obj->_uf ?></td>
                            <? } ?>
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                    <td colspan="1" ><b>CEP:</b> <?= @$obj->_cep ?></td>
                            <? } ?>
                            <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Whatsapp:</b> <?= @$obj->_whatsapp ?></td>
                            <? } ?>
                            <? if (in_array('indicacao', $opc_dadospaciente)) { ?>
                                <td colspan="3"><b>Indicaçao: </b><?= @$obj->_indicacao ?></td>
                            <? } ?> 
                        </tr>

                        <tr>
                        
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                    <td colspan="2" ><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/alteraremail1/<?= $paciente_id ?>', '_black', 'height=230, width=600, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-230)/2);">
                                                    <b>Email:</b><?= @$obj->_email ?>
                                                    </a></td>
                            <? } ?>

                            <? if (in_array('convenio', $opc_dadospaciente)) { ?>
                                <? if(@$obj->_outro_convenio != ''){?>
                                <td colspan="2"><b>Convenio:</b><?= @$obj->_outro_convenio; ?></td>
                                <? } else {?>
                                <td colspan="2"><b>Convenio:</b><?= @$obj->_convenio_paciente; ?></td>
                                <? } ?>
                            <? } ?>
                            <? if (in_array('exame', $opc_dadospaciente)) { ?>
                                <td colspan="3" title="<?= @$obj->_procedimento ?>"><b>Exame:</b> <?= substr(@$obj->_procedimento, 0, 25) ?></td>
                            <? } ?>   
                            <? if (in_array('ocupacao', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Ocupação: </b><?= @$obj->_profissao_cbo ?> </td>
                            <? } ?>

                            <? if (in_array('estadocivil', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Estado Civíl: </b><?= @$estado_civil ?> </td>
                            <? } ?>

                        </tr>

                        <? if(in_array('nome_pai', $opc_dadospaciente) || in_array('nome_mae', $opc_dadospaciente)){?>
                        <tr>
                            <? if (in_array('nome_pai', $opc_dadospaciente) && (@$obj->_nome_pai != '' && @$obj->_cpf_pai != '')) { ?>
                                <td colspan="3" ><b>Pai:</b> <?= @$obj->_nome_pai ?></td>
                            <? } ?>
                            <? if (in_array('cpf_pai', $opc_dadospaciente) && (@$obj->_nome_pai != '' && @$obj->_cpf_pai != '')) { ?>
                                <td colspan="2" ><b>CPF Pai: </b><?= @$obj->_cpf_pai ?></td>
                            <? } ?>

                            <? if (in_array('nome_mae', $opc_dadospaciente) && (@$obj->_nome_mae != '' && @$obj->_cpf_mae != '')) { ?>
                                <td colspan="3"><b>Mãe:</b> <?= @$obj->_nome_mae ?></td>
                            <? } ?>
                            <? if (in_array('cpf_mae', $opc_dadospaciente) && (@$obj->_nome_mae != '' && @$obj->_cpf_mae != '')) { ?>
                                <td colspan="2" ><b>CPF Mãe: </b><?= @$obj->_cpf_mae ?></td>
                            <? } ?>
                            <? if (in_array('indicacao', $opc_dadospaciente)) { ?>
                                <!-- <td colspan="3"><b>Indicaçao: </b><? @$obj->_indicacao ?></td> -->
                            <? } ?> 

                        </tr>
                            <? } ?>

                    </table>

                    <table>
                        <tr>
                            <td>
                                <? if (in_array('preencherform', $opc_telatendimento)) { ?>
                                    <div class="bt_link_new">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherformulario/<?= $ambulatorio_laudo_id ?>');" >
                                            Formulário</a></div>
                                <? } ?>
                            </td> 
                        </tr>                        
                    </table>
<?//die;?>


                </fieldset>
        <div class="clear"></div>
    </div>
</div>

<script>

jQuery("document").ready(function($){
    
    var nav = $('.nav-container');
    
    $(window).scroll(function () {
        if ($(this).scrollTop() > 136) {
            nav.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
        }
    });

});
</script>

<?
$informacao_exemplo = "Exemplo:  
Paciente com alergia a Dipirona...  
Paciente com Diabete...
Paciente com escoliose...";
?>
<br><br><br>

    <div >
        <form name="form_laudo" id="form_laudo" enctype="multipart/form-data" action="<?= base_url() ?>ambulatorio/laudo/gravaranaminese/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>/<?= $paciente_id ?>/<?= $procedimento_tuss_id ?>" method="POST">
            <div >
                <input type="hidden" name="guia_id" id="guia_id" class="texto01"  value="<?= @$obj->_guia_id; ?>"/>
                <fieldset hidden>
                    <legend>Dados</legend>

                    <table style="border-collapse: collapse; width: 100%;"> 
                        <tr >
                            <? if (in_array('paciente', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Paciente:</b><?= @$obj->_nome ?></td>
                            <? } ?>
                            <? if (in_array('cpf', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>CPF:</b> <?= @$obj->_cpf ?></td>
                            <? } ?>
                            <? if (in_array('nascimento', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Nascimento:</b><?= substr(@$obj->_nascimento, 8, 2) . "/" . substr(@$obj->_nascimento, 5, 2) . "/" . substr(@$obj->_nascimento, 0, 4); ?></td>
                            <? } ?>
                            <? if (in_array('idade', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Idade: </b><?= $teste ?></td>
                            <? } ?>
                            <? if (in_array('sexo', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Sexo: </b><?= @$obj->_sexo ?></td>
                            <? } ?>


                            <td rowspan="7"><img src="<?= base_url() ?>upload/webcam/pacientes/<?= $paciente_id ?>.jpg" width="100" height="120" /></td>
                        </tr>
                        <tr>
                            <? if (in_array('convenio', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Convenio:</b><?= @$obj->_convenio; ?></td>
                            <? } ?>
                            <? if (in_array('telefone', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Telefone:</b> <?= @$obj->_telefone ?></td>
                            <? } ?> 
                            <? if (in_array('celular', $opc_dadospaciente)) { ?>
                                <td colspan="2"><b>Telefone 2: </b><?= @$obj->_celular ?></td>
                            <? } ?> 
                            <? if (in_array('ocupacao', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Ocupação: </b><?= @$obj->_profissao_cbo ?> </td>
                            <? } ?>

                            <? if (in_array('estadocivil', $opc_dadospaciente)) { ?>
                                <td colspan="2" ><b>Estado Civíl: </b><?= @$estado_civil ?> </td>
                            <? } ?>

                        </tr>
                        <tr>
                            <? if (in_array('endereco', $opc_dadospaciente)) { ?>
                                <td colspan="12"><b>Endereco:</b> <?= @$obj->_logradouro ?>, <?= @$obj->_numero . ' ' . @$obj->_bairro ?> <?= (in_array('cidade', $opc_dadospaciente)) ? ' , ' . @$obj->_cidade : ''; ?> - <?= @$obj->_uf ?></td>
                            <? } ?>
                        </tr>

                        <tr>

                            <? if (in_array('sala', $opc_dadospaciente)) { ?>
                                <td colspan="3" >
                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/alterarsalalaudo/<?= $ambulatorio_laudo_id; ?>/<?= $exame_id; ?>/<?= $paciente_id; ?>/<?= $procedimento_tuss_id; ?>/<?= @$obj->_sala_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,\n\width=500,height=400');">
                                    <b>Sala:</b>
                                        <?= @$obj->_sala ?>
                                    </a>
                                </td>
                            <? } ?>                            

                            <? if (in_array('exame', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Exame:</b> <?= @$obj->_procedimento ?></td>
                            <? } ?>  

                            <? if (in_array('solicitante', $opc_dadospaciente)) { ?>
                                <td colspan="3" ><b>Solicitante: </b><?= @$obj->_solicitante ?></td>
                            <? } ?>
                            <? if (in_array('indicacao', $opc_dadospaciente)) { ?>
                                <td colspan="3"><b>Indicaçao: </b><?= @$obj->_indicacao ?></td>
                            <? } ?>                                                   

                        </tr>

                        <tr>

<!--<td>Indicacao: <?= @$obj->_indicado ?></td>-->

                        </tr>

                        <tr>
                            <? if (in_array('nome_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Pai:</b> <?= @$obj->_nome_pai ?></td>
                            <? } ?>
                            <? if (in_array('cpf_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>CPF Pai: </b><?= @$obj->_cpf_pai ?></td>
                            <? } ?>
                            <? if (in_array('ocupacao_pai', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Ocupação: </b><?= @$obj->_ocupacao_pai ?></td>
                            <? } ?>
                        </tr>

                        <tr>
                            <? if (in_array('nome_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4"><b>Mãe:</b> <?= @$obj->_nome_mae ?></td>
                            <? } ?>
                            <? if (in_array('cpf_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>CPF Mãe: </b><?= @$obj->_cpf_mae ?></td>
                            <? } ?>
                            <? if (in_array('ocupacao_mae', $opc_dadospaciente)) { ?>
                                <td colspan="4"><b>Ocupação: </b><?= @$obj->_ocupacao_mae ?></td>
                            <? } ?>

                        </tr>
                        <tr>
                            <? if (in_array('conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Cônjuge: </b><?= @$obj->_nome_conjuge ?></td>
                            <? } ?>
                            <?
                            $dataFuturo = date("Y-m-d");
                            $dataAtual = @$obj->_nascimento_conjuge;
                            $date_time = new DateTime($dataAtual);
                            $diff2 = $date_time->diff(new DateTime($dataFuturo));
                            $teste2 = $diff2->format('%Ya %mm %dd');
                            ?>
                            <? if (in_array('idade_conjuge', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Idade do Cônjuge: </b><?= @$teste2 ?></td>
                            <? } ?>
                            <? if (in_array('prontuario_antigo', $opc_dadospaciente)) { ?>
                                <td colspan="4" ><b>Prontuário antigo: </b><?= @$obj->_prontuario_antigo ?></td>
                            <? } ?>
                        </tr>

                    </table>

                    <table>
                        <tr>
                            <td>
                                <? if (in_array('preencherform', $opc_telatendimento)) { ?>
                                    <div class="bt_link_new">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/preencherformulario/<?= $ambulatorio_laudo_id ?>');" >
                                            Formulário</a></div>
                                <? } ?>
                            </td> 
                        </tr>                        
                    </table>



                </fieldset>

                <table>
                    <tr>
                        <td >
                            <? //=date("Y-m-d",strtotime(@$obj->_data_senha)) ?>
                            <? if (in_array('chamar', $opc_telatendimento)) { ?>
                                <? if (($endereco != '')) { ?>

                                    <div class="bt_link_new">
                                        <a href='#' id='botaochamar' >Chamar</a>
                                    </div>


                                <? } else {
                                    ?>
                                    <div class="bt_link_new">
                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/chamarpaciente/<?= $ambulatorio_laudo_id ?>');" >
                                            Chamar</a>
                                    </div>  
                                    <?
                                }
                            }
                            ?>
                            <? if (in_array('chamar', $opc_telatendimento)) { ?>

                                <?
                            }
                            ?>
                        </td>

                        <td>
                            <? if (in_array('editar', $opc_telatendimento)) { ?>
                                <div class="bt_link_new">
                                    <a onclick="javascript:window.open('<?= base_url() ?>cadastros/pacientes/carregar/<?= $paciente_id ?>');" >
                                        Editar</a></div>
                            <? } ?>
                        </td>

                        <!-- <? if (@$obj->_status != 'FINALIZADO') { ?>
                            <td>
                                <div class="bt_link_new">
                                    <a onclick="javascript: return confirm('Deseja realmente deixar o atendimento pendente?');" href="<?= base_url() ?>ambulatorio/laudo/pendenteespecialidade/<?= $exame_id ?>" >
                                        Pendente
                                    </a>
                                </div>
                            </td>
                        <? } ?> -->

                        <!-- <td>
                            <? if (in_array('encaminhar', $opc_telatendimento)) { ?>
                                <div class="bt_link_new">
                                    <a href="<?= base_url() ?>ambulatorio/laudo/encaminharatendimento/<?= $ambulatorio_laudo_id ?>" >
                                        Encaminhar
                                    </a>
                                </div>
                            <? } ?>
                        </td> -->
                        <!-- <td>
                            <? if (in_array('histconsulta', $opc_telatendimento)) { ?>
                                <div class="bt_link_new"><a href="<?= base_url() ?>ambulatorio/laudo/carregarlaudohistorico/<?= $paciente_id ?>">Hist. Consulta</a></div>
                            <? } ?>
                        </td>
                        <td>
                            <div class="bt_link_new"><a href="<?= base_url() ?>ambulatorio/laudo/carregaranamineseantigo/<?= $paciente_id ?>">Hist. Antigo</a></div>
                        </td> -->

                    <!-- <td>
                        <div  class="item flex flex-wrap" style="float:right;">
                            <button  type="button" id="mostrarDadosExtras">+</button>
                        </div> 
                    </td> -->
                    </tr>
                </table>
                <div id="DadosExtras" hidden>

                    <fieldset>
                        <legend>MEDIDAS</legend>
                        <table>
                            <tr>
                                <td><font size = -1>Peso:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="Peso" id="Peso" class="texto02"  alt="decimal"  onblur="calculaImc()" value="<?= $peso ?>"/></font></td>
                                <td width="50px;"><font size = -1>Kg</font></td>
                                <td ><font size = -1>Altura:</font></td>
                                <td width="50px;"><font size = -1><input type="number" name="Altura" step="0.1" id="Altura" class="texto02" value="<?= $altura; ?>" onblur="calculaImc()"/></font></td>
                                <td width="50px;"><font size = -1>Cm</font></td>

                                <td><font size = -1>IMC</font></td>
                                <td width="60px;"><font size = -1><input type="text" name="imc" id="imc" class="texto02"  readonly/></font></td>
                                <td width="30px;"></td>


                                <td><font size = -1>Diabetes:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="diabetes" id="diabetes" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='nao'<?
                                        if (@$diabetes == 'nao'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='sim' <?
                                        if (@$diabetes == 'sim'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font></td>
                                <td width="20px;"></td>
                                <td><font size = -1>Hipertens&atilde;o:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="hipertensao" id="hipertensao" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='nao'<?
                                        if (@$hipertensao == 'nao'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='sim' <?
                                        if (@$hipertensao == 'sim'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font>
                                    </td>
                                    

                                    <td><font size = -1>Medicação:</font></td>
                                <td colspan="2"><font size = -1>                            
                                    <select name="medicacao" id="medicacao" class="size1">
                                        <option value=''>SELECIONE</option>
                                        <option value='Nao'<?
                                        if (@$medicacao == 'f'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='Sim' <?
                                        if (@$medicacao == 't'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                                    </select><font>
                                    </td>
                            </tr>
                            </table>
                            <table>
                            <tr>

                                <td><font size = -1>Pulso:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="pulso" id="Pulso" class="texto02"  alt="decimal" value="<?= $pulso ?>"/></font></td>
                                <td width="50px;"><font size = -1>Bpm</font></td>

                                <td><font size = -1>Temperatura:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="temperatura" id="Temperatura" class="texto02"  alt="decimal" value="<?= $temperatura ?>"/></font></td>
                                <td width="50px;"><font size = -1>ºC</font></td>

                                <td><font size = -1>Pressao Arterial:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="pressao_arterial" id="pressao_arterial" class="texto02"  alt="decimal" value="<?= $pressao_arterial ?>"/></font></td>
                                <td width="50px;"><font size = -1>mm/Hg</font></td>

                                <td><font size = -1>F. Respiratoria:</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="f_respiratoria" id="f_respiratoria" class="texto02"  alt="decimal" value="<?= $f_respiratoria ?>"/></font></td>
                                <td width="50px;"><font size = -1>Rpm</font></td>

                                <td><font size = -1>SPO2</font></td>
                                <td width="50px;"><font size = -1><input type="number" step="0.01" name="spo2" id="spo2" class="texto02"  alt="decimal" value="<?= $spo2 ?>"/></font></td>
                                <td width="50px;"><font size = -1>%</font></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                    <script>
                        $(function () {
                        $("#tabs").tabs();
                        });
                        $(".tab-ativa").tabs("option", "active", 1);
                    </script>    
                <div>

                    <fieldset>
                        <div id="tabs">
                                
                                <ul>
                                    <li><a class="tab-ativa" href="#tabs-2">Evoluções</a></li>    
                                    <li ><a href="#tabs-5" onclick="tinyMCE.triggerSave();">Condutas</a></li>                                
                                    <li ><a href="#tabs-9" onclick="tinyMCE.triggerSave();">Anotação Privada</a></li>                                  
                                    <li ><a href="#tabsTomada" onclick="tinyMCE.triggerSave();">Tomadas</a></li>    
                                    <!-- <li ><a href="#tabs-8" onclick="tinyMCE.triggerSave();">Visualizar</a></li> -->
                                    <li ><a href="#tabsArquivo" onclick="tinyMCE.triggerSave();">Resultados de Exames</a></li>
                                    
                                    
                                </ul>

                            <div id="tabs-2">

                                <table style="font-size: 12px">
                                    <tr>
                                        <td>
                                            <label>Modelos</label>
                                        </td>
                                        <td>
                                            
                                                
                                            <select name="exame" id="exame" class="size2" >
                                                <option value='' >Selecione</option>
                                                <?php foreach ($lista as $item) { ?>
                                                    <option value="<?php echo $item->ambulatorio_modelo_laudo_id; ?>" ><?php echo $item->nome; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?
                                            if (@$obj->_cabecalho == "") {
                                                $cabecalho = @$obj->_procedimento;
                                            } else {
                                                $cabecalho = @$obj->_cabecalho;
                                            }
                                            ?>

                                            <input type="hidden" id="cabecalho" class="texto7" name="cabecalho" value="<?= $cabecalho ?>"/>
                                            <input type="hidden" id="ambulatorio_laudo_id" class="texto7" name="ambulatorio_laudo_id" value="<?= $ambulatorio_laudo_id ?>"/>
                                            
                                            
                                        </td>
                                    </tr>

                                </table>
                                  
                                <div class="row" >
                                    <div class="col" >
                                        <?
                                        $contador_col = 0;
                                        ?>
 
                                        <table >
                                            <tr>
                                            <td rowspan="11" >
                                                <div id="camposDiv" style="min-width: 600px; font-size: 12px;">
                                                
                                                </div>
                                                <div id="anamnesePadrao">
                                                    <textarea id="laudo" name="laudo" rows="30" class="tinymce" id="tinymce" cols="80" style="width: 800px"><?= @$obj->_texto; ?></textarea>
                                                </div>
                                            </td>

                                        </table>
                                        <!-- <table>
                                            <tr>
                                                <td width="40px;"><div class="bt_link_new">
                                                        <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $ambulatorio_laudo_id ?>/<?= $exame_id ?>');" >
                                                            Visualizar</a></div>
                                                </td>

                                            </tr>
                                        </table> -->
                                        <table>
                                            <tr>
                                                <? if ($adendo) { ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <label><h3>Adendo</h3></label>
                                                            <textarea id="adendo"   name="adendo" class="adendo" rows="30" cols="80" style="width: 80%"></textarea>
                                                        </div>  
                                                    </td>
                                                </tr>
                                            <? }
                                            ?>
                                            </tr>
                                        </table>
                                    </div>
                                    

                                     <div class="col">
                                        <table>
                                            <tr>    
                                                    <td><button type="submit" class="btn"  name="btnSalvar"><b>Salvar</b></button></td>
                                                    <td><button type="submit" class="btn_verde" onclick="tinyMCE.triggerSave();" name="btnFinalizar"><b>Liberar</b></button></td>
                                                    <td><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td>
                                            </tr>
                                            <tr>    
                                                    <td><label><font size="4"> Revisão?</font> </label><input type="checkbox" name="rev" id="rev" onchange="valueChanged()" /></td>
                                                    <td colspan='2' id="revisoes" hidden>
                                                    <input type="number" class="texto02" name="tempoRevisao"> 
                                                        <select name="tipodata">
                                                            <option value="dias"> Dias </option>
                                                            <option value="meses"> Meses </option>
                                                            <option value="anos"> Anos </option>
                                                        </select>
                                                    </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><label><font size="4">Dados Importantes do Paciente:</font></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><textarea id="informacao_laudo"  name="informacao_laudo" class="texto07" rows="10" placeholder="<?=$informacao_exemplo?>"><?= @$obj->_informacao_laudo ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><label><font size="3">Resultado de Exames: (Nomear o arquivo com o tipo do exame)</font></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="area-upload">
                                                        <label for="upload-file" class="label-upload">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                            <div class="texto">Clique ou arraste o arquivo</div>
                                                        </label>
                                                        <input type="file"  id="upload-file" multiple/>
                                                        
                                                        <div class="lista-uploads">
                                                            <!-- Lista de Uploads -->
                                                                <?
                                                                if($arquivos_anexados != false){
                                                                    foreach ($arquivos_anexados as $value) {
                                                                        ?>
                                                                            <div class="barra complete">
                                                                            <div class="fill" style="min-width: 100%;"></div>
                                                                                <div class="text">
                                                                                    <a href="<?=base_url().'upload/consulta/'.$ambulatorio_laudo_id.'/'.$value?>" target="_blank"><?=$value?></a> 
                                                                                    <!-- <a href="<?= base_url() ?>ambulatorio/laudo/excluirimagem/<?= $ambulatorio_laudo_id ?>/<?= $value ?>">Excluir</a> -->
                                                                                    <i class="fas fa-check"></i>
                                                                                </div>
                                                                            </div>
                                                                        <?
                                                                    }
                                                                }
                                                                ?>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>

                                    </div> 

                                </div>
                                <?
                                $perfil_id = $this->session->userdata('perfil_id');
                                ?>

                            </div>

                            <div id="tabs-5">
                            
                            <div class="row" >
                                    <div class="col" style="width:800px;">

                                    <div id="myModalAssinar" class="modal" hidden>

                                    </div>

                                    <?
                                    $assinaronline = false;
                                    if(count($impressao_receitas) > 0){
                                        if ($impressao_receitas[0]->ambulatorio_id != '') {
                                            $lista_receita = json_decode($impressao_receitas[0]->ambulatorio_id);
                                            $assinaronline = true;
                                        } else {
                                            $lista_receita = array();
                                        }
                                    }else{
                                        $lista_receita = array();
                                    }

                                    if(count($impressao_exames) > 0){
                                        if ($impressao_exames[0]->ambulatorio_id != '') {
                                            $lista_exames = json_decode($impressao_exames[0]->ambulatorio_id);
                                            $assinaronline = true;
                                        } else {
                                            $lista_exames = array();
                                        }
                                    }else{
                                        $lista_exames = array();
                                    }

                                    if(count($impressao_terapeuticas) > 0){
                                        if ($impressao_terapeuticas[0]->ambulatorio_id != '') {
                                            $lista_terapeuticas = json_decode($impressao_terapeuticas[0]->ambulatorio_id);
                                            $assinaronline = true;
                                        } else {
                                            $lista_terapeuticas = array();
                                        }
                                    }else{
                                        $lista_terapeuticas = array();
                                    }

                                    if(count($impressao_relatorio) > 0){
                                        if ($impressao_relatorio[0]->ambulatorio_id != '') {
                                            $lista_relatorio = json_decode($impressao_relatorio[0]->ambulatorio_id);
                                            $assinaronline = true;
                                        } else {
                                            $lista_relatorio = array();
                                        }
                                    }else{
                                        $lista_relatorio = array();
                                    }



                                    // ABAIXO O SISTEMA VAI VERIFCAR SER OS MODELOS POSSUEM NOME ASSOCIADO
                                    // CASO NÃO TENHA VAI TRAZER PARTE DO TEXTO
                                    function PegarTexto($texto)
                                    {
                                        $texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $texto);
                                        $texto = str_replace("<head>", '', $texto);
                                        $texto = str_replace("</head>", '', $texto);
                                        $texto = str_replace("<html>", '', $texto);
                                        $texto = str_replace("<body>", '', $texto);
                                        $texto = str_replace("<p>", '', $texto);
                                        $texto = str_replace("</p>", '', $texto);
                                        $texto = str_replace("</html>", '', $texto);
                                        $texto = str_replace("</body>", '', $texto);
                                        $texto = str_replace('align="center"', '', $texto);
                                        $texto = str_replace('align="left"', '', $texto);
                                        $texto = str_replace('align="right"', '', $texto);
                                        $texto = str_replace('align="justify"', '', $texto);

                                        $texto = substr($texto, 0, 35);
                                        return $texto;
                                    }

                                    ?>
                                
                                <table>
                                    <tr>
                                        <td>
                                            <div class="bt_link_new">
                                                <a onclick="javascript:window.open('<?= base_url()?> ambulatorio/modeloreceita/criarnovomodeloreceita/<?=$ambulatorio_laudo_id?>/<?=@$obj->_medico_parecer1?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                    <font size="-1">Nova Receitas</font>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="bt_link_new">
                                                <a onclick="javascript:window.open('<?= base_url()?> ambulatorio/modeloreceita/criarnovomodeloreceitaespecial/<?=$ambulatorio_laudo_id?>/<?=@$obj->_medico_parecer1?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                    <font size="-1">Nova R. Especial</font>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="bt_link_new">
                                            <a onclick="javascript:window.open('<?= base_url()?> ambulatorio/modeloreceita/criarnovomodelosexames/<?=$ambulatorio_laudo_id?>/<?=@$obj->_medico_parecer1?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                    <font size="-1">Nova S. Exames</font>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="bt_link_new">
                                            <a onclick="javascript:window.open('<?= base_url()?> ambulatorio/modeloreceita/criarnovomodeloterapeutica/<?=$ambulatorio_laudo_id?>/<?=@$obj->_medico_parecer1?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                    <font size="-1">Nova Terapeutica</font>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="bt_link_new">
                                            <a onclick="javascript:window.open('<?= base_url()?> ambulatorio/modeloreceita/criarnovomodelorelatorio/<?=$ambulatorio_laudo_id?>/<?=@$obj->_medico_parecer1?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                                    <font size="-1">Novo Relatorio</font>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                               <table border="0">
                                    <tr>
                                      <td colspan="3" align="center"><label><font size="3">Descrição</font></label></td>
                                      <td colspan="3" align="center"><label><font size="3"><!--Receitas por mês--></font></label></td>
                                    </tr>

                                    <tr>
                                      <td colspan="3"><label><font size="3">Receitas comuns:</font>
                                      <!-- </td> -->
                                        <!-- <td> -->
                                        <?if (count($receita_laudo) > 0){?>
                                            <!-- <div  > -->
                                                <button  type="button" id="mostrarDadosReceitas" onclick="mostrarDadosReceitas()">+</button>
                                            <!-- </div>  -->
                                        <? } ?>
                                        </label>
                                        </td>
                                    </tr>

                                    <?if (count($receita_laudo) > 0){?>
                                        <tr class="mostrartodasreceitas" hidden>
                                        <td colspan='3'><input type="checkbox" name="marcartodosreceitasimpes" id="marcartodosreceitasimpes" onclick="marcartodosreceita()">Marcar Todos</td>
                                        <td colspan='3'><input type="checkbox" id="sem_data_r" name="sem_data_r" > Sem Data</td>
                                        </tr>
                                        <?foreach($receita_laudo as $item){?>
                                            <tr class="mostrartodasreceitas" hidden>
                                            <td width="60px"></td>

                                            <td colspan="2" id="receita_destaque<?=$item->ambulatorio_receituario_id?>">
                                            
                                            <? //if(count($impressao_receitas) > 0){?>
                                            <input type="checkbox" name="receita_imprimir[]" class="rec_simples" value="<?=$item->ambulatorio_receituario_id?>" <?= (in_array($item->ambulatorio_receituario_id, $lista_receita)) ? 'checked' : ''; ?>>
                                            <? //} else { ?>
                                            <!-- <input type="checkbox" name="receita_imprimir[]" class="rec_simples" value="<?=$item->ambulatorio_receituario_id?>" checked> -->
                                            <? //} ?>

                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }elseif($item->receita_id == -10){ ?>
                                                <font size="2">Receituário Comum</font> 
                                                <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            <!-- </td>
                                            <td> -->
                                                <div  class="item flex flex-wrap" style="float:left;">
                                                    <button  type="button" id="mostrarDadosExtras_receita<?=$item->ambulatorio_receituario_id?>" onclick="mostrarDadosExtrasreceita(<?=$item->ambulatorio_receituario_id?>)">+</button>
                                                </div> 
                                            </td>

                                            <!-- <td width="60px"></td> -->
                                            <td style="width:100px" colspan="2">Receita por mês <br> <font size="2"> <input type="number" class="texto01" name="receita_imprimir_por_mes_<?=$item->ambulatorio_receituario_id?>" step="1" min="0" value="1" required> </font></td>
                                            <!-- <td style="width:100px" colspan="1">Repetir Impressão <br> <font size="2"> <input type="number" class="texto01" name="receita_simples_<?=$item->ambulatorio_receituario_id?>" step="1" min="1" value="1" required> </font></td> -->
                                            <td><button type="button" onclick="ExcluirModelos('Receita', <?=$item->ambulatorio_receituario_id?>)"> Excluir </button></td>
                                            <!-- <td width="60px"></td> -->
                                            </tr>

                                            <tr hidden id="tr_receita_<?=$item->ambulatorio_receituario_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="6"> 
                                                <div class="edicao_rapida" id="receita_<?=$item->ambulatorio_receituario_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarReceita(<?=$item->ambulatorio_receituario_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <tr>
                                      <td colspan="3"><label><font size="3">Receitas Especiais:</font>
                                      <!-- </td>
                                      <td> -->
                                      <?if (count($receita_especial_laudo) > 0){?>
                                            <!-- <div  class="item flex flex-wrap" style="float:right;"> -->
                                                <button  type="button" id="mostrarDadosReceitasEspecial" onclick="mostrarDadosReceitasEspecial()">+</button>
                                            <!-- </div>  -->
                                      <? } ?>
                                      </label>
                                        </td>
                                    </tr>
                                    <?if (count($receita_especial_laudo) > 0){?>
                                        <tr class="mostratodasreceitasespecial" hidden>
                                        <td colspan='3'><input type="checkbox" id="marcartodosreceitaespecial" onclick="marcartodosreceitae()">Marcar Todos</td>
                                        <td colspan='3'><input type="checkbox" id="sem_data_r_especial" name="sem_data_r_especial" > Sem Data</td>
                                        </tr>
                                        <?foreach($receita_especial_laudo as $item){?>
                                        <tr class="mostratodasreceitasespecial" hidden>
                                            <td width="60px"></td>
                                            <td colspan="2" id="receita_destaque<?=$item->ambulatorio_receituario_id?>">
                                            
                                            <? //if(count($impressao_receitas) > 0){?>
                                            <input type="checkbox" name="receita_imprimir[]" class="rec_especial" value="<?=$item->ambulatorio_receituario_id?>" <?= (in_array($item->ambulatorio_receituario_id, $lista_receita)) ? 'checked' : ''; ?>>
                                            <? //} else { ?>
                                            <!-- <input type="checkbox" name="receita_imprimir[]" class="rec_especial" value="<?=$item->ambulatorio_receituario_id?>" checked> -->
                                            <? //} ?>
                                            
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }elseif($item->receita_id == -10){ ?>
                                                <font size="2">Receituário Comum</font> 
                                                <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            <!-- </td>
                                            <td> -->
                                                <div  class="item flex flex-wrap" style="float:left;">
                                                    <button  type="button" id="mostrarDadosExtras_receita<?=$item->ambulatorio_receituario_id?>" onclick="mostrarDadosExtrasreceita(<?=$item->ambulatorio_receituario_id?>)">+</button>
                                                </div> 
                                            </td>

                                            <!-- <td width="60px"></td> -->
                                            <td style="width:100px" colspan="2">Receita por mês <br><font size="2"> <input type="number" class="texto01" name="receita_imprimir_por_mes_<?=$item->ambulatorio_receituario_id?>" step="1" min="0" value="1" required> </font></td>
                                            <!-- <td style="width:100px" colspan="1">Repetir Impressão <br> <font size="2"> <input type="number" class="texto01" name="receita_especial_<?=$item->ambulatorio_receituario_id?>" step="1" min="1" value="1" required> </font></td> -->
                                            <td><button type="button" onclick="ExcluirModelos('Receita', <?=$item->ambulatorio_receituario_id?>)"> Excluir </button></td>
                                            <!-- <td width="60px"></td> -->
                                        </tr>

                                        <tr hidden id="tr_receita_<?=$item->ambulatorio_receituario_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="6"> 
                                                <div class="edicao_rapida" id="receita_<?=$item->ambulatorio_receituario_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarReceita(<?=$item->ambulatorio_receituario_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>
                                    
                                    <?if ((count($exames_laudo) > 0) || (count($terapeuticas_laudo) > 0) || (count($relatorio_laudo) > 0)){?>
                                    <tr>
                                    <td colspan='6'><input type="checkbox" id="sem_data_e" name="sem_data_e" ><label><font size="2"> Sem Data (Relatorio, Exames, Terapeuticas)</font></td>
                                    </tr>
                                    <? } ?>

                                    <tr>
                                      <td colspan="3"><label><font size="3">Exames:</font>
                                      <!-- </td>
                                      <td> -->
                                        <?if (count($exames_laudo) > 0){?>
                                            <!-- <div  class="item flex flex-wrap" style="float:right;"> -->
                                                <button  type="button" id="mostrarDadosExames">+</button>
                                            <!-- </div>  -->
                                        <? } ?>
                                        </label>
                                        </td>
                                    </tr>
                                    <?if (count($exames_laudo) > 0){?>
                                        <tr class="mostrartodosexames" hidden>
                                        <td colspan='6'><input type="checkbox" id="marcartodossolicitaexames" onclick="marcartodosexames()">Marcar Todos</td>
                                        </tr>
                                        <?foreach($exames_laudo as $item){?>
                                            <tr class="mostrartodosexames" hidden>
                                            <td width="60px"></td>
                                            <td colspan="2" id="exames_destaque<?=$item->ambulatorio_exame_id?>">
                                            
                                            <?// if(count($impressao_exames) > 0){?>
                                                <input type="checkbox" name="s_exames_imprimir[]" class="s_exames_c" value="<?=$item->ambulatorio_exame_id?>" <?= (in_array($item->ambulatorio_exame_id, $lista_exames)) ? 'checked' : ''; ?>>
                                            <? //} else { ?>
                                                <!-- <input type="checkbox" name="s_exames_imprimir[]" value="<?=$item->ambulatorio_exame_id?>" checked> -->
                                            <?// } ?>

                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            
                                            <!-- </td>
                                            <td> -->
                                                <div  class="item flex flex-wrap" style="float:left;">
                                                    <button  type="button"  id="mostrarDadosExtras_exames<?=$item->ambulatorio_exame_id?>" onclick="mostrarDadosExtrasexames(<?=$item->ambulatorio_exame_id?>)">+</button>
                                                </div> 
                                            </td>

                                            <!-- <td width="60px"></td> -->
                                            <!-- <td style="width:100px" colspan="3">Repetir Impressão <br> <font size="2"> <input type="number" class="texto01" name="s_exames_<?=$item->ambulatorio_exame_id?>" step="1" min="1" value="1" required> </font></td> -->
                                            <td><button type="button" colspan="3" onclick="ExcluirModelos('Exame', <?=$item->ambulatorio_exame_id?>)"> Excluir </button></td>
                                            <!-- <td width="60px"></td> -->
                                            </tr>

                                            <tr hidden id="tr_exames_<?=$item->ambulatorio_exame_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="6"> 
                                                <div class="edicao_rapida" id="exames_<?=$item->ambulatorio_exame_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarExames(<?=$item->ambulatorio_exame_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <tr>
                                      <td colspan="3"><label><font size="3">Terapeuticas:</font>
                                      <!-- </td>
                                      <td> -->
                                        <?if (count($terapeuticas_laudo) > 0){?>
                                            <!-- <div  class="item flex flex-wrap" style="float:right;"> -->
                                                <button  type="button" id="mostrarDadosTerapeuticas">+</button>
                                            <!-- </div>  -->
                                        <? } ?>
                                        </label>
                                        </td>
                                    </tr>
                                    <?if (count($terapeuticas_laudo) > 0){?>
                                        <tr class="mostrartodosterapeuticas" hidden><td colspan='6'><input type="checkbox" id="marcartodosterapeuticas" onclick="marcartodosterapeutica()">Marcar Todos</td></tr>
                                        <?foreach($terapeuticas_laudo as $item){?>
                                            <tr class="mostrartodosterapeuticas" hidden>
                                            <td width="60px"></td>
                                            <td colspan="2" id="terapeuticas_destaque<?=$item->ambulatorio_terapeutica_id?>">
                                            
                                            <?// if(count($impressao_terapeuticas) > 0){?>
                                                <input type="checkbox" name="terapeuticas_imprimir[]" class="terapeutica_c" value="<?=$item->ambulatorio_terapeutica_id?>" <?= (in_array($item->ambulatorio_terapeutica_id, $lista_terapeuticas)) ? 'checked' : ''; ?>>
                                            <?// } else { ?>
                                                <!-- <input type="checkbox" name="terapeuticas_imprimir[]" value="<?=$item->ambulatorio_terapeutica_id?>" checked> -->
                                            <?// } ?>
                                            
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            <!-- </td>
                                            <td> -->
                                                <div  class="item flex flex-wrap" style="float:left;">
                                                    <button  type="button" id="mostrarDadosExtras_terapeuticas<?=$item->ambulatorio_terapeutica_id?>" onclick="mostrarDadosExtrasterapeuticas(<?=$item->ambulatorio_terapeutica_id?>)">+</button>
                                                </div> 
                                            </td>

                                            <!-- <td width="60px"></td> -->
                                            <!-- <td style="width:100px" colspan="3">Repetir Impressão <br> <font size="2"> <input type="number" class="texto01" name="terapeuticas_r<?=$item->ambulatorio_terapeutica_id?>" step="1" min="1" value="1" required> </font></td> -->
                                            <td><button type="button" colspan="3" onclick="ExcluirModelos('Terapeutica', <?=$item->ambulatorio_terapeutica_id?>)"> Excluir </button></td>
                                            <!-- <td width="60px"></td> -->
                                            </tr>
                                            
                                            <tr hidden id="tr_terapeuticas_<?=$item->ambulatorio_terapeutica_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="6"> 
                                                <div class="edicao_rapida" id="terapeuticas_<?=$item->ambulatorio_terapeutica_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarTerapeuticas(<?=$item->ambulatorio_terapeutica_id?>)">Ok</button></td>
                                            </tr>

                                        <?}?>
                                    <?}?>

                                    <tr>
                                      <td colspan="3"><label><font size="3">Relatorios / Atestados:</font>
                                      <!-- </td>
                                      <td> -->
                                        <?if (count($relatorio_laudo) > 0){?> 
                                            <!-- <div  class="item flex flex-wrap" style="float:right;"> -->
                                                <button  type="button" id="mostrarDadosRelatorio">+</button>
                                            <!-- </div>  -->
                                        <? } ?>
                                        </label>
                                        </td>
                                    </tr>

                                    <?if (count($relatorio_laudo) > 0){?>
                                        <tr class="mostrartodosrelatorios" hidden><td colspan='6'><input type="checkbox" id="marcartodosrelatorios" onclick="marcartodosrelatorio()">Marcar Todos</td></tr>
                                        <?foreach($relatorio_laudo as $item){?>
                                            <tr class="mostrartodosrelatorios" hidden>
                                            <td width="60px"></td>
                                            <td colspan="2" id="relatorios_destaque<?=$item->ambulatorio_relatorio_id?>">
                                            
                                            <?// if(count($impressao_relatorio) > 0){?>
                                                <input type="checkbox" name="relatorio_imprimir[]" class="relatorio_c" value="<?=$item->ambulatorio_relatorio_id?>" <?= (in_array($item->ambulatorio_relatorio_id, $lista_relatorio)) ? 'checked' : ''; ?>>
                                            <?// } else { ?>
                                                <!-- <input type="checkbox" name="relatorio_imprimir[]" value="<?=$item->ambulatorio_relatorio_id?>" checked> -->
                                            <?// } ?>
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            <!-- </td>
                                            <td> -->
                                                <div  class="item flex flex-wrap" style="float:left;">
                                                    <button  type="button" id="mostrarDadosExtras_relatorios<?=$item->ambulatorio_relatorio_id?>" onclick="mostrarDadosExtrasrelatorio(<?=$item->ambulatorio_relatorio_id?>)">+</button>
                                                </div> 
                                            </td>

                                            <!-- <td width="60px"></td> -->
                                            <!-- <td style="width:100px" colspan="3">Repetir Impressão <br> <font size="2"> <input type="number" class="texto01" name="relatorios_r<?=$item->ambulatorio_relatorio_id?>" step="1" min="1" value="1" required> </font></td> -->
                                            <td><button type="button" colspan="3" onclick="ExcluirModelos('Relatorio', <?=$item->ambulatorio_relatorio_id?>)"> Excluir </button></td>
                                            <!-- <td width="60px"></td> -->
                                            </tr>

                                            <tr hidden id="tr_relatorios_<?=$item->ambulatorio_relatorio_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="6"> 
                                                <div class="edicao_rapida" id="relatorios_<?=$item->ambulatorio_relatorio_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarRelatorios(<?=$item->ambulatorio_relatorio_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                </table>
                            </div>
                            
                                <div class="col top">
                                        <table>
                                            <tr>    
                                                    <td><button type="submit" class="btn_verde_2" onclick="tinyMCE.triggerSave();" name="btnAtivarprecriscao"><b>Ativar Prescrição</b></button></td>
                                                    <td><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td>
                                            </tr>
                                            
                                            <tr>
                                                    <td colspan="2" align="center"><button type="button" class="btn_medio"  name="btnVisualizareSalvar" onclick="abrirVisualizarTudo()"><b>Visualizar</b></button></td>
                                            </tr>

                                            <tr>
                                                    <td colspan="2" align="center"><button type="button" <?if($assinaronline == true){ echo 'class="btn_verde_4" onclick="Modal_assinar()"'; }else{ echo 'class="btn_disabled" disabled title="É necessario salvar a Prescrição para poder Assinar"';}?>><b>Assinar e Enviar OnLine</b></button></td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="3"><label><font size="4">Dados Importantes do Paciente:</font></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><textarea id="informacao_laudo_2"  name="informacao_laudo" class="texto07" rows="10" placeholder="<?=$informacao_exemplo?>"><?= @$obj->_informacao_laudo ?></textarea></td>
                                            </tr>

                                        </table>
                                        <!-- <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br> -->
                                    </div> 
                            </div>
                                            <br><br>
                            <table>
                                    <tr>
                                      <td colspan="4"><label><font size="3">Condutas anteriores:</font></td>
                                    </tr>

                                    <tr>
                                        <td width="60px"></td>
                                        <td colspan="2"><font size="2">Conduta</font></td>
                                        <td><font size="2">Data</font></td>
                                        <td><font size="2">Médico</font></td>
                                    </tr>
                                    
                                    <!-- <tr>
                                      <td colspan="3"><label><font size="2">Receitas comuns:</font></td>
                                    </tr> -->

                                    <?if (count($receita_antigo) > 0){?>
                                        <tr>
                                        <?foreach($receita_antigo as $item){?>
                                            <tr>
                                            <td width="60px"></td>
                                            <td id="receita_destaque<?=$item->ambulatorio_receituario_id?>">
                                            <input type="checkbox" name="receita_antiga_imprimir[]" value="<?=$item->ambulatorio_receituario_id?>" <?= (in_array($item->ambulatorio_receituario_id, $lista_receita)) ? 'checked' : ''; ?>>
                                            
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            </td>
                                            <td>
                                                <div  class="item flex flex-wrap" style="float:right;">
                                                    <button  type="button" id="mostrarDadosExtras_receita<?=$item->ambulatorio_receituario_id?>" onclick="mostrarDadosExtrasreceita(<?=$item->ambulatorio_receituario_id?>)">+</button>
                                                </div> 
                                            </td>
                                            <td><?=date("d/m/Y", strtotime($item->data));?></td>
                                            <td><?=$item->medico?></td>
                                            </tr>

                                            <tr hidden id="tr_receita_<?=$item->ambulatorio_receituario_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="5"> 
                                                <div class="edicao_rapida" id="receita_<?=$item->ambulatorio_receituario_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarReceita(<?=$item->ambulatorio_receituario_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <!-- <tr>
                                      <td colspan="3"><label><font size="2">Receitas Especiais:</font></td>
                                    </tr> -->
                                    <?if (count($receita_especial_antigo) > 0){?>
                                        <?foreach($receita_especial_antigo as $item){?>
                                        <tr>
                                            <td width="60px"></td>
                                            <td id="receita_destaque<?=$item->ambulatorio_receituario_id?>">
                                            <input type="checkbox" name="receita_antiga_imprimir[]" value="<?=$item->ambulatorio_receituario_id?>" <?= (in_array($item->ambulatorio_receituario_id, $lista_receita)) ? 'checked' : ''; ?>>
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            </td>
                                            <td>
                                                <div  class="item flex flex-wrap" style="float:right;">
                                                <button  type="button" id="mostrarDadosExtras_receita<?=$item->ambulatorio_receituario_id?>" onclick="mostrarDadosExtrasreceita(<?=$item->ambulatorio_receituario_id?>)">+</button>
                                                </div> 
                                            </td>
                                            <td><?=date("d/m/Y", strtotime($item->data));?></td>
                                            <td><?=$item->medico?></td>
                                        </tr>

                                        <tr hidden id="tr_receita_<?=$item->ambulatorio_receituario_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="5"> 
                                                <div class="edicao_rapida" id="receita_<?=$item->ambulatorio_receituario_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarReceita(<?=$item->ambulatorio_receituario_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <!-- <tr>
                                      <td colspan="3"><label><font size="2">Exames:</font></td>
                                    </tr> -->
                                    <?if (count($exames_antigo) > 0){?>
                                        <?foreach($exames_antigo as $item){?>
                                            <tr>
                                            <td width="60px"></td>
                                            <td id="exames_destaque<?=$item->ambulatorio_exame_id?>">
                                            <input type="checkbox" name="s_exames_antiga_imprimir[]" value="<?=$item->ambulatorio_exame_id?>" <?= (in_array($item->ambulatorio_exame_id, $lista_exames)) ? 'checked' : ''; ?>>
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            </td>
                                            <td>
                                                <div  class="item flex flex-wrap" style="float:right;">
                                                <button  type="button"  id="mostrarDadosExtras_exames<?=$item->ambulatorio_exame_id?>" onclick="mostrarDadosExtrasexames(<?=$item->ambulatorio_exame_id?>)">+</button>
                                                </div> 
                                            </td>
                                            <td><?=date("d/m/Y", strtotime($item->data));?></td>
                                            <td><?=$item->medico?></td>
                                            </tr>

                                            <tr hidden id="tr_exames_<?=$item->ambulatorio_exame_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="5"> 
                                                <div class="edicao_rapida" id="exames_<?=$item->ambulatorio_exame_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarExames(<?=$item->ambulatorio_exame_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <!-- <tr>
                                      <td colspan="3"><label><font size="2">Terapeuticas:</font></td>
                                    </tr> -->
                                    <?if (count($terapeuticas_antigo) > 0){?>
                                        <?foreach($terapeuticas_antigo as $item){?>
                                            <tr>
                                            <td width="60px"></td>
                                            <td id="terapeuticas_destaque<?=$item->ambulatorio_terapeutica_id?>">
                                            <input type="checkbox" name="terapeuticas_antiga_imprimir[]" value="<?=$item->ambulatorio_terapeutica_id?>" <?= (in_array($item->ambulatorio_terapeutica_id, $lista_terapeuticas)) ? 'checked' : ''; ?>>
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            </td>

                                            <td>
                                                <div  class="item flex flex-wrap" style="float:right;">
                                                    <button  type="button" id="mostrarDadosExtras_terapeuticas<?=$item->ambulatorio_terapeutica_id?>" onclick="mostrarDadosExtrasterapeuticas(<?=$item->ambulatorio_terapeutica_id?>)">+</button>
                                                </div> 
                                            </td>
                                            <td><?=date("d/m/Y", strtotime($item->data));?></td>
                                            <td><?=$item->medico?></td>
                                            </tr>

                                            <tr hidden id="tr_terapeuticas_<?=$item->ambulatorio_terapeutica_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="5"> 
                                                <div class="edicao_rapida" id="terapeuticas_<?=$item->ambulatorio_terapeutica_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarTerapeuticas(<?=$item->ambulatorio_terapeutica_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                                    <!-- <tr>
                                      <td colspan="3"><label><font size="2">Relatorios / Atestados:</font></td>
                                    </tr> -->

                                    <?if (count($relatorio_antigo) > 0){?>
                                        <?foreach($relatorio_antigo as $item){?>
                                            <tr>
                                            <td width="60px"></td>
                                            <td id="relatorios_destaque<?=$item->ambulatorio_relatorio_id?>">
                                            <input type="checkbox" name="relatorio_antiga_imprimir[]" value="<?=$item->ambulatorio_relatorio_id?>" <?= (in_array($item->ambulatorio_relatorio_id, $lista_relatorio)) ? 'checked' : ''; ?>>
                                            <? if($item->nome != ''){?>
                                            <font size="2"><?=$item->nome?> </font>
                                            <? }else{ ?>
                                            <font size="2"> <?=PegarTexto($item->texto)?> </font>
                                            <? } ?>
                                            </td>
                                            <td>
                                                <div  class="item flex flex-wrap" style="float:right;">
                                                    <button  type="button" id="mostrarDadosExtras_relatorios<?=$item->ambulatorio_relatorio_id?>" onclick="mostrarDadosExtrasrelatorio(<?=$item->ambulatorio_relatorio_id?>)">+</button>
                                                </div> 
                                            </td>
                                            <td><?=date("d/m/Y", strtotime($item->data));?></td>
                                            <td><?=$item->medico?></td>
                                            </tr>

                                            <tr hidden id="tr_relatorios_<?=$item->ambulatorio_relatorio_id?>">
                                                <!-- <td width="60px"></td> -->
                                                <td colspan="5"> 
                                                <div class="edicao_rapida" id="relatorios_<?=$item->ambulatorio_relatorio_id?>" ><?=$item->texto?></div>
                                                </td>
                                                <td><button type="button" onclick="SalvarRelatorios(<?=$item->ambulatorio_relatorio_id?>)">Ok</button></td>
                                            </tr>
                                        <?}?>
                                    <?}?>

                               </table>

                        </div> 
                        

                    <div id="tabsTomada">
                        <fieldset>
                            <legend>Adicionar Medicamentos</legend>

                        <div class="row">
                            <div class="col">
                            <fieldset class="fieldset_maior">
                                <legend>Procedimentos</legend>
                                <input type="hidden" name="paciente_id" id="paciente_id" value="<?=@$obj->_paciente_id?>"/>
                                <input type="hidden" name="convenio_id" id="convenio_id" value="<?=@$obj->_convenio_id?>"/>
                                <input type="hidden" name="laudo_tomada_id" id="laudo_tomada_id" value="<?=@$obj->_ambulatorio_laudo_id?>"/>
                                <input type="hidden" name="medico_tomada" id="medico_tomada" value="<?=@$obj->_medico_parecer1?>" class="texto01"/>
                                <table>
                                    
                                    <tr>
                                        <td>Quantidade</td>
                                        <td>
                                            <input type="number" step="0.01" name="quantidade" id="quantidade_medicamento" value="1" class="texto01"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Medicamento</td>
                                        <td>
                                            <select name="medicamento_id" id="medicamento_id" class="size4 chosen-select" tabindex="1" >
                                                <option value="">Selecione</option>
                                                <? foreach ($medicamentos as $item) { ?>
                                                    <option value="<?= $item->medicamento_id ?>"><?= $item->nome . ' - ' . $item->posologia ?></option>
                                                <? } ?>
                                            </select>
                                        </td>
                                        </tr>

                                        <tr>
                                        <td>Periodo</td>
                                        <td>
                                            <select name="horario_texto" id="horario_texto"  >
                                                <option value="">Selecione</option>
                                                <option value="1">Após Café da Manhã</option>
                                                <option value="2">Antes do Almoço</option>
                                                <option value="3">Antes do Jantar</option>
                                                <option value="4">Ao Deitar</option>
                                            </select>
                                        </td>
                                        </tr>
                                        <tr>

                                        <td> Horario </td>
                                        <td> <input name="horario" id="horario"  class="texto01" alt="horario"> </td>
                                        </tr>
                                </table>
                                <hr/>
                                <table id="table_agente_toxico" border="0" style="width:600px">
                                    <thead>

                                        <tr>
                                            <th class="tabela_header">Medicamento</th>
                                            <th class="tabela_header">Quantidade</th>
                                            <th class="tabela_header">Periodo</th>
                                            <th class="tabela_header">Horario</th>
                                            <th class="tabela_header">&nbsp;</th>
                                            <th class="tabela_header">&nbsp;</th>
                                            <th class="tabela_header">&nbsp;</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tabelaMedicamentos">

                                    </tbody>
                                </table>
                            </fieldset>
                            </div>

                            <div class="col top">
                            <table>
                                <tr>
                                    <td><button type="button" class="btn_verde" name="adicionarMedicamento" onclick="enviarMedicamentoSADT();" id="adicionarMedicamento"><b>Adicionar</b></button></td>
                                    <td><button type="button" class="btn" onclick="imprimirMedicamentosTomada();" ><b>Visualizar</b></button></td>
                                    <td colspan=""><button type="submit" class="btn_verde_grande" name="btnmedicamentostomadas"><b>Salvar R.</b></button></td>
                                    <!-- <td><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td> -->
                                </tr>

                                <tr>
                                    <td colspan="3"><button type="button" class="btn_grande" name="criarmedicamentos" onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelomedicamento/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=750');">
                                    <b>Criar Medicamentos</b>
                                    </button></td>
                                </tr>

                                <!-- <tr>
                                    <td colspan="3"><button type="submit" class="btn_verde_grande" name="btnmedicamentostomadas">
                                    <b>Salvar Receitas</b>
                                    </button></td>
                                </tr> -->

                                <tr>
                                    <td colspan="3"><label><font size="4">Dados Importantes do Paciente:</font></label></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><textarea id="informacao_laudo_5"  name="informacao_laudo" class="texto07" rows="10" placeholder="<?=$informacao_exemplo?>"><?= @$obj->_informacao_laudo ?></textarea></td>
                                </tr>
                            </table>
                            </div>
                        </div>

                        <fieldset class="fieldset_maior">
                            <legend>Total</legend>
                            <table id="table_agente_toxico" border="0" style="width:600px">
                                <thead>

                                    <tr>
                                        <th class="tabela_header">Medicamento</th>
                                        <th class="tabela_header">Quantidade</th>

                                    </tr>
                                </thead>
                                <tbody id="tabelaMedicamentosTotal">

                                </tbody>
                            </table>
                        </fieldset>

                        <br><br>
                    </div>
                        <!-- <div id="tabs-8"> -->
                        <!-- <iframe src="<?=base_url().'upload/teste/pdf.pdf'?>" width="500" height="300" style="border: none;"></iframe> -->
                            <?
                                // if (!is_dir("./upload/novoatendimento")) {
                                //     mkdir("./upload/novoatendimento");
                                //     $destino = "./upload/novoatendimento";
                                //     chmod($destino, 0777);
                                // }
                    
                                // if (!is_dir("./upload/novoatendimento/$ambulatorio_laudo_id")) {
                                //     mkdir("./upload/novoatendimento/$ambulatorio_laudo_id");
                                //     $destino = "./upload/novoatendimento/$ambulatorio_laudo_id";
                                //     chmod($destino, 0777);
                                // }

                            $receitaEspecial = false;
                            // $arquivo_pasta = directory_map("./upload/novoatendimento/$ambulatorio_laudo_id/");
                            ?> 
                            <!-- <div class="row">
                                <div class="col">
                                    <input type="checkbox" name="selecionar_todos" class="selecionar_todos" onclick='marcardesmarcar();'> <b><font size="3">Selecionar Todos</font></b>
                                </div>
                                <div class="col">
                                    <table>
                                        <tr>
                                            <td><button type="button" class="btn_verde_3" onclick="abrirImpressaoTudoAssinar();"><b>Assinar e Enviar OnLine</b></button></td>
                                            <td><button type="button" class="btn" onclick="abrirImpressaoTudo();"><b>Imprimir</b></button></td>
                                            <td><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div> -->

                            <!-- <div> -->
                                <? //$i = 0;?>
                                <!-- <table border="0"> -->
                                <? //foreach ($nomesimpressoes as $value) { 
                                    // $i++;
                                    // if($i == 1){
                                    //     echo '<tr>';
                                    // }
                                    ?>
                                    
                                        <!-- <td>
                                        <input type="checkbox" class="impressao_definitiva" id="impressanavegador" name="impressanavegador[]" value="<?=$value->nome?>"> <b><?=$value->nome_impressao?></b> <br>
                                        <? //$id_nome = str_replace(".", "X", $value) ?>
                                        <input type="number"  name="<?=$value->nome?>" id="<?=$value->nome?>" class="quantidade_de_impressoes texto01" step="1" min="1" value="1" required/>
                                        </td>
                                        <td><iframe src="<?=base_url().'upload/novoatendimento/'.$ambulatorio_laudo_id.'/'.$value->nome?>" width="450" height="500" style="border: none;"></iframe></td> -->
                                    <?
                                    // if($i == 2){ 
                                    //     $i = 0;
                                        
                                        ?>
                                    <!-- </tr> -->
                                    <? //}?>

                                <?//  }  ?>

                                <? //if($i == 0) {
                                // }else{ ?>
                                <!-- </tr> -->
                                 <?// } ?>
                                <!-- </table>
                            </div> -->


                        <!-- </div> -->
                    <div id="tabsArquivo">
                        <div class="row">
                                    <div class="col">
                                                <div class="area-upload2">                                                       
                                                      <div class="lista-uploads">
                                                            <!-- Lista de Uploads -->
                                                                <table width="100%" border="0">
                                                                    <tr>
                                                                        <th style="width:200px;">Inserido Por</th>
                                                                        <th>Data</th>
                                                                        <th>Nome Arquivo</th>
                                                                    </tr>
                                                                    <?
                                                                        foreach ($resultadoexames as $value) {
                                                                            ?>
                                                                                <tr>
                                                                                    <?if($value->paciente == 't'){?>
                                                                                        <td style="width:200px;">Paciente</td>
                                                                                    <?}else{?>
                                                                                        <td style="width:200px;"><?=$value->operador?></td>
                                                                                    <?}?>

                                                                                    <td><?=substr($value->data_cadastro, 8, 2) . '/' . substr($value->data_cadastro, 5, 2) . '/' . substr($value->data_cadastro, 0, 4)?></td>
                                                                                    <td>
                                                                                        <div class="barra complete">
                                                                                        <div class="fill" style="min-width: 100%;"></div>
                                                                                            <div class="text">
                                                                                                <?$extensao_arquivo = explode(".", $value->nome);
                                                                                                    if($extensao_arquivo[1] == 'pdf' || $extensao_arquivo[1] == 'png' || $extensao_arquivo[1] == 'jpg' || $extensao_arquivo[1] == 'xml'){?>
                                                                                                <a onclick="pre_apresentacao('<?=base_url()?><?=$value->caminho?>')" ><?=$value->nome?></a> 
                                                                                                    <?}else{?>
                                                                                                <a href="<?=base_url().''.$value->caminho?>" target="_blank"><?=$value->nome?></a> 
                                                                                                    <?}?>
                                                                                                <i class="fas fa-check"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td >
                                                                                            <div class="barra rename">
                                                                                                <div class="fill" style="min-width: 100%;"></div>
                                                                                                    <div class="text">
                                                                                                        <!-- <a onclick="Excluirarquivo('<?= base_url() ?>ambulatorio/laudo/excluirimagemnovoatendimento/<?= $value->laudo_id ?>/<?= $value->nome ?>');">Renomear</a>
                                                                                                         -->
                                                                                                         <?
                                                                                                            $extensao_arquivo = explode(".", $value->nome);
                                                                                                            // print_r($extensao_arquivo);
                                                                                                            ?>
                                                                                                        
                                                                                                         <button type="button" id="myBtn" class="btn_pequeno" onclick="criar_modal('<?=$extensao_arquivo[0]?>', '<?=$extensao_arquivo[1]?>')"><b>Rename</b> </button>
                                                                                                        <i class="fas fa-check"></i>
                                                                                                    </div>
                                                                                                </div>
                                                                                    </td>
                                                                                    <td>
                                                                                            <div class="barra error">
                                                                                                <div class="fill" style="min-width: 100%;"></div>
                                                                                                    <div class="text">
                                                                                                        <!-- <a target="_blank" href="<?= base_url() ?>ambulatorio/laudo/excluirimagemnovoatendimento/<?= $value->laudo_id ?>/<?=$value->nome?>">Excluir</a> -->
                                                                                                        <?if($operador_id == 1){?>
                                                                                                        <a onclick="Excluirarquivo('<?= base_url() ?>ambulatorio/laudo/excluirimagemnovoatendimento/<?= $value->laudo_id ?>/<?= $value->nome ?>');">Excluir</a>
                                                                                                        <?}?>
                                                                                                        <i class="fas fa-check"></i>
                                                                                                    </div>
                                                                                                </div>
                                                                                    </td>
                                                                                </tr>
                                                                            <?
                                                                        }
                                                                    ?>
                                                                </table>
                                                            </div>

                                                            
                                                                    <div id="myModal" class="modal" hidden>

                                                                    </div>

                                                        </div>

                                    </div>
                                    
                                    <div class="col top2">
                                        <table id="apresentacao_tabela">
                                            <!-- <tr>     -->
                                                    <!-- <td><button type="submit" class="btn" onclick="tinyMCE.triggerSave();" name="btnSalvar"><b>Salvar</b></button></td> -->
                                                    <!-- <td><button type="submit" class="btn_verde" onclick="tinyMCE.triggerSave();" name="btnFinalizar"><b>Liberar</b></button></td> -->
                                                    <!-- <td colspan="3"><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td> -->
                                            <!-- </tr> -->

                                            <tr id="removepreapresentacao"> </tr>
                                            <!-- <tr>
                                                <td colspan="3"><label><font size="4">Dados Importantes do Paciente:</font></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><textarea id="informacao_laudo_4"  name="informacao_laudo" class="texto07" rows="10" placeholder="<?=$informacao_exemplo?>"><?= @$obj->_informacao_laudo ?></textarea></td>
                                            </tr> -->
                                            
                                        </table>
                                    </div>
                        </div>
                            <div>
                                <? if(count($resultadoexames) > 7){
                                echo '<br>';
                                }else{
                                echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                                }
                                ?>
                                <input type="hidden" name="paciente_acompanhamento_id" id="paciente_acompanhamento_id" value="<?=@$obj->_paciente_id?>"/>
                                <input type="hidden" name="laudo_acompanhamento_id" id="laudo_acompanhamento_id" value="<?=@$obj->_ambulatorio_laudo_id?>"/>
                                <input type="hidden" name="medico_acompanhamento" id="medico_acompanhamento" value="<?=@$obj->_medico_parecer1?>" class="texto01"/>

                                <label><font size="3">Acompanhamento:</font></label>
                                <input type="text" name="acompanhamento" id="acompanhamento" class="texto03"/>
                                <input type="text" name="qtde_acompanhamento" id="qtde_acompanhamento" class="texto01"/>
                                <!-- <br> -->
                                <button type="button" class="btn_verde" name="adicionaracompanhamento" onclick="adicionarAcompanhamento();" id="adicionaracompanhamento"><b>Adicionar</b></button>
                                <br>
                                <fieldset>
                                    <legend>Historico</legend>

                                        <table id="table_agente_toxico" border="0">
                                            <thead>

                                                <tr>
                                                    <th class="tabela_header">Acomp.</th>
                                                    <th class="tabela_header" colspan="40">&nbsp;</th>

                                                </tr>
                                            </thead>
                                            <tbody id="tabelaAcompanhamento">

                                            </tbody>
                                    </table>
                                </fieldset>
                        </div>
                    </div>
                        <div id="tabs-9">
                            <fieldset>
                                <legend>Anotação Privada</legend>
                                <div class='row'>
                                    <div class='col'>
                                        <textarea class="tinymce" id="anotacao_privada" name="anotacao_privada" rows="20" cols="80" style="width: 80%"></textarea></td>
                                    </div>
                                    <div class="col">
                                        <table>
                                            <tr>    
                                                    <td><button type="submit" class="btn" onclick="tinyMCE.triggerSave();" name="btnSalvar"><b>Salvar</b></button></td>
                                                    <td><button type="submit" class="btn_verde" onclick="tinyMCE.triggerSave();" name="btnFinalizar"><b>Liberar</b></button></td>
                                                    <td><button type="submit" class="btn_rosa" onclick="tinyMCE.triggerSave();" name="btnFechar"><b>Fechar</b></button></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><label><font size="4">Dados Importantes do Paciente:</font></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><textarea id="informacao_laudo_3"  name="informacao_laudo" class="texto07" rows="10" placeholder="<?=$informacao_exemplo?>"><?= @$obj->_informacao_laudo ?></textarea></td>
                                            </tr>

                                        </table>
                                        <br><br><br><br><br><br><br><br><br><br><br>
                                    </div> 
                                </div>

                                <hr>
                            </fieldset>
                                <?
                                if (count($anotacaoprivada) > 0) {
                                ?>
                                <table id="table_agente_toxico" border="0">
                                    <thead>
                                        <tr>
                                            <th class="tabela_header">Data</th>                            
                                            <th class="tabela_header">Médico</th>
                                            <th class="tabela_header">Descri&ccedil;&atilde;o</th>
                                        </tr>
                                    </thead>
                                    <?
                                    $estilo_linha = "tabela_content01";
                                    foreach ($anotacaoprivada as $item) {
                                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                //                        var_dump($item);die;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td class="<?php echo $estilo_linha; ?>"><?= date("d/m/Y", strtotime($item->data_cadastro)); ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                                                <td class="<?php echo $estilo_linha; ?>"><?= $item->texto; ?></td>
                                            </tr>
                                        </tbody>
                                        <?
                                    }?>
                                </table>
                                <?}?>
                        </div>
                        
                    </div> 
                
                    
                    <hr>
                        
                        <?
                        $perfil_id = $this->session->userdata('perfil_id');
                        ?>

<select name="medico" id="medico" class="size2" <?= ($perfil_id == 1) ? '' : "style='display:none;'"; ?>>
                                <? foreach ($operadores as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>"<?
                                    if (@$obj->_medico_parecer1 == $value->operador_id):echo "selected = 'true'";
                                    endif;
                                    ?>><?= $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>

                            <input type="hidden" name="status" id="status" value="<?= @$obj->_status; ?>" class="size2" />
                        <!-- <div>
                            <label <?= ($perfil_id == 1) ? '' : "style='display:none;'"; ?> >M&eacute;dico respons&aacutevel</label>


                            <select name="medico" id="medico" class="size2" <?= ($perfil_id == 1) ? '' : "style='display:none;'"; ?>>
                                <? foreach ($operadores as $value) : ?>
                                    <option value="<?= $value->operador_id; ?>"<?
                                    if (@$obj->_medico_parecer1 == $value->operador_id):echo "selected = 'true'";
                                    endif;
                                    ?>><?= $value->nome; ?></option>
                                        <? endforeach; ?>
                            </select>

                            <? if ($empresapermissao[0]->desativar_personalizacao_impressao != 't') { ?>
                                <?php
                                if (@$obj->_assinatura == "t") {
                                    ?>
                                    <input type="checkbox" name="assinatura" checked ="true" /><label>Assinatura</label>
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="assinatura"  /><label>Assinatura</label>
                                    <?php
                                }
                                ?>

                                <?php
                                if (@$obj->_rodape == "t") {
                                    ?>
                                    <input type="checkbox" name="rodape" checked ="true" /><label>Rodape</label>
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" name="rodape"  /><label>Rodape</label>
                                    <?php
                                }
                                ?>

                            <? } ?>
                            <label>situa&ccedil;&atilde;o</label>
                            <select name="situacao" id="situacao" class="size2" ">
                                <option value='DIGITANDO'<?
                                if (@$obj->_status == 'DIGITANDO'):echo "selected = 'true'";
                                endif;
                                ?> >DIGITANDO</option>
                                <option value='FINALIZADO' <?
                                if (@$obj->_status == 'FINALIZADO'):echo "selected = 'true'";
                                endif;
                                ?> >FINALIZADO</option>
                            </select>
                            <input type="hidden" name="status" id="status" value="<?= @$obj->_status; ?>" class="size2" />

                            <label style="margin-left: 10pt" for="ret">Retorno?</label>
                            <input type="checkbox" name="ret" id="ret" <?= ( (int) @$obj->_dias_retorno != '0') ? 'checked' : '' ?>/>
                            <div style="display: inline-block" class="dias_retorno_div">
                                <input type="text" name="ret_dias" id="ret_dias" value="<?= @$obj->_dias_retorno; ?>" style="width: 80pt" />
                            </div>

                            <label style="margin-left: 10pt" for="rev">Revisão?</label>
                            <input type="checkbox" name="rev" id="rev" />
                            <div class="dias" style="display: inline">

                            </div>
                        </div>
                        <button type="submit" onclick="tinyMCE.triggerSave();" name="btnEnviar">Salvar</button>
                        <button type="submit" onclick="tinyMCE.triggerSave();" name="btnFinalizar">Salvar e Fechar</button>
                        <br>
                        
                </div> -->

            </div>

            <?
                if (@$empresapermissao[0]->modelos_atendimento != '') {
                    $modelo_atendimento = json_decode(@$empresapermissao[0]->modelos_atendimento);
                } else {
                    $modelo_atendimento = array();
                }

                // print_r($modelo_atendimento);
            ?>

            <table>
            <tr>
            <td>
            <div class="bt_link_new" style="display: inline-block">
                <!-- <a onclick="javascript:window.open('<?= base_url() ?>centrocirurgico/centrocirurgico/novasolicitacao/0/<?= $ambulatorio_laudo_id ?>');" >
                    Solicitar Cirurgia
                </a> -->
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelolaudo/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  Modelos de Exame </font>
                </a>
            </div>
            </td>

            <? if (in_array('receitas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloreceita/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  *Receitas </font>
                </a>
             </div>
            </td>
            <? } ?>


            <? if (in_array('receitas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloreceitaespecial/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  #R. Especial </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('S_exames', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelosolicitarexames/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  @S. Exames </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('teraupeticas', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloterapeuticas/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1">  $Soli. Terapeuticas </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('relatorio', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelorelatorio/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1"> %Relatorio </font>
                </a>
             </div>
            </td>
            <? } ?>

            <? if (in_array('protocolo', $modelo_atendimento)) { ?>
            <td>
            <div class="bt_link_new">
                <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modeloprotocolo/pesquisar2"; ?> ', '_blank', 'toolbar=no,Location=no,menubar=no,width=900,height=650');">
                    <font size="-1"> !Protocolo </font>
                </a>
             </div>
            </td>
            <? } ?>

             </tr>
             </table>
                
                
            </form>

           
            <hr>

                <?
                
                function QueixaPrincipal($texto)
                {
                    $texto = strip_tags($texto, '<p></p><br>');
                    $texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $texto);
                    $texto = str_replace("<!DOCTYPE html>", '', $texto);
                    $texto = str_replace("<head>", '', $texto);
                    $texto = str_replace("</head>", '', $texto);
                    $texto = str_replace("<html>", '', $texto);
                    $texto = str_replace("<body>", '', $texto);
                    $texto = str_replace("<p>", '', $texto);
                    $texto = str_replace("</p>", '<br>', $texto);
                    $texto = str_replace("</html>", '', $texto);
                    $texto = str_replace("</body>", '', $texto);
                    $texto = str_replace('align="center"', '', $texto);
                    $texto = str_replace('align="left"', '', $texto);
                    $texto = str_replace('align="right"', '', $texto);
                    $texto = str_replace('align="justify"', '', $texto);

                    $texto = str_replace('!', '<font class="transparente">!</font>', $texto);
                    $texto = str_replace('@', '<font class="transparente">@</font>', $texto);
                    $texto = str_replace('#', '<font class="transparente">#</font>', $texto);
                    $texto = str_replace('$', '<font class="transparente">$</font>', $texto);
                    $texto = str_replace('%', '<font class="transparente">%</font>', $texto);
                    $texto = str_replace('*', '<font class="transparente">*</font>', $texto);

                    // $texto = substr($texto, 0, 35);
                    return $texto;
                }
                ?>
            <fieldset>
                <legend><b><font size="3" color="red">Histórico Paciente</font></b></legend>
                <fieldset>
                                                <legend><b><font size="3" color="red">Consultas</font></b></legend>
                                                <div>
                                                    <?
                                                    $contador_teste = 0;

                                                    foreach ($historico as $item) {
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td ><font size="3"><b>Data:</b> <?= substr($item->data_cadastro, 8, 2) . "/" . substr($item->data_cadastro, 5, 2) . "/" . substr($item->data_cadastro, 0, 4); ?></font></td>
                                                                <?
                                                                $idade = 0;
                                                                $dataFuturo2 = $item->data_cadastro;
                                                                $dataAtual2 = @$obj->_nascimento;
                                                                if ($dataAtual2 != '') {
                                                                    $date_time2 = new DateTime($dataAtual2);
                                                                    $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                                                                    $teste2 = $diff2->format('%Y');
                                                                    $idade = $teste2;
                                                                }
                                                                ?>
                                                                    <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                                    <td ><b>Idade no atendimento: </b><?= $idade ?> Anos</td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3"><b><font size="3">Medico: </b><?= $item->medico; ?></font></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3"><?= QueixaPrincipal($item->texto); ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                                <br>
                                                    <? }
                                                    ?>
                                                </div>
                                                <?
                                                if (count($historico) == 0 || $contador_teste < count($historicowebcon)) {
                                                    while ($contador_teste < count($historicowebcon)) {
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td><span style="color: #007fff">Integração</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Empresa: <?= $historicowebcon[$contador_teste]->empresa; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Data: <?= substr($historicowebcon[$contador_teste]->data, 8, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 5, 2) . "/" . substr($historicowebcon[$contador_teste]->data, 0, 4); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Medico: <?= $historicowebcon[$contador_teste]->medico_integracao; ?></td>
                                                                </tr>

                                                                <tr>
                                                                    <td >Tipo: <?= $historicowebcon[$contador_teste]->procedimento; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >Queixa principal: <?= $historicowebcon[$contador_teste]->texto; ?></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                        <hr>

                                                        <?
                                                        $contador_teste++;
                                                    }
                                                }
                                                ?>



                                            </fieldset>

                                           
                                            
            </fieldset>

    </div> 
</div> 
</div> 
</div> <!-- Final da DIV content -->
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 1300px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<!--<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">-->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script> -->
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui.js" ></script>


<script type="text/javascript">

    $(function () {
        $("#horario").mask("99:99");
    });

    function marcardesmarcar(){
    $(".selecionar_todos").each(
        function() {
            if ($(this).prop("checked")) {
                $(".impressao_definitiva").prop("checked", true);
            } else {
                $(".impressao_definitiva").prop("checked", false);
            }
        }
    )};

    function marcartodosreceita(){
    $("#marcartodosreceitasimpes").each(
        function() {
            if ($(this).prop("checked")) {
                $(".rec_simples").prop("checked", true);
            } else {
                $(".rec_simples").prop("checked", false);
            }
        }
    )};

    function marcartodosreceitae(){
    $("#marcartodosreceitaespecial").each(
        function() {
            if ($(this).prop("checked")) {
                $(".rec_especial").prop("checked", true);
            } else {
                $(".rec_especial").prop("checked", false);
            }
        }
    )};

    function marcartodosexames(){
    $("#marcartodossolicitaexames").each(
        function() {
            if ($(this).prop("checked")) {
                $(".s_exames_c").prop("checked", true);
            } else {
                $(".s_exames_c").prop("checked", false);
            }
        }
    )};

    function marcartodosrelatorio(){
    $("#marcartodosrelatorios").each(
        function() {
            if ($(this).prop("checked")) {
                $(".relatorio_c").prop("checked", true);
            } else {
                $(".relatorio_c").prop("checked", false);
            }
        }
    )};

    function marcartodosterapeutica(){
    $("#marcartodosterapeuticas").each(
        function() {
            if ($(this).prop("checked")) {
                $(".terapeutica_c").prop("checked", true);
            } else {
                $(".terapeutica_c").prop("checked", false);
            }
        }
    )};

    function Excluirarquivo(url){
        if(confirm('Deseja realmente Excluir esse arquivo?')){
        window.open(url);
        } 
    }

    $(function () {

        $('#informacao_laudo').on('input', function () {
            $('#informacao_laudo_2').val(this.value);
            $('#informacao_laudo_3').val(this.value);
            $('#informacao_laudo_4').val(this.value);
            $('#informacao_laudo_5').val(this.value);
        });
    });


    $(function () {

        $('#informacao_laudo_2').on('input', function () {
            $('#informacao_laudo').val(this.value);
            $('#informacao_laudo_3').val(this.value);
            $('#informacao_laudo_4').val(this.value);
            $('#informacao_laudo_5').val(this.value);
        });
    });

    $(function () {

    $('#informacao_laudo_3').on('input', function () {
            $('#informacao_laudo').val(this.value);
            $('#informacao_laudo_2').val(this.value);
            $('#informacao_laudo_4').val(this.value);
            $('#informacao_laudo_5').val(this.value);
        });
    });

    $(function () {

    $('#informacao_laudo_4').on('input', function () {
            $('#informacao_laudo').val(this.value);
            $('#informacao_laudo_2').val(this.value);
            $('#informacao_laudo_3').val(this.value);
            $('#informacao_laudo_5').val(this.value);
        });
    });

    $(function () {

    $('#informacao_laudo_5').on('input', function () {
            $('#informacao_laudo').val(this.value);
            $('#informacao_laudo_2').val(this.value);
            $('#informacao_laudo_3').val(this.value);
            $('#informacao_laudo_4').val(this.value);
        });
    });

    $("#mostrarDadosExtras").click(function () {
            var botao = $("#mostrarDadosExtras").text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras").text('-');
            } else {
                $("#mostrarDadosExtras").text('+');
            }                                       
            $("#DadosExtras").toggle();

        });

        $("#mostrarDadosReceitas").click(function () {
            var botao = $("#mostrarDadosReceitas").text();
            
            if (botao == '+') {
                $("#mostrarDadosReceitas").text('-');
            } else {
                $("#mostrarDadosReceitas").text('+');
            }

            $(".mostrartodasreceitas").toggle();
            });

        $("#mostrarDadosReceitasEspecial").click(function () {
            var botao = $("#mostrarDadosReceitasEspecial").text();
            
            if (botao == '+') {
                $("#mostrarDadosReceitasEspecial").text('-');
            } else {
                $("#mostrarDadosReceitasEspecial").text('+');
            }

            $(".mostratodasreceitasespecial").toggle();
            });

        $("#mostrarDadosExames").click(function () {
            var botao = $("#mostrarDadosExames").text();
            
            if (botao == '+') {
                $("#mostrarDadosExames").text('-');
            } else {
                $("#mostrarDadosExames").text('+');
            }

            $(".mostrartodosexames").toggle();
            });

        $("#mostrarDadosTerapeuticas").click(function () {
            var botao = $("#mostrarDadosTerapeuticas").text();
            
            if (botao == '+') {
                $("#mostrarDadosTerapeuticas").text('-');
            } else {
                $("#mostrarDadosTerapeuticas").text('+');
            }

            $(".mostrartodosterapeuticas").toggle();
            });

        $("#mostrarDadosRelatorio").click(function () {
            var botao = $("#mostrarDadosRelatorio").text();
            
            if (botao == '+') {
                $("#mostrarDadosRelatorio").text('-');
            } else {
                $("#mostrarDadosRelatorio").text('+');
            }

            $(".mostrartodosrelatorios").toggle();
            });



        function mostrarDadosExtrasreceita(ambulatorio_receituario_id){
            var botao = $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('-');
                $('#receita_destaque'+ambulatorio_receituario_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('+');
                $('#receita_destaque'+ambulatorio_receituario_id).removeClass('destaque');
            }                                       
            $("#tr_receita_"+ambulatorio_receituario_id).toggle();

    }

    function mostrarDadosExtrasexames(ambulatorio_exame_id){
            var botao = $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('-');
                $('#exames_destaque'+ambulatorio_exame_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('+');
                $('#exames_destaque'+ambulatorio_exame_id).removeClass('destaque');
            }                                       
            $("#tr_exames_"+ambulatorio_exame_id).toggle();

    }

    function mostrarDadosExtrasterapeuticas(ambulatorio_terapeutica_id){
            var botao = $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text('-');
                $('#terapeuticas_destaque'+ambulatorio_terapeutica_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text('+');
                $('#terapeuticas_destaque'+ambulatorio_terapeutica_id).removeClass('destaque');
            }                                       
            $("#tr_terapeuticas_"+ambulatorio_terapeutica_id).toggle();

    }

    function mostrarDadosExtrasrelatorio(ambulatorio_relatorio_id){
            var botao = $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('-');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('+');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).removeClass('destaque');
            }                                       
            $("#tr_relatorios_"+ambulatorio_relatorio_id).toggle();

    }

    function ExcluirModelos(modelo, id){
        console.log(modelo);
        console.log(id);
            $.getJSON('<?= base_url() ?>ambulatorio/laudo/excluirmodelos/'+ modelo + '/'+ id, {ajax: true}, function (j) {
                alert(modelo + ' Excluido com Sucesso');

                submitted = true;
                window.location.reload();
            });
    }

    function SalvarReceita(ambulatorio_receituario_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('receita_'+ambulatorio_receituario_id).getContent();
        // console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarreceita_rapido', {ambulatorio_receituario_id: ambulatorio_receituario_id, texto: texto, ajax: true}, function (j) {
            // console.log(j)

            var botao = $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('-');
                $('#receita_destaque'+ambulatorio_receituario_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_receita"+ambulatorio_receituario_id).text('+');
                $('#receita_destaque'+ambulatorio_receituario_id).removeClass('destaque');
            } 
            $("#tr_receita_"+ambulatorio_receituario_id).toggle();

        });

    }

    function SalvarExames(ambulatorio_exame_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('exames_'+ambulatorio_exame_id).getContent();
        // console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarexames_rapido', {ambulatorio_exame_id: ambulatorio_exame_id, texto: texto, ajax: true}, function (j) {
            // console.log(j)

            var botao = $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('-');
                $('#exames_destaque'+ambulatorio_exame_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_exames"+ambulatorio_exame_id).text('+');
                $('#exames_destaque'+ambulatorio_exame_id).removeClass('destaque');
            } 
            $("#tr_exames_"+ambulatorio_exame_id).toggle();

        });

    }

    function SalvarTerapeuticas(ambulatorio_terapeutica_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('terapeuticas_'+ambulatorio_terapeutica_id).getContent();
        // console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarterapeuticas_rapido', {ambulatorio_terapeutica_id: ambulatorio_terapeutica_id, texto: texto, ajax: true}, function (j) {
            // console.log(j)

            var botao = $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text('-');
                $('#terapeuticas_destaque'+ambulatorio_terapeutica_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_terapeuticas"+ambulatorio_terapeutica_id).text('+');
                $('#terapeuticas_destaque'+ambulatorio_terapeutica_id).removeClass('destaque');
            } 
            $("#tr_terapeuticas_"+ambulatorio_terapeutica_id).toggle();

        });

    }

    function SalvarRelatorios(ambulatorio_relatorio_id){
        tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.getContent({format : 'raw'});
        var texto = tinyMCE.get('relatorios_'+ambulatorio_relatorio_id).getContent();
        // console.log(texto);

        $.getJSON('<?= base_url() ?>autocomplete/salvarrelatorios_rapido', {ambulatorio_relatorio_id: ambulatorio_relatorio_id, texto: texto, ajax: true}, function (j) {
            // console.log(j)

            var botao = $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text();                                        

            if (botao == '+') {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('-');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).addClass('destaque');
            } else {
                $("#mostrarDadosExtras_relatorios"+ambulatorio_relatorio_id).text('+');
                $('#relatorios_destaque'+ambulatorio_relatorio_id).removeClass('destaque');
            } 
            $("#tr_relatorios_"+ambulatorio_relatorio_id).toggle();

        });

    }
        function Modal_assinar() {
            $("#removemodal").remove();
            $("#myModalAssinar").append(
                "<div class='modal-content' id='removemodal'>"+
                    "<div class='modal-header'>"+
                        "<span class='close'>&times;</span>"+
                        "<h2>Renomear Arquivo</h2>"+
                    "</div>"+
                    "<div class='modal-body'>"+
                        // "<br><br>"+
                        "<h1> Codigo BirdID:</h1>"+
                        "<table border='0'>"+
                        "<tr><td colspan='2'>Obs: Esse campo deve ser preenchido com a Senha do Certificado do BirdID, caso a assinatura não seja pelo BirdID favor deixar o campo em branco!</td></tr>"+
                        "<tr><th>Código</th>"+
                        "<td><input type='text' id='certificado_birdID' class='texto1' name='certificado_birdID' value=''></td></tr>"+
                        "</table>"+
                        "<h1> Emails Paciente:</h1>"+
                        "<table border='0'>"+
                        "<tr><th>Email</th>"+
                        "<td><input type='text' id='txtCns' class='texto10' name='cns' onchange='validaremail()' value='<?=@$obj->_email?>'></td></tr>"+
                        "<tr><th>Email Alternativo</th>"+
                        "<td><input type='text' id='txtCns2' class='texto10' name='cns2' onchange='validaremail2()' value='<?=@$obj->_email2?>'></td></tr>"+
                        "</table>"+
                        "<h1> Informações Email Paciente:</h1>"+
                        "<textarea name='textoadicional' id='textoadicional' rows='5' cols='110'>Essas são receitas eletrônicas, assinadas digitalmente. Não é necessário imprimi-las. Basta encaminhar o arquivo para o farmacêutico, da farmácia onde irá comprar. O farmacêutico tem que abrir o arquivo em um leitor de PDF e ele também tem que assina-la digitalmente. Uma vez assinada digitalmente pela farmácia, ele envia o arquivo para o site https://assinaturadigital.iti.gov.br/ e valida a mesma.</textarea>"+
                        "<br><br>"+
                    "</div>"+
                    "<div class='modal-footer'>"+
                        "<h3><button type='button' class='btn_renomear_2' name='assinar' onclick='AssinareEnviarOnline()'> <b>Assinar e Enviar</b> </button></h3>"+
                    "</div>"+
                "</div>");

                $("#myModalAssinar").toggle();
                // myModal

                
                    var modal = document.getElementById("myModalAssinar");

                    var btn = document.getElementById("myBtn");

                    var span = document.getElementsByClassName("close")[0];

                    btn.onclick = function() {
                    modal.style.display = "block";
                    }

                    span.onclick = function() {
                    modal.style.display = "none";
                    }

                    window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                    }
        }

    function AssinareEnviarOnline(){
        $("#myModalAssinar").toggle();
        var impressoesGerais = [];

        var c_receita = jQuery('input[class^="rec_simples"]:checked:enabled');
        var c_receita_especial = jQuery('input[class^="rec_especial"]:checked:enabled');
        var c_exames = jQuery('input[name^="s_exames_imprimir"]:checked:enabled');
        var c_terapeuticas = jQuery('input[name^="terapeuticas_imprimir"]:checked:enabled');
        var c_relatorios = jQuery('input[name^="relatorio_imprimir"]:checked:enabled');

        for(var i = 0; i < c_receita.length; i++){
            impressoesGerais.push('receita_'+$(c_receita[i]).val()+'.pdf');
        }
        for(var i = 0; i < c_receita_especial.length; i++){
            impressoesGerais.push('receituarioEspecial_'+$(c_receita_especial[i]).val()+'.pdf');
        }
        for(var i = 0; i < c_exames.length; i++){
            impressoesGerais.push('solicitacaoexames_'+$(c_exames[i]).val()+'.pdf');
        }
        for(var i = 0; i < c_terapeuticas.length; i++){
            impressoesGerais.push('terapeuticas_'+$(c_terapeuticas[i]).val()+'.pdf');
        }
        for(var i = 0; i < c_relatorios.length; i++){
            impressoesGerais.push('relatorios_'+$(c_relatorios[i]).val()+'.pdf');
        }

        var email = $("#txtCns").val();
        var email2 = $("#txtCns2").val();
        var textoadicional = $("#textoadicional").val();
        var certificado_birdID = $('#certificado_birdID').val();

        if(certificado_birdID == ''){
            certificado_birdID = 0;
        }
        
        if(impressoesGerais.length == 0){
            alert('Selecione alguma Impressão, para que possa Assinar e Imprimir');
        }else{
            $.ajax({
                type: "GET",
                data: {
                    impressoesGerais: impressoesGerais
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                      window.open('<?= base_url() ?>ambulatorio/laudo/assinareenviaronline/<?= $ambulatorio_laudo_id ?>/<?=@$obj->_medico_parecer1?>/'+impressoesGerais+'/'+email+'/'+email2+'/'+certificado_birdID+'/'+textoadicional);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
        }

        // console.log(impressoesGerais);
    }

    function validaremail(){
        var email = $("#txtCns").val();
        var email2 = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns").val('');
                }else if(email == email2){
                    alert('O E-mail não pode ser Igual ao E-mail Alternativo');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function validaremail2(){
        var email2 = $("#txtCns").val();
        var email = $("#txtCns2").val();
        if(email != ''){
            $.getJSON('<?= base_url() ?>autocomplete/verificaremailpaciente', {email: email,  ajax: true}, function (j) {
                if(j != ''){
                    alert(j);
                    $("#txtCns2").val('');
                }else if(email == email2){
                    alert('O E-mail Alternativo não pode ser Igual ao E-mail');
                    $("#txtCns2").val('');
                }
            });
        }
    }

    function abrirImpressaoTudoAssinar(){
        var inputs = jQuery('input[name^="impressanavegador"]:checked:enabled');
        var impressoesGerais = [];
        for(var i = 0; i < inputs.length; i++){
            impressoesGerais.push($(inputs[i]).val());
        }

        if(impressoesGerais.length == 0){
            alert('Selecione alguma Impressão, para que possa Assinar e Imprimir');
        }else{
            $.ajax({
                type: "GET",
                data: {
                    impressoesGerais: impressoesGerais
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                      window.open('<?= base_url() ?>ambulatorio/laudo/assinareenviaronline/<?= $ambulatorio_laudo_id ?>/<?=@$obj->_medico_parecer1?>/'+impressoesGerais);
                    //  alert('deu certo');
                    //console.log(data);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
        }
    }

    function abrirVisualizarTudo(){
        var receitas = [];
        var receitaespecial = [];
        var relatorios = [];
        var solicitacaoexames = [];
        var terapeuticas = [];


        var c_receita = jQuery('input[class^="rec_simples"]:checked:enabled');
        var c_receita_especial = jQuery('input[class^="rec_especial"]:checked:enabled');
        var c_exames = jQuery('input[name^="s_exames_imprimir"]:checked:enabled');
        var c_terapeuticas = jQuery('input[name^="terapeuticas_imprimir"]:checked:enabled');
        var c_relatorios = jQuery('input[name^="relatorio_imprimir"]:checked:enabled');
        //var repetir_impressao = [];
        var sem_data_r_especial = $('input[name^="sem_data_r_especial"]:checked:enabled').val();
        var sem_data_r = $('input[name^="sem_data_r"]:checked:enabled').val();
        var sem_data_e = $('input[name^="sem_data_e"]:checked:enabled').val();



       

        for(var i = 0; i < c_receita.length; i++){
            receitas.push($(c_receita[i]).val());
           // var repetir = jQuery('input[name^="receita_simples_'+$(c_receita[i]).val()+'"]');
            //repetir_impressao.push(['receita_'+$(c_receita[i]).val() , $(repetir[0]).val()])

        }
        for(var i = 0; i < c_receita_especial.length; i++){
            receitaespecial.push($(c_receita_especial[i]).val());
            //var repetir = jQuery('input[name^="receita_especial_'+$(c_receita_especial[i]).val()+'"]');
            //repetir_impressao.push(['receituarioEspecial_'+$(c_receita_especial[i]).val() , $(repetir[0]).val()])
        }
        for(var i = 0; i < c_exames.length; i++){
            solicitacaoexames.push($(c_exames[i]).val());
            //var repetir = jQuery('input[name^="s_exames_'+$(c_exames[i]).val()+'"]');
            //repetir_impressao.push(['solicitacaoexames_'+$(c_exames[i]).val() , $(repetir[0]).val()])
        }
        for(var i = 0; i < c_terapeuticas.length; i++){
            terapeuticas.push($(c_terapeuticas[i]).val());
            //var repetir = jQuery('input[name^="terapeuticas_r'+$(c_terapeuticas[i]).val()+'"]');
            //repetir_impressao.push(['terapeuticas_'+$(c_terapeuticas[i]).val() , $(repetir[0]).val()])
        }
        for(var i = 0; i < c_relatorios.length; i++){
            relatorios.push($(c_relatorios[i]).val());
            //var repetir = jQuery('input[name^="relatorios_r'+$(c_relatorios[i]).val()+'"]');
            //repetir_impressao.push(['relatorios_'+$(c_relatorios[i]).val() , $(repetir[0]).val()])
        }


       // console.log(receitaespecial);

        if(receitas.length == 0){
            receitas = [0];
        }
        if(receitaespecial.length == 0){
            receitaespecial = [0];
        }
        if(solicitacaoexames.length == 0){
            solicitacaoexames = [0];
        }
        if(terapeuticas.length == 0){
            terapeuticas = [0];
        }
        if(relatorios.length == 0){
            relatorios = [0];
        }


        if(receitas[0] == 0 && receitaespecial[0] == 0 && relatorios[0] == 0 && solicitacaoexames[0] == 0 && terapeuticas[0] == 0){
                alert('Selecione alguma Impressão, para que possa Imprimir');
            }else{
                $.ajax({
                type: "POST",
                data: {
                    receitas: receitas,
                    receitaespecial: receitaespecial,
                    relatorios: relatorios,
                    solicitacaoexames: solicitacaoexames,
                    terapeuticas: terapeuticas,
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudotudonovo/<?= $ambulatorio_laudo_id ?>/'+solicitacaoexames+'/'+relatorios+'/'+terapeuticas+'/'+sem_data_e);
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitatodosnovo/<?= $ambulatorio_laudo_id?>/'+receitas+'/'+sem_data_r);
                    window.open('<?= base_url() ?>ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/<?= $ambulatorio_laudo_id ?>/'+receitaespecial+'/'+sem_data_r_especial);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
            }

        // console.log(receitas);
    }

    function abrirImpressaoTudo(){
        var inputs = jQuery('input[name^="impressanavegador"]:checked:enabled');
        var impressoes = jQuery('input[class^="quantidade_de_impressoes"]');
        var values = [];
        var impressao_repetir = [];
        for(var i = 0; i < inputs.length; i++){
            values.push($(inputs[i]).val());
            impressao_repetir.push([$(inputs[i]).val(), $(impressoes[i]).val()]);
        }

        var receitas = [];
        var receitaespecial = [];
        var relatorios = [];
        var solicitacaoexames = [];
        var terapeuticas = [];
        
        values.forEach(Arrayimpressao);

        function Arrayimpressao(item, index) {
            var textonovo = item.replace(".pdf", "");
            var retorno = textonovo.split("_");

            if(retorno[0] == 'receita'){
                receitas.push(retorno[1]);
            }else if(retorno[0] == 'receituarioEspecial'){
                receitaespecial.push(retorno[1]);
            }else if(retorno[0] == 'relatorios'){
                relatorios.push(retorno[1]);
            }else if(retorno[0] == 'solicitacaoexames'){
                solicitacaoexames.push(retorno[1]);
            }else if(retorno[0] == 'terapeuticas'){
                terapeuticas.push(retorno[1]);
            }
        }


        if(solicitacaoexames.length == 0){
            solicitacaoexames = [0];
        }
        if(relatorios.length == 0){
            relatorios = [0];
        }
        if(terapeuticas.length == 0){
            terapeuticas = [0];
        }
        if(receitaespecial.length == 0){
            receitaespecial = [0];
        }
        if(receitas.length == 0){
            receitas = [0];
        }

            if(receitas[0] == 0 && receitaespecial[0] == 0 && relatorios[0] == 0 && solicitacaoexames[0] == 0 && terapeuticas[0] == 0){
                alert('Selecione alguma Impressão, para que possa Imprimir');
            }else{
                $.ajax({
                type: "POST",
                data: {
                    receitas: receitas,
                    receitaespecial: receitaespecial,
                    relatorios: relatorios,
                    solicitacaoexames: solicitacaoexames,
                    terapeuticas: terapeuticas,
                    },
                url: "<?= base_url() ?>ambulatorio/laudo/imprimirnovoatendimento/<?=$ambulatorio_laudo_id?>",
                success: function(data) {
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudotudonovo/<?= $ambulatorio_laudo_id ?>/'+solicitacaoexames+'/'+relatorios+'/'+terapeuticas+'/'+impressao_repetir);
                    window.open('<?= base_url() ?>ambulatorio/laudo/impressaoreceitatodosnovo/<?= $ambulatorio_laudo_id?>/'+receitas+'/'+impressao_repetir);
                    window.open('<?= base_url() ?>ambulatorio/laudo/imprimirReceitaEspecialTodosnovo/<?= $ambulatorio_laudo_id ?>/'+receitaespecial+'/'+impressao_repetir);
                    // alert('deu certo');
                    //console.log(data);
                },
                error: function (data) {
                    alert('Erro ao Imprimir');
                    }
                });
            }
    }


    function enviarFormulario(){
        tinyMCE.triggerSave();
        saiuDoLaudo();
        $('#form_laudo').submit();
    }

    function saiuDoLaudo(){
        $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
        
        });
    }

    var submitted = false;

    $("#form_laudo").submit(function() {
     submitted = true;
     });

    window.onbeforeunload = function (e) {
    e = e || window.event;

    // For IE and Firefox prior to version 4
    if (e && !submitted) {
        e.returnValue = 'Sure?';
    }

    $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
        
    });

    if(!submitted){
        // For Safari
        return 'Sure?';
    }
};




    setTimeout(function(){
        timeout();
    }, 500);

    function timeout() {
    setTimeout(function () {
        tinyMCE.triggerSave();
        timeout();
    }, 1000);
}


// ------------------------------------------------------
let drop_ = document.querySelector('.area-upload #upload-file');
drop_.addEventListener('dragenter', function(){
	document.querySelector('.area-upload .label-upload').classList.add('highlight');
});
drop_.addEventListener('dragleave', function(){
	document.querySelector('.area-upload .label-upload').classList.remove('highlight');
});
drop_.addEventListener('drop', function(){
	document.querySelector('.area-upload .label-upload').classList.remove('highlight');
});

document.querySelector('#upload-file').addEventListener('change', function() {
	var files = this.files;
	for(var i = 0; i < files.length; i++){
		var info = validarArquivo(files[i]);
		
		//Criar barra
		var barra = document.createElement("div");
		var fill = document.createElement("div");
		var text = document.createElement("div");
		barra.appendChild(fill);
		barra.appendChild(text);
		
		barra.classList.add("barra");
		fill.classList.add("fill");
		text.classList.add("text");
		
		if(info.error == undefined){
			text.innerHTML = info.success;
			enviarArquivo(i, barra, 'upload-file'); //Enviar
		}else{
			text.innerHTML = info.error;
			barra.classList.add("error");
		}
		
		//Adicionar barra
         document.querySelector('.lista-uploads').appendChild(barra);
	};
});

function validarArquivo(file){
	// console.log(file);
	// Tipos permitidos
	var mime_types = [ 'image/jpeg', 'image/png', 'application/pdf', 
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/x-rar-compressed', 'application/vnd.zul', 'text/plain'];
	
	// Validar os tipos
	if(mime_types.indexOf(file.type) == -1) {
		return {"error" : "O arquivo " + file.name + " não permitido"};
	}

	// Apenas 50MB é permitido
	if(file.size > 52428800) {
		return {"error" : file.name + " ultrapassou limite de 50MB"};
	}

	// Se der tudo certo
	return {"success": "Enviando: " + file.name};
}

function enviarArquivo(indice, barra, upload){
	var data = new FormData();
	var request = new XMLHttpRequest();
	
	//Adicionar arquivo
	data.append('file', document.querySelector('#'+upload).files[indice]);
	
	// AJAX request finished
	request.addEventListener('load', function(e) {
		// Resposta
		if(request.response.status == "success"){
			barra.querySelector(".text").innerHTML = "<a href=\"" + request.response.path + "\" target=\"_blank\">" + request.response.name + "</a> <i class=\"fas fa-check\"></i>";
			barra.classList.add("complete");
		}else{
			barra.querySelector(".text").innerHTML = "Erro ao enviar: " + request.response.name;
			barra.classList.add("error");
		}
	});
	
	// Calcular e mostrar o progresso
	request.upload.addEventListener('progress', function(e) {
		var percent_complete = (e.loaded / e.total)*100;
		
		barra.querySelector(".fill").style.minWidth = percent_complete + "%"; 
	});
	
	//Resposta em JSON
	request.responseType = 'json';
	var caminho = '<?= base_url().'ambulatorio/laudo/importararrastaresoltar/'.$ambulatorio_laudo_id.'/'.$paciente_id;?>';
	// Caminho
	request.open('post', caminho); 
	request.send(data);
}

//  -----------------------------------------------------

   window.onload = function(){
   
        window.addEventListener("beforeunload", function(e){
        // Do something
            $.getJSON('<?= base_url() ?>autocomplete/saiuDoLaudo', {laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                
            });
        }, false);
        atualizarTextArea();       
//         document.getElementById('aih').onclick = function(){
// //                alert( tinyMCE.get('laudo').getContent() );
//                 var texto_antigo2  =  tinyMCE.get('laudo').getContent();
//                 <?= base_url() ?>ambulatorio/laudo/imprimirmodeloaih/<?= $ambulatorio_laudo_id ?>
                
//                  var texto_adicional_html2 = "</html>";
//                  var texto_adicional_body2 = "</body>";
//                  var texto_adicional_body3 = "<body>";
//                  var texto_adicional_html3 = "<html>";
//                   var texto_adicional_head2 = "<head>";
//                    var texto_adicional_head3 = "</head>";
//                    var texto_adicional_DOCTYPE2 = "<!DOCTYPE html>";
                 
//                  //aqui ele tira todas as tags html e body
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_head2, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_head3, "");
//                  texto_antigo2 = texto_antigo2.replace(texto_adicional_DOCTYPE2, "");   
//                //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
//                    var colocartexto = texto_antigo2 ;
                     
//                       s = encodeURIComponent(colocartexto);
//                       testess =  s.replace(/~/g,'%7E').replace(/%20/g,'+');
// //                   alert(testess);
     
//                var ambulatorio_laudo_id = <?= $ambulatorio_laudo_id ?>;
//                url = '<?= base_url() ?>ambulatorio/laudo/imprimirmodeloaih/'+<?= $ambulatorio_laudo_id ?>+'?texto='+testess;
//                $("#aih").attr("href", url);
   
//         }
}
                                        function valueChanged()
                                            {
                                                if($('#rev').is(":checked"))   
                                                    $("#revisoes").show();
                                                else
                                                    $("#revisoes").hide();
                                            }
                                            
                                            jQuery('#rev').change(function () {
                                            if (this.checked) {
                                            var tag = '<input type="number" class="texto01" name="tempoRevisao"><label> Meses</label>';
                                            jQuery(".dias").append(tag);
                                            } else {
                                            jQuery(".dias span").remove();
                                            jQuery(".dias input").remove();
                                            jQuery(".dias label").remove();
                                            }
                                            });
<? if ((int) @$obj->_dias_retorno != '0') { ?>
                                                jQuery(".dias_retorno_div").show();
<? } else { ?>
                                                jQuery(".dias_retorno_div").hide();
<? } ?>

                                            jQuery('#ret').change(function () {
                                            if (this.checked) {
                                            jQuery(".dias_retorno_div").show();
                                            } else {
                                            jQuery(".dias_retorno_div").hide();
                                            }
                                            });
                                            // jQuery("#Altura").mask("999", {placeholder: " "});
                                            //                                                    jQuery("#Peso").mask("999", {placeholder: " "});

                                            ////////// ORDENANDO OS SELECTS DA OFTAMOLOGIA//////////////////

                                            function oftamologia_od_esferico() {
                                            var itensOrdenados = $('#oftamologia_od_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_esferico();
                                            function oftamologia_oe_esferico() {
                                            var itensOrdenados = $('#oftamologia_oe_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_esferico();
                                            function oftamologia_od_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_od_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_cilindrico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_cilindrico();
                                            function oftamologia_oe_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_oe_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_cilindrico ?>';
                                                $('#oftamologia_oe_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_cilindrico();
                                            function oftamologia_oe_eixo() {
                                            var itensOrdenados = $('#oftamologia_oe_eixo option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_eixo').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_eixo != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_eixo ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_eixo').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_eixo').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_eixo();
                                            function oftamologia_oe_av() {
                                            var itensOrdenados = $('#oftamologia_oe_av option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_oe_av').html(itensOrdenados);
<? if (@$obj->_oftamologia_oe_av != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_oe_av ?>';
                                                //        alert(teste);
                                                $('#oftamologia_oe_av').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_oe_av').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_oe_av();
                                            function oftamologia_od_eixo() {
                                            var itensOrdenados = $('#oftamologia_od_eixo option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_eixo').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_eixo != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_eixo ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_eixo').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_eixo').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_eixo();
                                            function oftamologia_od_av() {
                                            var itensOrdenados = $('#oftamologia_od_av option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_od_av').html(itensOrdenados);
<? if (@$obj->_oftamologia_od_av != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_od_av ?>';
                                                //        alert(teste);
                                                $('#oftamologia_od_av').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_od_av').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_od_av();
                                            function oftamologia_ad_esferico() {
                                            var itensOrdenados = $('#oftamologia_ad_esferico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_ad_esferico').html(itensOrdenados);
<? if (@$obj->_oftamologia_ad_esferico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_ad_esferico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_ad_esferico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_ad_esferico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_ad_esferico();
                                            function oftamologia_ad_cilindrico() {
                                            var itensOrdenados = $('#oftamologia_ad_cilindrico option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#oftamologia_ad_cilindrico').html(itensOrdenados);
<? if (@$obj->_oftamologia_ad_cilindrico != '') { ?>
                                                var teste = '<?= @$obj->_oftamologia_ad_cilindrico ?>';
                                                //        alert(teste);
                                                $('#oftamologia_ad_cilindrico').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#oftamologia_ad_cilindrico').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            oftamologia_ad_cilindrico();
                                            function acuidade_oe() {
                                            var itensOrdenados = $('#acuidade_oe option').sort(function (a, b) {
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            $('#acuidade_oe').html(itensOrdenados);
<? if (@$obj->_acuidade_oe != '') { ?>
                                                var teste = '<?= @$obj->_acuidade_oe ?>';
                                                //        alert(teste);
                                                $('#acuidade_oe').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#acuidade_oe').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            acuidade_oe();
                                            function acuidade_od() {
                                            var acuidade_oditensOrdenados = $('#acuidade_od option').sort(function (a, b) {
                                            //                        alert(b.text);
                                            return a.text < b.text ? - 1 : 1;
                                            });
                                            //        console.log(acuidade_oditensOrdenados);
                                            $('#acuidade_od').html(acuidade_oditensOrdenados);
<? if (@$obj->_acuidade_od != '') { ?>
                                                var teste = '<?= @$obj->_acuidade_od ?>';
                                                //        alert(teste);
                                                $('#acuidade_od').find('option:contains("' + teste + '")').prop('selected', true);
<? } else { ?>
                                                $('#acuidade_od').find('option:contains(" ")').prop('selected', true);
<? } ?>
                                            }
                                            acuidade_od();
                                            //////////////////////////////////////////////////



                                            function validar(dom, tipo) {
                                            switch (tipo) {
                                            case'num':
                                                    var regex = /[A-Za-z]/g;
                                            break;
                                            case'text':
                                                    var regex = /\d/g;
                                            break;
                                            }
                                            dom.value = dom.value.replace(regex, '');
                                            }


                                            pesob1 = document.getElementById('Peso').value;
                                            peso = parseFloat(pesob1.replace(',', '.'));
                                            //                                        peso = pesob1.substring(0, 2)  + "." + pesob1.substring(3, 1);
                                            alturae1 = document.getElementById('Altura').value;
                                            var res = alturae1.substring(0, 1) + "." + alturae1.substring(1, 3);
                                            var altura = parseFloat(res);
                                            imc = peso / Math.pow(altura, 2);
                                            //imc = res;
                                            resultado = imc.toFixed(2)
                                                    document.getElementById('imc').value = resultado.replace('.', ',');
                                            function calculaImc() {
                                            pesob1 = document.getElementById('Peso').value;
                                            peso = parseFloat(pesob1.replace(',', '.'));
                                            //                                        peso = pesob1.substring(0, 2)  + "." + pesob1.substring(3, 1);
                                            alturae1 = document.getElementById('Altura').value;
                                            var res = alturae1.substring(0, 1) + "." + alturae1.substring(1, 3);
                                            var altura = parseFloat(res);
                                            imc = peso / Math.pow(altura, 2);
                                            //imc = res;
                                            resultado = imc.toFixed(2)
                                                    document.getElementById('imc').value = resultado.replace('.', ',');
                                            }



                                            var sHors = "0" + 0;
                                            var sMins = "0" + 0;
                                            var sSecs = - 1;
                                            function getSecs() {
                                            sSecs++;
                                            if (sSecs == 60) {
                                            sSecs = 0;
                                            sMins++;
                                            if (sMins <= 9)
                                                    sMins = "0" + sMins;
                                            }
                                            if (sMins == 60) {
                                            sMins = "0" + 0;
                                            sHors++;
                                            if (sHors <= 9)
                                                    sHors = "0" + sHors;
                                            }
                                            if (sSecs <= 9)
                                                    sSecs = "0" + sSecs;
                                            clock1.innerHTML = sHors + "<font color=#000000>:</font>" + sMins + "<font color=#000000>:</font>" + sSecs;
                                            setTimeout('getSecs()', 1000);
                                            }


                                            $(document).ready(function () {
                                                buscarMedicamentosSADTAtend();
                                                buscarMedicamentosTomadaTotal();
                                                buscarAcompanhamentosMedico();
                                            });
                                            $(document).ready(function () {
                                            $('#sortable').sortable();
                                            });
                                            $(document).ready(function () {
                                            jQuery('#ficha_laudo').validate({
                                            rules: {
                                            imagem: {
                                            required: true
                                            }
                                            },
                                                    messages: {
                                                    imagem: {
                                                    required: "*"
                                                    }
                                                    }
                                            });
                                            });
                                            function muda(obj) {
                                            if (obj.value != 'DIGITANDO') {
                                            document.getElementById('titulosenha').style.display = "block";
                                            document.getElementById('senha').style.display = "block";
                                            } else {
                                            document.getElementById('titulosenha').style.display = "none";
                                            document.getElementById('senha').style.display = "none";
                                            }
                                            }

                                   

                                    function repetir(receita_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituario', {receita: receita_id, ajax: true}, function (j) {
                                                options = "";

                                                options += j[0].texto;
                                                if(j[0].especial == 't'){
                                                  $('#receituario_especial').prop('checked', true);  
                                                }

                                                $('#receituario').val(options);
                                                var ed = tinyMCE.get('receituario');
                                                ed.setContent($('#receituario').val());

                                                    
                                        }); 
                                    }

                                    function repetirpormes(receita_id) {

                                        submitted = true;

                                        var meses =  $('#meses_receita_'+receita_id).val();

                                        if(meses == ''){
                                            alert('Quantidade de Meses não pode ser Vazio');
                                        }else if(meses == 0){
                                            alert('Quantidade de Meses não pode ser 0');
                                        }else{
                                        $.getJSON('<?= base_url() ?>autocomplete/repetirreceituariopormes', {receita: receita_id, meses: meses, ajax: true}, function (j) {
                                                
                                                if(j == true){
                                                    $('#meses_receita_'+receita_id).val('');
                                                    window.location.reload();
                                                }             
                                        }); 
                                        }
                                        }

                                    function atualizarTextArea(){
                                        // console.log('aomething');
                                        tinyMCE.triggerSave();
                                    }

                                    function repetirexame(exame_id) {

                                        $.getJSON('<?= base_url() ?>autocomplete/repetirexame', {exame_id: exame_id, ajax: true}, function (j) {
                                            options = "";

                                            options += j[0].texto;

                                            $('#solicitar_exames').val(options);
                                            var ed = tinyMCE.get('solicitar_exames');
                                            ed.setContent($('#solicitar_exames').val());

                                                
                                        }
                                     
                                    )}

                                    $(function () {
                                        $('#modeloexame').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelossolicitarexamesPreenchido', {exame: $(this).val(), ambulatorio_laudo_id: <?=$ambulatorio_laudo_id?>, ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#solicitar_exames').val(options)
                                                    var ed = tinyMCE.get('solicitar_exames');
                                                    ed.setContent($('#solicitar_exames').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#solicitar_exames').html('value=""');
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#modeloreceita').change(function () {
                                            if ($(this).val()) {
                                                //$('#laudo').hide();
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/modelosreceita', {exame: $(this).val(), ajax: true}, function (j) {
                                                    options = "";

                                                    options += j[0].texto;
                                                    //                                                document.getElementById("laudo").value = options

                                                    $('#receituario').val(options);
                                                    var ed = tinyMCE.get('receituario');
                                                    ed.setContent($('#receituario').val());

                                                    //$('#laudo').val(options);
                                                    //$('#laudo').html(options).show();
                                                    //                                                $('.carregando').hide();
                                                    //history.go(0) 
                                                });
                                            } else {
                                                $('#receituario').html('value=""');
                                            }
                                        });
                                    });
                                    $(function () {
                                        $('#btnReceituario').click(function () {
                                            // form_receituario
                                            // $('#form_receituario').submit();
                                        });
                                    });

                                   $(function () {
                                        $("#medicamento").autocomplete({
                                            source: "<?= base_url() ?>index.php?c=autocomplete&m=medicamentolaudo",
                                            minLength: 1,
                                            focus: function (event, ui) {
                                                $("#medicamento").val(ui.item.label);
                                                return false;
                                            },
                                            select: function (event, ui) {
                                                 $("#medicamento").val(ui.item.value);
                                            
                                                  var texto_antigo2 = tinyMCE.get('receituario').getContent();
                                                  var texto_adicional_html2 = "</html>";
                                                  var texto_adicional_body2 = "</body>";
                                                   //aqui ele tira todas as tags html e body
                                                  texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
                                                  texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
                                                     //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
                                                   var colocartexto = texto_antigo2 + ui.item.id;
                                                   var ed = tinyMCE.get('receituario');
                                                   ed.setContent(colocartexto); 
                                                   $("#medicamento").val(""); 
                                                return false;
                                            }
                                        });
                                    });

                                    function enviarProcedimentoSADT(){

                                        $.ajax({
                                                type: "POST",
                                                data: {
                                                        solicitacao_id: $("#solicitacao_id").val(),
                                                        solicitante_id: $("#medico_sadt").val(),
                                                        paciente_id: $("#paciente_id").val(),
                                                        convenio_id: $("#convenio_id").val(),
                                                        quantidade: $("#quantidade").val(),
                                                        procedimento1: $("#procedimento1").val(),
                                                        direcao: $("#direcao").val(),
                                                        justificativa: $("#justificativa").val(),
                                                        dig_provavel: $("#dig_provavel").val(),
                                                        valor1: $("#valor1").val(),
                                                    },
                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                url: "<?= base_url() ?>ambulatorio/guia/gravarsolicitacaosadtatendi/",
                                                success: function (data) {
                                                    // console.log(data);
                                                    // alert('Operação efetuada com sucesso');
                                                    $("#solicitacao_id").val(data);
                                                    buscarProcedimentosSADTAtend();
                                                    // console.log($("#solicitacao_id").val());
                                                },
                                                error: function (data) {
                                                    alert('Erro ao chamar paciente');
                                                }
                                            });
                                    }

                                    function buscarProcedimentosSADTAtend(){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarProcedimentosSADTAtend', {solicitacao_id: parseInt($("#solicitacao_id").val()), ajax: true}, function (j) {
                                            table = "";
                                            // console.log(j);
                                            var situacao = '';
                                            for (var c = 0; c < j.length; c++) {
                                                table += "<tr>";
                                                if (j[c].emergencia == "t") {
                                                    situacao = "Emergência";
                                                } else if (j[c].eletivo == "t") {
                                                    situacao = "Eletivo";
                                                } else {
                                                    situacao = '';
                                                }
                                                table +="<td class='tabela_content01'>"+j[c].procedimento+"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].quantidade+"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].convenio+"</td>";
                                                table +="<td class='tabela_content01'>"+situacao+"</td>";
                                                table +="<td class='tabela_content01'><button type='button' onclick='apagarProcedimentoSADT("+parseInt($("#solicitacao_id").val())+","+j[c].solicitacao_sadt_procedimento_id+");'>Excluir</button></td>";
                                                table += "</tr>";
                                            }
                                            $('#tabelaProcedimentos tr').remove();
                                            $('#tabelaProcedimentos').append(table);
                                            
                                        });
                                    }

                                    function apagarProcedimentoSADT(solicitacao_id, id){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/excluirsolicitacaoprocedimentosadtatend/'+ solicitacao_id + '/' + id, {ajax: true}, function (j) {
                                            buscarProcedimentosSADTAtend();
                                        }); 
                                    }

                                    function imprimirsolicitacaoSADT(){
                                        solicitacao_id =  parseInt($("#solicitacao_id").val());
                                        if(solicitacao_id > 0){
                                            window.open('<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/' + solicitacao_id);
                                        }else{
                                            alert("Adicione um procedimento primeiro");
                                        }
                                       
                                    }

                                    function imprimirMedicamentosTomada(){
                                        laudo_id =  parseInt($("#laudo_tomada_id").val());
                                        
                                        window.open('<?= base_url() ?>ambulatorio/laudo/impressaoTomada/' + laudo_id);
                                        
                                       
                                    }

                                    function enviarMedicamentoSADT(){
                                    if($("#medicamento_id").val() == ''){
                                        alert('Medicamento não pode ser Vazio');
                                    }else{
                                        if($("#horario").val() == ''){
                                            alert('Horario não pode ser Vazio');
                                        }else{
                                            $.ajax({
                                                    type: "POST",
                                                    data: {
                                                            laudo_id: $("#laudo_tomada_id").val(),
                                                            paciente_id: $("#paciente_id").val(),
                                                            quantidade: $("#quantidade_medicamento").val(),
                                                            medicamento_id: $("#medicamento_id").val(),
                                                            horario_texto: $("#horario_texto").val(),
                                                            horario: $("#horario").val()
                                                        },
                                                                //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                                url: "<?= base_url() ?>ambulatorio/guia/gravarMedicamentoTomada/",
                                                                success: function (data) {
                                                                    // console.log(data);
                                                                    // alert('Operação efetuada com sucesso');
                                                                    buscarMedicamentosSADTAtend();
                                                                    buscarMedicamentosTomadaTotal();
                                                                    // console.log($("#solicitacao_id").val());
                                                                },
                                                                error: function (data) {
                                                                    alert('Erro ao chamar paciente');
                                                                }
                                                            });
                                                    }
                                        }
                                    }

                                    function adicionarAcompanhamento(){
                                            if($("#acompanhamento").val() == ''){
                                                alert('Acompanhamento Não pode ser Vazio');
                                            }else{
                                                if($("#qtde_acompanhamento").val() == 0){
                                                    $("#qtde_acompanhamento").val(0);
                                                }
                                            $.ajax({
                                                type: "POST",
                                                data: {
                                                        laudo_id: $("#laudo_acompanhamento_id").val(),
                                                        medico_id: $("#medico_acompanhamento").val(),
                                                        paciente_id: $("#paciente_acompanhamento_id").val(),
                                                        quantidade: $("#qtde_acompanhamento").val(),
                                                        acompanhamento: $("#acompanhamento").val()
                                                    },
                                                            url: "<?= base_url() ?>ambulatorio/guia/gravaracompanhamentomedico/",
                                                            success: function (data) {
                                                                // console.log(data);
                                                                // alert('Operação efetuada com sucesso');
                                                                $("#acompanhamento").val('');
                                                                $("#qtde_acompanhamento").val('');
                                                                buscarAcompanhamentosMedico();
                                                            },
                                                            error: function (data) {
                                                                alert('Erro ao adicionar o Acompanhamento');
                                                            }
                                                        });
                                                }
                                    }

        function buscarMedicamentosSADTAtend(){
            $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarMedicamentosTomada', {laudo_id: parseInt($("#laudo_tomada_id").val()), ajax: true}, function (j) {
                table = "";

                var situacao = '';
                for (var c = 0; c < j.length; c++) {
                    var situacao = '';
                    table += "<tr>";
                    if (j[c].horario_texto == "1") {
                        situacao = "Após Café da Manhã";
                    } else if (j[c].horario_texto == "2") {
                        situacao = "Antes do Almoço";
                    } else if (j[c].horario_texto == "3"){
                        situacao = 'Antes do Jantar';
                    }else if (j[c].horario_texto == "4"){
                        situacao = 'Ao Deitar';
                    }
                    

                    table +="<td class='tabela_content01' id='medicamentoeditar_"+j[c].ambulatorio_laudo_tomada_id+"_3'>"+j[c].medicamento+"</td>";
                    table +="<td hidden id='medicamentoeditar_"+j[c].ambulatorio_laudo_tomada_id+"_2' class='tabela_content01'><select name='medicamentoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' id='medicamentoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' ></select></td>";
                    table +="<td class='tabela_content01'><input type='number' step='0.1' min='0.1' class='texto01' disabled name='quantidadeeditar_"+j[c].ambulatorio_laudo_tomada_id+"' id='quantidadeeditar_"+j[c].ambulatorio_laudo_tomada_id+"' value='"+j[c].quantidade+"'></td>";
                    if(j[c].texto != null){
                        table +="<td class='tabela_content01'><input type='text'  disabled name='situacaoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' id='situacaoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' value='"+j[c].texto+"'></td>";
                    }else{
                        table +="<td class='tabela_content01'><input type='text'  disabled name='situacaoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' id='situacaoeditar_"+j[c].ambulatorio_laudo_tomada_id+"' value='"+situacao+"'></td>";
                    }
                    
                    table +="<td class='tabela_content01'><input type='text' class='texto01' disabled name='horarioeditar_"+j[c].ambulatorio_laudo_tomada_id+"' id='horarioeditar_"+j[c].ambulatorio_laudo_tomada_id+"' value='"+j[c].horario+"'></td>";
                    table +="<td class='tabela_content01'><button type='button' onclick='apagarMedicamentoTomada("+parseInt($("#laudo_tomada_id").val())+","+j[c].ambulatorio_laudo_tomada_id+");'>Excluir</button></td>";
                    table +="<td class='tabela_content01'><button type='button' onclick='duplicarMedicamentoTomada("+parseInt($("#laudo_tomada_id").val())+","+j[c].ambulatorio_laudo_tomada_id+");'>Duplicar</button></td>";
                    table +="<td id='editar_"+j[c].ambulatorio_laudo_tomada_id+"' class='tabela_content01'><button type='button' onclick='editartomadas("+j[c].ambulatorio_laudo_tomada_id+", "+j[c].medicamento_id+");'>Editar</button></td>";
                    table +="<td hidden id='salvareditar_"+j[c].ambulatorio_laudo_tomada_id+"' class='tabela_content01'><button type='button' onclick='salvartomadaeditada("+j[c].ambulatorio_laudo_tomada_id+");'>OK</button></td>";
                    table += "</tr>";


                }
                $('#tabelaMedicamentos tr').remove();
                $('#tabelaMedicamentos').append(table);
                
            });
        }

        function editartomadas(id, medicamento){
            <?$medicamentos_json = json_encode($medicamentos);?>
            arraymedicamentos = <?=$medicamentos_json?>; 

            options = '';
            for (var d = 0; d < arraymedicamentos.length; d++) {
                        options += '<option value="' + arraymedicamentos[d].medicamento_id + '" >' + arraymedicamentos[d].nome +'</option>';
                    }


            $('#medicamentoeditar_'+id+ 'option').remove();
            $('#medicamentoeditar_'+id).append(options);
            $('#medicamentoeditar_'+id).trigger("chosen:updated");

            $("#medicamentoeditar_"+id+" option[value='" + medicamento + "']").attr("selected","selected");

            $('#medicamentoeditar_'+id+'_2').toggle();
            $('#medicamentoeditar_'+id+'_3').toggle();



            $('#situacaoeditar_'+id).prop("disabled", false);
            $('#horarioeditar_'+id).prop("disabled", false);
            $('#quantidadeeditar_'+id).prop("disabled", false);
            $('#medicamentoeditar_'+id).prop("disabled", false);
            $('#editar_'+id).toggle();
            $('#salvareditar_'+id).toggle();
        }

        function salvartomadaeditada(id){

            situacao = $('#situacaoeditar_'+id).val();
            horario = $('#horarioeditar_'+id).val();
            quantidade = $('#quantidadeeditar_'+id).val();
            medicamento_id = $('#medicamentoeditar_'+id).val();


            $.getJSON('<?= base_url() ?>ambulatorio/guia/updatesolicitacaomedicamentoatend/' + id, {situacao: situacao, horario: horario, quantidade:quantidade, medicamento_id: medicamento_id, ajax: true}, function (j) {
                buscarMedicamentosSADTAtend();
                buscarMedicamentosTomadaTotal();
            }); 
        }


        function buscarAcompanhamentosMedico(){
            $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarAcompanhamentosMedico', {paciente_id: parseInt($("#paciente_acompanhamento_id").val()), ajax: true}, function (j) {
                table_c = "";
                // console.log(j);
                var situacao = '';
                for (var c = 0; c < j.length; c++) {
                    // var situacao = '';
                    
                    table_c += "<tr>";
                    table_c +="<td class='tabela_content01'><b><font size='3'>"+j[c].acompanhamento+"</font><b></td>";

                    var res = buscarAcompanhamentosMedicoDatas(j[c].acompanhamento);
                    // console.log(table);
                    table_c += res;
                    var quant = buscarAcompanhamentosMedicoQuantidades(j[c].acompanhamento);
                    table_c += quant;

                    table_c += "</tr>";
                }

                $('#tabelaAcompanhamento tr').remove();
                $('#tabelaAcompanhamento').append(table_c);
                
            });
        }


        function buscarAcompanhamentosMedicoDatas(acompanhamento){
            var tablee = '';
            $.ajax({
                    type: "POST",
                    data: {
                            acompanhamento: acompanhamento
                        },
                    url: "<?= base_url() ?>ambulatorio/guia/buscarAcompanhamentosMedicoDatas/",
                    dataType: 'json',
                    async: false,
                        success: function (d) {
                            for(var a = 0; a < d.length; a++){
                                 var date = d[a].data;
                                // var txtData = date.toLocaleString();
                                var txtData = date.split('-').reverse().join('/');
                                tablee +="<td class='tabela_content01'>"+txtData+"</td>";
                            }
                            //  console.log(tablee);
                        },
                        error: function (d) {
                            alert('Erro');
                        }    
                    });
        // console.log(tablee);
            return tablee;
        }


        function buscarAcompanhamentosMedicoQuantidades(acompanhamento){
            var tablee = '';
            tablee = '</tr><tr><td class="tabela_content01"></td>';
            $.ajax({
                    type: "POST",
                    data: {
                            acompanhamento: acompanhamento
                        },
                    url: "<?= base_url() ?>ambulatorio/guia/buscarAcompanhamentosMedicoQuantidades/",
                    dataType: 'json',
                    async: false,
                        success: function (d) {
                            for(var a = 0; a < d.length; a++){
                                //  var date = d[a].data;
                                tablee +="<td class='tabela_content01'>"+d[a].quantidade+"</td>";
                            }
                            //  console.log(tablee);
                        },
                        error: function (d) {
                            alert('Erro');
                        }    
                    });
        // console.log(tablee);
            return tablee;
        }


                                    function buscarMedicamentosTomadaTotal(){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/buscarMedicamentosTomadaTotal', {laudo_id: parseInt($("#laudo_tomada_id").val()), ajax: true}, function (j) {
                                            table = "";
                                            // console.log(j);
                                            var situacao = '';
                                            for (var c = 0; c < j.length; c++) {
                                                table += "<tr>";
                                                if (j[c].emergencia == "t") {
                                                    situacao = "Emergência";
                                                } else if (j[c].eletivo == "t") {
                                                    situacao = "Eletivo";
                                                } else {
                                                    situacao = '';
                                                }
                                                table +="<td class='tabela_content01'>"+j[c].medicamento+ " - " + j[c].posologia +"</td>";
                                                table +="<td class='tabela_content01'>"+j[c].quantidade+"</td>";
                                                table += "</tr>";
                                            }
                                            $('#tabelaMedicamentosTotal tr').remove();
                                            $('#tabelaMedicamentosTotal').append(table);
                                            
                                        });
                                    }

                                    function apagarMedicamentoTomada(laudo_tomada_id, id){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/excluirsolicitacaomedicamentoatend/'+ laudo_tomada_id + '/' + id, {ajax: true}, function (j) {
                                            buscarMedicamentosSADTAtend();
                                            buscarMedicamentosTomadaTotal();
                                        }); 
                                    }

                                    function duplicarMedicamentoTomada(laudo_tomada_id, id){
                                        $.getJSON('<?= base_url() ?>ambulatorio/guia/duplicarsolicitacaomedicamentoatend/'+ laudo_tomada_id + '/' + id, {ajax: true}, function (j) {
                                            buscarMedicamentosSADTAtend();
                                            buscarMedicamentosTomadaTotal();
                                        }); 
                                    }

                                    function imprimirsolicitacaoSADT(){
                                        solicitacao_id =  parseInt($("#solicitacao_id").val());
                                        if(solicitacao_id > 0){
                                            window.open('<?= base_url() ?>ambulatorio/guia/impressaosolicitacaosadt/' + solicitacao_id);
                                        }else{
                                            alert("Adicione um medicamento primeiro");
                                        }
                                       
                                    }   

                                                                function pre_apresentacao(link) {
                                                                            $("#removepreapresentacao").remove();
                                                                            // nome_arquivo_on = '"'+nome_arquivo+'"';
                                                                            // extensao_on = '"'+extensao+'"';
                                                                            $("#apresentacao_tabela").append(
                                                                                "<tr id='removepreapresentacao'>"+
                                                                                    "<td colspan='3'>"+
                                                                                    "<iframe src='"+link+"' width='450' height='480' style='border: none;'></iframe>"+
                                                                                    "</td>"+
                                                                                "</tr>");
                                                                }

                                                                function criar_modal(nome_arquivo, extensao) {
                                                                            $("#removemodal").remove();
                                                                            nome_arquivo_on = '"'+nome_arquivo+'"';
                                                                            extensao_on = '"'+extensao+'"';
                                                                            $("#myModal").append(
                                                                                "<div class='modal-content' id='removemodal'>"+
                                                                                    "<div class='modal-header'>"+
                                                                                        "<span class='close'>&times;</span>"+
                                                                                        "<h2>Renomear Arquivo</h2>"+
                                                                                    "</div>"+
                                                                                    "<div class='modal-body'>"+
                                                                                        // "<br><br>"+
                                                                                        "<h1>"+nome_arquivo+"</h1>"+
                                                                                        "<input type='text' name='renomear_arquivo' id='renomear_arquivo' class='texto10'/>"+
                                                                                        "<input type='text' readonly name='extensao_arquivo' class='texto01' value='."+extensao+"'/>"+
                                                                                        "<br><br>"+
                                                                                    "</div>"+
                                                                                    "<div class='modal-footer'>"+
                                                                                        "<h3><button type='button' class='btn_renomear' name='renomear' onclick='trocarnomearquivo("+nome_arquivo_on+", "+extensao_on+")'> <b>Renomear</b> </button></h3>"+
                                                                                    "</div>"+
                                                                                "</div>");

                                                                                $("#myModal").toggle();
                                                                                // myModal



                                                                                
                                                                                 var modal = document.getElementById("myModal");

                                                                                 var btn = document.getElementById("myBtn");

                                                                                 var span = document.getElementsByClassName("close")[0];

                                                                                 btn.onclick = function() {
                                                                                 modal.style.display = "block";
                                                                                 }

                                                                                 span.onclick = function() {
                                                                                 modal.style.display = "none";
                                                                                 }

                                                                                 window.onclick = function(event) {
                                                                                 if (event.target == modal) {
                                                                                     modal.style.display = "none";
                                                                                 }
                                                                                 }
                                                                        }

                                                                        function trocarnomearquivo(nome_arquivo, extensao){
                                                                                if($('#renomear_arquivo').val() == ''){
                                                                                        $("#renomear_arquivo").prop('required', true);
                                                                                }else{
                                                                               novo_nome = $('#renomear_arquivo').val();
                                                                            //    console.log(novo_nome);
                                                                            $.ajax({
                                                                            type: "POST",
                                                                            data: {
                                                                                    nome_arquivo: nome_arquivo,
                                                                                    extensao: extensao,
                                                                                    novo_nome: novo_nome

                                                                                },
                                                                            url: "<?= base_url() ?>ambulatorio/guia/trocarnomearquivo/",
                                                                            dataType: 'json',
                                                                            // async: false,
                                                                                success: function (data) {
                                                                                    $("#myModal").toggle();
                                                                                },
                                                                                error: function (data) {
                                                                                    // alert('Erro');
                                                                                    $("#myModal").toggle();
                                                                                }    
                                                                            });
                                                                            }
                                                                        }





<? if (($endereco != '')) { ?>
    <?
    if ($obj->_cpf != '') {
        $cpf = $obj->_cpf;
    } else {
        $cpf = 'null';
    }
    $url_enviar_ficha = "$endereco/webService/telaAtendimento/enviarFicha/$obj->_toten_fila_id/$obj->_nome/$cpf/$obj->_medico_parecer1/$obj->_medico_nome/$obj->_toten_sala_id/false";
    ?>
                                                $("#botaochamar").click(function () {
                                                //    alert('<? //= $url_enviar_ficha        ?>');
                                                $.ajax({
                                                type: "POST",
                                                        data: {teste: 'teste'},
                                                        //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                        url: "<?= $url_enviar_ficha ?>",
                                                        success: function (data) {
                                                        //                console.log(data);
                                                        //                    alert(data.id);
                                                        $("#idChamada").val(data.id);
                                                        },
                                                        error: function (data) {
                                                        // console.log(data);
                                                        //                alert('DEU MERDA');
                                                        }
                                                });
                                                setTimeout(enviarChamadaPainel, 2000);
                                                });
                                                function enviarChamadaPainel(){
                                                $.ajax({
                                                type: "POST",
                                                        data: {teste: 'teste'},
                                                        //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                        url: "<?= $endereco ?>/webService/telaChamado/proximo/<?= @$obj->_medico_parecer1 ?>/<?= @$obj->_toten_fila_id ?>/<?= @$obj->_toten_sala_id ?>",
                                                                    success: function (data) {

                                                                    alert('Operação efetuada com sucesso');
                                                                    },
                                                                    error: function (data) {
                                                                    // console.log(data);
                                                                    alert('Erro ao chamar paciente');
                                                                    }
                                                            });
                                                            $.ajax({
                                                            type: "POST",
                                                                    data: {teste: 'teste'},
                                                                    //url: "http://192.168.25.47:8099/webService/telaAtendimento/cancelar/495",
                                                                    url: "<?= $endereco ?>/webService/telaChamado/cancelar/<?= @$obj->_toten_fila_id ?>",
                                                                                success: function (data) {

                                                                                //                            alert('Operação efetuada com sucesso');


                                                                                },
                                                                                error: function (data) {
                                                                                // console.log(data);
                                                                                //                            alert('Erro ao chamar paciente');
                                                                                }
                                                                        });
                                                                        }
<? } ?>



                                                                    $(function () {
                                                                    $("#txtCICPrimariolabel").autocomplete({

                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=cid1",
                                                                            minLength: 3,
                                                                            focus: function (event, ui) {
                                                                            $("#txtCICPrimariolabel").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#txtCICPrimariolabel").val(ui.item.value);
                                                                            $("#txtCICPrimario").val(ui.item.id);
                                                                            return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $("#txtCodigoTusslabel").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=procedimentotusspesquisa",
                                                                            minLength: 3,
                                                                            focus: function (event, ui) {
                                                                            $("#txtCodigoTusslabel").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#txtCodigoTusslabel").val(ui.item.value);
                                                                            $("#txtCodigoTuss").val(ui.item.id);
                                                                            //                                                                $("#txtcodigo").val(ui.item.codigo);
                                                                            //                                                                $("#txtdescricao").val(ui.item.descricao);
                                                                            return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $("#txtCICSecundariolabel").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=cid2",
                                                                            minLength: 3,
                                                                            focus: function (event, ui) {
                                                                            $("#txtCICSecundariolabel").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#txtCICSecundariolabel").val(ui.item.value);
                                                                            $("#txtCICSecundario").val(ui.item.id);
                                                                            return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    var readonly = <?= $readonly ?>;
                                                                    var readonlyadendo = <?= $readonlyadendo ?>;
                                                                    // NOVO TINYMCE

                                                                    // var specialChars = [
                                                                    // { text: 'exclamation mark', value: '!' },
                                                                    // { text: 'at', value: '@' },
                                                                    // { text: 'hash', value: '#' },
                                                                    // { text: 'dollars', value: '$' },
                                                                    // { text: 'percent sign', value: '%' },
                                                                    // { text: 'caret', value: '^' },
                                                                    // { text: 'ampersand', value: '&' },
                                                                    // { text: 'asterisk', value: '*' }
                                                                    // ];
                                                                    
                                                                    
                                                                        tinymce.init({
                                                                    selector: "#adendo",
                                                                            setup : function(ed)
                                                                            {
                                                                            ed.on('init', function()
                                                                            {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            ed.on('SetContent', function (e) {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            },
                                                                            language: 'pt_BR',
                                                                            readonly : readonlyadendo,
                                                                            // lists_indent_on_tab : false,
                                                                            // forced_root_block : '',
<? if (@$empresa[0]->impressao_laudo == 33) { ?>
                                                                        forced_root_block : '',
<? } ?>
                                                                    //                                                            browser_spellcheck : true,
                                                                    //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
                                                                    //                                                            nanospell_server: "php",
                                                                    //  
                                                                    width: 800,                                                          nanospell_dictionary: "pt_br" ,
                                                                    height: 450, // Pra tirar a lista automatica é só retirar o textpattern
                                                                            plugins: [
                                                                                    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                                    "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                            ],
                                                                            toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough | link unlink anchor image | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                            // toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                                                                            // toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
                                                                            // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                            menubar: false,
                                                                            toolbar_items_size: 'small',
                                                                            style_formats: [{
                                                                            title: 'Bold text',
                                                                                    inline: 'b'
                                                                            }, {
                                                                            title: 'Red text',
                                                                                    inline: 'span',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Red header',
                                                                                    block: 'h1',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Example 1',
                                                                                    inline: 'span',
                                                                                    classes: 'example1'
                                                                            }, {
                                                                            title: 'Example 2',
                                                                                    inline: 'span',
                                                                                    classes: 'example2'
                                                                            }, {
                                                                            title: 'Table styles'
                                                                            }, {
                                                                            title: 'Table row 1',
                                                                                    selector: 'tr',
                                                                                    classes: 'tablerow1'
                                                                            }],
                                                                            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
                                                                            templates: [{
                                                                            title: 'Test template 1',
                                                                                    content: 'Test 1'
                                                                            }, {
                                                                            title: 'Test template 2',
                                                                                    content: 'Test 2'
                                                                            }],
                                                                            init_instance_callback: function () {
                                                                            window.setTimeout(function () {
                                                                            $("#div").show();
                                                                            }, 1000);
                                                                            },
                                                                    });

                                                                    <? 
                                                                    $totalreceitas = count($listareceita);
                                                                    $qtde_receita = 0;
                                                                    ?>
                                                                    var receitas_tiny = [
                                                                    <?
                                                                        foreach($listareceita as $receita){
                                                                            $qtde_receita++;
                                                                            if($qtde_receita != $totalreceitas){
                                                                                echo "{ text: '$receita->nome', value: '*$receita->nome*'},";
                                                                            }else{
                                                                                echo "{ text: '$receita->nome', value: '*$receita->nome*'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalreceitas = count($listareceitaespecial);
                                                                    $qtde_receita = 0;
                                                                    ?>
                                                                    var receitas_especial_tiny = [
                                                                    <?
                                                                        foreach($listareceitaespecial as $receita){
                                                                            $qtde_receita++;
                                                                            if($qtde_receita != $listareceitaespecial){
                                                                                echo "{ text: '$receita->nome', value: '#$receita->nome#'},";
                                                                            }else{
                                                                                echo "{ text: '$receita->nome', value: '#$receita->nome#'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalexames = count($listaexames);
                                                                    $qtde_exames = 0;
                                                                    ?>
                                                                    var exames_tiny = [
                                                                    <?
                                                                        foreach($listaexames as $exames){
                                                                            $qtde_exames++;
                                                                            if($qtde_exames != $totalexames){
                                                                                echo "{ text: '$exames->nome', value: '@$exames->nome@'},";
                                                                            }else{
                                                                                echo "{ text: '$exames->nome', value: '@$exames->nome@'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalterapeuticas = count($listaterapeuticas);
                                                                    $qtde_terapeuticas = 0;
                                                                    ?>
                                                                    var terapeutica_tiny = [
                                                                    <?
                                                                        foreach($listaterapeuticas as $terapeuticas){
                                                                            $qtde_terapeuticas++;
                                                                            if($qtde_terapeuticas != $totalterapeuticas){
                                                                                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'},";
                                                                            }else{
                                                                                echo "{ text: '$terapeuticas->nome', value: '$$terapeuticas->nome$'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];


                                                                    <? 
                                                                    $totalrelatorio = count($listarelatorios);
                                                                    $qtde_relatorio = 0;
                                                                    ?>
                                                                    var relatorio_tiny = [
                                                                    <?
                                                                        foreach($listarelatorios as $relatorio){
                                                                            $qtde_relatorio++;
                                                                            if($qtde_relatorio != $totalrelatorio){
                                                                                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'},";
                                                                            }else{
                                                                                echo "{ text: '$relatorio->nome', value: '%$relatorio->nome%'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];

                                                                    <? 
                                                                    $totalprotocolo = count($listaprotocolos);
                                                                    $qtde_protocolo = 0;
                                                                    ?>
                                                                    var protocolo_tiny = [
                                                                    <?
                                                                        foreach($listaprotocolos as $protocolo){
                                                                            $pos1 = stripos($protocolo->texto, '<p>');
                                                                            $pos2 = strripos($protocolo->texto, '</p>');
                                                                            $posD = $pos2 - $pos1;
                                                                            $teste_texto = substr($protocolo->texto, $pos1+3, $posD-3); 
                                                                            $teste_texto = str_replace("</p>", " <br>'+", $teste_texto);
                                                                            $teste_texto = str_replace("<p>", "'", $teste_texto);
                                                                            $qtde_protocolo++;
                                                                            if($qtde_protocolo != $totalprotocolo){
                                                                                echo "{ text: '$protocolo->nome', value: '$teste_texto'},";
                                                                            }else{
                                                                                echo "{ text: '$protocolo->nome', value: '$teste_texto'}";
                                                                            }
                                                                    }
                                                                    ?>
                                                                    ];
                                                                    
                                                                    tinymce.init({
                                                                        selector: "div.edicao_rapida",
                                                                        plugins: [ 'quickbars' ],
                                                                        toolbar: false,
                                                                        menubar: false,
                                                                        inline: true
                                                                    });


                                                                    tinymce.init({
                                                                        selector: 'textarea.tinymce',
                                                                        width: 800,
                                                                        height: 500,
                                                                        // plugins: [
                                                                                    //  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                                                                                    //  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                                    //  "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                            //  ],
                                                                        //  toolbar: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat | cut copy paste",
                                                                        plugins: [
                                                                            "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
                                                                            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
                                                                            "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
                                                                        ],

                                                                        //toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect ",
                                                                        //toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                                                                        //toolbar3: "table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                         toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough  | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
                                                                         // | link unlink anchor image
                                                                         //menubar: 'format edit table view tools',
                                                                         menubar: false,
                                                                         language: 'pt_BR',
                                                                        <? if (@$empresa[0]->impressao_laudo == 33) { ?>
                                                                        forced_root_block : '',
                                                                        <? } 
                                                                        if ($empresapermissao[0]->corretor_ortografico == 't'){ ?>
                                                                                browser_spellcheck: true,
                                                                                contextmenu: false,
                                                                        <? } ?>
                                                                        setup: function (editor) {
                                                                        /* An autocompleter that allows you to insert special characters */
                                                                        editor.ui.registry.addAutocompleter('receitas_tiny', {
                                                                        ch: '*',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = receitas_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('receitas_especial_tiny', {
                                                                        ch: '#',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = receitas_especial_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('exames_tiny', {
                                                                        ch: '@',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = exames_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('terapeutica_tiny', {
                                                                        ch: '$',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = terapeutica_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('relatorio_tiny', {
                                                                        ch: '%',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = relatorio_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                        editor.ui.registry.addAutocompleter('protocolo_tiny', {
                                                                        ch: '!',
                                                                        minChars: 0,
                                                                        columns: 1,
                                                                        fetch: function (pattern) {
                                                                            var matchedChars = protocolo_tiny.filter(function (char) {
                                                                            return char.text.indexOf(pattern) !== -1;
                                                                            });

                                                                            return new tinymce.util.Promise(function (resolve) {
                                                                            var results = matchedChars.map(function (char) {
                                                                                return {
                                                                                value: char.value,
                                                                                text: char.text
                                                                                // icon: char.value
                                                                                }
                                                                            });
                                                                            resolve(results);
                                                                            });
                                                                        },
                                                                        onAction: function (autocompleteApi, rng, value) {
                                                                            editor.selection.setRng(rng);
                                                                            editor.insertContent(value);
                                                                            autocompleteApi.hide();
                                                                        }
                                                                        });
                                                                            editor.on('init', function()
                                                                            {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                            editor.on('SetContent', function (e) {
                                                                            this.getDoc().body.style.fontSize = '12pt';
                                                                            this.getDoc().body.style.fontFamily = 'Arial';
                                                                            });
                                                                    },
                                                                    toolbar_items_size: 'small',
                                                                            style_formats: [{
                                                                            title: 'Bold text',
                                                                                    inline: 'b'
                                                                            }, {
                                                                            title: 'Red text',
                                                                                    inline: 'span',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Red header',
                                                                                    block: 'h1',
                                                                                    styles: {
                                                                                    color: '#ff0000'
                                                                                    }
                                                                            }, {
                                                                            title: 'Example 1',
                                                                                    inline: 'span',
                                                                                    classes: 'example1'
                                                                            }, {
                                                                            title: 'Example 2',
                                                                                    inline: 'span',
                                                                                    classes: 'example2'
                                                                            }, {
                                                                            title: 'Table styles'
                                                                            }, {
                                                                            title: 'Table row 1',
                                                                                    selector: 'tr',
                                                                                    classes: 'tablerow1'
                                                                            }],
                                                                            fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
                                                                            init_instance_callback: function () {
                                                                            window.setTimeout(function () {
                                                                            $("#div").show();
                                                                            }, 1000);
                                                                            }
                                                                    });


//                                                                     tinymce.init({
//                                                                     selector: "textarea.tinymce",
//                                                                             setup : function(ed)
//                                                                             {
//                                                                             ed.on('init', function()
//                                                                             {
//                                                                             this.getDoc().body.style.fontSize = '12pt';
//                                                                             this.getDoc().body.style.fontFamily = 'Arial';
//                                                                             });
//                                                                             ed.on('SetContent', function (e) {
//                                                                             this.getDoc().body.style.fontSize = '12pt';
//                                                                             this.getDoc().body.style.fontFamily = 'Arial';
//                                                                             });
//                                                                             },
//                                                                             theme: "modern",
//                                                                             skin: "custom",
//                                                                             language: 'pt_BR',
//                                                                             readonly : readonly,
//                                                                             // lists_indent_on_tab : false,
//                                                                             // forced_root_block : '',
//                                                                     <? if (@$empresa[0]->impressao_laudo == 33) { ?>
//                                                                         forced_root_block : '',
//                                                                     <? } ?>
//                                                                     //                                                            browser_spellcheck : true,
//                                                                     //                                                            external_plugins: {"nanospell": "<?= base_url() ?>js/tinymce2/nanospell/plugin.js"},
//                                                                     //                                                            nanospell_server: "php",
//                                                                     //                                                            nanospell_dictionary: "pt_br" ,
//                                                                     height: 450, // Pra tirar a lista automatica é só retirar o textpattern
//                                                                             plugins: [
//                                                                                     "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
//                                                                                     "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
//                                                                                     "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
//                                                                             ], 
// //                                                                              external_plugins: {'powerpaste': '<?= base_url().'js/powerpaste/plugin.min.js'?>'},
//                                                                             toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
//                                                                             toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | removeformat",
//                                                                             // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
//                                                                             menubar: false,
//                                                                             toolbar_items_size: 'small',
//                                                                             style_formats: [{
//                                                                             title: 'Bold text',
//                                                                                     inline: 'b'
//                                                                             }, {
//                                                                             title: 'Red text',
//                                                                                     inline: 'span',
//                                                                                     styles: {
//                                                                                     color: '#ff0000'
//                                                                                     }
//                                                                             }, {
//                                                                             title: 'Red header',
//                                                                                     block: 'h1',
//                                                                                     styles: {
//                                                                                     color: '#ff0000'
//                                                                                     }
//                                                                             }, {
//                                                                             title: 'Example 1',
//                                                                                     inline: 'span',
//                                                                                     classes: 'example1'
//                                                                             }, {
//                                                                             title: 'Example 2',
//                                                                                     inline: 'span',
//                                                                                     classes: 'example2'
//                                                                             }, {
//                                                                             title: 'Table styles'
//                                                                             }, {
//                                                                             title: 'Table row 1',
//                                                                                     selector: 'tr',
//                                                                                     classes: 'tablerow1'
//                                                                             }],
//                                                                             fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
//                                                                             templates: [{
//                                                                             title: 'Test template 1',
//                                                                                     content: 'Test 1'
//                                                                             }, {
//                                                                             title: 'Test template 2',
//                                                                                     content: 'Test 2'
//                                                                             }],
//                                                                             init_instance_callback: function () {
//                                                                             window.setTimeout(function () {
//                                                                             $("#div").show();
//                                                                             }, 1000);
//                                                                             }
//                                                                     });
                                                                    
                                                                    
                                                                    $(function () {
                                                                    $('#exame').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/modeloslaudo', {exame: $(this).val(), ajax: true}, function (j) {
                                                                    options2 = "";
                                                                    options2 += j[0].texto;
                                                                    //                                                document.getElementById("laudo").value = options2

<? if (@$empresapermissao[0]->nao_sobrepor_laudo == 't') : ?>

                                                                        //recuperando texto antigo do textarea
                                                                        var texto_antigo2 = tinyMCE.get('laudo').getContent();
                                                                        var texto_adicional_html2 = "</html>";
                                                                        var texto_adicional_body2 = "</body>";
                                                                        //aqui ele tira todas as tags html e body
                                                                        texto_antigo2 = texto_antigo2.replace(texto_adicional_html2, "");
                                                                        texto_antigo2 = texto_antigo2.replace(texto_adicional_body2, "");
                                                                        //pegando o texto antigo do textarea e somando com o texto buscado ao selecionar
                                                                        var colocartexto = texto_antigo2 + j[0].texto;
                                                                        var ed = tinyMCE.get('laudo');
                                                                        ed.setContent(colocartexto);
                                                                        $('#exame').val("");
<? else: ?>

                                                                        $('#laudo').val(options2)
                                                                                var ed = tinyMCE.get('laudo');
                                                                        ed.setContent($('#laudo').val());
                                                                        //                                                         alert('teste');

<? endif; ?>
                                                                    //$('#laudo').val(options);
                                                                    //$('#laudo').html(options).show();
                                                                    //                                                $('.carregando').hide();
                                                                    //history.go(0) 
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value=""');
                                                                    }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $('#linha').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/modeloslinhas', {linha: $(this).val(), ajax: true}, function (j) {
                                                                    options = "";
                                                                    options += j[0].texto;
                                                                    //                                                document.getElementById("laudo").value = $('#laudo').val() + options
                                                                    $('#laudo').val() + options
                                                                            var ed = tinyMCE.get('laudo');
                                                                    ed.setContent($('#laudo').val());
                                                                    //$('#laudo').html(options).show();
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value=""');
                                                                    }
                                                                    });
                                                                    });
                                                                    $(function () {
                                                                    $("#linha2").autocomplete({
                                                                    source: "<?= base_url() ?>index.php?c=autocomplete&m=linhas",
                                                                            minLength: 1,
                                                                            focus: function (event, ui) {
                                                                            $("#linha2").val(ui.item.label);
                                                                            return false;
                                                                            },
                                                                            select: function (event, ui) {
                                                                            $("#linha2").val(ui.item.value);
                                                                            tinyMCE.triggerSave(true, true);
                                                                            document.getElementById("laudo").value = $('#laudo').val() + ui.item.id
                                                                                    $('#laudo').val() + ui.item.id
                                                                                    var ed = tinyMCE.get('laudo');
                                                                            ed.setContent($('#laudo').val());
                                                                            //$( "#laudo" ).val() + ui.item.id;
                                                                            document.getElementById("linha2").value = ''
                                                                                    return false;
                                                                            }
                                                                    });
                                                                    });
                                                                    $(function (a) {
                                                                    $('#anteriores').change(function () {
                                                                    if ($(this).val()) {
                                                                    //$('#laudo').hide();
                                                                    $('.carregando').show();
                                                                    $.getJSON('<?= base_url() ?>autocomplete/laudosanteriores', {anteriores: $(this).val(), ajax: true}, function (i) {
                                                                    option = "";
                                                                    option = i[0].texto;
                                                                    tinyMCE.triggerSave();
                                                                    document.getElementById("laudo").value = option
                                                                            //$('#laudo').val(options);
                                                                            //$('#laudo').html(options).show();
                                                                            $('.carregando').hide();
                                                                    history.go(0)
                                                                    });
                                                                    } else {
                                                                    $('#laudo').html('value="texto"');
                                                                    }
                                                                    });
                                                                    });
                                                                    //bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                                                                    //                $('.jqte-test').jqte();



                                                                    $(function () {
                                                                    $('#datahist').change(function () {
//                                                                                                    alert('entra!');

                                                                    $.getJSON('<?= base_url() ?>autocomplete/historicopordia', {paciente: <?= $paciente_id ?>, dataescolhida: $(this).val()}, function (j) {
                                                                    // console.log(j);
                                                                    options = '';
                                                                    for (var c = 0; c < j.length; c++) {
//                                                                  
                                                                    if (j[c].texto != '' && j[c].texto != null) {
                                                                    var texto = j[c].texto;
                                                                    } else {
                                                                    var texto = '';
                                                                    }
                                                                    options += 'Médico: ' + j[c].medico + ' <br> ' + 'Procedimento: ' + j[c].procedimento + ' <br> <hr> Queixa Principal:  ' + texto + ' <br><hr>';
//                                                                                                        console.log(options);
                                                                    }

                                                                    $('#pordata').html(options);
                                                                    });
                                                                    });
                                                                    });
var campos = [];
var camposObj = [];



function carregarCampos(obj){

    var campoAdd = obj.nome;
    var campoAddName = limparEspacos(campoAdd);
    var campoTipo = obj.tipo;
    var campoValue = obj.value;

    if(typeof obj.opcoes !== 'undefined'){
        var opcoesCampo = obj.opcoes;
    }else{
        var opcoesCampo = [];
    }
    camposObj.push(obj);
    campos.push(campoAddName);
    // console.log(opcoesCampo);
    if(campoTipo == 'textoCurto'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'textoLongo'){
        var div = '<div id="'+campoAdd+'Div"><br><label>'+campoAdd+'</label><br><textarea onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" cols="40" rows="10" type="text" name="'+campoAddName+'" id="'+campoAddName+'">'+campoValue+'</textarea></div>';
    }
    else if(campoTipo == 'textoData'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="text" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'textoNumero'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><input onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="number" name="'+campoAddName+'" id="'+campoAddName+'" class="texto04" value="'+campoValue+'" /></div>';
    }
    else if(campoTipo == 'select'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><select onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" name="'+campoAddName+'" id="'+campoAddName+'" class="size2"></select></div>';
    }
    else if(campoTipo == 'multiplo'){
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><br><select onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" data-placeholder="Selecione uma ou mais opções" multiple name="'+campoAddName+'" id="'+campoAddName+'" class="size2 chosen-select"></select></div>';
    }
    else if(campoTipo == 'checkbox'){
        if(campoValue != ''){
            var checked = 'checked ';
        }else{
            var checked = '';
        }
        // console.log(obj);
        var div = '<div id="'+campoAdd+'Div"><label>'+campoAdd+'</label><input '+checked+' onchange="alterarvalueTemplate('+"'" +campoAddName+ "'" +')" type="checkbox" name="'+campoAddName+'" id="'+campoAddName+'" value="" /></div>';
    }
    $('#camposDiv').append(div);
    if(campoTipo == 'textoData'){
        calendario(campoAddName);
    }
    if(campoTipo == 'multiplo'){
        $('#' + campoAddName).chosen();
    }
    adicionarOpcaoCarrado(campoAddName, opcoesCampo, campoValue);

    // $('#nomeCampo').val('');
}

function alterarvalueTemplate(id){
    
    var index = campos.indexOf(id);
    
    camposObj[index].value = $('#'+id).val();
    // console.log($('#'+id).val());
    if($('#'+id).is(':checked') && $('#'+id).val() == ''){
        camposObj[index].value = 'on';
    }
    $("#objTemplate").val(JSON.stringify(camposObj));
    $("#arrayTemplate").val(campos);
}

function adicionarOpcaoCarrado(id, opcoesCampo, campoValue){
    // console.log(id);
    var option_str = '';
    for (let index = 0; index < opcoesCampo.length; index++) {
        const element = opcoesCampo[index];
        if(campoValue == element){
            var selected = 'selected ';
        }else{
            var selected = '';
        }
        option_str += '<option '+selected+' value="'+element+'">'+element+'</option>';
    }

    $('#'+id).append(option_str);
    $('#'+id).trigger("chosen:updated");
    $('#'+ id +'adc').val('');
}

function calendario(id){
    $("#"+ id).datepicker({
        autosize: true,
        changeYear: true,
        changeMonth: true,
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        buttonImage: '<?= base_url() ?>img/form/date.png',
        dateFormat: 'dd/mm/yy'
    });
}
function limparEspacos(vlr) {

    while(vlr.indexOf(" ") != -1){
        vlr = vlr.replace(" ", "");
    }
    return vlr;
}

</script>

