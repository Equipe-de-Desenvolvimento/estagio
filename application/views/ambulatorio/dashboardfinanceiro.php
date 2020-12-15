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

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>js/fullcalendar/fullcalendar.css" rel="stylesheet" />
        <link href="<?= base_url() ?>js/fullcalendar/lib/cupertino/jquery-ui.min.css" rel="stylesheet" />

        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.js" ></script>-->
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
    .custom-combobox {
        position: relative;
        display: inline-block;
    }
    .custom-combobox-toggle {
        position: absolute;
        top: 0;
        bottom: 0;
        margin-left: -1px;
        padding: 0;
    }
    .custom-combobox-input {
        margin: 0;
        padding: 5px 10px;
        width: 50px;
    }
    .custom-combobox a {
        display: inline-block;        
    }
</style>
<script src="<?= base_url() ?>js/webcam.js"></script>
<script>
    (function ($) {
        $(function () {
            $('input:text').setMask();
        });
    })(jQuery);

</script>
<style>

    body {
        /*margin: 40px 10px;*/
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        background-color: white;
    }
    .content{
        margin-left: 0px;
        margin-bottom:0px;
    }

    .singular table div.bt_link_new .btnTexto {color: #2779aa; }
    .singular table div.bt_link_new .btnTexto:hover{ color: red; font-weight: bolder;}
    .vermelho{
        color: red;
    }
    /*#pop{display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:300px;height:200px;border:1px solid #d0d0d0}*/

</style>
<div class="content"> <!-- Inicio da DIV content -->
    
    <div class="accordion">
        <h3 class="singular"><a href="#">Dashboard Financeiro</a></h3>
        <div>
            <table>
                <thead>
                    <!-- <tr> -->
                    
                        <form method="get" action="<?= base_url() ?>ambulatorio/exame/dashboardfinanceiro">
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
    <!-- <br> -->
    <?
    // Montando os gráficos
    // 1 - Convenios.
    // paciente
    $valorTotal = 0;
    @$pacienteContador = count($paciente);
    @$ticketProc = 0;
    @$arrayConvenio = array();
    @$arrayNome = array();
    $arrayQtde = array();
    $qtdeTotal = 0;

    foreach ($convenios as $key => $value) {
        array_push($arrayNome, $value->convenio_realizado);
        array_push($arrayQtde, $value->quantidade);
        $valorTotal += $value->valor_total;
        $qtdeTotal += $value->qtde_exames;
        # code...
    }
    if($pacienteContador > 0){
        $ticketProc = round($valorTotal/$pacienteContador, 2);
    }
    
    $arrayConvenio[0] = (count($arrayNome) > 0) ? json_encode($arrayNome) : '[]';
    $arrayConvenio[1] = (count($arrayQtde) > 0) ? str_replace('"', '',json_encode($arrayQtde)) : '[]';
    $arrayConvenio[2] = count($arrayQtde);
    // var_dump($arrayConvenio); die;
    
    ?>
    <style>
   
    </style>
    <div class="accordion">
        <h3 class="singular"><a href="#">Resultados</a></h3>
        <div>
            <div class="flex center" >
                <div class="flex-item-1">
                    <div class="panelFinanceiro">
                        <span class="spanFinanceiro">
                            <i class="fas fa-coins"></i> R$: <?=number_format($valorTotal, 2, ',', '.');?>
                        </span>
                        <br>
                        <span>Procedimentos</span>
                        
                    </div>
                </div>
                <div class="flex-item-1">
                    <div class="panelFinanceiro">
                        <span class="spanFinanceiro">
                            <i class="fas fa-user-check"></i> <?=$pacienteContador?>
                        </span>
                        <br>
                        <span>Pacientes</span>
                        
                    </div>
                </div>
                <div class="flex-item-1">
                    <div class="panelFinanceiro">
                        <span class="spanFinanceiro">
                            <i class="fas fa-money-bill"></i> R$: <?=number_format($ticketProc, 2, ',', '.')?>
                        </span>
                        <br>
                        <span>Ticket Médio</span>
                        
                    </div>
                </div>
                
            </div>
            <br>
            <div>
                <div class="flex" style="width: 88%">                   
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Pacientes por Convênio</span></a>
                        <a href="#void" onclick="enviarFormExcel('myChart', 'Pacientes por Convênio');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="myChart" ></canvas>
                    </div> 
                </div>
                <br>
                <br>
                <div class="flex" style="width: 98%">
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Formas de Pagamento</span></a>
                        <a href="#void" onclick="enviarFormExcel('pagamentoGrafico', 'Formas de Pagamento');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="pagamentoGrafico" ></canvas>
                        <a href="#" ><span class="VermelhoTitle">Formas de Pagamento Valores</span></a>
                        <a href="#void" onclick="enviarFormExcel('pagamentoGraficovalor', 'Formas de Pagamento');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="pagamentoGraficovalor" ></canvas>
                    </div>
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ranking De Produtos Divididos Em Grandes Grupos</span></a>
                        <a href="#void" onclick="enviarFormExcel('gruposGrafico', 'Ranking De Produtos Divididos Em Grandes Grupos');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="gruposGrafico" ></canvas>
                    </div>
                </div>
               
                <br>
                <div class="flex" style="width: 98%">
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ranking De Indicações</span></a>
                        <a href="#void" onclick="enviarFormExcel('indicacaoGrafico', 'Ranking De Indicações');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="indicacaoGrafico" ></canvas>
                    </div>
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ranking De Cancelamentos</span></a>
                        <a href="#void" onclick="enviarFormExcel('cancelamentosGrafico', 'Ranking De Cancelamentos');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="cancelamentosGrafico"></canvas>
                    </div>
                </div>
                
                <br>
                
                <div class="flex" style="width: 78%">
                    
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Consultas Agendadas Por Especialidades</span></a>
                        <a href="#void" onclick="enviarFormExcel('subgrupoAgenGrafico', 'Consultas Agendadas Por Especialidades');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="subgrupoAgenGrafico" ></canvas>
                    </div>
                    
                </div>
                <br>
                <div class="flex" style="width: 78%">
                    
                    <br>
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ranking De Médicos Solicitantes Internos</span></a>
                        <a href="#void" onclick="enviarFormExcel('solicitantesGrafico', 'Ranking De Médicos Solicitantes Internos');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="solicitantesGrafico"></canvas>
                    </div>
                    
                </div>
                <div class="flex" style="width: 78%">
                    
                    
                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ranking De Médicos Solicitantes Externos</span></a>
                        <a href="#void" onclick="enviarFormExcel('solicitantesExtGrafico', 'Ranking De Médicos Solicitantes Externos');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="solicitantesExtGrafico"></canvas>
                    </div>
                </div>

                <br>
                <div style="width: 58%;">
                    <a href="#"><span class="VermelhoTitle">Paciente Recorrente</span></a>
                    <a href="#void" onclick="enviarFormExcel('pacienteRecorrenteGrafico', 'Paciente Recorrente');"><i class="fas fa-file-excel"></i></a>
                    <canvas id="pacienteRecorrenteGrafico"></canvas>
                </div>
                
                <br>
                <div class="flex" style="width: 98%">
                    
                    <div class="flex-item-1">
                    <a href="#" ><span class="VermelhoTitle">Número de Pacientes</span></a>
                    <a href="#void" onclick="enviarFormExcel('atendentePacienteGrafico', 'Atendente Número de Pacientes');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="atendentePacienteGrafico" ></canvas>
                    </div>
                    <div class="flex-item-1">
                    <a href="#" ><span class="VermelhoTitle">Valor Total</span></a>
                    <a href="#void" onclick="enviarFormExcel('atendenteValorGrafico', 'Atendente Valor Total');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="atendenteValorGrafico"></canvas>
                    </div>
                    
                </div>


                <div class="flex" style="width:100%">
                <div class="flex-item-1">
                    <a href="#" ><span class="VermelhoTitle">Valor Total 2</span></a>
                    <a href="#void" onclick="enviarFormExcel('atendenteValorGrafico2', 'Atendente Valor Total');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="atendenteValorGrafico2"></canvas>
                    </div>

                    <div class="flex-item-1">
                        <a href="#" ><span class="VermelhoTitle">Ticket Médio</span></a>
                        <a href="#void" onclick="enviarFormExcel('atendenteTicketGrafico', 'Atendente Ticket Médio');"><i class="fas fa-file-excel"></i></a>
                        <canvas id="atendenteTicketGrafico"></canvas>
                    </div>
                </div>
                
                <br>
                <div style="width: 98%;">
                    <a href="#" ><span class="VermelhoTitle">Faturamento Mensal</span></a>
                    <a href="#void" onclick="enviarFormExcel('mensalGrafico', 'Faturamento Mensal');"><i class="fas fa-file-excel"></i></a>
                    <canvas id="mensalGrafico"></canvas>
                </div>

                
            </div>
            
            
        </div>
    </div>

    <?
    // Montando gráficos

    // 2 - Forma de Pagamento
    $arrayPag = array();
    $arrayPagNome = array();
    $arrayPagQtde = array();
    $arrayPagQtdeCon = array();
    $arrayvalores = array();
    $arrayPagQtdeCon2 = array();

    // Como essa lógica da forma de pagamento é triste e utiliza 4 colunas, 
    //precisa ter essa quantidade absurda de código pra algo simples.
    foreach ($pagamento as $key => $value) {
        // 1
        if(array_key_exists(@$value->formadepagamento, @$arrayPagQtde)){
            @$arrayPagQtde[$value->formadepagamento]++;
            @$arrayvalores[$value->formadepagamento] = @$arrayvalores[$value->formadepagamento] + $value->valor1;
            

        }else{
            @$arrayPagQtde[$value->formadepagamento] = 1;
            @$arrayvalores[$value->formadepagamento] = $value->valor1;
        }
        // 2
        if(array_key_exists(@$value->formadepagamento2, @$arrayPagQtde)){
            @$arrayPagQtde[$value->formadepagamento2]++;
            @$arrayvalores[$value->formadepagamento2] = @$arrayvalores[$value->formadepagamento2] + $value->valor2;
            

        }else{
            @$arrayPagQtde[$value->formadepagamento2] = 1;
            @$arrayvalores[$value->formadepagamento2] = $value->valor2;
        }
        // 3
        if(@array_key_exists(@$value->formadepagamento3, @$arrayPagQtde)){
            @$arrayPagQtde[$value->formadepagamento3]++;
            @$arrayvalores[$value->formadepagamento3] = @$arrayvalores[$value->formadepagamento3] + $value->valor3;
            

        }else{
            @$arrayPagQtde[$value->formadepagamento3] = 1;
            @$arrayvalores[$value->formadepagamento3] = $value->valor3;
        }
        // 4
        if(array_key_exists(@$value->formadepagamento4, @$arrayPagQtde)){
            @$arrayPagQtde[$value->formadepagamento4]++;
            @$arrayvalores[$value->formadepagamento4] = @$arrayvalores[$value->formadepagamento4] + $value->valor4;
            

        }else{
            @$arrayPagQtde[$value->formadepagamento4] = 1;
            @$arrayvalores[$value->formadepagamento4] = $value->valor4;
        }
       
    }

    // echo '<pre>';
    // print_r ($pagamento);
    // print_r ($arrayPagQtde);
    // print_r ($arrayvalores);
    // die;



    // Ajustando o array com o nome das formas de pagamento
    foreach ($arrayPagQtde as $key => $value) {
        if($key != ''){
            array_push($arrayPagNome, $key);
            array_push($arrayPagQtdeCon, $value);
        }
        
    }

    foreach ($arrayvalores as $key => $value) {
        if($key != ''){
            array_push($arrayPagQtdeCon2, $value);
        }
        
    }

    // echo '<pre>';
    // print_r ($arrayPagQtdeCon);
    // print_r ($arrayPagQtdeCon2);
    // die;

    // foreach($arrayPagQtdeCon as $key => $value){
    //     $arrayPagQtdeCon[$key] = $value ." Valor: ".$arrayPagQtdeCon2[$key];
    // }

    // print_r ($arrayPagQtdeCon);
    // die;
    unset($arrayvalores[""]);
    unset($arrayPagQtde[""]);
    // Tirando as keys vazias
    
    $arrayPag[0] = (count($arrayPagNome) > 0) ? json_encode($arrayPagNome) : '[]';
    $arrayPag[1] = (count($arrayPagQtdeCon) > 0) ? str_replace('"', '', json_encode($arrayPagQtdeCon)) : '[]';
    $arrayPag[2] = count($arrayPagQtde);
    $arrayPag[3] = (count($arrayPagQtdeCon2) > 0) ? str_replace('"', '', json_encode($arrayPagQtdeCon2)) : '[]';


    // echo '<pre>';
    // print_r ($arrayPag);
    // die;

    
    // 3 - Grupos

    $arrayGrupo = array();
    $arrayGrupoNome = array();
    $arrayGrupoQtde = array();
    $arrayGrupoPerc = array();

    foreach ($grupos as $key => $value) {
        $percentual = round((100 * $value->quantidade) / $qtdeTotal, 2);
        // $percentualCon = str_replace(".", ',', $percentual) ;
        if($percentual < 1){
            continue;
        }
        array_push($arrayGrupoNome, $value->grupo);
        array_push($arrayGrupoPerc, $percentual);
        array_push($arrayGrupoQtde, $value->quantidade );
        # code...
    }

    $arrayGrupo[0] = (count($arrayGrupoNome) > 0) ? json_encode($arrayGrupoNome) : '[]';
    $arrayGrupo[1] = (count($arrayGrupoQtde) > 0) ? str_replace('"', '', json_encode($arrayGrupoQtde)) : '[]';
    $arrayGrupo[2] = count($arrayGrupoQtde);

//  4 Médicos
    $arraymedico = array();
    $arraymedicoNome = array();
    $arraymedicoQtde = array();
    $arraymedicoID = array();

    foreach ($medicos as $key => $value) {
        array_push($arraymedicoNome, $value->medico);
        array_push($arraymedicoQtde, $value->quantidade);
        array_push($arraymedicoID, $value->medico_consulta_id);
    }

    $arraymedico[0] = (count($arraymedicoNome) > 0) ? json_encode($arraymedicoNome) : '[]';
    $arraymedico[1] = (count($arraymedicoQtde) > 0) ? str_replace('"', '', json_encode($arraymedicoQtde)) : '[]';
    $arraymedico[2] = count($arraymedicoQtde);
    $arraymedico[3] = (count($arraymedicoID) > 0) ? str_replace('"', '', json_encode($arraymedicoID)) : '[]';

// 5 Indicação

    $arrayIndicacao = array();
    $arrayIndicacaoNome = array();
    $arrayIndicacaoQtde = array();
    $arrayIndicacaoPerc = array();
    
 

    foreach ($indicacao as $key => $value) {
        $percentual = round((100 * $value->quantidade) / $qtdeTotal, 2);
        // $percentualCon = str_replace(".", ',', $percentual) ;
        if($percentual < 1){
            continue;
        }
        array_push($arrayIndicacaoNome, $value->indicacao);
        array_push($arrayIndicacaoPerc, $percentual);
        array_push($arrayIndicacaoQtde, $value->quantidade );
        # code...
    }

    $arrayIndicacao[0] = (count($arrayIndicacaoNome) > 0) ? json_encode($arrayIndicacaoNome) : '[]';
    $arrayIndicacao[1] = (count($arrayIndicacaoQtde) > 0) ? str_replace('"', '', json_encode($arrayIndicacaoQtde)) : '[]';
    $arrayIndicacao[2] = count($arrayIndicacaoQtde);
//    echo "<pre>";
//    print_r($qtdeTotal);

// 6 Subgrupos

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



// 7 Agendamentos confirmados

    $arrayAgenConf = array();
    $arrayAgenConfNome = array();
    $arrayAgenConfQtde = array();

    foreach ($AgenConf as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        // if($key != ''){
        array_push($arrayAgenConfNome, $value->procedimento);
        array_push($arrayAgenConfQtde, $value->quantidade);
        // }
    }
    

    $arrayAgenConf[0] = (count($arrayAgenConfNome) > 0) ? json_encode($arrayAgenConfNome) : '[]';
    $arrayAgenConf[1] = (count($arrayAgenConfQtde) > 0) ? str_replace('"', '', json_encode($arrayAgenConfQtde)) : '[]';
    $arrayAgenConf[2] = count($arrayAgenConfQtde);


// 8 Cancelamentos

    $arrayCancelamentos = array();
    $arrayCancelamentosNome = array();
    $arrayCancelamentosQtde = array();

    foreach ($cancelamentos as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        if($value->motivo != ''){
            array_push($arrayCancelamentosNome, $value->motivo);
            array_push($arrayCancelamentosQtde, $value->quantidade);
        }
    }
    

    $arrayCancelamentos[0] = (count($arrayCancelamentosNome) > 0) ? json_encode($arrayCancelamentosNome) : '[]';
    $arrayCancelamentos[1] = (count($arrayCancelamentosQtde) > 0) ? str_replace('"', '', json_encode($arrayCancelamentosQtde)) : '[]';
    $arrayCancelamentos[2] = count($arrayCancelamentosQtde);
    // echo '<pre>'; 
    // var_dump($arrayCancelamentos);
    // var_dump($arraySubgruposAgenQtde);
    // die;


// 10 Solicitantes

    $arraySolicitantes = array();
    $arraySolicitantesNome = array();
    $arraySolicitantesQtde = array();
    $qtdeTotalSol = 0;
    foreach ($solicitantes as $key => $value) {
        # code...
        $qtdeTotalSol += $value->quantidade;
    }
    foreach ($solicitantes as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        $percentual = round((100 * $value->quantidade) / $qtdeTotalSol, 2);
        if($percentual > 1){
            array_push($arraySolicitantesNome, $value->solicitante);
            array_push($arraySolicitantesQtde, $value->quantidade);
        }
    }

    $arraySolicitantes[0] = (count($arraySolicitantesNome) > 0) ? json_encode($arraySolicitantesNome) : '[]';
    $arraySolicitantes[1] = (count($arraySolicitantesQtde) > 0) ? str_replace('"', '', json_encode($arraySolicitantesQtde)) : '[]';
    $arraySolicitantes[2] = count($arraySolicitantesQtde);


    $arraySolicitantesExt = array();
    $arraySolicitantesExtNome = array();
    $arraySolicitantesExtQtde = array();
    $qtdeTotalSolExt = 0;
    foreach ($solicitantesExt as $key => $value) {
        # code...
        $qtdeTotalSolExt += $value->quantidade;
    }
    foreach ($solicitantesExt as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        $percentual = round((100 * $value->quantidade) / $qtdeTotalSolExt, 2);
        if($percentual > 1){
            array_push($arraySolicitantesExtNome, $value->solicitante);
            array_push($arraySolicitantesExtQtde, $value->quantidade);
        }
    }

    $arraySolicitantesExt[0] = (count($arraySolicitantesExtNome) > 0) ? json_encode($arraySolicitantesExtNome) : '[]';
    $arraySolicitantesExt[1] = (count($arraySolicitantesExtQtde) > 0) ? str_replace('"', '', json_encode($arraySolicitantesExtQtde)) : '[]';
    $arraySolicitantesExt[2] = count($arraySolicitantesExtQtde);
    // echo '<pre>'; 
    // var_dump($arraySolicitantes);
    // var_dump($arraySubgruposAgenQtde);
    // die;
    
// 11 Solicitantes

    $arrayAtendentes = array();
    $arrayAtendentesNome = array();
    $arrayAtendentesQtde = array();
    $arrayAtendentesValor = array();
    $arrayAtendentesTicket = array();
    $qtdeTotalSol = 0;
    foreach ($solicitantes as $key => $value) {
        # code...
        $qtdeTotalSol += $value->quantidade;
    }
    foreach ($atendentes as $key => $value) {
        // $percentualCon = str_replace(".", ',', $percentual) ;
        if($value->quantidade > 0){
            $ticket = round($value->valor_total/$value->quantidade, 2);
        }else{
            $ticket = 0;
        }
        if($value->quantidade == 0){
            continue;
        }
        array_push($arrayAtendentesNome, $value->operador);
        array_push($arrayAtendentesValor, $value->quantidade);
        array_push($arrayAtendentesQtde, $value->valor_total);
        array_push($arrayAtendentesTicket, $ticket);
        
    }

    $arrayAtendentes[0] = (count($arrayAtendentesNome) > 0) ? json_encode($arrayAtendentesNome) : '[]';
    $arrayAtendentes[1] = (count($arrayAtendentesValor) > 0) ? str_replace('"', '', json_encode($arrayAtendentesValor)) : '[]';
    $arrayAtendentes[2] = (count($arrayAtendentesQtde) > 0) ? str_replace('"', '', json_encode($arrayAtendentesQtde)) : '[]';
    $arrayAtendentes[3] = (count($arrayAtendentesTicket) > 0) ? str_replace('"', '', json_encode($arrayAtendentesTicket)) : '[]';
    $arrayAtendentes[4] = count($arrayAtendentesQtde);

//        echo '<pre>';
//    print_r($arrayAtendentes);
//    die;
    // echo '<pre>'; 
    // var_dump($arrayAgenConf);
    // var_dump($arraySubgruposAgenQtde);
    // die;

// 11 Paciente Recorrente

    $arrayRecorrente = array();
    $arrayRecorrenteNome = array("Paciente Novos",
                                "Recorrente | 02 a 04 Meses", 
                                "Recorrente | 04 a 06 Meses",
                                "Recorrente | Acima 06 Meses",
                                );
    $arrayRecorrenteQtde = array(0, 0, 0, 0);
    //    $arrayRecorrenteNome
    foreach ($pacienteRec as $key => $value) {
        if($value->mes_diferenca > 6){
            $arrayRecorrenteQtde[3]++;
        }else if($value->mes_diferenca >= 4){
            $arrayRecorrenteQtde[2]++;
        }else if ($value->mes_diferenca >= 2){
            $arrayRecorrenteQtde[1]++;
        }else{
            $arrayRecorrenteQtde[0]++;
        }
    }

    $arrayRecorrente[0] = (count($arrayRecorrenteNome) > 0) ? json_encode($arrayRecorrenteNome) : '[]';
    $arrayRecorrente[1] = (count($arrayRecorrenteQtde) > 0) ? str_replace('"', '', json_encode($arrayRecorrenteQtde)) : '[]';
    $arrayRecorrente[2] = count($arrayRecorrenteQtde); 
//    echo '<pre>'; 
    // var_dump($mensal);
//    die;

// 12 Faturamento Mensal

   $arrayMensal = array();
   $arrayMensalNome = array();
   $arrayMensalQtde = array();

   foreach ($mensal as $key => $value) {
       // $percentualCon = str_replace(".", ',', $percentual) ;
       // if($key != ''){
       $nome_mes = $this->utilitario->retornarNomeMes($value->mes);
       array_push($arrayMensalNome, $nome_mes);
       array_push($arrayMensalQtde, $value->valor_total);
       // }
        // echo '<pre>'; 
        // var_dump($nome_mes);
        // var_dump($arraySubgruposAgenQtde);
        // die;
   }
//    echo '<pre>'; 
    // var_dump($mensal);
//    die;
   

   $arrayMensal[0] = (count($arrayMensalNome) > 0) ? json_encode($arrayMensalNome) : '[]';
   $arrayMensal[1] = (count($arrayMensalQtde) > 0) ? str_replace('"', '', json_encode($arrayMensalQtde)) : '[]';
   $arrayMensal[2] = count($arrayMensalQtde); 

   $arrayValorConvenio = array();
   $arrayValorConvenioNome = array();
   $arrayValorConvenioQtde = array();

   foreach($totalconvenio as $key => $value){
        if($value->valor_convenio == ''){
            $value->valor_convenio = 0;
            array_push($arrayValorConvenioNome, 'Convenio');
            array_push($arrayValorConvenioQtde, $value->valor_convenio);
        }else{
            array_push($arrayValorConvenioNome, 'Convenio');
            array_push($arrayValorConvenioQtde, $value->valor_convenio);
        }

        if($value->valor_particular == ''){
            array_push($arrayValorConvenioNome, 'Particular');
            array_push($arrayValorConvenioQtde, $value->valor_particular);
        }else{
            array_push($arrayValorConvenioNome, 'Particular');
            array_push($arrayValorConvenioQtde, $value->valor_particular);
        }
   }

   $arrayValorConvenio[0] = (count($arrayValorConvenioNome) > 0) ? json_encode($arrayValorConvenioNome) : '[]';
   $arrayValorConvenio[1] = (count($arrayValorConvenioQtde) > 0) ? str_replace('"', '', json_encode($arrayValorConvenioQtde)) : '[]';
   $arrayValorConvenio[2] = count($arrayValorConvenioQtde); 

//    echo '<pre>';
//    print_r($arrayValorConvenio);
//    die;
    
    ?>

    <form method="post" action="<?= base_url() ?>ambulatorio/exame/gerarplanilhadashboard" target="_blank">
        <input type="hidden" name="nome_form" id="nome_form" value=''>
        <input type="hidden" name="valor_form" id="valor_form" value=''>
        <input type="hidden" name="titulo_form" id="titulo_form" value=''>
        <button style="display:none" type="submit" id="enviarFormExcel">Enviar</button>
    </form>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="<?= base_url() ?>js/morris/raphael.js"></script>
<script src="https://kit.fontawesome.com/8ec73d6e70.js"></script>
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
    // Convenio
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?=$arrayConvenio[0]?>,
            datasets: [{
                label: '# Pacientes por convênio',
                data: <?=$arrayConvenio[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayConvenio[2]?>),
                 
                
                borderWidth: 1
            }]
        }
    });
    // Forma de Pagamento
    // Convenio
    var pagamentoCTX = document.getElementById('pagamentoGrafico').getContext('2d');
    var pagamentoGrafico = new Chart(pagamentoCTX, {
        type: 'pie',
        data: {
            labels: <?=$arrayPag[0]?>,
            datasets: [{
                
                data: <?=$arrayPag[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayPag[2]?>),
                 
                
                borderWidth: 1
            }]
        }
    });

    var pagamentoCTX = document.getElementById('pagamentoGraficovalor').getContext('2d');
    var pagamentoGraficovalor = new Chart(pagamentoCTX, {
        type: 'pie',
        data: {
            labels: <?=$arrayPag[0]?>,
            datasets: [{
                
                data: <?=$arrayPag[3]?>,
                backgroundColor: getRandomColorHex(<?=$arrayPag[2]?>),
                 
                
                borderWidth: 1
            }]
        }
    });
    // Evento de click no gráfico de pie (Pagamento).
    document.getElementById("pagamentoGrafico").onclick = function (evt) {
        var activePoints = pagamentoGrafico.getElementsAtEventForMode(evt, 'point', pagamentoGrafico.options);
        var firstPoint = activePoints[0];
        var label = pagamentoGrafico.data.labels[firstPoint._index];
        var value = pagamentoGrafico.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
        // alert(label);
        window.open('<?= base_url() . "ambulatorio/exame/dashboardpagamentoparcela?data_inicio="?>' + $("#data_inicio").val() + '&data_fim=' + $("#data_fim").val() + '&forma=' + label + '&empresa_id=' + $("#empresa_id").val(), '_blank', 'width=1000,height=600');
    };


    function enviarFormExcel(id, titulo){
        
        var grafico = window[id];

        var value = JSON.stringify(grafico.data.datasets[0].data);
        var nome = JSON.stringify(grafico.data.labels);
        $("#nome_form").val(nome);
        $("#valor_form").val(value);
        $("#titulo_form").val(titulo);
        $("#enviarFormExcel").trigger('click');
    }
    
    var gruposCTX = document.getElementById('gruposGrafico').getContext('2d');
    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayGrupo[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayGrupo[2]?>)
            }],
            labels: <?=$arrayGrupo[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = parseFloat(((currentValue/total) * 100)).toFixed(2);         
                        return "Quantidade " + currentValue + " - " + precentage + "%";
                    }
                }      
            },
            legend: {
                display: true,
                labels: {
                    // fontSize: 10,
                    // boxWidth: 20,
                }
            }// Tamanho 
        }
        
    };

    var gruposGrafico = new Chart(gruposCTX, config);
    // Evento de click no gráfico de pie (Grupos).
    document.getElementById("gruposGrafico").onclick = function (evt) {
        var activePoints = gruposGrafico.getElementsAtEventForMode(evt, 'point', gruposGrafico.options);
        var firstPoint = activePoints[0];
        var label = gruposGrafico.data.labels[firstPoint._index];
        // var arrayNomes = <?//=$arraygrupo[3]?>;
        // console.log(label);
        // console.log(grupo_id);
        window.open('<?= base_url() . "ambulatorio/exame/dashboardgruposubgrupo?data_inicio="?>' + $("#data_inicio").val() + '&data_fim=' + $("#data_fim").val() + '&grupo=' + label + '&empresa_id=' + $("#empresa_id").val(), '_blank', 'width=1000,height=600');
    };

    var indicacaoCTX = document.getElementById('indicacaoGrafico').getContext('2d');
    var configIndicacao = {
        type: 'pie',
        data: {
            datasets: [{
                data: <?=$arrayIndicacao[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayIndicacao[2]?>)
            }],
            labels: <?=$arrayIndicacao[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = parseFloat(((currentValue/total) * 100)).toFixed(2);         
                        return precentage + "%";
                    }
                }
            }
        }
    };
    var indicacaoGrafico = new Chart(indicacaoCTX, configIndicacao);

    var solicitantesCTX = document.getElementById('solicitantesGrafico').getContext('2d');
    var configSolicitantes = {
        type: 'pie',
        data: {
            datasets: [{
                data: <?=$arraySolicitantes[1]?>,
                backgroundColor: getRandomColorHex(<?=$arraySolicitantes[2]?>)
            }],
            labels: <?=$arraySolicitantes[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = parseFloat(((currentValue/total) * 100)).toFixed(2);         
                        return "Quantidade: " + currentValue + " - " + precentage + "%";
                    }
                }
            }
        }
    };
    var solicitantesGrafico = new Chart(solicitantesCTX, configSolicitantes);

    var solicitantesExtCTX = document.getElementById('solicitantesExtGrafico').getContext('2d');
    var configExtSolicitantes = {
        type: 'pie',
        data: {
            datasets: [{
                data: <?=$arraySolicitantesExt[1]?>,
                backgroundColor: getRandomColorHex(<?=$arraySolicitantesExt[2]?>)
            }],
            labels: <?=$arraySolicitantesExt[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = parseFloat(((currentValue/total) * 100)).toFixed(2);         
                        return "Quantidade: " + currentValue + " - " + precentage + "%";
                    }
                }
            }
        }
    };
    var solicitantesExtGrafico = new Chart(solicitantesExtCTX, configExtSolicitantes);


    var pacienteRecorrenteCTX = document.getElementById('pacienteRecorrenteGrafico').getContext('2d');
    var pacienteRecorrenteConfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayRecorrente[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayRecorrente[2]?>)
            }],
            labels: <?=$arrayRecorrente[0]?>
        }
    };
    var pacienteRecorrenteGrafico = new Chart(pacienteRecorrenteCTX, pacienteRecorrenteConfig);

    var atendentePacienteCTX = document.getElementById('atendentePacienteGrafico').getContext('2d');
    var atendentePacienteConfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayAtendentes[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayAtendentes[4]?>)
            }],
            labels: <?=$arrayAtendentes[0]?>
        }
    };
    var atendentePacienteGrafico = new Chart(atendentePacienteCTX, atendentePacienteConfig);


    var atendenteValorCTX = document.getElementById('atendenteValorGrafico').getContext('2d');
    var atendenteValorConfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayAtendentes[2]?>,
                data2: <?=$arrayAtendentes[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayAtendentes[4]?>)
            }],
            labels: <?=$arrayAtendentes[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        var currentValue2 = dataset.data2[tooltipItem.index];
         
                        return "R$: " + currentValue.toLocaleString('pt-BR') + " Quantidade: " + currentValue2;
                    }
                }
            }
        }
    };
    var atendenteValorGrafico = new Chart(atendenteValorCTX, atendenteValorConfig);


    var atendenteValorCTX2 = document.getElementById('atendenteValorGrafico2').getContext('2d');
    var atendenteValorConfig2 = {
        type: 'bar',
        data: {
            datasets: [{
                data: <?=$arrayValorConvenio[1]?>,
                label: 'Valores Convenio',
                data2: <?=$arrayValorConvenio[0]?>,
                backgroundColor: getRandomColorHex(<?=$arrayAtendentes[4]?>)
            }],
            labels: <?=$arrayValorConvenio[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        var currentValue2 = dataset.data2[tooltipItem.index];
         
                        return "R$: " + currentValue.toLocaleString('pt-BR');
                    }
                }
            }
        }
    };
    var atendenteValorGrafico2 = new Chart(atendenteValorCTX2, atendenteValorConfig2);

    var atendenteTicketCTX = document.getElementById('atendenteTicketGrafico').getContext('2d');
    var atendenteTicketConfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?=$arrayAtendentes[3]?>,
                backgroundColor: getRandomColorHex(<?=$arrayAtendentes[4]?>)
            }],
             labels: <?=$arrayAtendentes[0]?>
        },
        options: {
            
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
         
                        return "R$: " + currentValue.toLocaleString('pt-BR');
                    }
                }
            }
        }
    };
    var atendenteTicketGrafico = new Chart(atendenteTicketCTX, atendenteTicketConfig);

    var mensalCTX = document.getElementById('mensalGrafico').getContext('2d');
    var mensalConfig = {
        type: 'line',
        data: {
            datasets: [{
                data: <?=$arrayMensal[1]?>,
                label: '# Relatorio Mensal',
            }],
            
            labels: <?=$arrayMensal[0]?>
        },
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
         
                        return "R$: " + currentValue.toLocaleString('pt-BR');
                    }
                }
            }
        },
        
    };
    var mensalGrafico = new Chart(mensalCTX, mensalConfig);
    
    var cancelamentosCTX = document.getElementById('cancelamentosGrafico').getContext('2d');
    var configCancelamentos = {
        type: 'pie',
        data: {
            datasets: [{
                data: <?=$arrayCancelamentos[1]?>,
                backgroundColor: getRandomColorHex(<?=$arrayCancelamentos[2]?>)
            }],
            labels: <?=$arrayCancelamentos[0]?>
        }
    };
    // console.log(configCancelamentos);
    var cancelamentosGrafico = new Chart(cancelamentosCTX, configCancelamentos);

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
    document.getElementById("subgrupoAgenGrafico").onclick = function (evt) {
        var activePoints = subgrupoAgenGrafico.getElementsAtEventForMode(evt, 'point', subgrupoAgenGrafico.options);
        var firstPoint = activePoints[0];
        var label = subgrupoAgenGrafico.data.labels[firstPoint._index];
        // var arrayNomes = <?//=$arraygrupo[3]?>;
        // console.log(label);
        // console.log(grupo_id);
        window.open('<?= base_url() . "ambulatorio/exame/dashboardsubgrupoconfirmado?data_inicio="?>' + $("#data_inicio").val() + '&data_fim=' + $("#data_fim").val() + '&grupo=' + label + '&empresa_id=' + $("#empresa_id").val(), '_blank', 'width=1000,height=600');
    };
    

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

</script>
