<html>
    <?
        $recomendacao_obrigatorio = $this->session->userdata('recomendacao_obrigatorio');
        $empresa = $this->guia->listarempresapermissoes();
        $empresaPermissoes = $this->guia->listarempresapermissoes();
        $odontologia_alterar = $empresa[0]->odontologia_valor_alterar;
        $retorno_alterar = $empresa[0]->selecionar_retorno;
        $desabilitar_trava_retorno = $empresa[0]->desabilitar_trava_retorno;
        $logo_clinica = $this->session->userdata('logo_clinica');
        $empresa_id = $this->session->userdata('empresa_id');
        //var_dump($retorno_alterar); die;
    ?>
    <head>
        <title>STG - SISTEMA DE GESTAO DE CLINICAS v1.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Reset de CSS para garantir o funcionamento do layout em todos os brownsers -->
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo2.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>js/fullcalendar/fullcalendar.css" rel="stylesheet" />
        <link href="<?= base_url() ?>js/fullcalendar/lib/cupertino/jquery-ui.min.css" rel="stylesheet" />

        <!-- <link href="<?= base_url() ?>css/bootstrap4_1_1.min" rel="stylesheet" id="bootstrap-css"> -->
        <!-- <script src="<?= base_url() ?>js/bootstrap4_1_1.min.js"></script> -->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>

        <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>
    </head>
    <div class="header">
        <div id="imglogo">
            <a href="<?=base_url() . "home"?>">
                <img src="<?= base_url(); ?>img/stg - logo.jpg" alt="Logo"
                    title="Logo" height="70" id="Insert_logo"
                    style="display:block;" />
            </a>
        </div>

        <div id="login">
            <div id="user_info">
                <label style='font-family: serif; font-size: 8pt;'>Seja bem vindo <?= $this->session->userdata('login'); ?>! </label>
                <?
                $numLetras = strlen($this->session->userdata('empresa'));
                $css = ($numLetras > 20) ? 'font-size: 7pt' : '';
                ?>
                <label style='font-family: serif; font-size: 8pt;'>Empresa: <span style="<?= $css ?>"><?= $this->session->userdata('empresa'); ?></span></label>
            </div>
            <div id="login_controles">

                <!--<a href="#" alt="Alterar senha" id="login_pass">Alterar Senha</a>-->

                <a id="login_sair" title="Sair do Sistema" onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                    href="<?= base_url() ?>login/sair">Sair</a>

                
            </div>
            <!--<div id="user_foto">Imagem</div>-->

        </div>


        <? if ($logo_clinica == 't') { ?>
            <div id="imgLogoClinica">
                <img src="<?= base_url(); ?>upload/logomarca/<?= $empresa_id; ?>/logomarca.jpg" alt="Logo Clinica"
                        title="Logo Clinica" height="70" id="Insert_logo" />
            </div>
        <? } ?>
    </div>
    <div class="decoration_header">&nbsp;</div>


    <style>
    body {
        /*margin: 40px 10px;*/
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        background-color: white;
    }

    h2 {
        font-family: "Comic Sans MS", cursive, sans-serif;
        font-size: 28px;
        margin-left: auto;
        margin-right: auto;
        width: 9em;
        text-shadow: 0.1em 0.1em 0.2em #333;
    }

    .row{
    display: flex;
}
    .card-counter{
    box-shadow: 2px 2px 10px #DADADA;
    margin: 5px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    max-width: 1000px;
    width: 500px;
    border-radius: 5px;
    transition: .5s linear all;
  }

  .card-counter:hover{
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary{
    background-color: #007bff;
    color: #FFF;
  }

  .card-counter.danger{
    background-color: #ef5350;
    color: #FFF;
  }  

  .card-counter.success{
    background-color: #66bb6a;
    color: #FFF;
  }  

  .card-counter.info{
    background-color: #26c6da;
    color: #FFF;
  } 

  .card-counter.primary2{
    background-color: #6495ED;
    color: #FFF;
  } 

  .card-counter.particular{
    background-color: #006400;
    color: #FFF;
  } 

  .card-counter.convenio{
    background-color: #D2691E;
    color: #FFF;
  } 

  .card-counter.totalatendimento{
    background-color: #E9967A;
    color: #FFF;
  }

    .card-counter.agendavago{
    background-color: #808080;
    color: #FFF;
  } 

  .card-counter.agenda{
    background-color: #1E90FF;
    color: #FFF;
  } 

  .card-counter.encaixe{
    background-color: #EE0000;
    color: #FFF;
  } 

    .card-counter.cancelado{
    background-color: #FF7F50;
    color: #FFF;
  } 

  .card-counter.faltou{
    background-color: #008080;
    color: #FFF;
  } 

  .card-counter.atendido{
    background-color: #006400;
    color: #FFF;
  }  

  .card-counter i{
    font-size: 5em;
    opacity: 0.2;
  }

  .pai{
        position: relative;
  }

  .card-counter .count-numbers{
    position: absolute;
    right: 35px;
    top: 20px; 
    font-size: 32px;
    display: block;
  }

  .card-counter .count-name{
    position: absolute;
    right: 35px;
    top: 65px;
    font-style: italic;
    text-transform: capitalize;
    opacity: 0.5;
    display: block;
    font-size: 18px;
  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

<div class="accordion">
        <h3 class="singular"><a href="#">Dashboard Financeiro</a></h3>
        <div>
            <table>
                <thead>
                    <!-- <tr> -->
                    
                        <form method="get" action="<?= base_url() ?>ambulatorio/exame/dashboardrecepcao">
                            <tr>
                                <th class="tabela_title" style="width:20%">Data Inicio</th>
                                <th class="tabela_title" style="width:20%">Data Fim</th>
                                <th class="tabela_title" style="width:20%">Empresa</th>
                            </tr>
                            <tr>
                                <th class="tabela_title">
                                    <input type="text" class="texto02" name="data_inicio" id="data_inicio" value="<?=(@$_GET['data_inicio'] != '')? @$_GET['data_inicio'] : date("01/m/Y"); ?>" />
                                </th>
                                <th class="tabela_title">
                                    <input type="text" class="texto02" name="data_fim" id="data_fim" value="<?=(@$_GET['data_fim'] != '')? @$_GET['data_fim'] : date("t/m/Y"); ?>" />
                                </th>
                                <th class="tabela_title">
                                    <select name="empresa_id" id="empresa_id" class="size2">
                                        <? foreach ($empresas as $value) : ?>
                                            <option value="<?= $value->empresa_id; ?>" <?=($value->empresa_id == @$_GET['empresa_id'])? 'selected':'';?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>

                                        <option value="0">TODOS</option>
                                    </select>
                                </th>
                                <th class="tabela_title">
                                    <button type="submit" id="enviar">Pesquisar</button>
                                </th>
                            </tr>
                        </form>        
                    <!-- </tr> -->
                        
            </table>
            
        </div>
    </div>

    <div class="accordion">
        <h3 class="singular"><a href="#">Resultados</a></h3>
            <div>


<h2>Pacientes</h2>
<div class="container">
    <div class="row">

            <!-- <div class="pai"> -->
                <div class="pai card-counter primary">
                    <i class="fa fa-male"></i>
                    <span class="count-numbers"><?=$pessoas[0]->homens?></span>
                    <span class="count-name">Masculino</span>
                </div>
            <!-- </div> -->


            <div class="pai card-counter danger">
                <i class="fa fa-female"></i>
                <span class="count-numbers"><?=$pessoas[0]->mulheres?></span>
                <span class="count-name">Feminino</span>
            </div>



            <div class="pai card-counter success">
                <i class="fa fa-users"></i>
                <span class="count-numbers"><?=$pessoas[0]->sem_sexo?></span>
                <span class="count-name">Sem sexo</span>
            </div>



            <div class="pai card-counter info">
                <i class="fa fa-female"></i>
                <span class="count-numbers"><?=$pessoas[0]->total?></span>
                <span class="count-name">Total</span>
            </div>

  </div>
</div>


<div class="container">
    <div class="row">

            <!-- <div class="pai"> -->
                <div class="pai card-counter primary2">
                    <i class="fa fa-address-card"></i>
                    <span class="count-numbers"><?=$faixaidade[0]->idade1?></span>
                    <span class="count-name">0 a 18 anos</span>
                </div>
            <!-- </div> -->


            <div class="pai card-counter primary2">
                <i class="fa fa-address-card"></i>
                <span class="count-numbers"><?=$faixaidade[0]->idade2?></span>
                <span class="count-name">18 a 30 anos</span>
            </div>



            <div class="pai card-counter primary2">
                <i class="fa fa-address-card"></i>
                <span class="count-numbers"><?=$faixaidade[0]->idade3?></span>
                <span class="count-name">30 a 50 anos</span>
            </div>



            <div class="pai card-counter primary2">
                <i class="fa fa-address-card"></i>
                <span class="count-numbers"><?=$faixaidade[0]->idade4?></span>
                <span class="count-name">50 + anos</span>
            </div>

  </div>
</div>

<hr>

<h2>Atendimentos</h2>
<div class="container">
    <div class="row">

                <div class="pai card-counter particular">
                    <i class="fa fa-stethoscope"></i>
                    <span class="count-numbers"><?=@$atendimento[0]->particular?></span>
                    <span class="count-name">Particulares</span>
                </div>


            <div class="pai card-counter convenio">
                <i class="fa fa-stethoscope"></i>
                <span class="count-numbers"><?=@$atendimento[0]->convenio?></span>
                <span class="count-name">Convenio</span>
            </div>



            <div class="pai card-counter totalatendimento">
                <i class="fa fa-user-md"></i>
                <span class="count-numbers"><?=@$atendimento[0]->total_atendimento?></span>
                <span class="count-name">Total Atendimento</span>
            </div>

  </div>
</div>   

<hr>

<h2>Agendamentos</h2>
<div class="container">
    <div class="row">

            <div class="pai card-counter agendavago">
                <i class="fa fa-calendar"></i>
                <span class="count-numbers"><?=$agendamento[0]->vagas?></span>
                <span class="count-name">Agendas Vagas</span>
            </div>

            <div class="pai card-counter agenda">
                <i class="fa fa-user-plus"></i>
                <span class="count-numbers"><?=$agendamento[0]->agendado?></span>
                <span class="count-name">Agendados</span>
            </div>

            <div class="pai card-counter encaixe">
                <i class="fa fa-calendar-plus-o"></i>
                <span class="count-numbers"><?=$agendamento[0]->encaixe?></span>
                <span class="count-name">Encaixes</span>
            </div>

            <div class="pai card-counter cancelado">
                <i class="fa fa-calendar-times-o"></i>
                <span class="count-numbers"><?=$agendamento[0]->cancelados?></span>
                <span class="count-name">Cancelados</span>
            </div>
            
            <?if(@$_GET['data_inicio'] > date('Y-m-d')){?>
                <div class="pai card-counter faltou">
                    <i class="fa fa-calendar-minus-o"></i>
                    <span class="count-numbers">0</span>
                    <span class="count-name">Faltou</span>
                </div>
            <?}else{?>
            <div class="pai card-counter faltou">
                <i class="fa fa-calendar-minus-o"></i>
                <span class="count-numbers"><?=$agendamento[0]->faltou?></span>
                <span class="count-name">Faltou</span>
            </div>
            <?}?>

            <div class="pai card-counter atendido">
                <i class="fa fa-user-md"></i>
                <span class="count-numbers"><?=$agendamento[0]->atendido?></span>
                <span class="count-name">Atendidos</span>
            </div>

  </div>
</div> 

<hr>

<h2>Agenda por Médico</h2>
    
<br>
    <div class="flex" style="width: 100%">
                            
        <div class="flex-item-1">
            <span class="VermelhoTitle">Agendas Por Médico</span>
            <canvas id="AgendMedicoGrafico" ></canvas>
        </div>

        <div class="flex-item-1">
            <span class="VermelhoTitle">Agendas Por Especialidade</span>
            <canvas id="subgrupoAgenGrafico" ></canvas>
        </div>
        
    </div>



            </div>
    </div>




<?
    $arraySubgruposAgen = array();
    $arraySubgruposAgenNome = array();
    $arraySubgruposAgenQtde = array();

    foreach ($subgruposAgen as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        if($key != ''){
            array_push($arraySubgruposAgenNome, $value->subgrupo);
            array_push($arraySubgruposAgenQtde, $value->quantidade);
        }
    }

    $arraySubgruposAgen[0] = (count($arraySubgruposAgenNome) > 0) ? json_encode($arraySubgruposAgenNome) : '[]';
    $arraySubgruposAgen[1] = (count($arraySubgruposAgenQtde) > 0) ? str_replace('"', '', json_encode($arraySubgruposAgenQtde)) : '[]';
    $arraySubgruposAgen[2] = count($arraySubgruposAgenQtde);




    $arrayAgendMedico = array();
    $arrayAgendMedicoNome = array();
    $arrayAgendMedicoQtde = array();

    foreach ($agendamentopormedico as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
         if($value->medico != ''){
            array_push($arrayAgendMedicoNome, $value->medico);
            array_push($arrayAgendMedicoQtde, $value->quantidade);
         }
    }

    $arrayAgendMedico[0] = (count($arrayAgendMedicoNome) > 0) ? json_encode($arrayAgendMedicoNome) : '[]';
    $arrayAgendMedico[1] = (count($arrayAgendMedicoQtde) > 0) ? str_replace('"', '', json_encode($arrayAgendMedicoQtde)) : '[]';
    $arrayAgendMedico[2] = count($arrayAgendMedicoQtde);

    // echo '<pre>';
    // print_r($arrayAgendMedico);
    // die;

?>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="<?= base_url() ?>js/morris/raphael.js"></script>
<!-- <script src="https://kit.fontawesome.com/8ec73d6e70.js"></script> -->
<script type="text/javascript">
    $(function () {
        $(".accordion").accordion();
    });
    $(function () {
        $("#data_inicio").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });
    $(function () {
        $("#data_fim").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    function getRandomColorHex(qtde) {
        var cores = [];
        for (let index = 0; index < qtde; index++) {
            var hex = "0123456789ABCDEF",
                color = "#";
            for (var i = 1; i <= 6; i++) {
                color += hex[Math.floor(Math.random() * 16)];
                
            }
            cores.push(color);
            
        }
        
        return cores;
    }

    var AgendMedicoGraficoCTX = document.getElementById('AgendMedicoGrafico').getContext('2d');
    var AgendMedicoGraficoconfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayAgendMedico[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayAgendMedico[2]?>)
            }],
            labels: <?=$arrayAgendMedico[0]?>
        }
    };
    <?if($arrayAgendMedico[1] != '[]'){?>
        var AgendMedicoGrafico = new Chart(AgendMedicoGraficoCTX, AgendMedicoGraficoconfig);
    <?}?>

    // Evento de click no gráfico de pie (Grupos).
    // document.getElementById("AgendMedicoGrafico").onclick = function (evt) {
    //     var activePoints = AgendMedicoGrafico.getElementsAtEventForMode(evt, 'point', AgendMedicoGrafico.options);
    //     var firstPoint = activePoints[0];
    //     var label = AgendMedicoGrafico.data.labels[firstPoint._index];
    //     window.open('<? base_url() . "ambulatorio/exame/dashboardsubgrupoconfirmado?data_inicio="?>' + $("#data_inicio").val() + '&data_fim=' + $("#data_fim").val() + '&grupo=' + label + '&empresa_id=' + $("#empresa_id").val(), '_blank', 'width=1000,height=600');
    // };


    var subgrupoAgenCTX = document.getElementById('subgrupoAgenGrafico').getContext('2d');
    var subgrupoAgenconfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arraySubgruposAgen[1]?>,
                backgroundColor: getRandomColorHex(<?=$arraySubgruposAgen[2]?>)
            }],
            labels: <?=$arraySubgruposAgen[0]?>
        }
    };
    <?if($arraySubgruposAgen[1] != '[]'){?>
        var subgrupoAgenGrafico = new Chart(subgrupoAgenCTX, subgrupoAgenconfig);
    <?}?>

    // Evento de click no gráfico de pie (Grupos).
    // document.getElementById("subgrupoAgenGrafico").onclick = function (evt) {
    //     var activePoints = subgrupoAgenGrafico.getElementsAtEventForMode(evt, 'point', subgrupoAgenGrafico.options);
    //     var firstPoint = activePoints[0];
    //     var label = subgrupoAgenGrafico.data.labels[firstPoint._index];
    //     window.open('<? base_url() . "ambulatorio/exame/dashboardsubgrupoconfirmado?data_inicio="?>' + $("#data_inicio").val() + '&data_fim=' + $("#data_fim").val() + '&grupo=' + label + '&empresa_id=' + $("#empresa_id").val(), '_blank', 'width=1000,height=600');
    // };


</script>