<?php

class QualidadeAPI extends Controller {

    function QualidadeAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('qualidadeapi_model', 'qualidadeapi');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('seguranca/operador_model', 'operador_m');

        $this->load->library('utilitario');

    }

    function index(){
        echo json_encode('WebService');
    }

    function autenticador($authorization){
        // var_dump($json_post); 
        // die;
        $usuario = 'Teste';
        $senha = '2223313';
        if($authorization->user == $usuario && $authorization->password == $senha){
            return true;
        }else{
            return false;
        }

        
    }

    function getLaudos(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);

        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }
        // $table->integer('agendamento_id')->nullable();
        // $table->integer('paciente_id')->nullable();
        // $table->integer('procedimento_id')->nullable();
        // $table->integer('solicitante_id')->nullable();
        // $table->integer('radiologista_id')->nullable();
        // $table->integer('responsavel_id')->nullable();
        // $table->integer('integracoes_id')->nullable();
        // $table->text('procedimento')->nullable();
        // $table->string('nome', 300)->nullable();
        // $table->string('cpf', 14)->nullable();
        $resposta = $this->qualidadeapi->getLaudos($data);
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $array[$key] = new stdClass();
                $array[$key]->agendamento_id = $value->agenda_exames_id;
                $array[$key]->paciente_id = $value->paciente_id;
                $array[$key]->procedimento_tuss = $value->codigo;
                $array[$key]->procedimento = $value->procedimento;
                $array[$key]->nome = $value->paciente;
                $array[$key]->cpf = $value->cpf;
                $array[$key]->nascimento = $value->nascimento;
                $array[$key]->email_paciente = $value->email_paciente;
                $array[$key]->medico = $value->medico;
                $array[$key]->conselho = 'CRM 2223124';
                $array[$key]->telefone = '85 99695-4405';
                $array[$key]->email_medico = $value->email_medico;
                $array[$key]->medico_solicitante = $value->medico;
                $array[$key]->conselho_solicitante = 'CRM 2223124';
                $array[$key]->email_medico_solicitante = $value->email_medico;
                
                $array[$key]->data = $value->data;
                $array[$key]->laudo = $value->laudo;
                $array[$key]->link = "http://localhost/clinicas/qualidadeAPI/showLaudo/$value->agenda_exames_id";
                $array[$key]->link_imagens = "http://localhost/clinicas/qualidadeAPI/showImagens/$value->agenda_exames_id";
                
            }
        }
        // echo json_encode($retorno);
        // var_dump($array);
        // die;
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->result = 'OK';
            $obj->return = $array;
        }else{
            $obj->result = 'ERROR';
        }
        echo json_encode($obj); 
        die;
        
    }
    

    function impressao_laudo($ambulatorio_laudo_id, $baixar = false) {
        $this->load->plugin('mpdf');
        $empresa_id = 1;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $exame_id = $data['laudo'][0]->exame_id;
        // var_dump($data['laudo'][0]->template_obj); die;
        $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Imprimiu Laudo');
        if($data['laudo'][0]->template_obj != ''){
            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
        }
        $texto = $data['laudo'][0]->texto;
        
        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // var_dump($data['laudo'][0]->texto); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        // var_dump($data['cabecalho']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        // var_dump($data['empresa']); die;
         
        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') { 
//            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho);

            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
//            print_r($cabecalho);
//              die('morreu'); 
            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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



            $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
            // echo '<pre>';
            // echo $html;
            // die;

            if ($data['empresa'][0]->impressao_tipo == 33) {
                // ossi rezaf rop adiv ahnim oiedo uE
                // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                // açneod ?euq roP
                $html = str_replace('xx-small', '5pt', $html);
            }

            $teste_cabecalho = "$cabecalho";
 
            if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                if($baixar == true){
                    pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                }else{
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                }
                
            }else{
                if($baixar == true){
                    if ($sem_margins == 't') {
                        pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                    } else {
                        pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                    }
                }else{
                    if ($sem_margins == 't') {
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                    } else {
                        pdf($html, $filename, $cabecalho, $rodape);
                    }
                }
                

                

            }
            
            
        } 
    }

    function impressao_exames($ambulatorio_exames_id, $ambulatorio_laudo_id, $baixar = false) {
        $this->load->plugin('mpdf');
        $empresa_id = 1;
        $data['solicitacao'] = $this->laudo->listarexameimpressao($ambulatorio_exames_id);
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        // var_dump($data['cabecalho']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
       
        
        if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
            $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
        } else {
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
        $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
        $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $data['laudo'][0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos');
        $cabecalho = str_replace("_idade_", $idade, $cabecalho);

        $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
        $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

        if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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
        // var_dump($data['laudo']); die;
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        // var_dump($data['solicitacao']);
        // die;
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);
            $filename = "solicitacao$ambulatorio_exames_id.pdf";
            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);

            if($baixar == true){
                if ($sem_margins == 't') {
                    pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                } else {
                    pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                }
            }else{
                if ($sem_margins == 't') {
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                } else {
                    pdf($html, $filename, $cabecalho, $rodape);
                }
            }
        }

        return $string;
    }
    
    function impressao_receita($ambulatorio_receita_id, $ambulatorio_laudo_id, $baixar = false) {
        $this->load->plugin('mpdf');
        $empresa_id = 1;
        $data['solicitacao'] = $this->laudo->listarreceitaimpressaoatendimento($ambulatorio_receita_id);
        // var_dump($data['solicitacao']); 
        // die;
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes(1);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
       
        
        if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
            $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
        } else {
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
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
        $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
        $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
        $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $data['laudo'][0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos');
        $cabecalho = str_replace("_idade_", $idade, $cabecalho);

        $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
        $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

        if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
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
        // var_dump($data['laudo']); die;
        if (count($data['solicitacao']) == 0) {
            return '';
        }
        // var_dump($data['solicitacao']);
        // die;
        $data['medico'] = $this->operador_m->medicoreceituario($data['solicitacao'][0]->medico_parecer1);

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;

        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['solicitacao'][0]->data_cadastro, 8, 2);
        $mes = substr($data['solicitacao'][0]->data_cadastro, 5, 2);
        $ano = substr($data['solicitacao'][0]->data_cadastro, 0, 4);
        $nomemes = $meses[$mes];
        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $string = '';
        if (file_exists("upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
            $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['solicitacao'][0]->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
            $data['assinatura'] = "";
        }


        foreach ($data['solicitacao'] as $key => $value) {
            $value->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $value->texto);
            $filename = "receita$ambulatorio_receita_id.pdf";
            // Por causa do Tinymce Novo.
            $data['laudo'] = array($value);
            $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
            $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);

            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);

            if($baixar == true){
                if ($sem_margins == 't') {
                    pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                } else {
                    pdfTuoTempoAPI($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                }
            }else{
                if ($sem_margins == 't') {
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                } else {
                    pdf($html, $filename, $cabecalho, $rodape);
                }
            }
        }

        return $string;
    }
    

    function templateParaTexto($json_obj) {
        $array_obj = json_decode($json_obj);
        $string = '';
        // echo '<pre>';
        // var_dump($array_obj); die;
        foreach ($array_obj as $key => $value) {
            if ($value->tipo == 'checkbox') {
                if ($value->value != '') {
                    $string_value = 'Verdadeiro';
                } else {
                    $string_value = 'Falso';
                }
            } elseif ($value->tipo == 'multiplo') {
                $string_array = '';
                foreach ($value->value as $key => $item) {
                    $string_array .= " $item,";
                }
                $string_value = $string_array;
            } else {
                $string_value = $value->value;
            }
            $string .= "<p style='font-weight: bold'>{$value->nome}</p>
            <p>{$string_value}</p>";
        }
        // echo '<pre>';
        // var_dump($string); die;
        return $string;
    }

    function adicionalcabecalho($cabecalho, $laudo) {
        $ano = 0;
        $mes = 0;
//        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", $laudo['0']->convenio, $cabecalho);
        $cabecalho = str_replace("_sala_", $laudo['0']->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($laudo[0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $laudo['0']->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $laudo['0']->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $laudo['0']->procedimento, $cabecalho);
        $cabecalho = str_replace("_laudo_", $laudo['0']->texto, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_peso_", $laudo['0']->peso, $cabecalho);
        $cabecalho = str_replace("_altura_", $laudo['0']->altura, $cabecalho);
        $cabecalho = str_replace("_cid1_", $laudo['0']->cid1, $cabecalho);
        $cabecalho = str_replace("_cid2_", $laudo['0']->cid2, $cabecalho);
        $cabecalho = str_replace("_guia_", $laudo[0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $laudo[0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $laudo[0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $laudo[0]->grupo, $cabecalho);
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $laudo[0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos'); 
     
        $ano = $diff2->format('%Y'); 
        $mes = $diff2->format('%m');  
        
        if ($ano > 1) {
           $s = "s"; 
        }else{
           $s = ""; 
        }
        
        if ($ano == 0) { 
             if ($mes > 1) {
                    $sm = "es";
                }else{
                    $sm = "";
                } 
            $ano_formado = $mes." mes".$sm;   
        }else{
            if ($mes > 0) { 
                if ($mes > 1) {
                    $sm = " meses";
                }else{
                    $sm = " mês";
                }
                  $ano_formado = $ano." ano".$s." e ".$mes.$sm; 
            }else{
                  $ano_formado = $ano." ano".$s;  
            }
        } 
        
        $cabecalho = str_replace("_idade_", $ano_formado, $cabecalho);
          
        return $cabecalho;
    }
     
}

     
