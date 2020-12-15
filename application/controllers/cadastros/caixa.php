<?php

require_once APPPATH . 'controllers/base/BaseController.php';

/**
 * Esta classe é o controler de Servidor. Responsável por chamar as funções e views, efetuando as chamadas de models
 * @author Equipe de desenvolvimento APH
 * @version 1.0
 * @copyright Prefeitura de Fortaleza
 * @access public
 * @package Model
 * @subpackage GIAH
 */
class Caixa extends BaseController {

    function Caixa() {
        parent::Controller();
        $this->load->model('cadastro/caixa_model', 'caixa');
        $this->load->model('cadastro/nivel1_model', 'nivel1');
        $this->load->model('cadastro/nivel2_model', 'nivel2');
        $this->load->model('cadastro/tipo_model', 'tipo');
        $this->load->model('cadastro/classe_model', 'classe');
        $this->load->model('cadastro/forma_model', 'forma');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->model('seguranca/operador_model', 'operador');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('ambulatorio/motivocancelamento_model', 'motivocancelamento');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
        if ($this->session->userdata('autenticado') != true) {
            redirect(base_url() . "login/index/login004", "refresh");
        }
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('cadastros/entrada-lista', $args);
    }

    function pesquisar2($args = array()) {

        $this->loadView('cadastros/saida-lista', $args);
    }

    function pesquisar3($args = array()) {

        $this->loadView('cadastros/sangria-lista', $args);
    }

    function carregar($saidas_id) {
        $obj_saidas = new caixa_model($saidas_id);
        $data['obj'] = $obj_saidas;
        $data['conta'] = $this->forma->listarformaempresa();
        $data['classe'] = $this->classe->listarclasse();
        $data['tipo'] = $this->tipo->listartipo();
        $this->loadView('cadastros/saida-form', $data);
    }

    function novaentrada($empresa_id = null, $parametros = null) {
        $data['tipo'] = $this->tipo->listartipo();
        $data['classe'] = $this->classe->listarclasse();
        $data['nivel1'] = $this->nivel1->listarnivel1();
        $data['nivel2'] = $this->nivel2->listarnivel2();
        $data['conta'] = $this->forma->listarformaempresa();
        $data['empresas'] = $this->exame->listarempresas();
        $data['empresa_id'] = $empresa_id;
        $data['parametros'] = $parametros;
        $this->loadView('cadastros/entrada-form', $data);
    }

    function novasaida($empresa_id = null, $parametros = null) {
        $data['tipo'] = $this->tipo->listartipo();
        $data['classe'] = $this->classe->listarclasse();
        $data['conta'] = $this->forma->listarformaempresa();
        $data['empresas'] = $this->exame->listarempresas();
        $data['empresa_id'] = $empresa_id;
        $data['parametros'] = $parametros;
        $this->loadView('cadastros/saida-form', $data);
    }

    function transferencia() {
        $data['conta'] = $this->forma->listarformaempresa();
        $this->loadView('cadastros/transferencia-form', $data);
    }

    function painelfinanceiro() {
//        $data['conta'] = $this->forma->listarforma();

        $data['saida'] = $this->caixa->painelfinanceirosaida();

        $data['entrada'] = $this->caixa->painelfinanceiroentrada();
        $data['contaspagar'] = $this->caixa->painelfinanceirocontaspagar();
        $data['contasreceber'] = $this->caixa->painelfinanceirocontasreceber();

        $data['saidames'] = $this->caixa->painelfinanceiromessaida();
        $data['entradames'] = $this->caixa->painelfinanceiromesentrada();
        $data['contaspagarmes'] = $this->caixa->painelfinanceiromescontaspagar();
        $data['contasrecebermes'] = $this->caixa->painelfinanceiromescontasreceber();

        $data['saidamesatual'] = $this->caixa->painelfinanceiromesatualsaida();
        $data['entradamesatual'] = $this->caixa->painelfinanceiromesatualentrada();
        $data['contaspagarmesatual'] = $this->caixa->painelfinanceiromesatualcontaspagar();
        $data['contasrecebermesatual'] = $this->caixa->painelfinanceiromesatualcontasreceber();


//        var_dump($data['contasrecebermes']);
//        die;
        
//        $data['relatoriomedico'] = $this->caixa->relatorioprevisaomedicacontaspagar();
//        $data['relatoriopromotor'] = $this->caixa->relatorioprevisaopromotorcontaspagar();
//        $data['relatoriolaboratorio'] = $this->caixa->relatorioprevisaolaboratoriocontaspagar();
//        $data['relatorioconvenio'] = $this->caixa->relatorioprevisaoconveniocontasreceber();
//        
//        $data['relatoriomesmedico'] = $this->caixa->relatoriomesprevisaomedicacontaspagar();
//        $data['relatoriomespromotor'] = $this->caixa->relatoriomesprevisaopromotorcontaspagar();
//        $data['relatoriomeslaboratorio'] = $this->caixa->relatoriomesprevisaolaboratoriocontaspagar();
//        $data['relatoriomesconvenio'] = $this->caixa->relatoriomesprevisaoconveniocontasreceber();
//        echo '<pre>';
//        var_dump($data['relatoriomedico']);
//        die;

        $this->loadView('cadastros/painelfinanceiro', $data);
    }

    function novasangria() {
        $data['operador'] = $this->operador->listaradminitradores();
        $data['operadorcaixa'] = $this->operador->listartecnicos();
        $data['conta'] = $this->forma->listarforma();
        $this->loadView('cadastros/sangria-form', $data);
    }

    function cancelarsangria($sangria_id) {
        $data['sangria'] = $this->caixa->listarcancelarsangria($sangria_id);
        $this->loadView('cadastros/cancelarsangria-form', $data);
    }

    function excluir($exame_sala_id) {
        if ($this->procedimento->excluir($exame_sala_id)) {
            $mensagem = 'Sucesso ao excluir a Sala';
        } else {
            $mensagem = 'Erro ao excluir a sala. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/sala");
    }

    function gravarentrada() {
//        if ($_POST['devedor'] == '') {
//            $mensagem = 'É necessário selecionar o item no campo Receber de: ';
//            $this->session->set_flashdata('message', $mensagem);
//            redirect(base_url() . "cadastros/caixa/novaentrada");
//        }
//        var_dump($_POST);die;
        $caixa_id = $this->caixa->gravarentrada();
        if ($caixa_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar entrada. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a entrada.';
            $this->importararquivoNovoEntrada($caixa_id);

        }
        redirect(base_url() . "cadastros/caixa/pesquisar?".@$_POST['parametros']);
    }

    function importararquivoNovoEntrada($entradas_id){

        if (!is_dir("./upload/entrada")) {
            mkdir("./upload/entrada");
            chmod("./upload/entrada", 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/entrada/$entradas_id")) {
                mkdir("./upload/entrada/$entradas_id");
                $destino = "./upload/entrada/$entradas_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/entrada/" . $entradas_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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
    }

    function importararquivoNovoSaida($saidas_id){

        if (!is_dir("./upload/saida")) {
            mkdir("./upload/saida");
            chmod("./upload/saida", 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/saida/$saidas_id")) {
                mkdir("./upload/saida/$saidas_id");
                $destino = "./upload/saida/$saidas_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/saida/" . $saidas_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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

    }

    function gravarsaida() {
        $caixa_id = $this->caixa->gravarsaida();
        if ($caixa_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Saida. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Saida.';
            $this->importararquivoNovoSaida($caixa_id);
        }
        
        redirect(base_url() . "cadastros/caixa/pesquisar2?".@$_POST['parametros']);
    }

    function gravartransferencia() {
        $data_atual = date("Y-m-d");
 
        if(strtotime(date("Y-m-d", strtotime(str_replace('/', '-', $_POST['inicio'])))) <= strtotime(date("Y-m-d"))){
          $caixa_id = $this->caixa->gravartransferencia();  
        }else{
            $caixa_id = "-2";
        }
        if ($caixa_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Transferencia. Opera&ccedil;&atilde;o cancelada.';
        } else if($caixa_id == "-2") {
            $data['mensagem'] = 'Erro ao gravar a Transferencia. Data escolhida maior que a Atual';
        }else{
            $data['mensagem'] = 'Sucesso ao gravar a Transferencia.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/caixa/pesquisar2", $data);
    }

    function gravarsangria() {
        $caixa_id = $this->caixa->gravarsangria();

//        if ($caixa_id == 1) {
//            $data['mensagem'] = 'Erro ao gravar a Saida. Opera&ccedil;&atilde;o cancelada.';
//        } else
        if ($caixa_id == 1) {
            $data['mensagem'] = 'Sucesso ao gravar a sangria.';
        } elseif ($caixa_id == 0) {
            $data['mensagem'] = 'Senha incorreta.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/caixa/pesquisar3", $data);
    }

    function gravarcancelarsangria() {
        $caixa_id = $this->caixa->gravarcancelarsangria();

//        if ($caixa_id == 1) {
//            $data['mensagem'] = 'Erro ao gravar a Saida. Opera&ccedil;&atilde;o cancelada.';
//        } else
        if ($caixa_id == 1) {
            $data['mensagem'] = 'Sucesso ao cancelar a sangria.';
        } elseif ($caixa_id == 0) {
            $data['mensagem'] = 'Senha incorreta.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/caixa/pesquisar3", $data);
    }

    function excluirentrada($entrada) {
        $this->caixa->excluirentrada($entrada);
        // Apaga os arquivos
        if (is_dir("./upload/entrada")) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/entrada/$entrada");
            if ($arquivo_pasta != false) {
                foreach ($arquivo_pasta as $value) {
                    unlink("./upload/entrada/$entrada/$value");
                }
                rmdir("./upload/entrada/$entrada");
            }
        }
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        
    }

    function excluirsaida($saida) {
        $this->caixa->excluirsaida($saida);
        // Apaga os arquivos
        if (is_dir("./upload/saida")) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/saida/$saida");
            if ($arquivo_pasta != false) {
                foreach ($arquivo_pasta as $value) {
                    unlink("./upload/saida/$saida/$value");
                }
                rmdir("./upload/saida/$saida");
            }
        }
        
     redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        
    }

    function excluirsangria($saida) {
        $this->caixa->excluirsaida($saida);
        redirect(base_url() . "cadastros/caixa/pesquisar2");
    }

    function gravarprocedimentos() {
        $agenda_exames_id = $this->guia->gravarexames();
        if ($agenda_exames_id == "-1") {
            $data['mensagem'] = 'Erro ao agendar Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao agendar Exame.';
        }
        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "cadastros/pacientes");
    }

    function novo($data) {
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('ambulatorio/guia-form', $data);
    }

    function relatoriosaida() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriosaida', $data);
    }

    function relatorioacompanhamentodecontas() {
        $data['grupo'] = $this->guia->listargrupo();
        $this->loadView('ambulatorio/relatorioacompanhamentodecontas', $data);
    }

    function gerarelatorioacompanhamentodecontas() {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['grupo'] = $_POST['grupo'];
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['relatoriosaida'] = $this->caixa->relatoriosaidaacompanhamentodecontas();
//        echo '<pre>';
//        var_dump($data['relatoriosaida']);
//        echo '<pre>';
        $data['relatorioentrada'] = $this->caixa->relatorioentradaacompanhamentodecontas();
        $data['relatorioexamesgrupoprocedimento'] = $this->caixa->relatorioexamesgrupoprocedimentoacompanhamento();
        $this->load->View('ambulatorio/impressaorelatorioacompanhamentodecontas', $data);
    }

    function gerarelatoriosaida() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->caixa->relatoriosaida();

        if ($_POST['email'] == "NAO") {
            $this->load->View('ambulatorio/impressaorelatoriosaida', $data);
        } elseif ($_POST['email'] == "SIM") {

            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' ate ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorio']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Dt saida</th>
                    <th class="tabela_header">Valor</th>

                    <th class="tabela_header">Observacao</th>
                </tr>
            </thead>
            <tbody>';

                $total = 0;
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorio'] as $item) :
                    $total = $total + $item->valor;

                    $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4"><b>TOTAL</b></td>
                    <td colspan="2"><b>' . number_format($total, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            }
            else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatoriosaida';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    function formrelatorioemail($email_id, $tiporelatorio) {
        $data['email_id'] = $email_id;
        $data['tiporelatorio'] = $tiporelatorio;
        $this->loadView("ambulatorio/enviaremail-form", $data);
    }

    function enviaremail($email_id) {
        $relatorio = $_POST['tiporelatorio'];

        $msg = $this->caixa->listaremailmensagem($email_id);
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
        $config['smtp_pass'] = 'aramis*123@';
        $config['validate'] = TRUE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $this->email->initialize($config);
        $this->email->from('equipe2016gcjh@gmail.com', $_POST['seunome']);
        $this->email->to($_POST['destino1']);
        $this->email->subject($_POST['assunto']);
        $this->email->message($msg);
        
//        var_dump($this->email->send());
//        die();
        if ($this->email->send()) {
            $data['mensagem'] = "Email enviado com sucesso.";
        } else {
            $data['mensagem'] = "Envio de Email malsucedido.";
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/caixa/$relatorio/");
    }

    function relatoriosaidagrupo() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriosaidagrupo', $data);
    }

    function relatoriofluxocaixa() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriofluxocaixa', $data);
    }

    function relatoriocontrolefluxocaixa() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriocontrolefluxocaixa', $data);
    }

    function relatoriosaidaporano() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriosaidaporano', $data);
    }

    function relatoriosaidaclasse() {
        $data['conta'] = $this->forma->listarforma();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriosaidaclasse', $data);
    }

    function gerarelatoriosaidaporano() {
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio_saida'] = $this->caixa->relatoriosaidaporano();
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
        // $data['saldo_anterior'] = $this->caixa->relatoriosaidaporanosaldoanterior();
        // echo '<pre>';
        // var_dump($data['relatorio_saida']); die;
        $this->load->View('ambulatorio/impressaorelatoriosaidaporano', $data);
    }

    function gerarelatoriofluxocaixa() {
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio_entrada'] = $this->caixa->relatoriofluxocaixaentrada();
        // $data['caixa_saldo'] = $this->caixa->relatoriocaixa_saldo();
        $data['relatorio_saida'] = $this->caixa->relatoriofluxocaixasaida();
        // $data['saldo_anterior'] = $this->caixa->relatoriofluxocaixasaldoanterior();
        $data['relatorio_saldo_entrada'] = $this->caixa->relatoriofluxocaixasaldoanteriorcompleto_entradas();
        $data['relatorio_saldo_saida'] = $this->caixa->relatoriofluxocaixasaldoanteriorcompleto_saidas();

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ep.financ_4n');
        $this->db->from('tb_empresa_permissoes ep');        
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno = $this->db->get()->result(); 
                    
        if($retorno[0]->financ_4n == 't'){
            
            $this->load->View('ambulatorio/impressaorelatoriofluxocaixa4n', $data);
            
        }else{
        
            $this->load->View('ambulatorio/impressaorelatoriofluxocaixa', $data);
            
        }
    }

    function gerarelatoriocontrolefluxocaixa() {
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio_entrada'] = $this->caixa->relatoriocontrolefluxocaixaentrada();
        // $data['relatorio_caixa'] = $this->caixa->relatoriocontrolefluxocaixaentrada();
        $data['relatorio_saida'] = $this->caixa->relatoriofluxocaixasaida();
        $data['saldo_anterior'] = $this->caixa->relatoriofluxocaixasaldoanterior();

        foreach ($data['relatorio_entrada'] as $key => $value) {
            if($value->valor_tipo == null){
                $data['relatorio_entrada'][$key]->valor_tipo = $value->valor_caixa;
                $data['relatorio_entrada'][$key]->tipo = 'CAIXA E AFINS';
            }else{

            }
        }
        // echo '<pre>';
        // var_dump($data['saldo_anterior']); 
        // die;
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ep.financ_4n');
        $this->db->from('tb_empresa_permissoes ep');        
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno = $this->db->get()->result();
                    
        if($retorno[0]->financ_4n == 't'){
            
            $this->load->View('ambulatorio/impressaorelatoriocontrolefluxocaixa4n', $data);
            
        }else{
        
            $this->load->View('ambulatorio/impressaorelatoriocontrolefluxocaixa', $data);
            
        }
    }

    function gerarelatoriosaidagrupo() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->caixa->relatoriosaidagrupo();
        $data['contador'] = $this->caixa->relatoriosaidacontador();

        if ($_POST['email'] == "NAO") {
            $this->load->View('ambulatorio/impressaorelatoriosaidagrupo', $data);
        } elseif ($_POST['email'] == "SIM") {
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' ate ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorio']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Dt saida</th>
                    <th class="tabela_header">Valor</th>

                    <th class="tabela_header">Observacao</th>
                </tr>
                </tr>
            </thead>
            <tbody>';

                $totalgeral = 0;
                $totaltipo = 0;
                $i = 0;
                $s = '';
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorio'] as $item) :
                    $totalgeral = $totalgeral + $item->valor;
                    if ($i == 0 || $item->tipo == $s) {
                        $s = $item->tipo;
                        $totaltipo = $totaltipo + $item->valor;
                        $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                    } else {
                        $corpo2 = $corpo2 . '
                        <tr>
                            <td colspan="5" bgcolor="#C0C0C0"><b>SUB-TOTAL</b></td>
                            <td bgcolor="#C0C0C0"><b>' . number_format($totaltipo, 2, ",", ".") . '</b></td>
                        </tr> 
                        <tr>
                            <td >' . $item->conta . '</td>
                            <td >' . $item->razao_social . '</td>
                            <td >' . $item->tipo . '</td>
                            <td >' . $item->classe . '</td>
                            <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                            <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                            <td >' . $item->observacao . '</td>
                        </tr>';
                        $s = $item->tipo;
                        $totaltipo = 0;
                        $totaltipo = $item->valor;
                    }
                    $i++;
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4" bgcolor="#C0C0C0"><b>TOTAL</b></td>
                    <td colspan="2" bgcolor="#C0C0C0"><b>' . number_format($totalgeral, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatoriosaidagrupo';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    function gerarelatoriosaidaclasse() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->caixa->relatoriosaidaclasse();
        $data['contador'] = $this->caixa->relatoriosaidacontadorclasse();

        if ($_POST['email'] == "NAO") {
            $this->load->View('ambulatorio/impressaorelatoriosaidaclasse', $data);
        } elseif ($_POST['email'] == "SIM") {
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' ate ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorio']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Dt saida</th>
                    <th class="tabela_header">Valor</th>

                    <th class="tabela_header">Observacao</th>
                </tr>
                </tr>
            </thead>
            <tbody>';

                $totalgeral = 0;
                $totaltipo = 0;
                $i = 0;
                $s = '';
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorio'] as $item) :
                    $totalgeral = $totalgeral + $item->valor;
                    if ($i == 0 || $item->tipo == $s) {
                        $s = $item->tipo;
                        $totaltipo = $totaltipo + $item->valor;
                        $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                    } else {
                        $corpo2 = $corpo2 . '
                        <tr>
                            <td colspan="5" bgcolor="#C0C0C0"><b>SUB-TOTAL</b></td>
                            <td bgcolor="#C0C0C0"><b>' . number_format($totaltipo, 2, ",", ".") . '</b></td>
                        </tr> 
                        <tr>
                            <td >' . $item->conta . '</td>
                            <td >' . $item->razao_social . '</td>
                            <td >' . $item->tipo . '</td>
                            <td >' . $item->classe . '</td>
                            <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                            <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                            <td >' . $item->observacao . '</td>
                        </tr>';
                        $s = $item->tipo;
                        $totaltipo = 0;
                        $totaltipo = $item->valor;
                    }
                    $i++;
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4" bgcolor="#C0C0C0"><b>TOTAL</b></td>
                    <td colspan="2" bgcolor="#C0C0C0"><b>' . number_format($totalgeral, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatoriosaidagrupo';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    function relatorioentrada() {
        $data['conta'] = $this->forma->listarforma();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioentrada', $data);
    }

    function gerarelatorioentrada() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorioentrada'] = $this->caixa->relatorioentrada();
//        var_dump($_POST['email']);die;
        if ($_POST['email'] == "NAO") {
//            die;
            $this->load->View('cadastros/impressaorelatorioentrada', $data);
        } elseif ($_POST['email'] == "SIM") {
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' até ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorioentrada']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Dt entrada</th>
                    <th class="tabela_header">Valor</th>
                    <th class="tabela_header">Observacao</th>
                </tr>
            </thead>
            <tbody>';

                $total = 0;
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorioentrada'] as $item) :
                    $total = $total + $item->valor;
                    $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4" bgcolor="#C0C0C0"><b>TOTAL</b></td>
                    <td colspan="2" bgcolor="#C0C0C0"><b>' . number_format($total, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatorioentrada';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    function relatorioentradagrupo() {
        $data['conta'] = $this->forma->listarforma();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioentradagrupo', $data);
    }

    function gerarelatorioentradagrupo() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartiporelatorio($_POST['tipo']);
        $data['classe'] = $this->tipo->buscartiporelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorioentrada'] = $this->caixa->relatorioentradagrupo();
        $data['contadorentrada'] = $this->caixa->relatorioentredacontador();


        if ($_POST['email'] == "NAO") {
            $this->load->View('cadastros/impressaorelatorioentradagrupo', $data);
        } elseif ($_POST['email'] == "SIM") {
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' até ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorioentrada']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Dt entrada</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Valor</th>
                    <th class="tabela_header">Observacao</th>
                </tr>
            </thead>
            <tbody>';

                $totalgeral = 0;
                $totaltipo = 0;
                $i = 0;
                $s = '';
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorioentrada'] as $item) :
                    $totalgeral = $totalgeral + $item->valor;
                    if ($i == 0 || $item->conta == $s) {
                        $s = $item->conta;
                        $totaltipo = $totaltipo + $item->valor;
                        $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                    } else {
                        $corpo2 = $corpo2 . '
                        <tr>
                            <td colspan="5" bgcolor="#C0C0C0"><b>SUB-TOTAL</b></td>
                            <td bgcolor="#C0C0C0"><b>' . number_format($totaltipo, 2, ",", ".") . '</b></td>
                        </tr>
                        <tr>
                            <td >' . $item->conta . '</td>
                            <td >' . $item->razao_social . '</td>
                            <td >' . $item->tipo . '</td>
                            <td >' . $item->classe . '</td>
                            <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                            <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                            <td >' . $item->observacao . '</td>
                        </tr>';
                        $s = $item->conta;
                        $totaltipo = 0;
                        $totaltipo = $item->valor;
                    }
                    $i++;
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4" bgcolor="#C0C0C0"><b>TOTAL</b></td>
                    <td colspan="2" bgcolor="#C0C0C0"><b>' . number_format($totalgeral, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatorioentradagrupo';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    function relatoriomovitamentacao() {
        $data['conta'] = $this->forma->listarforma();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
        $data['empresas'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatoriomovimento', $data);
    }

    function relatorioduplaAssinatura() {
        $data['conta'] = $this->forma->listarforma();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
        $data['empresas'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioduplaAssinatura', $data);
    }

    function gerarelatorioduplaAssinatura() {

        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        if($_POST['movimentacao'] != 'DESCONTO'){
            $data['relatorio'] = $this->caixa->relatorioduplaAssinatura();
        }else{
            $data['relatorio'] = array();
        }

        if($_POST['movimentacao'] != 'ENTRADA' && $_POST['movimentacao'] != 'SAIDA'){
            $data['relatorio_desconto'] = $this->caixa->relatorioduplaAssinaturaDesconto();
        }else{
            $data['relatorio_desconto'] = array();
        }
        
       
        // $data['saldoantigo'] = $this->caixa->relatoriomovimentosaldoantigo();
        // echo '<pre>';
        // var_dump($data['relatorio']); die;
       
        $this->load->View('ambulatorio/impressaorelatorioduplaAssinatura', $data);
        
    }


    function confirmarduplaAssinatura($id, $movimentacao) {

        $this->caixa->confirmarduplaAssinatura($id, $movimentacao);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        
    }

    function gerarelatoriomovitamentacao() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);

        $data['relatorio'] = $this->caixa->relatoriomovimento();
        $data['saldoantigo'] = $this->caixa->relatoriomovimentosaldoantigo();
        // echo '<pre>';
        // print_r($data['saldoantigo']);
        // die;


        if ($_POST['email'] == "NAO") {
            $this->load->View('ambulatorio/impressaorelatoriomovimento', $data);
        } elseif ($_POST['email'] == "SIM") {
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS DEVEDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' até ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorio']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Data</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Valor</th>
                    <th class="tabela_header">Observa&ccedil;&atilde;o</th>
                    <th class="tabela_header">Saldo</th>
                </tr>
            </thead>
            <tbody>';

                $total = $data['saldoantigo']->total;
                $data = 0;
                $totalrelatorio = 0;
                $corpo2 = '';
                $corpo3 = '';
                $corpo4 = '';
                $corpo5 = '';
                foreach ($data['relatorio'] as $item) :
                    $total = $total + $item->valor;
                    $totalrelatorio = $totalrelatorio + $item->valor;
                    $dataatual = substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4);
                    $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->contanome . '</td>
                        <td ><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                        <td >' . $item->razao_social . '</td>';
                    if ($item->tiposaida != null) {
                        $corpo3 = $corpo3 . '
                            <td >' . $item->tiposaida . '</td>
                            <td >' . $item->classesaida . '</td>
                            <td ><font color="red">' . number_format($item->valor, 2, ",", ".") . '</td> ';
                    } else {
                        $corpo3 = $corpo3 . '
                            <td >' . $item->tipoentrada . '</td>
                            <td >' . $item->classeentrada . '</td>                      
                            <td ><font color="blue">' . number_format($item->valor, 2, ",", ".") . '</td>';
                    }
                    if ($item->observacaosaida != null) {
                        $corpo4 = $corpo4 . '
                                <td >' . $item->observacaosaida . '</td>';
                    } else {
                        $corpo4 = $corpo4 . '
                                <td >' . $item->observacaoentrada . '</td>';
                    }
                endforeach;
                $corpo5 = '
                             <td colspan="2"><b>' . number_format($total, 2, ",", ".") . '</b></td>
                    </tr>
                <tr>
                    <td colspan="4"><b>Saldo Final</b></td>
                    <td ><b><?= number_format($totalrelatorio, 2, ",", "."); ?></b></td>
                    <td ><b></b></td>
                    <td ><b><?= number_format($total, 2, ",", "."); ?></b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3 . $corpo4 . $corpo5;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatoriomovitamentacao';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }

    private
            function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }

        if ($this->utilitario->autorizar(2, $this->session->userdata('modulo')) == true) {
            $this->load->view('header', $data);
            if ($view != null) {
                $this->load->view($view, $data);
            } else {
                $this->load->view('giah/servidor-lista', $data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login005');
            $this->load->view('header', $data);
            $this->load->view('home');
        }
        $this->load->view('footer');
    }

    function anexarimagementrada($entradas_id) {
        if (!is_dir("./upload/entrada")) {
            mkdir("./upload/entrada");
            chmod("./upload/entrada", 0777);
        }

        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/entrada/$entradas_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['entradas_id'] = $entradas_id;
        $this->loadView('cadastros/importacao-imagementrada', $data);
    }

    function importarimagementrada() {
        $entradas_id = $_POST['paciente_id'];
//        $data = $_FILES['userfile'];
//        var_dump($data);
//        die;
        if (!is_dir("./upload/entrada")) {
            mkdir("./upload/entrada");
            chmod("./upload/entrada", 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/entrada/$entradas_id")) {
                mkdir("./upload/entrada/$entradas_id");
                $destino = "./upload/entrada/$entradas_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/entrada/" . $entradas_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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

        $data['entradas_id'] = $entradas_id;
        redirect(base_url() . "cadastros/caixa/anexarimagementrada/$entradas_id/");
    }

    function anexarimagemsaida($saidas_id) {

        if (!is_dir("./upload/saida")) {
            mkdir("./upload/saida");
            chmod("./upload/saida", 0777);
        }

        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/saida/$saidas_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['saidas_id'] = $saidas_id;
        $this->loadView('cadastros/importacao-imagemsaida', $data);
    }

    function importarimagemsaida() {
        if (!is_dir("./upload/saida")) {
            mkdir("./upload/saida");
            chmod("./upload/saida", 0777);
        }

        $saidas_id = $_POST['paciente_id'];
//        $data = $_FILES['userfile'];
//        var_dump($data);
//        die;
        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            if (!is_dir("./upload/saida/$saidas_id")) {
                mkdir("./upload/saida/$saidas_id");
                $destino = "./upload/saida/$saidas_id";
                chmod($destino, 0777);
            }

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "./upload/saida/" . $saidas_id . "/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
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

        $data['saidas_id'] = $saidas_id;
        redirect(base_url() . "cadastros/caixa/anexarimagemsaida/$saidas_id");
//        $this->anexarimagemsaida($saidas_id);
    }

    function ecluirimagementrada($entradas_id, $value) {
        unlink("./upload/entrada/$entradas_id/$value");
        redirect(base_url() . "cadastros/caixa/anexarimagementrada/$entradas_id");
    }

    function ecluirimagemsaida($saidas_id, $value) {

        unlink("./upload/saida/$saidas_id/$value");
        redirect(base_url() . "cadastros/caixa/anexarimagemsaida/$saidas_id");
    }
    
    function entradaexclusao($entradas_id){
         $data['motivos'] = $this->motivocancelamento->listartodos();
         $data['lista'] = $this->caixa->dadosentrada($entradas_id);   
         $data['entradas_id'] = $entradas_id;
         $this->loadView('cadastros/entradaexclusao-form', $data);  
    } 
    
     function saidaexclusao($saidas_id){
         $data['motivos'] = $this->motivocancelamento->listartodos(); 
         $data['saidas_id'] = $saidas_id;
         $this->loadView('cadastros/saidaexclusao-form', $data);  
    } 
    
     function relatorioentradatipo() {
        $data['conta'] = $this->forma->listarformaempresa();
        $data['credordevedor'] = $this->caixa->listarcredordevedor();
        $data['tipo'] = $this->tipo->listartipo();
//        $data['empresa'] = $this->guia->listarempresas();
        $this->loadView('ambulatorio/relatorioentradatipo', $data);
    }

    function gerarelatorioentradatipo() {
        $data['txtdata_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
        $data['txtdata_fim'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        $data['credordevedor'] = $this->caixa->buscarcredordevedor($_POST['credordevedor']);
        if($_POST['tipo'] != "" && ($_POST['tipo'] != "CAIXA" && $_POST['tipo'] != "TRANSFERENCIA")){
          $data['tipo'] = $this->tipo->buscartipo($_POST['tipo']);
        }else{ 
            if($_POST['tipo'] != ""){
                $obj = new stdClass;
                $obj->descricao = $_POST['tipo'];
                $data['tipo'][]  = $obj;
            }else{ 
                $data['tipo']  = Array();  
            } 
        } 
      
        $data['classe'] = $this->classe->buscarclasserelatorio($_POST['classe']);
        $data['forma'] = $this->forma->buscarforma($_POST['conta']);
//        $data['empresa'] = $this->guia->listarempresa($_POST['empresa']);
        $data['relatorio'] = $this->caixa->relatorioentradatipo();
        $data['contador'] = count($data['relatorio']);
        
//        print_r($data['tipo']);echo "<br>";
//       
//    die("mo");
//        
        if ($_POST['email'] == "NAO") {
            $this->load->View('ambulatorio/impressaorelatorioentradatipo', $data);
        } elseif ($_POST['email'] == "SIM") {
            
            if (count($data['tipo']) > 0) {
                $tipo = "TIPO:" . $data['tipo'][0]->descricao;
            } else {
                $tipo = "TODOS OS TIPOS";
            }
            
            if (count($data['classe']) > 0) {
                $texto = strtr(strtoupper($data['classe'][0]->descricao), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $classe = "CLASSE:" . $texto;
            } else {
                $classe = "TODAS AS CLASSES";
            }
            if (count($data['forma']) > 0) {
                $forma = "CONTA:" . $data['forma'][0]->descricao;
            } else {
                $forma = "TODAS AS CONTAS";
            }
            if (count($data['credordevedor']) > 0) {
                $credordevedor = $data['credordevedor'][0]->razao_social;
            } else {
                $credordevedor = "TODOS OS CREDORES";
            }

            $cabecalho = '<div class="content"> <!-- Inicio da DIV content -->

        <h4> ' . $tipo . ' </h4>
        <h4> ' . $classe . ' </h4>
        <h4>' . $forma . '</h4>
        <h4>' . $credordevedor . '</h4>
        <h4>RELATORIO DE SAIDA</h4>
    <h4>PERIODO: ' . $data['txtdata_inicio'] . ' ate ' . $data['txtdata_fim'] . '</h4>
    <hr>';

            if (count($data['relatorio']) > 0) {

                $corpo = '
        <table border="1">
            <thead>
                <tr>
                    <th width="100px;" class="tabela_header">Conta</th>
                    <th class="tabela_header">Nome</th>
                    <th class="tabela_header">Tipo</th>
                    <th class="tabela_header">Classe</th>
                    <th class="tabela_header">Dt entrada</th>
                    <th class="tabela_header">Valor</th>

                    <th class="tabela_header">Observacao</th>
                </tr>
                </tr>
            </thead>
            <tbody>';

                $totalgeral = 0;
                $totaltipo = 0;
                $i = 0;
                $s = '';
                $corpo2 = '';
                $corpo3 = '';
                foreach ($data['relatorio'] as $item) :
                    $totalgeral = $totalgeral + $item->valor;
                    if ($i == 0 || $item->tipo == $s) {
                        $s = $item->tipo;
                        $totaltipo = $totaltipo + $item->valor;
                        $corpo2 = $corpo2 . '
                    <tr>
                        <td >' . $item->conta . '</td>
                        <td >' . $item->razao_social . '</td>
                        <td >' . $item->tipo . '</td>
                        <td >' . $item->classe . '</td>
                        <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                        <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                        <td >' . $item->observacao . '</td>
                    </tr>';
                    } else {
                        $corpo2 = $corpo2 . '
                        <tr>
                            <td colspan="5" bgcolor="#C0C0C0"><b>SUB-TOTAL</b></td>
                            <td bgcolor="#C0C0C0"><b>' . number_format($totaltipo, 2, ",", ".") . '</b></td>
                        </tr> 
                        <tr>
                            <td >' . $item->conta . '</td>
                            <td >' . $item->razao_social . '</td>
                            <td >' . $item->tipo . '</td>
                            <td >' . $item->classe . '</td>
                            <td >' . substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4) . '</td>
                            <td >' . number_format($item->valor, 2, ",", ".") . '</td>
                            <td >' . $item->observacao . '</td>
                        </tr>';
                        $s = $item->tipo;
                        $totaltipo = 0;
                        $totaltipo = $item->valor;
                    }
                    $i++;
                endforeach;
                $corpo3 = '<tr>
                    <td colspan="4" bgcolor="#C0C0C0"><b>TOTAL</b></td>
                    <td colspan="2" bgcolor="#C0C0C0"><b>' . number_format($totalgeral, 2, ",", ".") . '</b></td>
                </tr>
            </tbody>';

                $html = $cabecalho . $corpo . $corpo2 . $corpo3;
            } else {
                $corpo = '
                <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
                ';
                $html = $cabecalho . $corpo;
            }


//                    var_dump($html);
//            die;
            $tiporelatorio = 'relatoriosaidagrupo';
            $email_id = $this->caixa->gravaremailmensagem($html);
            $this->formrelatorioemail($email_id, $tiporelatorio);
        }
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
