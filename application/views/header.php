<?
//Da erro no home
//if ($this->session->userdata('autenticado') != true) {
//    redirect(base_url() . "login/index/login004", "refresh");
//}
$perfil_id = $this->session->userdata('perfil_id');
$operador_id = $this->session->userdata('operador_id');

$empresa_id = $this->session->userdata('empresa_id');
$this->db->select('e.*, ep.*');
$this->db->from('tb_empresa e');
$this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
$this->db->where('e.empresa_id', $empresa_id);
$retorno_header = $this->db->get()->result();


$this->db->select('o.operador_id,
                    o.perfil_id,
                    o.profissional_agendar_o,
                    p.nome as perfil,
                    a.modulo_id,
                    oe.operador_empresa_id,
                    o.medico_agenda');
$this->db->from('tb_operador o');
$this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
$this->db->join('tb_acesso a', 'a.perfil_id = o.perfil_id','left');
$this->db->join('tb_operador_empresas oe', 'oe.operador_id = o.operador_id', 'left');
$this->db->where('o.operador_id', $this->session->userdata('operador_id'));
$this->db->where('oe.empresa_id', $empresa_id);
$this->db->where('oe.ativo = true');
$this->db->where('o.ativo = true');
$retorno_paciente = $this->db->get()->result();

$chat = @$retorno_header[0]->chat;
$geral = @$retorno_header[0]->geral;
$ponto = @$retorno_header[0]->ponto;
$caixa = @$retorno_header[0]->caixa;
$imagem = @$retorno_header[0]->imagem;
$estoque = @$retorno_header[0]->estoque;
$consulta = @$retorno_header[0]->consulta;
$farmacia = @$retorno_header[0]->farmacia;
$uso_salas = @$retorno_header[0]->uso_salas;
$perfil_id = $this->session->userdata('perfil_id');
$marketing = @$retorno_header[0]->marketing;
$enfermagem = @$retorno_header[0]->enfermagem;
$internacao = @$retorno_header[0]->internacao;
$financeiro = @$retorno_header[0]->financeiro;
$empresa_id = $this->session->userdata('empresa_id');
$calendario = @$retorno_header[0]->calendario;
$manternota = @$retorno_header[0]->manternota;
$operador_id = $this->session->userdata('operador_id');
$relatoriorm = @$retorno_header[0]->relatoriorm;
$odontologia = @$retorno_header[0]->odontologia;
$laboratorio = @$retorno_header[0]->laboratorio;
$faturamento =  @$retorno_header[0]->faturamento;
$relatorio_rm = @$retorno_header[0]->relatorio_rm;
$logo_clinica = $this->session->userdata('logo_clinica');
$especialidade =  @$retorno_header[0]->especialidade;
$endereco_toten = $this->session->userdata('endereco_toten');
$dashboard_administrativo = @$retorno_header[0]->dashboard_administrativo;
$financ_4n = @$retorno_header[0]->financ_4n;
$agenda_modelo2 = @$retorno_header[0]->agenda_modelo2;
$faturamento_novo = @$retorno_header[0]->faturamento_novo;
$medico_estoque = @$retorno_header[0]->medico_estoque;
$relatorio_dupla = @$retorno_header[0]->relatorio_dupla;
$hora_agendamento = @$retorno_header[0]->hora_agendamento;
$historico_antigo_administrador = @$retorno_header[0]->historico_antigo_administrador;
$orcamento_multiplo = @$retorno_header[0]->orcamento_multiplo;
$relatorio_caixa_antigo = @$retorno_header[0]->relatorio_caixa_antigo;
$limitar_acesso = @$retorno_header[0]->limitar_acesso;
$fila_impressao = @$retorno_header[0]->fila_impressao;
$relatorio_caixa = @$retorno_header[0]->relatorio_caixa;
$centrocirurgico = @$retorno_header[0]->centrocirurgico;
$relatorio_ordem = @$retorno_header[0]->relatorio_ordem;
$integracaosollis = @$retorno_header[0]->integracaosollis;
$manter_indicacao = @$retorno_header[0]->manter_indicacao;
$calendario_layout = @$retorno_header[0]->calendario_layout;
$sala_de_espera = $this->session->userdata('autorizar_sala_espera');
$perfil_marketing_p = @$retorno_header[0]->perfil_marketing_p;
$medicinadotrabalho = @$retorno_header[0]->medicinadotrabalho;
$medico_solicitante = @$retorno_header[0]->medico_solicitante;
$orcamento_recepcao = @$retorno_header[0]->orcamento_recepcao;
$relatorio_producao = @$retorno_header[0]->relatorio_producao;
$relatorio_operadora = @$retorno_header[0]->relatorio_operadora;
$relatorios_recepcao = @$retorno_header[0]->relatorios_recepcao;
$financeiro_cadastro = @$retorno_header[0]->financeiro_cadastro;
$caixa_personalizado = @$retorno_header[0]->caixa_personalizado;
$profissional_agendar = @$retorno_header[0]->profissional_agendar;
$profissional_agendar_o = @$retorno_paciente[0]->profissional_agendar_o;
$gerente_contasapagar = @$retorno_header[0]->gerente_contasapagar;
$subgrupo_procedimento = $this->session->userdata('subgrupo_procedimento');
$relatorios_clinica_med = @$retorno_header[0]->relatorios_clinica_med;
$relatorio_demandagrupo = @$retorno_header[0]->relatorio_demandagrupo;
$procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
$gerente_recepcao_top_saude = @$retorno_header[0]->gerente_recepcao_top_saude;
$gerente_relatorio_financeiro = @$retorno_header[0]->gerente_relatorio_financeiro;
$retirar_preco_procedimento = @$retorno_header[0]->retirar_preco_procedimento;
$aparecer_orcamento = @$retorno_header[0]->aparecer_orcamento;
$cirugico_manual = @$retorno_header[0]->cirugico_manual;
$tecnico_acesso_acesso = @$retorno_header[0]->tecnico_acesso_acesso;
$convenio_padrao = @$retorno_header[0]->convenio_padrao;
@$tarefa_medico = @$retorno_header[0]->tarefa_medico;
@$valores_recepcao = @$retorno_header[0]->valores_recepcao;
@$filaaparelho = @$retorno_header[0]->filaaparelho;
@$setores = @$retorno_header[0]->setores;
@$bardeira_status = @$retorno_header[0]->bardeira_status;
@$manter_gastos = @$retorno_header[0]->manter_gastos;
$medico_agenda = $this->session->userdata('medico_agenda');

function alerta($valor) {
    echo "<script>alert('$valor');</script>";
}

function debug($object) {

}

?>
<!DOCTYPE html PUBLIC "-//carreW3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR" >
<head><!--<meta charset="utf-8">-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STG - SISTEMA DE GESTAO DE CLINICAS v2.0</title>
    <!-- <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" /> -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<!--    <link src="http://maxcdn.bootstrapcdn.com/font-awesome/5.10.0/css/font-awesome.min.css" rel="stylesheet">-->
    <!-- Font Awesome Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <link href="<?= base_url() ?>bootstrap/assets/css/all.css" rel="stylesheet" />
<!--    <link href="--><?//= base_url() ?><!--bootstrap/assets/css/fontawesome.css" rel="stylesheet" />-->
    <link href="<?= base_url() ?>bootstrap/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="<?= base_url() ?>bootstrap/assets/less/fontawesome.less" rel="stylesheet/less" type="text/css" />
    <link href="<?= base_url() ?>bootstrap/assets/css/fontawesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>bootstrap/assets/css/brands.css" rel="stylesheet">
    <link href="<?= base_url() ?>bootstrap/assets/css/solid.css" rel="stylesheet">

<!--    <script src="--><?//= base_url() ?><!--bootstrap/assets/js/all.js" type="text/javascript"></script>-->
    <script src="<?= base_url() ?>bootstrap/assets/js/brands.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/solid.js" type="text/javascript"></script>
<!--    <script src="--><?//= base_url() ?><!--bootstrap/assets/js/fontawesome.js" type="text/javascript"></script>-->
    <!-- CSS Files -->
    <link href="<?= base_url() ?>bootstrap/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <link href="<?= base_url() ?>bootstrap/assets/css/argon-design-system.css" rel="stylesheet"/>

    <!-- <link href="<?= base_url() ?>css/header.css" rel="stylesheet"/> -->
    <link href="<?= base_url() ?>js/jquery-ui.css" rel="stylesheet"/>
    <link href="<?= base_url() ?>js/jquery-ui.structure.css" rel="stylesheet"/>
    <link href="<?= base_url() ?>js/jquery-ui.theme.css" rel="stylesheet"/>
    <link href="<?= base_url() ?>css/header1.css?v=2.2" rel="stylesheet"/>

    <!--CSS DO Calendário-->
    <link href="<?= base_url() ?>bootstrap/fullcalendar/main.css" rel="stylesheet" />
    <!--DEFINE TAMANHO MAXIMO DOS CAMPOS-->
    <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
    <!--CSS DO ALERTA BONITINHO-->
    <link href="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.css" rel="stylesheet" type="text/css" />
    <!--   Core JS Files   -->
<!--    <script src="--><?//= base_url() ?><!--bootstrap/assets/js/all.js" type="text/javascript"></script>-->
    <script src="<?= base_url() ?>bootstrap/vendor/alert/dist/sweetalert.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>bootstrap/assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/plugins/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="<?= base_url() ?>bootstrap/assets/js/plugins/bootstrap-switch.js" type="text/javascript"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?= base_url() ?>bootstrap/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/assets/js/plugins/datetimepicker.js" type="text/javascript"></script>
    <!-- <script src="<?= base_url() ?>bootstrap/assets/js/plugins/bootstrap-datepicker.min.js" type="text/javascript"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>bootstrap/vendor/autocomplete/jquery.easy-autocomplete.js" ></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskMoney.js"></script>
    <script src="<?= base_url() ?>bootstrap/vendor/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>bootstrap/vendor/jquery/jquery.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui.js"></script>

    <script src="<?= base_url() ?>bootstrap/vendor/font-awesome/css/fonts.js" type="text/javascript"></script>

    <script  src="<?= base_url() ?>bootstrap/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script  src="<?= base_url() ?>bootstrap/vendor/metisMenu/metisMenu.min.js" type="text/javascript"></script>


    <script type="text/javascript" src="<?= base_url() ?>bootstrap/vendor/autocomplete/jquery.easy-autocomplete.js" ></script>

    <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskMoney.js"></script>
    <!-- Control Center for Argon UI Kit: parallax effects, scripts for the example pages etc -->
    <!--  Google Maps Plugin    -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> -->
    <script src="<?= base_url() ?>bootstrap/assets/js/argon-design-system.min.js" type="text/javascript"></script>

    <!--Scripts necessários para o calendário-->
    <link href="<?= base_url() ?>bootstrap/fullcalendar-scheduler/main.css" rel='stylesheet'/>
    <script src="<?= base_url() ?>bootstrap/fullcalendar-scheduler/main.js"></script>
    <script src="<?= base_url() ?>bootstrap/fullcalendar-scheduler/locales/pt-br.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>js/tinymce5/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        $(document).ready(function () {
            $('.datas').datepicker({
                autosize: true,
                changeYear: true,
                changeMonth: true,
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                buttonImage: '<?= base_url() ?>img/form/date.png',
                dateFormat: 'dd/mm/yy'
            });
        });

    </script>

</head>

<body class="index-page">
<!--NAV BAR -->
<nav id="navbar-main" class="navbar navbar-light navbar-expand-lg">

    <a class="navbar-brand" href="<?= base_url() ?>home">
        <img src="<?= base_url() ?>img/logo.png"  alt="stg - logo">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="navbar_global">
        <div class="navbar-collapse-header">
            <div class="row">
                <div class="col-6 collapse-brand">
                    <a href="<?= base_url() ?>home">
                        <img src="<?= base_url() ?>img/logo.png"  alt="stg - logo">
                    </a>
                </div>
                <div class="col-6 collapse-close">
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
        <?if($this->session->userdata('operador_id') > 0){?>
        <ul class="navbar-nav mr-auto">
            <!-- MENUS START -->
            <li class="dropdown">
                
                <a class="dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown" >
                    <i class="fa fa-address-book-o fa-fw"></i>Cadastro
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <!-- <li class="dropdown"> -->
                        <li>
                            <a class="dropdown-item"  href="<?= base_url() ?>cadastros/pacientes">Aluno</a>
                        </li>
                       <?if($this->session->userdata('perfil_id') != 9){?>
                        <li>
                             <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/convenio">Convênios</a>
                        </li>  
                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>ambulatorio/modelolaudo/pesquisar">Instituição de Ensino</a>
                        </li> 
                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/representanteunidade">Representante da Unidade</a>
                        </li> 
                        <?}?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>cadastros/pacientes/solicitarvagas">Solicitação de Vaga</a>
                        </li>
                    <!-- </li> -->
                </ul>
            </li>


            <li class="nav-item dropdown">
            
                    

                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-edit fa-fw"></i> Rotinas <span class="fa arrow"></span></a>
                        <ul  class="dropdown-menu">
                            <li>
                                <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta">Atendimento Médico</a>
                            </li>
                            <li>
                                <a hidden class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/exame/relatorioteste">Relatório Teste</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-book-medical fa-fw"></i> Imagem <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-edit fa-fw"></i>Rotinas</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedico">Multifuncao Medico</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo">Manter Laudo</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisardigitador">Manter Laudo Digitador</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarrevisor">Manter Revisor</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarlaudoantigo">Manter Antigo</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsultaantigo">Histórico de Atendimentos Antigos</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bar-chart-o fa-fw"></i> Relatorios</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio de Produ&ccedil;&atilde;o</a>
                                    </li>

                                </ul>
                            </li>

                        </ul>

                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-book-medical"></i> Consultas </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-edit fa-fw"></i>Rotinas</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta">Multifuncao Medico</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsulta">Manter Consulta</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsultaantigo">Histórico de Atendimentos Antigos</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarrevisor">Manter Revisor</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarlaudoantigo">Manter Antigo</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsultaantigo">Histórico de Atendimentos Antigos</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bar-chart-o fa-fw"></i> Relatorios</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio de Produ&ccedil;&atilde;o</a>
                                    </li>

                                </ul>
                            </li>

                        </ul>

                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-book-medical"></i> Especialidade </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-edit fa-fw"></i>Rotinas</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicofisioterapia">Multifuncao Especialidade</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicofisioterapiareagendar">Reagendar</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bar-chart-o fa-fw"></i> Relatorios</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio de Produ&ccedil;&atilde;o</a>
                                    </li>

                                </ul>
                            </li>

                        </ul>

                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-book-medical"></i> Odontologia </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-edit fa-fw"></i>Rotinas</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoodontologia">Multifuncao Especialidade</a>
                                    </li>
                                    <li>
                                        <!--<a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoodontologiareagendar">Reagendar</a>-->
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bar-chart-o fa-fw"></i> Relatorios</a>
                                <ul  class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio de Produ&ccedil;&atilde;o</a>
                                    </li>

                                </ul>
                            </li>

                        </ul>

                    </li>
                </ul>

            </li>

            <?if($this->session->userdata('perfil_id') != 9){?>
            <li class="nav-item dropdown">
<!--                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-university fa-fw"></i> Gestão Financeira </a>-->

                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-money fa-fw"></i></i> Financeiro <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/caixa">Entradas</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/caixa/pesquisar2">Saidas</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/contaspagar">Contas a Pagar</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/contasreceber">Contas a Receber</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/fornecedor">Credor/Devedor</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Relatorios <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/guia/relatoriocaixa">Relatorio Caixa</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconveniofinanceiro">Relatorio Produ&ccedil;&atilde;o M&eacute;dica</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/guia/relatorioindicacaoexames">Relatorio Recomendação</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
                <?}?>


            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-handshake-o"></i> Gestão de Vagas </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/pesquisarMapaGestao">Cadastro de vagas</a>
                        </li>

                        <?if($this->session->userdata('perfil_id') != 9){?>
                      
                        <!-- <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/procedimentoplano">Convenios</a>
                        </li> -->
                        <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/confinformacao">Configuração de campos</a>
                        </li>

                        <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/responsavelorigem">Supervisores na Origem</a>
                        </li>

                        <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/responsavelifj">Supervisores IJF</a>
                        </li>

                        <li>
                            <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/relatoriodeestagiovagas">Relatorio de Vagas Associadas</a>
                        </li>
                        <?}?>


                    </ul>
            </li>

            <?if($this->session->userdata('perfil_id') != 9){?>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-line-chart"></i> Gestão de Estágio </a>
                    <!-- <i class="fa fa-edit fa-fw"></i> Rotinas <span class="fa arrow"></span></a> -->
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/pesquisarGestaoEstagio">Lista De Estágios</a>
                            </li>
                        </ul>
            </li>
            <?}?>

            <?if($this->session->userdata('perfil_id') != 9){?>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs fa-fw"></i>Gestão de Acessos</a>

                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-address-book-o fa-fw"></i> Recepção <span class="fa arrow"></span></a>
                   
                            <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>seguranca/operador">Listar Profissionais</a>
                            </li>
                            <li>
                                <!--<a href="<?= base_url() ?>ambulatorio/tipoconsulta">Tipo consulta</a>-->
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/agenda">Criação de Agenda</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/agenda/medicoagendaconsulta">Excluir/Alterar Agenda</a>
                            </li>
                            <li>
                                <!--<a href="<?= base_url() ?>ambulatorio/exame">Agenda Manter</a>-->
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/motivocancelamento">Motivo cancelamento</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/indicacao">Manter Indicação</a>
                            </li>
                            <!-- <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/guia/configurarimpressao">Configurar Impressão</a>
                            </li> -->
                               
                                <li>
                                <!--<a href="<?= base_url() ?>ambulatorio/modelodeclaracao">Modelo Declara&ccedil;&atilde;o</a>-->
                            </li>
                                
                            </ul>
                    </li>
                    <!-- <li class="dropdown">
                             <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-clone fa-fw"></i> Modelos</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modelolinha/pesquisar">Manter Modelo Linha</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modeloreceita/pesquisar">Manter Modelo Receita</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modeloatestado/pesquisar">Manter Modelo Atestado</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modeloreceitaespecial/pesquisar">Manter Modelo R. Especial</a>
                           </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modelodeclaracao/pesquisar">Modelo Declara&ccedil;&atilde;o</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modelosolicitarexames/pesquisar">Manter Modelo S.Exames</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modelomedicamento/pesquisar">Manter Medicamento</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/modelomedicamento/pesquisarunidade">Manter Medicamento Unidade</a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>ambulatorio/modelolinha">Manter Modelo Linha</a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-medkit fa-fw"></i> Procedimentos <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/procedimento/pesquisartuss">Manter Procedimentos TUSS</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/procedimento">Manter Procedimentos</a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-money fa-fw"></i> Financeiro <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        </ul>
                    </li> -->
                    <!-- <li class="dropdown">
                       <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-gear fa-fw"></i> Aplicativo <span class="fa arrow"></span></a>
                       <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            
                        </ul>
                    </li> -->

                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-money fa-fw"></i> Financeiro <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/tipo">Manter Tipo</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/classe">Manter Classe</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/forma">Manter Conta</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/formapagamento">Manter Forma de Pagamento</a>
                            </li>
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-gear fa-fw"></i> Configuração <span class="fa arrow"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/pacientes/listardocumentacaoprofissional">Documentação Estagiarios</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>cadastros/empresa/gerecianet">Manter Gerencia Net</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/empresa/listarpostsblog">Posts Blog</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/empresa/listarpesquisaSatisfacao">Pesquisa Satisfação</a>
                            </li>
                            <li>
                                <a class="dropdown-item drop-head" href="<?= base_url() ?>ambulatorio/empresa/">Dados da Empresa</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <?}?>
            <li class="nav-item nav-item-user dropdown">
                <a class="dropdown-toggle" href="#" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Seja bem-vindo, <?= $this->session->userdata('login'); ?>
                    <i class="fa fa-user fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                    <ul>
                        <a href="<?= base_url() ?>seguranca/operador/alterarheader/<?=$operador_id?>"><i class="fa fa-user fa-fw"></i> Perfil</a>
                        <a class="dropdown-item drop-head" href="<?= base_url() ?>seguranca/operador"><i class="fa fa-gear fa-fw"></i> Configurações</a>
                        <div class="dropdown-divider"></div>
                        <a onclick="confirmacao()" style="cursor: pointer;"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <?}?>
</nav>
</body>

<script>



    // $('.dropdown-menu a.removefromcart').click(function(e) {
    //     e.stopPropagation();
    // });

    (function($){
        $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');

            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");
            });

            return false;
        });
    })(jQuery)

    $(document).ready(function () {
        $('#txtNascimento').mask('99/99/9999');
        $("#valor").maskMoney();
        $('#txtCpf').mask('999.999.999-99');
        $('.data').mask('99/99/9999');
        $('.cnpj').mask('99.999.999/9999-99');
        $('.cpf').mask('999.999.999-99');
        $('.cep').mask('99999-999');
        $('.celular').mask('(99) 99999-9999');
        $('.telefone').mask('(99) 9999-9999');
        $('.hora').mask('99:99');
        $(".integer").maskMoney({allowNegative: false, decimal: '.', affixesStay: false, precision: 2});
        $(".percentual").maskMoney({allowNegative: true, decimal: '.', affixesStay: false, precision: 3});
        $(".dinheiro").maskMoney({allowNegative: false, thousands: '.', decimal: ',', affixesStay: false, precision: 2});

    });

    function confirmacao() {
        swal({
                title: "Tem certeza?",
                text: "Você está prestes a sair do sistema!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#337ab7",
                confirmButtonText: "Sim, quero sair!",
                cancelButtonText: "Não, cancele!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.open('<?= base_url() ?>login/sair', '_self');
                } else {
                    swal("Cancelado", "Você desistiu de sair do sistema", "error");
                }
            });

    }

</script>


<!--    Aqui abaixo você encontra a função que chama a mensagem bonitinha, pessoa que estiver olhando.-->
<!--
PRA ALTERAR A MENSAGEM PADRÃO QUE APARECE E O ICONE A QUE É ATRIBUIDO, É SÓ ENTRAR NO UTILITARIO E PROCURAR A FUNÇÃO QUE ELE CHAMA
DAI TEM LÁ UM ARRAY ONDE EU PASSO DUAS COISAS, UMA É A MENSAGEM QUE VAI APARECER E A OUTRA É SE É 'WARNING' 'ERROR' OU 'SUCCESS'-->
<?php
$this->load->library('utilitario');
$mensagem = Utilitario::pmf_mensagem($this->session->flashdata('message'));
?>
