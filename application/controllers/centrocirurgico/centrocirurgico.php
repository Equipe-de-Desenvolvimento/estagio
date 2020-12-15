<?php

require_once APPPATH . 'controllers/base/BaseController.php';

class centrocirurgico extends BaseController {

    function __construct() {
        parent::__construct();
        $this->load->model('emergencia/solicita_acolhimento_model', 'acolhimento');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('cadastro/formapagamento_model', 'formapagamento');
        $this->load->model('internacao/internacao_model', 'internacao_m');
        $this->load->model('internacao/unidade_model', 'unidade_m');
        $this->load->model('internacao/motivosaida_model', 'motivosaida');
        $this->load->model('internacao/enfermaria_model', 'enfermaria_m');
        $this->load->model('internacao/leito_model', 'leito_m');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('internacao/solicitainternacao_model', 'solicitacaointernacao_m');
        $this->load->model('centrocirurgico/centrocirurgico_model', 'centrocirurgico_m');
        $this->load->model('centrocirurgico/solicita_cirurgia_model', 'solicitacirurgia_m');
        $this->load->model('cadastro/convenio_model', 'convenio');
        $this->load->model('ambulatorio/GExtenso', 'GExtenso');
        $this->load->model('ambulatorio/motivocancelamento_model', 'motivocancelamento');
        $this->load->model('ambulatorio/modelodeclaracao_model', 'modelodeclaracao');
        $this->load->library('utilitario');
    }

    public function index() {
        $this->pesquisar();
    }

    public function pesquisar($args = array()) {
        $this->loadView('centrocirurgico/listarsolicitacao');
    }

    public function pesquisarsolicitacaorotina($args = array()) {
        $this->loadView('centrocirurgico/pesquisarsolicitacaorotina');
    }

    public function mapacirurgico($args = array()) {
        $this->load->View('centrocirurgico/calendariocirurgico');
    }

    public function pesquisarsaida($args = array()) {
        $this->loadView('internacao/listarsaida');
    }

    public function pesquisarunidade($args = array()) {
        $this->loadView('internacao/listarunidade');
    }

    public function pesquisarcirurgia($args = array()) {
        $this->loadView('centrocirurgico/listarcirurgia');
    }

    public function pesquisarsolicitacaointernacao($args = array()) {
        $this->loadView('internacao/listarsolicitacaointernacao');
    }

    public function pesquisarhospitais($args = array()) {
        $this->loadView('centrocirurgico/hospital-lista');
    }

    public function manterespecialidadeparecer($args = array()) {
        $this->loadView('centrocirurgico/manterespecialidadeparecer-lista');
    }

    public function pesquisarfornecedormaterial($args = array()) {
        $this->loadView('centrocirurgico/fornecedormaterial-lista');
    }

    public function pesquisarequipecirurgica($args = array()) {
        $this->loadView('centrocirurgico/equipecirurgica-lista');
    }

    public function pesquisargrauparticipacao($args = array()) {
        $this->loadView('centrocirurgico/grauparticipacao-lista');
    }

    function solicitacirurgia($internacao_id) {
        $data['paciente'] = $this->solicitacirurgia_m->solicitacirurgia($internacao_id);
        $this->loadView('centrocirurgico/solicitacirurgia', $data);
    }

    function gravarsolicitacaocirurgia() {

        if ($this->solicitacirurgia_m->gravarsolicitacaocirurgia()) {
            $data['mensagem'] = 'Erro ao efetuar solicitação de cirurgia';
        } else {
            $data['mensagem'] = 'Solicitação de Cirurgia efetuada com Sucesso';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function autorizarcirurgia() {
        $this->centrocirurgico_m->autorizarcirurgia();
        $data['mensagem'] = 'Autorizacao efetuada com Sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function faturarprocedimentos($solicitacao_id, $guia_id, $financeiro_grupo_id = null) {
        $data['exame'][0] = new stdClass();
        $data['solicitacao_id'] = $solicitacao_id;
        
        // Criar acima a variável resolve o Warning que aparece na página de Faturar Guia.
        // A linha acima inicia o Objeto antes de atribuir um valor
        if (isset($financeiro_grupo_id)) {
            $data['forma_pagamento'] = $this->centrocirurgico_m->formadepagamentoguiaprocedimentos($guia_id, $financeiro_grupo_id,@$_GET['data']);
            $data['exame'] = $this->centrocirurgico_m->listarexameguiaformaprocedimentos($guia_id, $financeiro_grupo_id,@$_GET['data']);
        } else {
            $data['forma_pagamento'] = $this->centrocirurgico_m->formadepagamento();
            $data['exame1'] = $this->centrocirurgico_m->listarexameguiaprocedimentos($guia_id,@$_GET['data']);
//            $data['exame2'] = $this->centrocirurgico_m->listarexameguiaformaprocedimentos($guia_id, $financeiro_grupo_id);
            $data['exame'][0]->total = $data['exame1'][0]->total;
        }
      
//   echo "<pre>";
//print_r($data['exame1']);
//die();
        $data['financeiro_grupo_id'] = $financeiro_grupo_id;
        $data['guia_id'] = $guia_id;
        $data['valor'] = 0.00;

        $this->load->View('centrocirurgico/faturarprocedimentoscirurgicos-form', $data);
    }

    function faturarequipe($solicitacao_id, $guia_id, $financeiro_grupo_id = null) {
        $data['exame'][0] = new stdClass();
        $data['solicitacao_id'] = $solicitacao_id;
        // Criar acima a variável resolve o Warning que aparece na página de Faturar Guia.
        // A linha acima inicia o Objeto antes de atribuir um valor
        if (isset($financeiro_grupo_id)) {
            $data['forma_pagamento'] = $this->centrocirurgico_m->formadepagamentoguiaprocedimentos($guia_id, $financeiro_grupo_id);
            $data['exame'] = $this->centrocirurgico_m->listarexameguiaformaequipe($guia_id, $financeiro_grupo_id);
        } else {
            $data['forma_pagamento'] = $this->centrocirurgico_m->formadepagamento();
            $data['exame1'] = $this->centrocirurgico_m->listarexameguiaequipe($guia_id);
//            $data['exame2'] = $this->centrocirurgico_m->listarexameguiaformaequipe($guia_id, $financeiro_grupo_id);
            $data['exame'][0]->total = $data['exame1'][0]->total;
        }
//        echo '<pre>';
//        var_dump($data['exame1']); die;

        $data['financeiro_grupo_id'] = $financeiro_grupo_id;
        $data['guia_id'] = $guia_id;
        $data['valor'] = 0.00;

        $this->load->View('centrocirurgico/faturarequipecirurgicos-form', $data);
    }

    function gravarfaturadoprocedimentos() {
//        var_dump($_POST); die;
        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {

            $erro = false;
//             if ($_POST['valorMinimo1'] != '' && ( ((float) $_POST['valorMinimo1']) > ((float) $_POST['valor1']) / $_POST['parcela1'] ) && $_POST['parcela1'] != 1) {
//                 $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 1 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
//                 $erro = true;
// //                echo "<script>alert('something');</script>";
//             }
//             if ($_POST['valorMinimo2'] != '' && ( ((float) $_POST['valorMinimo2']) > ((float) $_POST['valor2']) / $_POST['parcela2'] ) && $_POST['parcela2'] != 1) {
//                 $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 2 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
//                 $erro = true;
// //                echo "<script>alert('something');</script>";
//             }
//             if ($_POST['valorMinimo3'] != '' && ( ((float) $_POST['valorMinimo3']) > ((float) $_POST['valor3']) / $_POST['parcela3'] ) && $_POST['parcela3'] != 1) {
//                 $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 3 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
//                 $erro = true;
// //                echo "<script>alert('something');</script>";
//             }
//             if ($_POST['valorMinimo4'] != '' && ( ((float) $_POST['valorMinimo4']) > ((float) $_POST['valor4']) / $_POST['parcela4'] ) && $_POST['parcela4'] != 1) {
//                 $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 4 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
//                 $erro = true;
// //                echo "<script>alert('something');</script>";
//             }
//             if ($_POST['valorMinimo4'] == '' && $_POST['valorMinimo3'] == '' && $_POST['valorMinimo2'] == '' && $_POST['valorMinimo1'] == '') {
//                $data['mensagem'] = 'Erro ao gravar faturamento.';
//                 $erro = true;
// //                echo "<script>alert('something');</script>";
//             }

            $_POST['parcela1'] = ($_POST['parcela1'] == '' || $_POST['parcela1'] == 0) ? 1 : $_POST['parcela1'];
            $_POST['parcela2'] = ($_POST['parcela2'] == '' || $_POST['parcela2'] == 0) ? 1 : $_POST['parcela2'];
            $_POST['parcela3'] = ($_POST['parcela3'] == '' || $_POST['parcela3'] == 0) ? 1 : $_POST['parcela3'];
            $_POST['parcela4'] = ($_POST['parcela4'] == '' || $_POST['parcela4'] == 0) ? 1 : $_POST['parcela4'];

            if (!$erro) {
                $ambulatorio_guia_id = $this->centrocirurgico_m->gravarfaturamentototalprocedimentos();  
                if ($_POST['valorcredito'] != '' && $_POST['valorcredito'] != '0') {
//                    $this->guia->descontacreditopaciente();
                }
//                var_dump($_POST['valorcredito']);die;

                if ($ambulatorio_guia_id == "-1") {
                    $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
                } else {
                    $data['mensagem'] = 'Sucesso ao gravar faturamento.';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
            } else {
                $mensagem = $data['mensagem'];
                echo "<html>
                    <meta charset='UTF-8'>
        <script type='text/javascript'>
        
        alert('$mensagem');
        window.onunload = fechaEstaAtualizaAntiga;
        function fechaEstaAtualizaAntiga() {
            window.opener.location.reload();
            }
        window.close();
            </script>
            </html>";
//                echo "<meta charset='UTF-8'><script>alert('$mensagem');</script>";
            }
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function gravarfaturadoequipe() {
//        var_dump($_POST); die;
        $resulta = $_POST['valortotal'];
        if ($resulta == "0.00") {

            $erro = false;
            if ($_POST['valorMinimo1'] != '' && ( ((float) $_POST['valorMinimo1']) > ((float) $_POST['valor1']) / $_POST['parcela1'] ) && $_POST['parcela1'] != 1) {
                $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 1 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
                $erro = true;
//                echo "<script>alert('something');</script>";
            }
            if ($_POST['valorMinimo2'] != '' && ( ((float) $_POST['valorMinimo2']) > ((float) $_POST['valor2']) / $_POST['parcela2'] ) && $_POST['parcela2'] != 1) {
                $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 2 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
                $erro = true;
//                echo "<script>alert('something');</script>";
            }
            if ($_POST['valorMinimo3'] != '' && ( ((float) $_POST['valorMinimo3']) > ((float) $_POST['valor3']) / $_POST['parcela3'] ) && $_POST['parcela3'] != 1) {
                $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 3 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
                $erro = true;
//                echo "<script>alert('something');</script>";
            }
            if ($_POST['valorMinimo4'] != '' && ( ((float) $_POST['valorMinimo4']) > ((float) $_POST['valor4']) / $_POST['parcela4'] ) && $_POST['parcela4'] != 1) {
                $data['mensagem'] = 'Erro ao gravar faturamento. Valor da forma de pagamento 4 é menor que o valor da parcela minima cadastrado na forma de pagamento.';
                $erro = true;
//                echo "<script>alert('something');</script>";
            }
            if ($_POST['valorMinimo4'] != '' && $_POST['valorMinimo3'] != '' && $_POST['valorMinimo2'] != '' && $_POST['valorMinimo1'] != '') {
                $erro = true;
//                echo "<script>alert('something');</script>";
            }

            $_POST['parcela1'] = ($_POST['parcela1'] == '' || $_POST['parcela1'] == 0) ? 1 : $_POST['parcela1'];
            $_POST['parcela2'] = ($_POST['parcela2'] == '' || $_POST['parcela2'] == 0) ? 1 : $_POST['parcela2'];
            $_POST['parcela3'] = ($_POST['parcela3'] == '' || $_POST['parcela3'] == 0) ? 1 : $_POST['parcela3'];
            $_POST['parcela4'] = ($_POST['parcela4'] == '' || $_POST['parcela4'] == 0) ? 1 : $_POST['parcela4'];

            if (!$erro) {
                $ambulatorio_guia_id = $this->centrocirurgico_m->gravarfaturamentototalequipe();


                if ($_POST['valorcredito'] != '' && $_POST['valorcredito'] != '0') {
//                    $this->guia->descontacreditopaciente();
                }
//                var_dump($_POST['valorcredito']);die;

                if ($ambulatorio_guia_id == "-1") {
                    $data['mensagem'] = 'Erro ao gravar faturamento. Opera&ccedil;&atilde;o cancelada.';
                } else {
                    $data['mensagem'] = 'Sucesso ao gravar faturamento.';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
            } else {
                $mensagem = $data['mensagem'];
                echo "<html>
                    <meta charset='UTF-8'>
        <script type='text/javascript'>
        
        alert('$mensagem');
        window.onunload = fechaEstaAtualizaAntiga;
        function fechaEstaAtualizaAntiga() {
            window.opener.location.reload();
            }
        window.close();
            </script>
            </html>";
//                echo "<meta charset='UTF-8'><script>alert('$mensagem');</script>";
            }
        } else {
            $this->load->View('ambulatorio/erro');
        }
    }

    function importarquivos($solicitacao_cirurgia_id) {
        $this->load->helper('directory');

        if (!is_dir("./upload/centrocirurgico")) {
            mkdir("./upload/centrocirurgico");
            $destino = "./upload/centrocirurgico";
            chmod($destino, 0777);
        }
        if (!is_dir("./upload/centrocirurgico/$solicitacao_cirurgia_id")) {
            mkdir("./upload/centrocirurgico/$solicitacao_cirurgia_id");
            $destino = "./upload/centrocirurgico/$solicitacao_cirurgia_id";
            chmod($destino, 0777);
        }

        $data['arquivo_pasta'] = directory_map("./upload/centrocirurgico/$solicitacao_cirurgia_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['solicitacao_cirurgia_id'] = $solicitacao_cirurgia_id;
        $this->loadView('centrocirurgico/importacao-imagemcentrocirurgico', $data);
    }

    function importarimagemcentrocirurgico() {
        $solicitacao_cirurgia_id = $_POST['paciente_id'];

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/centrocirurgico/$solicitacao_cirurgia_id")) {
                mkdir("./upload/centrocirurgico/$solicitacao_cirurgia_id");
                $destino = "./upload/centrocirurgico/$solicitacao_cirurgia_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/centrocirurgico/" . $solicitacao_cirurgia_id . "/";
            $config['allowed_types'] = 'gif|jpg|BMP|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar|xml|txt';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
            }
        }
//        var_dump($error); die;


        $data['solicitacao_cirurgia_id'] = $solicitacao_cirurgia_id;
        redirect(base_url() . "centrocirurgico/centrocirurgico/importarquivos/$solicitacao_cirurgia_id");
    }

    function excluirarquivocentrocirurgico($solicitacao_cirurgia_id, $nome) {

        if (!is_dir("./uploadopm/centrocirurgico")) {
            if (!is_dir("./uploadopm/centrocirurgico")) {
                mkdir("./uploadopm/centrocirurgico");
            }
            mkdir("./uploadopm/centrocirurgico");
            $destino = "./uploadopm/centrocirurgico/";
            chmod($destino, 0777);
        }

        if (!is_dir("./uploadopm/centrocirurgico/$solicitacao_cirurgia_id")) {
            if (!is_dir("./uploadopm/centrocirurgico")) {
                mkdir("./uploadopm/centrocirurgico");
            }
            mkdir("./uploadopm/centrocirurgico/$solicitacao_cirurgia_id");
            $destino = "./uploadopm/centrocirurgico/$solicitacao_cirurgia_id";
            chmod($destino, 0777);
        }

        $origem = "./upload/centrocirurgico/$solicitacao_cirurgia_id/$nome";
        $destino = "./uploadopm/centrocirurgico/$solicitacao_cirurgia_id/$nome";
        copy($origem, $destino);
        unlink($origem);
        redirect(base_url() . "centrocirurgico/centrocirurgico/importarquivos/$solicitacao_cirurgia_id");
    }

    function excluirequipecirurgica($equipe_cirurgia_id) {
        $this->centrocirurgico_m->excluirequipecirurgica($equipe_cirurgia_id);
        $data['mensagem'] = 'Equipe excluida com Sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarequipecirurgica");
    }

    function confirmarcirurgia($solicitacao_id) {
        $this->centrocirurgico_m->confirmarcirurgia($solicitacao_id);
        $data['mensagem'] = 'Cirurgia confirmada';
        $this->session->set_flashdata('message', $data['mensagem']);
         redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function excluirsolicitacaocirurgia($solicitacao_id,$calendario=NULL) {
      
        $this->solicitacirurgia_m->excluirsolicitacaocirurgia($solicitacao_id); 
         
        if ($this->session->userdata('perfil_id') == 25) {
            $data['solicitacao_cirurgia_id'] = $solicitacao_id;
            $data['observacao'] = $this->solicitacirurgia_m->listardadospaciente($solicitacao_id); 
            $this->loadView('centrocirurgico/exclusaosolicitacao',$data); 
        }else{ 
            $mensagem = 'Solicitacao excluida com sucesso';
            $this->session->set_flashdata('message', $mensagem); 
            redirect(base_url() . "centrocirurgico/centrocirurgico", $data); 
        }
        
    }

    function excluirsolicitacaoprocedimento($solicitacao_procedimento_id, $solicitacao) {
        $this->solicitacirurgia_m->excluirsolicitacaoprocedimento($solicitacao_procedimento_id);
        $data['mensagem'] = 'Procedimento removido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacao/$solicitacao");
    }

    function excluirsolicitacaomaterial($solicitacao_procedimento_id, $solicitacao) {
        $this->solicitacirurgia_m->excluirsolicitacaomaterial($solicitacao_procedimento_id);
        $data['mensagem'] = 'Material removido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaomaterial/$solicitacao");
    }

    function excluirsolicitacaoprocedimentoeditar($solicitacao_procedimento_id, $solicitacao) {
        $this->solicitacirurgia_m->excluirsolicitacaoprocedimento($solicitacao_procedimento_id);
        $data['mensagem'] = 'Procedimento removido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditar/$solicitacao");
    }

    function excluirsolicitacaoprocedimentoeditarorcamento($solicitacao_procedimento_id, $solicitacao) {
        $this->solicitacirurgia_m->excluirsolicitacaoprocedimento($solicitacao_procedimento_id);
        $data['mensagem'] = 'Procedimento removido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarorcamento/$solicitacao");
    }

    function excluirsolicitacaoprocedimentoeditarconvenio($solicitacao_procedimento_id, $solicitacao) {
        $this->solicitacirurgia_m->excluirsolicitacaoprocedimento($solicitacao_procedimento_id);
        $data['mensagem'] = 'Procedimento removido com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarconvenio/$solicitacao");
    }

    function novo($paciente_id) {
        $data['paciente'] = $this->paciente->listardados($paciente_id);

        $horario = date(" Y-m-d H:i:s");

        $hour = substr($horario, 11, 3);
        $minutes = substr($horario, 15, 2);
        $seconds = substr($horario, 18, 4);

        $this->loadView('emergencia/solicitacoes-paciente', $data);
    }

    function novograuparticipacao() {
        $this->loadView('centrocirurgico/grauparticipacao-form');
    }

    function editarpercentualoutros($percentual_id) {
        $data['percentual_id'] = $percentual_id;
        $data['percentual'] = $this->centrocirurgico_m->carregarpercentualoutros($percentual_id);
        $this->loadView('centrocirurgico/configurarpercentuaisoutros-form', $data);
    }

    function editarpercentualfuncao($percentual_id) {
        $data['percentual_id'] = $percentual_id;
        $data['percentual'] = $this->centrocirurgico_m->carregarpercentualfuncao($percentual_id);
        $this->loadView('centrocirurgico/configurarpercentuais-form', $data);
    }

    function editarhorarioespecial($percentual_id) {
        $data['percentual_id'] = $percentual_id;
        $data['percentual'] = $this->centrocirurgico_m->carregarpercentualfuncao($percentual_id);
        $this->loadView('centrocirurgico/configurarhorarioespecial-form', $data);
    }

    function configurarpercentuais() {
        $data['funcao'] = $this->centrocirurgico_m->listarpercentualfuncao();
        $data['percentual'] = $this->centrocirurgico_m->listarpercentualoutros();
        $this->loadView('centrocirurgico/configurarpercentuais-lista', $data);
    }

    function atribuirpadraopercentualans() {
        $this->centrocirurgico_m->atribuirpadraopercentualans();
        $data['mensagem'] = 'Percentual alterado com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/configurarpercentuais");
    }

    function carregar($emergencia_solicitacao_acolhimento_id) {
        $obj_paciente = new paciente_model($emergencia_solicitacao_acolhimento_id);
        $data['obj'] = $obj_paciente;
        $this->loadView('emergencia/solicita-acolhimento-ficha', $data);
    }

    function carregarunidade($internacao_unidade_id) {
        $obj_paciente = new unidade_model($internacao_unidade_id);
        $data['obj'] = $obj_paciente;
        $this->loadView('internacao/cadastrarunidade', $data);
    }

    function carregarmotivosaida($internacao_motivosaida_id) {
        $obj_paciente = new motivosaida_model($internacao_motivosaida_id);
        $data['obj'] = $obj_paciente;
        $this->loadView('internacao/cadastrarmotivosaida', $data);
    }

    function carregarenfermaria($internacao_enfermaria_id) {
        $obj_paciente = new enfermaria_model($internacao_enfermaria_id);
        $data['obj'] = $obj_paciente;
        $this->loadView('internacao/cadastrarenfermaria', $data);
    }

    function carregarleito($internacao_leito_id) {
        $obj_paciente = new leito_model($internacao_leito_id);
        $data['obj'] = $obj_paciente;
        $this->loadView('internacao/cadastrarleito', $data);
    }

    function editarprocedimentoscirurgia($solicitacao_id, $guia_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['guia_id'] = $guia_id;
        $data['ambulatorio_guia_id'] = $guia_id;
        $data['financeiro'] = $this->solicitacirurgia_m->verificarfinanceiro($guia_id);
        $data['convenios'] = $this->solicitacirurgia_m->listarconveniostodos();
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoautorizar($solicitacao_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgicaeditar($solicitacao_id);
        // echo '<pre>';
        // print_r($data['procedimentos']);
        // die;
        $this->loadView('centrocirurgico/editarprocedimentoscirurgia', $data);
    }

    function mostraautorizarcirurgia($solicitacao_id,$internacao_id = NULL) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['internacao_id'] = $internacao_id;
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoautorizar($solicitacao_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgica($solicitacao_id);
//        var_dump($data['procedimentos']); die;
        $this->loadView('centrocirurgico/autorizarcirurgia', $data);
    }

    function editarcirurgia($solicitacao_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoautorizar($solicitacao_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgicaeditar($solicitacao_id);
        $this->loadView('centrocirurgico/editarcirurgia', $data);
    }

    function impressaoorcamento($solicitacao_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['empresa'] = $this->solicitacirurgia_m->burcarempresa();
        $data['procedimentos'] = $this->solicitacirurgia_m->impressaoorcamento($solicitacao_id);
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoorcamentoimpressao($solicitacao_id);
//        echo "<pre>"; var_dump($data['procedimentos']); die;
        $this->load->view('centrocirurgico/impressaoorcamento', $data);
    }

    function impressaosolicitacao($solicitacao_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        // $data['empresa'] = $this->solicitacirurgia_m->burcarempresa();
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        @$data['cabecalho_config'] = $data['cabecalho'][0]->cabecalho;
        $data['procedimentos'] = $this->solicitacirurgia_m->impressaoorcamento($solicitacao_id);
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoimpressao($solicitacao_id);
        // echo "<pre>"; var_dump($data['solicitacao']); die;
        $this->load->plugin('mpdf');
        $html = $this->load->view('centrocirurgico/impressaosolicitacao', $data);
        // pdf($html, 'Impressao Solicitacao', '', '');
    }

    function adicionarprocedimentos($solicitacao) {
        $data['solicitacao'] = $solicitacao;
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacao/$solicitacao");
    }

    function gravarpercentualhorarioespecial() {
        $this->centrocirurgico_m->gravarpercentualhorarioespecial();

        $data['mensagem'] = 'Percentual gravado com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/configurarpercentuais");
    }

    function gravarpercentualoutros() {
        $this->centrocirurgico_m->gravarpercentualoutros();

        $data['mensagem'] = 'Percentual gravado com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/configurarpercentuais");
    }

    function gravarpercentualfuncao() {
        $this->centrocirurgico_m->gravarpercentualfuncao();

        $data['mensagem'] = 'Percentual gravado com sucesso.';
        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/configurarpercentuais");
    }

    function finalizarequipecirurgica($solicitacaocirurgia_id, $paciente_id, $operatorio) {
        $this->centrocirurgico_m->finalizarequipecirurgica($solicitacaocirurgia_id);

        if ($operatorio != "INTERNACAO") {
            redirect(base_url() . "centrocirurgico/centrocirurgico/mostraautorizarcirurgia/$solicitacaocirurgia_id");
        }else{
            redirect(base_url() . "internacao/internacao/novointernacaocirurgia/$paciente_id/$solicitacaocirurgia_id");
        }

        // redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function gravarguiacirurgicaequipe() {
        $guia_id = $_POST['txtambulatorioguiaid'];

        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['procedimentos'] = $this->centrocirurgico_m->listarprocedimentosguiacirurgica($guia_id);
        $funcao = $this->centrocirurgico_m->listarfuncaoexameequipe($guia_id);
//        echo '<pre>';
//        var_dump($funcao);
//        die;

        if (count($funcao) == 0) {

            $data['mensagem'] = 'Função gravada com sucesso.';
            $this->centrocirurgico_m->gravarguiacirurgicaequipe($data['procedimentos'], $data['guia'][0]);
        } else {
            $data['mensagem'] = 'Função já existente.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/cadastrarequipeguiacirurgica/$guia_id");
    }

    function gravarguiacirurgicaequipeeditar($guia_id, $solicitacao_id) {
//        var_dump($guia_id); die;
        $data['guia'] = $this->guia->instanciarguia($guia_id);
        $data['procedimentos'] = $this->centrocirurgico_m->listarprocedimentosguiacirurgica($guia_id);
        $funcao = $this->centrocirurgico_m->listarfuncaoexameequipe($guia_id);
//        echo '<pre>';
//        var_dump($funcao);
//        die;

        if (count($funcao) == 0) {
            $data['mensagem'] = 'Função gravada com sucesso.';
            $this->centrocirurgico_m->gravarguiacirurgicaequipeeditar($data['procedimentos'], $data['guia'][0], $solicitacao_id);
        } else {
            $data['mensagem'] = 'Função já existente.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/cadastrarequipeguiacirurgicasolicitacao/$solicitacao_id/$guia_id");
    }

    function procedimentocirurgicovalor($agenda_exames_id) {
        $data['valor'] = $this->centrocirurgico_m->procedimentocirurgicovalor($agenda_exames_id);
//        var_dump($data['valor']); die;

        $this->load->View('ambulatorio/procedimentocirurgicovalor-form', $data);
    }

    function gravarprocedimentocirurgicovalor($agenda_exames_id) {
        $this->centrocirurgico_m->gravarprocedimentocirurgicovalor($agenda_exames_id);
//        var_dump($data['valor']); die;

        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function cadastrarequipeguiacirurgica($guia) {

        $data['guia_id'] = $guia;
        $data['guia'] = $this->guia->instanciarguia($guia);

        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['grau_participacao'] = $this->solicitacirurgia_m->grauparticipacao();
        $data['equipe_operadores'] = $this->centrocirurgico_m->listarequipeoperadores($guia);
        $this->loadView('centrocirurgico/equipeguiacirurgica-form', $data);
    }

    function cadastrarequipeguiacirurgicasolicitacao($solicitacao_cirurgia_id, $guia) {

        $data['guia_id'] = $guia;
        $data['solicitacao_id'] = $solicitacao_cirurgia_id;
        $data['guia'] = $this->guia->instanciarguia($guia);

        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['grau_participacao'] = $this->solicitacirurgia_m->grauparticipacao();
        $data['equipe_operadores'] = $this->centrocirurgico_m->listarequipeoperadoreseditar($guia);
        $this->loadView('centrocirurgico/equipeguiacirurgicaeditar-form', $data);
    }

    function cadastrarequipe() {
        $this->loadView("centrocirurgico/equipecirurgica-form");
    }

    function excluirguiacirurgica($guia) {
        $this->centrocirurgico_m->excluirguiacirurgica($guia);

        $data['mensagem'] = 'Guia Cirurgica cancelada com sucesso';
        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "ambulatorio/exame/faturamentomanual");
    }

    function finalizarcadastroequipecirurgica($guia) {
        $verifica = $this->centrocirurgico_m->finalizarcadastroequipecirurgica($guia);
        if ($verifica) {
            $data['mensagem'] = 'Equipe gravada com sucesso';
        } else {
            $data['mensagem'] = 'Erro ao finalizar equipe';
        }
        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "ambulatorio/exame/faturamentomanual");
    }

//    function adicionarprocedimentosdecisao() {
////        if ($_POST['escolha'] == "SIM") {
//            $solicitacao = $_POST['solicitacao_id'];
//            redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacao/$solicitacao");
////        } else {
////            redirect(base_url() . "centrocirurgico/centrocirurgico/centrocirurgico");
////        }
//    }

    function gravargrauparticipacao() {
        $solicitacao = $this->centrocirurgico_m->gravargrauparticipacao();
        if ($solicitacao == -1) {
            $data['mensagem'] = 'Erro ao Gravar. Esse Código ja foi cadastrado.';
        } else {
            $data['mensagem'] = 'Grau de partipação salvo com Sucesso';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisargrauparticipacao");
    }

    function gravarnovasolicitacao() {
//        var_dump($_POST);die;
 
        if ($_POST["txtNomeid"] == "") {
            $paciente_id = $this->paciente->gravarpacienteparcial();
            $_POST['txtNomeid'] = $paciente_id;
//            $data['mensagem'] = 'Paciente escolhido não é válido';
//            $this->session->set_flashdata('message', $data['mensagem']);
//            redirect(base_url() . "centrocirurgico/centrocirurgico/novasolicitacao/0");
        } else {
            $paciente_id = $_POST['txtNomeid'];
        }
       

        $solicitacao = $this->solicitacirurgia_m->gravarnovasolicitacao();
        if ($_POST['medicocirurgia'] != "") {
            $cirurgiao = $_POST['medicocirurgia'];
            $gravarcirurgiao = $this->solicitacirurgia_m->gravarnovasolicitacaomedicocirurgiao($solicitacao, $cirurgiao);
        }
        if ($_POST['telefone'] != "") {

            $telefone = $_POST['telefone'];
            $paciente = $this->paciente->ajustarpaciente($paciente_id, $telefone);
//            echo '<pre>';
//            var_dump($_POST);die;
        }
        if ($solicitacao == -1) {
            $data['mensagem'] = 'Erro ao efetuar Solicitacao';
        } else {
            $data['mensagem'] = 'Solicitacao efetuada com Sucesso';
//            var_dump($solicitacao);
        }
        $this->session->set_flashdata('message', $data['mensagem']);        
        if ($_POST['mapacirugia'] != 'true') {
             redirect(base_url() . "centrocirurgico/centrocirurgico/adicionarprocedimentos/$solicitacao");
        }else{
           redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
        }
       
    }

    function relatoriocirurgiaconvenio() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['operadores'] = $this->operador_m->listaroperadores(); 
        $this->loadView('centrocirurgico/relatoriocirurgiaconvenio', $data);
    }

    function gerarelatoriocirurgiaconvenio() {
 
        $data['data_inicio'] = $_POST['txtdata_inicio'];
        $data['data_fim'] = $_POST['txtdata_fim'];
        $data['relatoriocirurgiaconvenio'] = $this->centrocirurgico_m->relatoriocirurgiaconvenio();

        if ($_POST['convenio'] == '-1') {
            $data['convenio'] = 'Não Tem';
        } else {
            if ($_POST['convenio'] != 0) {
                $convenio = $this->internacao_m->pesquisarconvenio($_POST['convenio']);
                $data['convenio'] = $convenio[0]->nome;
            } else {
                $data['convenio'] = 'TODOS';
            }
        }


        $this->load->View('centrocirurgico/impressaorelatoriocirurgiaconvenio', $data);
    }

    function gravarsolicitacaoprocedimentosalterarorcamento() {
//        var_dump($_POST); die;
        $solicitacao = $_POST['solicitacao_id'];


        if ($_POST['procedimentoID'] != '') {
            $verifica = count($this->solicitacirurgia_m->verificasolicitacaoprocedimentorepetidos());
            if ($verifica == 0) {
                $valor = $this->solicitacirurgia_m->listarvalorprocedimentocadastrar();
                if ($this->solicitacirurgia_m->gravarsolicitacaoprocedimentoalterar($valor)) {
                    $data['mensagem'] = 'Procedimento adicionado com Sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar Procedimento';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarorcamento/$solicitacao");
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar Procedimento. Procedimento nao selecionado ou invalido.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarorcamento/$solicitacao");
    }

    function gravarsolicitacaoprocedimentosalterarconvenio() {
//        var_dump($_POST); die;
        $solicitacao = $_POST['solicitacao_id'];


        if ($_POST['procedimentoID'] != '') {
            $verifica = count($this->solicitacirurgia_m->verificasolicitacaoprocedimentorepetidos());
            $valor = $this->solicitacirurgia_m->listarvalorprocedimentocadastrar();
            if ($verifica == 0) {
                if ($this->solicitacirurgia_m->gravarsolicitacaoprocedimentoalterar($valor)) {
                    $data['mensagem'] = 'Procedimento adicionado com Sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar Procedimento';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarconvenio/$solicitacao");
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar Procedimento. Procedimento nao selecionado ou invalido.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditarconvenio/$solicitacao");
    }

    function gravarsolicitacaoprocedimentosalterar() {
//        var_dump($_POST); die;
        $solicitacao = $_POST['solicitacao_id'];


        if ($_POST['procedimentoID'] != '') {
            $valor = $this->solicitacirurgia_m->listarvalorprocedimentocadastrar();
            $verifica = count($this->solicitacirurgia_m->verificasolicitacaoprocedimentorepetidos());
            if ($verifica == 0) {
                if ($this->solicitacirurgia_m->gravarsolicitacaoprocedimentoalterar($valor)) {
                    $data['mensagem'] = 'Procedimento adicionado com Sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar Procedimento';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditar/$solicitacao");
            }
        } else {
            $data['mensagem'] = 'Erro ao gravar Procedimento. Procedimento nao selecionado ou invalido.';
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaoeditar/$solicitacao");
    }

    function gravarsolicitacaomateriais() {

        $solicitacao = $_POST['solicitacao_id'];
        $procedimento_tuss_id = $_POST['material_id'];

        if ($this->solicitacirurgia_m->verificamaterialagrupador($procedimento_tuss_id)) {
            // Caso tenha selecionado um agrupador
            if ($this->solicitacirurgia_m->gravarsolicitacaomateriaisagrupador()) {
                $data['mensagem'] = 'Materiais adicionados com Sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar Materiais';
            }
        } else {
            // Caso não seja um agrupador
            if ($this->solicitacirurgia_m->gravarsolicitacaomateriais()) {
                $data['mensagem'] = 'Material adicionado com Sucesso';
            } else {
                $data['mensagem'] = 'Erro ao gravar Material';
            }
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacaomaterial/$solicitacao");
    }

    function gravarsolicitacaoprocedimentos() {

        $solicitacao = $_POST['solicitacao_id'];
        $procedimento = $_POST['procedimentoID'];

        if ($this->solicitacirurgia_m->verificaprocedimentoagrupador($procedimento)) {
            $procedimentos = $this->solicitacirurgia_m->listarprocedimentosagrupador($procedimento);
            foreach ($procedimentos as $item) {
                $_POST['procedimentoID'] = $item->procedimento_convenio_id;
                $valor = $this->solicitacirurgia_m->listarvalorprocedimentocadastrar();
                $verifica = count($this->solicitacirurgia_m->verificasolicitacaoprocedimentorepetidos());
                if ($verifica == 0) {
                    $this->solicitacirurgia_m->gravarsolicitacaoprocedimento($valor);
                }
            }
        } else {
            $valor = $this->solicitacirurgia_m->listarvalorprocedimentocadastrar();
            $verifica = count($this->solicitacirurgia_m->verificasolicitacaoprocedimentorepetidos());
            if ($verifica == 0) {
                if ($this->solicitacirurgia_m->gravarsolicitacaoprocedimento($valor)) {
                    $data['mensagem'] = 'Procedimento adicionado com Sucesso';
                } else {
                    $data['mensagem'] = 'Erro ao gravar Procedimento';
                }
                $this->session->set_flashdata('message', $data['mensagem']);
                redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacao/$solicitacao");
            }
        }

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/carregarsolicitacao/$solicitacao");
    }

    function carregarsolicitacao($solicitacao_id) { 
        $data['solicitacao_id'] = $solicitacao_id;
        $data['dados'] = $this->centrocirurgico_m->listarsolicitacoes3($solicitacao_id);
        $data['convenios'] = $this->solicitacirurgia_m->listarconvenios();
        $data['procedimento'] = $this->solicitacirurgia_m->carregarsolicitacaoprocedimento($data['dados'][0]->convenio_id);
        $data['agrupador'] = $this->solicitacirurgia_m->carregarsolicitacaoagrupador($data['dados'][0]->convenio_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarsolicitacaosprocedimentos($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacaoprocedimentos-form', $data);
    }

    function carregarsolicitacaoeditarorcamento($solicitacao_id) {

        $data['solicitacao_id'] = $solicitacao_id;
        $data['dados'] = $this->centrocirurgico_m->listarsolicitacoes3($solicitacao_id);
        $data['convenios'] = $this->solicitacirurgia_m->listarconveniosdinheiro();
        $data['procedimento'] = $this->solicitacirurgia_m->carregarsolicitacaoprocedimento($data['dados'][0]->convenio_id);
        $data['agrupador'] = $this->solicitacirurgia_m->carregarsolicitacaoagrupador($data['dados'][0]->convenio_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarsolicitacaosprocedimentosorcamento($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacaoprocedimentosalterarorcamento-form', $data);
    }

    function carregarsolicitacaoeditarconvenio($solicitacao_id) {

        $data['solicitacao_id'] = $solicitacao_id;
        $data['dados'] = $this->centrocirurgico_m->listarsolicitacoes3($solicitacao_id);
        $data['convenios'] = $this->solicitacirurgia_m->listarconvenios();
        $data['procedimento'] = $this->solicitacirurgia_m->carregarsolicitacaoprocedimento($data['dados'][0]->convenio_id);
        $data['agrupador'] = $this->solicitacirurgia_m->carregarsolicitacaoagrupador($data['dados'][0]->convenio_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarsolicitacaosprocedimentosconvenio($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacaoprocedimentosalterarconvenio-form', $data);
    }

    function carregarsolicitacaoeditar($solicitacao_id) {

        $data['solicitacao_id'] = $solicitacao_id;
        $data['dados'] = $this->centrocirurgico_m->listarsolicitacoes3($solicitacao_id);
        $data['convenios'] = $this->solicitacirurgia_m->listarconveniostodos();
        $data['procedimento'] = $this->solicitacirurgia_m->carregarsolicitacaoprocedimento($data['dados'][0]->convenio_id);
        $data['agrupador'] = $this->solicitacirurgia_m->carregarsolicitacaoagrupador($data['dados'][0]->convenio_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarsolicitacaosprocedimentos($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacaoprocedimentosalterar-form', $data);
    }

    function carregarsolicitacaomaterial($solicitacao_id) {

        $data['solicitacao_id'] = $solicitacao_id;
        $data['fornecedor'] = $this->centrocirurgico_m->listarfornecedorsolicitacao();
        $data['dados'] = $this->centrocirurgico_m->listarsolicitacoes3($solicitacao_id);
        $data['procedimento'] = $this->solicitacirurgia_m->carregarsolicitacaomaterial();
        $data['agrupador'] = $this->solicitacirurgia_m->carregarsolicitacaoagrupadormaterial($solicitacao_id);
        // echo '<pre>';
        // print_r($data['agrupador']);
        // die;

        $data['procedimentos'] = $this->solicitacirurgia_m->listarsolicitacaosmateriais($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacaomateriais-form', $data);
    }

    function editarprocedimentossolicitacaocirurgia($solicitacao_id, $guia_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['guia_id'] = $guia_id;
        $data['ambulatorio_guia_id'] = $guia_id;
        $data['convenios'] = $this->solicitacirurgia_m->listarconvenios();
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoautorizar($solicitacao_id);
        $data['forma_pagamento'] = $this->guia->formadepagamento();
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgicaeditar($solicitacao_id);
        $this->loadView('centrocirurgico/editarprocedimentoscirurgia', $data);
    }

    function carregarhospital($hospital_id) {

        $data['hospital_id'] = $hospital_id;
        $data['hospital'] = $this->centrocirurgico_m->instanciarhospitais($hospital_id);
//        echo "<pre>";var_dump($data['hospital'] );die;
        $this->loadView('centrocirurgico/hospital-form', $data);
    }

    function carregarespecialidadeparecer($especialidade_parecer_id) {

        $data['especialidade_parecer_id'] = $especialidade_parecer_id;
        $data['parecer'] = $this->centrocirurgico_m->instanciarespecialidadeparecer($especialidade_parecer_id);
//        echo "<pre>";var_dump($data['hospital'] );die;
        $this->loadView('centrocirurgico/especialidadeparecer-form', $data);
    }

    function subrotinaespecialidadeparecer($especialidade_parecer_id) {

        $data['especialidade_parecer_id'] = $especialidade_parecer_id;
        $data['parecer'] = $this->centrocirurgico_m->instanciarespecialidadeparecer($especialidade_parecer_id);
        $data['lista'] = $this->centrocirurgico_m->instanciarespecialidadeparecersubrotina($especialidade_parecer_id);
//        echo "<pre>";var_dump($data['hospital'] );die;
        $this->loadView('centrocirurgico/subrotinaespecialidadeparecer-form', $data);
    }

    function atendersolicitacaoparecer($centrocirurgico_parecer_id, $mensagem = null) {
        $data['parecer'] = $this->centrocirurgico_m->atendersolicitacaoparecer($centrocirurgico_parecer_id);
        $data['empresapermissao'] = $this->centrocirurgico_m->listarempresapermissoes();
        $data['mensagem'] = $mensagem;
        $data['centrocirurgico_parecer_id'] = $centrocirurgico_parecer_id;

        $ambulatorio_laudo_id = $data['parecer'][0]->ambulatorio_laudo_id;
        $paciente_id = $data['parecer'][0]->paciente_id;

        $data['laudo_peso'] = $this->laudo->listarlaudospesoaltura($paciente_id);
        // var_dump($data['laudo_peso']); die;

        $this->load->helper('directory');
        $data['arquivos_anexados'] = directory_map("./upload/consulta/$ambulatorio_laudo_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_anexados'] != false) {
            sort($data['arquivos_anexados']);
        }
        $data['arquivos_paciente'] = directory_map("./upload/paciente/$paciente_id/");
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivos_paciente'] != false) {
            sort($data['arquivos_paciente']);
        }

        $data['historico'] = $this->laudo->listarconsultahistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicowebcon'] = $this->laudo->listarconsultahistoricoweb($paciente_id);
//        var_dump($data['historicowebcon']); die;
        $data['historicowebexa'] = $this->laudo->listarexamehistoricoweb($paciente_id);
        $data['historicowebesp'] = $this->laudo->listarespecialidadehistoricoweb($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        $data['historicoespecialidade'] = $this->laudo->listarespecialidadehistorico($paciente_id);
        $data['laudo_sigiloso'] = $data['empresapermissao'][0]->laudo_sigiloso;
        $data['integracaosollis'] = $data['empresapermissao'][0]->integracaosollis;
        $this->load->View('centrocirurgico/atendersolicitacaoparecer-form', $data);
    }

    function gravarparecercirurgico($centrocirurgico_parecer_id) {
        // var_dump($centrocirurgico_parecer_id); die;
        $gravar = $this->centrocirurgico_m->gravarparecercirurgico($centrocirurgico_parecer_id);

        $data['mensagem'] = 'Sucesso ao gravar parecer';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/atendersolicitacaoparecer/$centrocirurgico_parecer_id/$messagem");
    }

    function adicionalcabecalho($cabecalho, $laudo) {

        //        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", '', $cabecalho);
        $cabecalho = str_replace("_sala_", '', $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_medico_", '', $cabecalho);
        $cabecalho = str_replace("_revisor_", '', $cabecalho);
        $cabecalho = str_replace("_procedimento_", '', $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", '', $cabecalho);
        $cabecalho = str_replace("_queixa_", '', $cabecalho);
        $cabecalho = str_replace("_peso_", '', $cabecalho);
        $cabecalho = str_replace("_altura_", '', $cabecalho);
        $cabecalho = str_replace("_cid1_", '', $cabecalho);
        $cabecalho = str_replace("_cid2_", '', $cabecalho);
        $cabecalho = str_replace("_guia_", '', $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);

        return $cabecalho;
    }

    function impressaoparecercirurgico($centrocirurgico_parecer_id) {


        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->centrocirurgico_m->impressaosolicitacaoparecer($centrocirurgico_parecer_id);

        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_id);
//        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') {
//            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_id . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_id . '.jpg"/>';
                } else {
                    if ($data['impressaolaudo'][0]->cabecalho == 't') {
                        if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                            $cabecalho = "$cabecalho_config";
                        } else {
                            $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                        }
                    } else {
                        $cabecalho = '';
                    }
                }
            }

            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", '', $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_medico_", '', $cabecalho);
            $cabecalho = str_replace("_revisor_", '', $cabecalho);
            $cabecalho = str_replace("_procedimento_", '', $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", '', $cabecalho);
            $cabecalho = str_replace("_queixa_", '', $cabecalho);
            $cabecalho = str_replace("_cid1_", '', $cabecalho);
            $cabecalho = str_replace("_guia_", '', $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_id . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }

            if ($data['cabecalhomedico'][0]->rodape != '') { // Rodapé do profissional
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
                $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                $rodape = $rodape_config;
            } else {
                if ($data['impressaolaudo'][0]->rodape == 't') { // rodape da empresa
                    if ($data['empresa'][0]->rodape_config == 't') {
                        $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);

                        $rodape = $rodape_config;
                    } else {
                        $rodape = "";
                    }
                } else {
                    $rodape = "";
                }
            }



            $html = $this->load->view('centrocirurgico/impressaoparecercirurgicoconfiguravel', $data, true);
            //    echo '<pre>';
            //    echo $cabecalho;

            if ($data['empresa'][0]->impressao_tipo == 33) {
                // ossi rezaf rop adiv ahnim oiedo uE
                // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                // açneod ?euq roP
                $html = str_replace('xx-small', '5pt', $html);
            }

            $teste_cabecalho = "$cabecalho";

            pdf($html, $filename, $teste_cabecalho, $rodape);
        } // CASO O LAUDO NÃO CONFIGURÁVEL
        else {//GERAL       //este item fica sempre por último
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') {
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho . "<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            } else {
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    $cabecalho = "$cabecalho_config<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                        $img = '<img style="width: 100%; height: 40%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                    } else {
                        $img = "<img align = 'left'style='width: 100%; height: 40%;'  src='img/cabecalho.jpg'>";
                    }
                    $cabecalho = "<table><tr><td>" . $img . "</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                }
            }
            //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            if ($data['cabecalhomedico'][0]->rodape != '') {
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
            } else {
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = $rodape_config;
                } else {
                    if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                    }
                }
            }


            $html = $this->load->view('centrocirurgico/impressaoparecercirurgico', $data, true);
            pdf($html, $filename, $cabecalho, $rodape);
            $this->load->View('centrocirurgico/impressaoparecercirurgico', $data);
        }
    }

    function visualizarparecercirurgico($centrocirurgico_parecer_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->centrocirurgico_m->impressaosolicitacaoparecer($centrocirurgico_parecer_id);
        // pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('centrocirurgico/visualizarparecercirurgico', $data);
    }

    function carregarfornecedormaterial($fornecedormaterial_id) {

        $data['fornecedormaterial_id'] = $fornecedormaterial_id;
        $data['fornecedormaterial'] = $this->centrocirurgico_m->instanciarfornecedormaterial($fornecedormaterial_id);
//        echo "<pre>";var_dump($data['fornecedormaterial'] );die;
        $this->loadView('centrocirurgico/fornecedormaterial-form', $data);
    }

    function gravarequipeoperadores($cirurgiao_id) {
        $solicitacao_id = $_POST['solicitacao_id'];
        $paciente_id = $_POST['paciente_id'];
        $operatorio = $_POST['operatorio'];
        
       
        $equipe_funcao = $this->centrocirurgico_m->listarequipeoperadoresfuncao();
        if (count($equipe_funcao) == 0) {
            $this->centrocirurgico_m->gravarequipeoperadores($cirurgiao_id);
            $data['mensagem'] = 'Sucesso ao gravar função.';
        } else {
            $data['mensagem'] = 'Função ou operador já cadastrado(a)';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/montarequipe/$solicitacao_id/$paciente_id/$operatorio");
    }

    function finalizarcadastroprocedimentosguia($guia) {
        redirect(base_url() . "centrocirurgico/centrocirurgico/cadastrarequipeguiacirurgica/$guia");
    }

    function gravarespecialidadeparecer() {
        $hospital_id = $this->centrocirurgico_m->gravarespecialidadeparecer();
        if ($hospital_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar especialidade parecer. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar especialidade parecer.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/manterespecialidadeparecer");
    }

    function gravarespecialidadeparecersubrotina($especialidade_parecer_id) {
        $sub_rotina = $this->centrocirurgico_m->gravarespecialidadeparecersubrotina();
        if ($sub_rotina == "-1") {
            $data['mensagem'] = 'Erro ao gravar sub-rotina especialidade parecer. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar sub-rotina especialidade parecer.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/subrotinaespecialidadeparecer/$especialidade_parecer_id");
    }

    function gravarhospital() {
        $hospital_id = $this->centrocirurgico_m->gravarhospital();
        if ($empresa_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar Hospital. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar Hospital.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarhospitais");
    }

    function gravarfornecedormaterial() {
        $hospital_id = $this->centrocirurgico_m->gravarfornecedormaterial();
        if ($empresa_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar Fornecedor. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar Fornecedor.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarfornecedormaterial");
    }

    function excluirgrauparticipacao($grau_participacao_id) {
        $this->centrocirurgico_m->excluirgrauparticipacao($grau_participacao_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisargrauparticipacao");
    }

    function excluirhospital($hospital_id) {
        $this->centrocirurgico_m->excluirhospital($hospital_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarhospitais");
    }

    function excluirespecialidadeparecer($especialidade_parecer_id) {
        $this->centrocirurgico_m->excluirespecialidadeparecer($especialidade_parecer_id);
        $data['mensagem'] = 'Sucesso ao excluir especialidade parecer';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/manterespecialidadeparecer");
    }

    function excluirespecialidadeparecersubrotina($especialidade_parecer_subrotina_id, $especialidade_parecer_id) {
        $this->centrocirurgico_m->excluirespecialidadeparecersubrotina($especialidade_parecer_subrotina_id);
        $data['mensagem'] = 'Sucesso ao excluir sub-rotina';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/subrotinaespecialidadeparecer/$especialidade_parecer_id");
    }

    function excluirfornecedormaterial($hospital_id) {
        $this->centrocirurgico_m->excluirfornecedormaterial($hospital_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarfornecedormaterial");
    }

    function excluiritemorcamento($orcamento_id, $solicitacao_id, $convenio_id) {
        $this->solicitacirurgia_m->excluiritemorcamento($orcamento_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/solicitacarorcamento/$solicitacao_id/$convenio_id");
    }

    function excluiritemequipe($cirurgia_operadores_id, $equipe_id) {
        $this->solicitacirurgia_m->excluiritemequipe($cirurgia_operadores_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/montarequipe/$equipe_id");
    }

    function excluiroperadorequipecirurgica($guia_id, $funcao_id, $solicitacao_id) {
        $this->solicitacirurgia_m->excluiroperadorequipecirurgica($guia_id, $funcao_id, $solicitacao_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/cadastrarequipeguiacirurgica/$guia_id");
    }

    function excluiroperadorequipecirurgicaeditar($guia_id, $funcao_id, $solicitacao_id) {
        $this->solicitacirurgia_m->excluiroperadorequipecirurgicaeditar($guia_id, $funcao_id, $solicitacao_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/cadastrarequipeguiacirurgicasolicitacao/$solicitacao_id/$guia_id");
    }

    function liberar($solicitacao_id, $orcamento) {
        if ($this->centrocirurgico_m->liberarsolicitacao($solicitacao_id, $orcamento)) {
            $data['mensagem'] = "LIBERADO!";
        } else {
            $data['mensagem'] = "Falha ao realizar Liberação!";
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function orcamentopergunta($solicitacao_id, $convenio_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['convenio_id'] = $convenio_id;
        $teste = $this->centrocirurgico_m->verificasituacao($solicitacao_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/solicitacarorcamento/$solicitacao_id/$convenio_id");
    }

    function orcamentoescolha($solicitacao_id, $convenio_id) {
        if ($_POST['escolha'] == 'SIM') {
            $this->centrocirurgico_m->alterarsituacaoorcamento($solicitacao_id);
            redirect(base_url() . "centrocirurgico/centrocirurgico/solicitacarorcamento/$solicitacao_id/$convenio_id");
        } else {
            $this->centrocirurgico_m->alterarsituacaoorcamentodisnecessario($solicitacao_id);
            redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
        }
    }

//    function novasolicitacaoconsulta($exame_id) {
//        $data['paciente'] = $this->solicitacirurgia_m->solicitacirurgiaconsulta($exame_id);
//        $data['medicos'] = $this->operador_m->listarmedicos();
//        $this->loadView('centrocirurgico/novasolicitacao', $data);
//    }

    function novasolicitacao($solicitacao_id, $laudo_id = null) {
        $data['laudo_id'] = $laudo_id;
        $data['solicitacao_id'] = $solicitacao_id;
        $data['hospitais'] = $this->centrocirurgico_m->listarhospitaissolicitacao();
//        $data['medicos'] = $this->operador_m->listarmedicostodos();
        $data['medicos'] = $this->operador_m->listarmedicoscirurgia();
        $data['salas'] = $this->centrocirurgico_m->listarsalascirurgico();
        $data['convenio'] = $this->centrocirurgico_m->listarconveniocirurgiaorcamento();
        if ($laudo_id != null && $laudo_id != '0') {
            $data['laudo'] = $this->centrocirurgico_m->listarlaudosolicitacaocirurgica($laudo_id);
        }
        $this->loadView('centrocirurgico/novasolicitacao', $data);
    }

    function novasolicitacaointernacao($internacao_id, $paciente_id) {
        $data['internacao_id'] = $internacao_id;
        $data['paciente_id'] = $paciente_id;
        $data['hospitais'] = $this->centrocirurgico_m->listarhospitaissolicitacao();
        $data['medicos'] = $this->operador_m->listarmedicos();
        $data['convenio'] = $this->centrocirurgico_m->listarconveniocirurgiaorcamento();
        $data['paciente'] = $this->centrocirurgico_m->listarpacientesolicitacaocirurgicainternacao($paciente_id); 
        $this->loadView('centrocirurgico/novasolicitacaointernacao', $data);
    }

    function listarhorarioscalendariocirurgico() {
 
//            echo $_POST['custom_param1'];
        if (count($_POST) > 0) {
            $result = $this->centrocirurgico_m->listarcirurgiacalendario($_POST['medico']);
//            $algo = 'asd';
        } else {
            $result = $this->centrocirurgico_m->listarcirurgiacalendario();
//            $algo = 'dsa';
        }
//        echo '<pre>';
//        var_dump($result);
//        die;

        $var = Array();
        $i = 0;
//            $result2 = $this->exametemp->listarhorarioscalendarioocupado();

        foreach ($result as $item) {
            $i++;
            $data_atual = date("Y-m-d");
            if ($item->nascimento != '') {
                $dataFuturo = date("Y-m-d");
                $dataAtual = $item->nascimento;
                $date_time = new DateTime($dataAtual);
                $diff = $date_time->diff(new DateTime($dataFuturo));
                $idade = $diff->format('%Y'); 
            } else {
                $idade = '';
            }
            if ($item->autorizado == 't') {
                $situacao = 'Autorizada';
            } else {
                $situacao = 'Solicitada';
            }
            $anestesista_select = $this->centrocirurgico_m->listacalendarioanestesistaautocomplete($item->solicitacao_cirurgia_id);
            $procedimento_select = $this->centrocirurgico_m->listacalendarioprocedimentoautocomplete($item->solicitacao_cirurgia_id);
            if (count($anestesista_select) > 0) {
                $anestesista = $anestesista_select[0]->nome;
            } else {
                $anestesista = '';
            }
            if (count($procedimento_select) > 0) {
                $procedimento = $procedimento_select[0]->nome;
            } else {
                $procedimento = '';
            }
//            var_dump($procedimento); die;
//            var_dump(date("Y-m-d",strtotime($item->nascimento))); die;

            $retorno['id'] = $i;
            $retorno['solicitacao_id'] = $item->solicitacao_cirurgia_id;
            $retorno['title'] = "Situação: $situacao \n \n Cirurgião: $item->cirurgiao | Hospital: $item->hospital | Paciente: $item->nome | Convênio: $item->convenio | Procedimento: $procedimento | Fornecedor : $item->fornecedor  | Anestesista : $anestesista | Telefone: $item->celular / $item->telefone | Idade : $idade ";
            $retorno['texto'] = "Situação:  $situacao <br> \n Cirurgião: $item->cirurgiao <br> \n Hospital: $item->hospital  <br> \nPaciente: $item->nome  <br> \nConvênio: $item->convenio  <br> \nProcedimento: $procedimento <br> \nFornecedor : $item->fornecedor  <br> \nAnestesista  : $anestesista  <br> \n Telefone: $item->celular / $item->telefone  <br> \n Idade : $idade <br> \n Observação:  $item->observacao";
            
            $retorno['start'] = date("Y-m-d", strtotime($item->data_prevista)) . "T" . date("H:i:s", strtotime($item->hora_prevista));
            if ($item->hora_prevista_fim != '') {
                $retorno['end'] = date("Y-m-d", strtotime($item->data_prevista)) . "T" . date("H:i:s", strtotime($item->hora_prevista_fim));
            } else {
                $retorno['end'] = date("Y-m-d", strtotime($item->data_prevista)) . "T" . date("H:i:s", strtotime($item->hora_prevista));
            }

//            $retorno['start'] = date("Y-m-d",strtotime($item->data_prevista));
//            $retorno['end'] = date("Y-m-d",strtotime($item->data_prevista));
            if ($item->cor_mapa != '') {
                $retorno['color'] = $item->cor_mapa;
            } else {
                $retorno['color'] = '#62C462';
            }
//            $situacao = $item->situacao;
//            if (isset($item->medico)) {
//                $medico = $item->medico;
//            } else {
//                $medico = null;
//            }
//            $dia = date("d", strtotime($item->data_prevista));
//            $mes = date("m", strtotime($item->data_prevista));
//            $ano = date("Y", strtotime($item->data_prevista));
//            $medico = $item->medico;
//            if ($this->session->userdata('calendario_layout') == 't') {
//                $retorno['url'] = "../../ambulatorio/exame/listarmultifuncaocalendario2?empresa=$empresa&grupo=$grupo&sala=$sala&tipoagenda=$tipoagenda&medico=$medico&situacao=$situacao&data=$dia%2F$mes%2F$ano&nome=$nome";
//            } else {
//                $retorno['url'] = "../../ambulatorio/exame/listarmultifuncaocalendario?empresa=$empresa&grupo=$grupo&sala=$sala&tipoagenda=$tipoagenda&medico=$medico&situacao=$situacao&data=$dia%2F$mes%2F$ano&nome=$nome";
//            }

            $var[] = $retorno;
        }
//        echo '<pre>';
//        var_dump($var); die;
        echo json_encode($var);
    }

    function finalizarorcamento($solicitacao_id) {
        if ($this->centrocirurgico_m->finalizarrcamento($solicitacao_id)) {
            $data['mensagem'] = "Orçamento Finalizado";
        } else {
            $data['mensagem'] = "ERRO: Orçamento NÃO Finalizado";
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function solicitacarorcamento($solicitacao_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoorcamento($solicitacao_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgicaorcamento($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacarorcamento-form', $data);
    }

    function solicitacarorcamentoconvenio($solicitacao_id) {
        $data['solicitacao_id'] = $solicitacao_id;
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoorcamentoconvenio($solicitacao_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentosolicitacaocirurgicaconvenio($solicitacao_id);
        $this->loadView('centrocirurgico/solicitacarorcamentoconvenio-form', $data);
    }

    function gerarelatoriocaixacirurgico() {
        $data['operador'] = $this->operador_m->listaroperador($_POST['operador']);
        $data['medico'] = $this->operador_m->listaroperador($_POST['medico']);
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))); 
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->centrocirurgico_m->relatoriocaixacirurgico(); 
//        echo "<pre>";
//        print_r( $data['relatorio']); 
//        die();
        $data['formapagamento'] = $this->formapagamento->listarforma();
        $this->load->View('centrocirurgico/impressaorelatoriocaixacirurgico', $data);
    }

    function relatoriocaixacirurgico() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['medicos'] = $this->operador_m->listarmedicostodos();
//        $data['grupos'] = $this->procedimento->listargrupos();
//        $data['procedimentos'] = $this->procedimento->listarprocedimentos();
//        $data['grupomedico'] = $this->grupomedico->listargrupomedicos();
        $this->loadView('ambulatorio/relatoriocaixacirurgico', $data);
    }

    function fecharcaixacirurgico() {
//        echo '<pre>';
//        var_dump($_POST); die;
        $caixa = $this->centrocirurgico_m->fecharcaixacirurgico();
//        $this->guia->fecharcaixacredito();
//        echo 'mostre algo';
//        die;
        if ($caixa == "-1") {
            $data['mensagem'] = 'Erro ao fechar caixa. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($caixa == 10) {
            $data['mensagem'] = 'Erro ao fechar caixa. Forma de pagamento não configurada corretamente.';
        } else {
            $data['mensagem'] = 'Sucesso ao fechar caixa.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "centrocirurgico/centrocirurgico/relatoriocaixacirurgico", $data);
    }

    function montarequipe($solicitacaocirurgia_id, $paciente_id, $operatorio) {
        $data['solicitacaocirurgia_id'] = $solicitacaocirurgia_id;
        $data['paciente_id'] = $paciente_id;
        $data['operatorio'] = $operatorio;
        $data['medicos'] = $this->operador_m->listarmedicostodos();
        $data['cirurgiao'] = $this->centrocirurgico_m->listarcirurgiao($solicitacaocirurgia_id);
//        $data['equipe'] = $this->solicitacirurgia_m->listarequipe($solicitacaocirurgia_id);
        $data['equipe_operadores'] = $this->solicitacirurgia_m->listarequipeoperadores($solicitacaocirurgia_id);
        $data['grau_participacao'] = $this->solicitacirurgia_m->grauparticipacao();
//        echo "<pre>";var_dump($data['equipe_operadores'] );die;
        $this->loadView('centrocirurgico/montarequipe-form', $data);
    }

    function gravarequipe() {
        $equipe_id = $this->solicitacirurgia_m->gravarequipe();
        redirect(base_url() . "centrocirurgico/centrocirurgico/montarequipe/$equipe_id");
    }

    function finalizarrequipe($solicitacao_id) {
        $this->centrocirurgico_m->finalizarequipe($solicitacao_id);
        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function autorizarsolicitacaocirurgica() {
        $this->solicitacirurgia_m->autorizarsolicitacaocirurgica();

        $guia_id = $this->solicitacirurgia_m->gravarguiasolicitacaocirurgica();

        if ($this->solicitacirurgia_m->gravarprocedimentosolicitacaocirurgica($guia_id)) {
            $data['mensagem'] = "Solicitação autorizada gravado com sucesso!";
        } else {
            $data['mensagem'] = "Erro ao gravar Orçamento. Opera&ccedil;&atilde;o cancelada.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function excluirprocedimentoscirurgia($solicitacao_cirurgia_procedimento_id, $guia_id, $solicitacao_id, $agenda_exames_id, $data) {
//        var_dump($_POST); die;

        $data = date("d/m/Y", strtotime($data));
        
        $_POST['txtdata'] = $data;

        $this->solicitacirurgia_m->excluirprocedimentocirurgico($solicitacao_cirurgia_procedimento_id, $guia_id, $solicitacao_id);
        
        $this->solicitacirurgia_m->excluirentradasagendaexames2($guia_id, $agenda_exames_id);
   
        $this->solicitacirurgia_m->gravarguiaeditarprocedimentoscirurgia($guia_id, $solicitacao_id); 
        
       
          
        if ($this->solicitacirurgia_m->gravareditarprocedimentosolicitacaocirurgica($guia_id, $solicitacao_id)) {
            $mensagem = "Solicitação excluida com sucesso!";
        } else {
            $mensagem = "Erro ao gravar Orçamento. Opera&ccedil;&atilde;o cancelada.";
        }
        // print_r($mensagem);
        // die;
        $this->session->set_flashdata('message', $mensagem);

        redirect(base_url() . "centrocirurgico/centrocirurgico/editarprocedimentoscirurgia/$solicitacao_id/$guia_id");
    }

    function gravareditarprocedimentoscirurgia($guia_id, $solicitacao_id) {
        // echo '<pre>';
        // print_r($_POST);
        // die;
        $this->solicitacirurgia_m->gravarsolicitacaoeditarprocedimento($guia_id, $solicitacao_id);
 
        $this->solicitacirurgia_m->excluirentradasagendaexames($guia_id);
         
        $this->solicitacirurgia_m->gravarguiaeditarprocedimentoscirurgia($guia_id, $solicitacao_id);
         

        if ($this->solicitacirurgia_m->gravareditarprocedimentosolicitacaocirurgica($guia_id, $solicitacao_id)) {
            $data['mensagem'] = "Solicitação autorizada gravado com sucesso!";
        } else {
            $data['mensagem'] = "Erro ao gravar Orçamento. Opera&ccedil;&atilde;o cancelada.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/editarprocedimentoscirurgia/$solicitacao_id/$guia_id");
    }

    function gravareditarcirurgia() {

        if ($this->solicitacirurgia_m->gravareditarcirurgia()) {
            $data['mensagem'] = "Sucesso ao editar cirurgia!";
        } else {
            $data['mensagem'] = "Erro ao editar cirurgia. Opera&ccedil;&atilde;o cancelada.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisarcirurgia");
    }

    function gravarsolicitacaorcamento() {
        $orcamento_id = $this->solicitacirurgia_m->gravarsolicitacaorcamento();

        if ($this->solicitacirurgia_m->gravarsolicitacaorcamentoitens($orcamento_id)) {
            $data['mensagem'] = "Orçamento gravado com sucesso!";
        } else {
            $data['mensagem'] = "Erro ao gravar Orçamento. Opera&ccedil;&atilde;o cancelada.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/pesquisar");
    }

    function impressaosolicitacaocirurgicamaterialopme($solicitacao_cirurgia_id) {
        $data['solicitacao_cirurgia_id'] = $solicitacao_cirurgia_id;
        $data['empresa'] = $this->solicitacirurgia_m->burcarempresa();
        $data['relatorio'] = $this->solicitacirurgia_m->listarsolicitacaocirurgicamaterialopme($solicitacao_cirurgia_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarmateriaisguiacirurgicaopme($solicitacao_cirurgia_id);
        $this->load->View('centrocirurgico/impressaosolicitacaocirurgicamaterialopme', $data);
    }

    function impressaosolicitacaocirurgicaconveniospsadt($solicitacao_cirurgia_id) {
        $data['solicitacao_cirurgia_id'] = $solicitacao_cirurgia_id;
        $data['empresa'] = $this->solicitacirurgia_m->burcarempresa();
        $data['relatorio'] = $this->solicitacirurgia_m->listarsolicitacaocirurgicaconveniospsadt($solicitacao_cirurgia_id);
        $data['procedimentos'] = $this->solicitacirurgia_m->listarprocedimentoguiacirurgicaconvenio($solicitacao_cirurgia_id);
        $this->load->View('centrocirurgico/impressaosolicitacaocirurgicaconveniospsadt', $data);
    }

    function gravarsolicitacaorcamentoconvenio() {
        $orcamento_id = $this->solicitacirurgia_m->gravarsolicitacaorcamentoconvenio();
        $solicitacao_id = $_POST['txtsolcitacao_id'];

        if ($this->solicitacirurgia_m->gravarsolicitacaorcamentoconvenioitens($orcamento_id)) {
            $data['mensagem'] = "Orçamento gravado com sucesso!";
        } else {
            $data['mensagem'] = "Erro ao gravar Orçamento. Opera&ccedil;&atilde;o cancelada.";
        }

        $this->session->set_flashdata('message', $data['mensagem']);

        redirect(base_url() . "centrocirurgico/centrocirurgico/solicitacarorcamentoconvenio/$solicitacao_id", $data);
    }

    function internacaoalta($internacao_id) {

        $data['resultado'] = $this->internacao_m->internacaoalta($internacao_id);
    }

    function listarfichaanestesia($solicitacao_cirurgia_id) {
        $data['paciente'] = $this->solicitacirurgia_m->listardadospaciente($solicitacao_cirurgia_id);
        $data['materiais'] = $this->solicitacirurgia_m->listarmateriaisguiacirurgicaopme($solicitacao_cirurgia_id);
        $this->load->View('centrocirurgico/impressaosolicitacaofichaanesteria', $data);
    }

    
    function editarsolicitacao($solicitacao_id){
        $data['solicitacao_id'] = $solicitacao_id;
        $data['solicitacao'] = $this->solicitacirurgia_m->listardadossolicitacaoautorizar($solicitacao_id);
        $data['hospitais'] = $this->centrocirurgico_m->listarhospitaissolicitacao();
        $data['medicos'] = $this->operador_m->listarmedicoscirurgia();
        $data['salas'] = $this->centrocirurgico_m->listarsalascirurgico();
        $data['convenio'] = $this->centrocirurgico_m->listarconveniocirurgiaorcamento();  
        $this->loadView('centrocirurgico/solicitacao-form', $data);
          
    }
    
    
    function relatoriocaixainternacao() {
        $data['operadores'] = $this->operador_m->listartecnicos();
        $data['empresa'] = $this->guia->listarempresas();
        $data['medicos'] = $this->operador_m->listarmedicostodos();
//        $data['grupos'] = $this->procedimento->listargrupos();
//        $data['procedimentos'] = $this->procedimento->listarprocedimentos();
//        $data['grupomedico'] = $this->grupomedico->listargrupomedicos();
        $this->loadView('ambulatorio/relatoriocaixainternacao', $data);
    }
    
     
     function gerarelatoriocaixainternacao() {
        $data['operador'] = $this->operador_m->listaroperador($_POST['operador']);
        $data['medico'] = $this->operador_m->listaroperador($_POST['medico']);
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->centrocirurgico_m->relatoriocaixainternacao();
        $data['formapagamento'] = $this->formapagamento->listarforma();
        $this->load->View('centrocirurgico/impressaorelatoriocaixainternacao', $data);
    } 
    
 function relatoriocancelamentocirurgia(){ 
     
        $data['empresa'] = $this->guia->listarempresas(); 
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('centrocirurgico/relatoriocancelamentocirurgia', $data);
        
 } 
 
   function gerarelatoriocancelamentocirurgia() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        if ($_POST['convenio'] != "TODOS") {
                $data['convenio']  = $this->convenio->listarconvenioselecionado($_POST['convenio']);
        }
        $data['relatorio'] = $this->centrocirurgico_m->relatoriocancelamentocirurgia(); 
        $this->load->View('centrocirurgico/impressaorelatoriocancelamentocirurgia', $data); 
    }
    
    function excluircirurgia($solicitacao_id,$calendario=NULL) {
        $this->solicitacirurgia_m->excluirsolicitacaocirurgia($solicitacao_id);
        $mensagem = 'Solicitacao excluida com sucesso';
        $this->session->set_flashdata('message',$mensagem); 
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);    
    }
    function impressaomapacirurgico(){
        $this->load->plugin('mpdf');   
       $empresa_id =  $this->session->userdata('empresa_id');
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;        
        $data['relatorio'] =  $this->centrocirurgico_m->impressaomapacirurgico($_GET['data'],$_GET['medico']);
        $data['data'] = $_GET['data'];
        $html = $this->load->View('centrocirurgico/impressaomapacirurgico',$data,true);
        pdf($html, 'Impressao Solicitacao', $cabecalho_config, $rodape_config); 
        
    }
    
    function gravarobservacaocirurgia(){ 
        $this->solicitacirurgia_m->gravarobservacaocirurgia();
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);    
    }
    
    function fecharcaixainternacao() { 
        $caixa = $this->centrocirurgico_m->fecharcaixainternacao(); 
        if ($caixa == "-1") {
            $mensagem = 'Erro ao fechar caixa. Opera&ccedil;&atilde;o cancelada.';
        } elseif ($caixa == 10) {
            $mensagem = 'Erro ao fechar caixa. Forma de pagamento não configurada.';
        } else {
            $mensagem = 'Sucesso ao fechar caixa.';
        }
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "centrocirurgico/centrocirurgico/relatoriocaixainternacao", $data);
    }
    
    function alterardataprocedimento($agenda_exames_id){
       $data['dados'] = $this->centrocirurgico_m->listarprocedimento($agenda_exames_id);    
         
       $this->load->View('centrocirurgico/alterardataprocedimento',$data);
    }
    
    
    function gravardataprocedimento(){ 
        $this->centrocirurgico_m->gravardataprocedimento();
        $mensagem = 'Data alterada com sucesso';
        $this->session->set_flashdata('message',$mensagem); 
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);   
    }
    
    function recibocirurgia($solicitacao_cirurgia_id){
       $empresa_id =  $this->session->userdata('empresa_id');
       $data['empresa'] = $this->guia->listarempresa($empresa_id);
       $data['recibo'] = $this->centrocirurgico_m->recibocirurgia($solicitacao_cirurgia_id);
       $data['formapagamento'] = $this->formapagamento->listarforma();
       $this->load->View('centrocirurgico/impressaorecibocirurgia',$data);
       
       
    }
     
    
    function cirurgicoexclusao($solicitacao_id){
         $data['motivos'] = $this->motivocancelamento->listartodos();
         $data['lista'] = $this->solicitacirurgia_m->listardadospaciente($solicitacao_id);   
         $data['solicitacao_id'] = $solicitacao_id;
         $this->loadView('centrocirurgico/cirurgicoexclusao-form', $data);  
    } 
   
    
    function internacaoclaracao($internacao_id){ 
        $data['internacao'] = $this->solicitacirurgia_m->verificaodeclaracao($internacao_id);
        $data['modelos'] = $this->modelodeclaracao->listarmodelo();
        $this->load->View('centrocirurgico/internacaodeclaracao-form', $data);  
    }
    
    function impressaodeclaracaointernacao($internacao_id){
        
        $this->load->plugin('mpdf');
        $data['emissao'] = date("d-m-Y");
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $this->solicitacirurgia_m->gravardeclaracaointernacao($internacao_id);
        $data['internacao'] = $this->solicitacirurgia_m->verificaodeclaracao($internacao_id);
        
        
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $filename = "declaracao.pdf";
        if ($data['empresa'][0]->cabecalho_config == 't') {
            $cabecalho = @$cabecalho_config;
        } else {
            $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
        }
        @$rodape_config = str_replace("_assinatura_", '', @$rodape_config);
        if ($data['empresa'][0]->rodape_config == 't') {
            $rodape = @$rodape_config;
        } else {
            $rodape = "<img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'>";
        }
//        $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
//        $rodape = "";

        if ($data['empresa'][0]->declaracao_config == 't') {
            $html = $this->load->view('centrocirurgico/impressaodeclaracaointernacaoconfiguravel', $data, true);
        } else {
            $html = $this->load->view('centrocirurgico/impressaodeclaracaointernacao', $data, true);
        }
  
        pdf($html, $filename, $cabecalho, $rodape);
         
    }
    
     function cirurgicocancelar($solicitacao_id){
         $data['motivos'] = $this->motivocancelamento->listartodos();
         $data['lista'] = $this->solicitacirurgia_m->listardadospaciente($solicitacao_id);   
         $data['solicitacao_id'] = $solicitacao_id;
         $this->loadView('centrocirurgico/cirurgicocancelar-form', $data);  
    } 
    
    
     function cancelarsolicitacaocirurgia($solicitacao_id,$calendario=NULL) { 
       $this->solicitacirurgia_m->cancelarsolicitacaocirurgia($solicitacao_id);  
       $mensagem = 'Solicitacao cancelada com sucesso';
       $this->session->set_flashdata('message', $mensagem); 
       redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);  
    }
    
     
}

?>
