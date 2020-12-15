<?php

class TuoTempoAPI extends Controller {

    function TuoTempoAPI() {

        parent::Controller();
        $this->load->model('login_model', 'login_m');
        $this->load->model('tuotempo_model', 'tuotempo');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('cadastro/paciente_model', 'paciente_m');
        $this->load->library('utilitario');

    }

    function index(){
        echo json_encode('WebService');
    }

    function autenticador($authorization){
        // var_dump($json_post); 
        // die;
        $usuario = 'gGKWlHHtIV';
        $senha = 'H0vDHauMdt';
        if($authorization->user == $usuario && $authorization->password == $senha){
            return true;
        }else{
            return false;
        }

        
    }

    function getLocations(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
        $ID = $data->LOCATION_LID;

        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }

        $resposta = $this->tuotempo->listarEmpresa($ID);
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $array[$key] = new stdClass();
                $array[$key]->LOCATION_LID = $value->id;
                $array[$key]->NAME = $value->nome;
                $array[$key]->ADDRESS = $value->endereco;
                $array[$key]->ZIP_CODE = $value->cep;
                $array[$key]->CITY = $value->municipio;
                // $retorno->PROVINCE = $resposta[0]->bairro;
                // $retorno->REGION = $resposta[0]->estado;
                $array[$key]->COUNTRY = 'BRASIL';
                $array[$key]->PHONE = $value->telefone_01;
                $array[$key]->EMAIL = $value->email;
                $array[$key]->WEB_ENABLED = 1;
            }
        }
        // echo json_encode($retorno);
        // var_dump($retorno); 
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
    
    function getResources(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
        $empresa_id = $data->LOCATION_LID;
        $convenio_id = $data->INSURANCE_LID;

        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }

        $resposta = $this->tuotempo->listarMedicos($empresa_id, $convenio_id);
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $nome = explode(' ', trim($value->nome));
                
                $array[$key] = new stdClass();
                $array[$key]->RESOURCE_LID = $value->id;
                $array[$key]->FIRST_NAME = $nome[0];
                $array[$key]->SECOND_NAME = '';
                if(count($nome) > 1){
                    $array[$key]->SECOND_NAME = str_replace($nome[0], '', $value->nome);
                }
                
                $array[$key]->MOBILE_PHONE = $value->telefone;
                $array[$key]->EMAIL = $value->email;
                $array[$key]->ID_NUMBER = $value->cpf;

            }
            
        }
        // var_dump($array); 
        // echo json_encode($array);
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

    function getActivities(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
        $medico_id = $data->RESOURCE_LID;
        $convenio_id = $data->INSURANCE_LID;
        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }
        $array = array();
        $resposta = $this->tuotempo->listarProcedimentoConvenioMedico($medico_id, $convenio_id);
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                
                $array[$key] = new stdClass();
                $array[$key]->ACTIVITY_LID = $value->procedimento_convenio_id;
                $array[$key]->NAME = $value->procedimento;
                if($value->subgrupo != ''){
                    $array[$key]->GROUP_NAME = $value->subgrupo;
                    $array[$key]->GROUP_LID = $value->subgrupo_id;
                }else{
                    $array[$key]->GROUP_NAME = $value->grupo;
                    $array[$key]->GROUP_LID = $value->ambulatorio_grupo_id;
                }
                $array[$key]->PRICE = $value->valortotal;

            }
        }
        
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
        echo json_encode($obj); 
        die;
        
        
    }

    function getInsurances(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
        $medico_id = $data->RESOURCE_LID;
        $procedimento_id = $data->ACTIVITY_LID;
        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }
        $array = array();
        $resposta = $this->tuotempo->listarConvenioMedico($procedimento_id, $medico_id);
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                
                $array[$key] = new stdClass();
                $array[$key]->INSURANCE_LID = $value->convenio_id;
                $array[$key]->INSURANCE_NAME = $value->convenio;

            }
        }
        
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

    function getAvailabilities(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
        // echo '<pre>';
        // var_dump($json_post); 
        // die;
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
        $convenio_id = $data->INSURANCE_LID;
        $procedimento_tuss_id = $data->ACTIVITY_LID;

        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die();
        }
        $array = array();
        $resposta = $this->tuotempo->listarAgendamento($data);
 //        var_dump(count($resposta)); die;
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) { 
                $array[$key] = new stdClass();
                $array[$key]->AVAILABILITY_LID = $value->agenda_exames_id;
                $array[$key]->RESOURCE_LID = $value->medico_id;
                if(isset($value->procedimento_convenio_id)){
                 $array[$key]->ACTIVITY_LID = $value->procedimento_convenio_id;
                }else{
                 $array[$key]->ACTIVITY_LID = "";    
                }
                $array[$key]->AVA_DATE = date("d/m/Y",strtotime($value->data));
                $array[$key]->AVA_START_TIME = date("H:i",strtotime($value->inicio));
                $array[$key]->AVA_END_TIME = date("H:i",strtotime($value->fim));
                $array[$key]->LOCATION_LID =  $value->empresa_id;
                if(isset($value->convenio_id)){
                $array[$key]->INSURANCE_LID =  $value->convenio_id;
                }else{
                $array[$key]->INSURANCE_LID =  "";   
                }

            }
        }
        
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
        echo json_encode($obj); 
        die;
    }

    function addAppointment(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: content-type");
        $json_post = json_decode(file_get_contents("php://input"));
          
        $authorization = $json_post->authorization;
        $data = $json_post->data;
        $autenticador = $this->autenticador($authorization);
           
        $retorno = new stdClass();

        if(!$autenticador){
            $retorno->error = 'Erro na autenticação';
            echo json_encode($retorno);
            die;
        }
        if(!$data->USER_LID > 0){
            $paciente_id = $this->tuotempo->gravarPacienteApp($data);
            $data->USER_LID = $paciente_id;
        } 
        
        $resultadoQuantidade =  $this->verificarquantidademedico($data);
        $resQuantidade = json_decode($resultadoQuantidade,true);
        if($resQuantidade['id'] == "-1"){ 
             $obj = new stdClass(); 
             $obj->result = 'ERROR';
             $obj->message = $resQuantidade['mensagem']; 
             echo json_encode($obj); 
             die();
        }   
        $resultadoFaixetaria =  $this->buscarfaixaetaria($data);
        $resFaixetaria = json_decode($resultadoFaixetaria,true);
        if($resFaixetaria['id'] == "-1"){ 
             $obj = new stdClass(); 
             $obj->result = 'ERROR';
             $obj->message = $resFaixetaria['mensagem']; 
             echo json_encode($obj); 
             die();
        }   
        $verificarProcedimentoMedico = $this->tuotempo->listarprocedimentomedico($data); 
        if(count($verificarProcedimentoMedico) == 0){
             $obj = new stdClass(); 
             $obj->result = 'ERROR';
             $obj->message = "Procedimento não disponível para esse médico na empresa selecionada"; 
             echo json_encode($obj); 
             die();
        } 
         
        // var_dump($data->USER_LID); die;
        $resposta = $this->tuotempo->agendarPaciente($data);
        // var_dump($resposta); die;
         
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->result = 'OK';
            $obj->return = $resposta;
        }else{
            $obj->result = 'ERROR';
            $obj->message = 'Horário indisponível';
        }
        echo json_encode($obj); 
        die;
    }

    function cancelAppointment(){
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
        $resposta = $this->tuotempo->deletarAgendarPaciente($data);
        // var_dump($resposta); die;
        
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->DELETE_RESULT = 'OK';
            $obj->ERROR_MESSAGE = '';
            $obj->APP_LID = $data->APP_LID;
        }else{
            $obj->DELETE_RESULT = 'ERROR';
            $obj->ERROR_MESSAGE = 'Horário não encontrado ou já autorizado';
            $obj->APP_LID = $data->APP_LID;
        }
        echo json_encode($obj); 
        die;
    }

    function getAppointments(){
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
        $resposta = $this->tuotempo->listarAgendamentosPaciente($data);
        // var_dump($resposta); die;
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $nome = explode(' ', trim($value->paciente));
                
                $array[$key] = new stdClass();
                $array[$key]->APP_LID = $value->agenda_exames_id;
                if($value->confirmado == 't' && $value->realizada == 't'){
                    $array[$key]->STATUS = 'Confirmed';
                }else if($value->confirmado == 'f' && $value->realizada == 'f'){
                    $array[$key]->STATUS = 'NotAttended';
                }
                $array[$key]->APP_DATE = date("d/m/Y",strtotime($value->data));
                $array[$key]->START_TIME = date("H:i",strtotime($value->inicio));
                $array[$key]->END_TIME = date("H:i",strtotime($value->fim));
                $array[$key]->CHECKEDIN = '';
                if($value->data_autorizacao != ''){
                    $array[$key]->CHECKEDIN = date("d/m/Y H:i",strtotime($value->data_autorizacao));
                }
                $array[$key]->USER_LID = $value->paciente_id;
                $array[$key]->USER_FIRST_NAME = $nome[0];
                $array[$key]->USER_SECOND_NAME = '';
                if(count($nome) > 1){
                    $array[$key]->USER_SECOND_NAME = $nome[1];
                }
                if($value->nascimento != ''){
                    $array[$key]->USER_DATE_OF_BIRTH = date("d/m/Y",strtotime($value->nascimento));
                }else{
                    $array[$key]->USER_DATE_OF_BIRTH = '';
                }
                
                $array[$key]->USER_ID_NUMBER = $value->cpf;
                $array[$key]->USER_GENDER = $value->sexo;
                $array[$key]->USER_ZIP_CODE = $value->cep;
                $array[$key]->USER_MOBILE_PHONE = $value->celular;
                $array[$key]->USER_EMAIL = $value->email;
                $array[$key]->ACTIVITY_LID = $value->procedimento_convenio_id;
                $array[$key]->ACTIVITY_NAME = $value->procedimento;
                $array[$key]->ACTIVITY_GROUP_LID = $value->ambulatorio_grupo_id;
                $array[$key]->ACTIVITY_GROUP_NAME = $value->grupo;
                $array[$key]->RESOURCE_LID = $value->medico_id;
                $array[$key]->RESOURCE_NAME = $value->medico;

            }
        }
        
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
        echo json_encode($obj); 
        die;
    }

    function notifyPayment(){
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
        // var_dump($data->USER_LID); die;
        $resposta = $this->tuotempo->pagarAgendamento($data);
        // var_dump($resposta); die;
        
        $obj = new stdClass();
        if(count($resposta) > 0){
            $obj->result = 'OK';
            $obj->return = $resposta;
        }else{
            $obj->result = 'ERROR';
            $obj->message = 'Horário indisponível';
        }
        echo json_encode($obj); 
        die;
    }

    function getEpisodes(){
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
        $resposta = $this->tuotempo->listarAtendimentosPaciente($data);
        // var_dump($resposta); die;
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $nome = explode(' ', trim($value->paciente));
                
                $array[$key] = new stdClass();
                $array[$key]->EPISODE_LID = $value->laudo_id;
                $array[$key]->CATEGORY = $value->grupo;
                $array[$key]->STATUS = 'Valid';
                $array[$key]->DATE = date("d/m/Y",strtotime($value->data));
                $array[$key]->TITLE = $value->procedimento;
                $array[$key]->DESCRIPTION = $value->descricao;
                
                $array[$key]->USER_LID = $value->paciente_id;
                $array[$key]->USER_FIRST_NAME = $nome[0];
                $array[$key]->USER_SECOND_NAME = '';
                if(count($nome) > 1){
                    $array[$key]->USER_SECOND_NAME = $nome[count($nome) -1];
                }
                if($value->nascimento != ''){
                    $array[$key]->USER_DATE_OF_BIRTH = date("d/m/Y",strtotime($value->nascimento));
                }else{
                    $array[$key]->USER_DATE_OF_BIRTH = '';
                }
                $array[$key]->USER_ID_NUMBER = $value->cpf;
                $array[$key]->USER_GENDER = $value->sexo;
                $array[$key]->USER_ZIP_CODE = $value->cep;
                $array[$key]->USER_MOBILE_PHONE = $value->celular;
                $array[$key]->USER_EMAIL = $value->email;
            }
        }
        // var_dump($array); die;
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
        echo json_encode($obj); 
        die;
    }

    function getEpisodeDocuments(){
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
        $resposta = $this->tuotempo->listarLaudoPaciente($data);
        // var_dump($resposta); die;
        $array = array();
        if(count($resposta) > 0){
            $contador = 0;
            foreach ($resposta as $key => $value) {
                
                $array[$contador] = new stdClass();
                $array[$contador]->DOCUMENT_LID = $value->laudo_id;
                $array[$contador]->EPISODE_LID = $value->laudo_id;
                $array[$contador]->STATUS = 'Valid';
                $array[$contador]->CATEGORY = 'Laudo';
                $array[$contador]->URL = base_url() . 'tuoTempoAPI/impressao_laudo/' . $value->laudo_id;
                $array[$contador]->ERROR_MESSAGE = '';
                if ($data->INCLUDE_CONTENT == 'true') {
                    $array[$contador]->FILENAME = 'laudo.pdf';
                    $array[$contador]->TYPE = '.pdf';
                    $array[$contador]->ISSUED = date("d/m/Y H:i", strtotime($value->data_cadastro));
                    $this->impressao_laudo($value->laudo_id, true);
                    $array[$key]->CONTENT = base64_encode(file_get_contents(base_url(). "upload/tuoTempo/$value->laudo_id/laudo.pdf"));
                }   
                $contador++;
                // Solicitacao de Exame
                $solicitacao = $this->laudo->listarexameimpressaotodos($value->laudo_id);
                // echo '<pre>';
                // var_dump($solicitacao); die;
                foreach ($solicitacao as $key => $item) {

                    $array[$contador] = new stdClass();
                    $array[$contador]->DOCUMENT_LID = $item->ambulatorio_exame_id;
                    $array[$contador]->EPISODE_LID = $value->laudo_id;
                    $array[$contador]->STATUS = 'Valid';
                    $array[$contador]->CATEGORY = 'Solicitação de Exames';
                    $array[$contador]->URL = base_url() . 'tuoTempoAPI/impressao_exames/' . $item->ambulatorio_exame_id . '/' . $value->laudo_id;
                    $array[$contador]->ERROR_MESSAGE = '';
                    if ($data->INCLUDE_CONTENT == 'true') {
                        $array[$contador]->FILENAME = 'solicitacao' . $item->ambulatorio_exame_id . '.pdf';
                        $array[$contador]->TYPE = '.pdf';
                        $array[$contador]->ISSUED = date("d/m/Y H:i", strtotime($item->data_cadastro));
                        $this->impressao_exames($item->ambulatorio_exame_id, $value->laudo_id, true);
                        $array[$key]->CONTENT = base64_encode(file_get_contents(base_url(). "upload/tuoTempo/$value->laudo_id/solicitacao$item->ambulatorio_exame_id.pdf"));
                    } 
                    $contador++; 
                }
                
                // Receita
                $receita = $this->laudo->listarreceitaimpressaotodos($value->laudo_id);
                // echo '<pre>';
                // var_dump($receita); 
                // die;
                foreach ($receita as $key => $item) {

                    $array[$contador] = new stdClass();
                    $array[$contador]->DOCUMENT_LID = $item->ambulatorio_receituario_id;
                    $array[$contador]->EPISODE_LID = $value->laudo_id;
                    $array[$contador]->STATUS = 'Valid';
                    $array[$contador]->CATEGORY = 'Receituário';
                    $array[$contador]->URL = base_url() . 'tuoTempoAPI/impressao_receita/' . $item->ambulatorio_receituario_id . '/' . $value->laudo_id;
                    $array[$contador]->ERROR_MESSAGE = '';
                    if ($data->INCLUDE_CONTENT == 'true') {
                        $array[$contador]->FILENAME = 'receita' . $item->ambulatorio_receituario_id . '.pdf';
                        $array[$contador]->TYPE = '.pdf';
                        $array[$contador]->ISSUED = date("d/m/Y H:i", strtotime($item->data_cadastro));
                        $this->impressao_receita($item->ambulatorio_receituario_id, $value->laudo_id, true);
                        $array[$key]->CONTENT = base64_encode(file_get_contents(base_url(). "upload/tuoTempo/$value->laudo_id/receita$item->ambulatorio_receituario_id.pdf"));
                    } 
                    $contador++; 
                }
                 
                // Solicitacao de Receita 

            }
        }
        // var_dump($array); die;
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
        echo json_encode($obj); 
        die;
    }

    function getUsers(){
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
        $resposta = $this->tuotempo->listarPacientes($data);
        // var_dump($resposta); die;
        $array = array();
        if(count($resposta) > 0){
            foreach ($resposta as $key => $value) {
                $nome = explode(' ', trim($value->nome));
                $array[$key] = new stdClass();
                $array[$key]->USER_LID = $value->paciente_id;
                $array[$key]->FIRST_NAME = $nome[0];
                $array[$key]->SECOND_NAME = '';
                $array[$key]->THIRD_NAME = '';
                if(count($nome) > 1){
                    $array[$key]->SECOND_NAME = $nome[count($nome) -1];
                }
                if(count($nome) > 2){
                    $array[$key]->SECOND_NAME = $nome[count($nome) - 2];
                    $array[$key]->THIRD_NAME = $nome[count($nome) -1];
                }
                $array[$key]->DATE_OF_BIRTH = date("d/m/Y",strtotime($value->nascimento));
                $array[$key]->ID_NUMBER = $value->cpf;
                $array[$key]->GENDER = $value->sexo;
                $array[$key]->ZIP_CODE = $value->cep;
                $array[$key]->MOBILE_PHONE = $value->celular;
                $array[$key]->EMAIL = $value->cns;
            }
        }
        
        $obj = new stdClass();
        $obj->result = 'OK';
        $obj->return = $array;
        
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
    
    
    function verificarquantidademedico($data){
         
        $agenda_exames_id = $data->AVAILABILITY_LID;
        $Agenda = $this->tuotempo->listarAgenda($agenda_exames_id);
        $empresa_id = $Agenda[0]->empresa_id;
        $procedimento_convenio_id = $data->ACTIVITY_LID;
        $medico_id = $Agenda[0]->medico_agenda;
        $inicio = $Agenda[0]->inicio;
        $data = $Agenda[0]->data; 
          
        $procedimentos = $this->procedimentoplano->procedimentoplanolog($procedimento_convenio_id);
        $procedimento_tuss_id = $procedimentos[0]->procedimento_tuss_id; 
                    
        $re =  $this->exametemp->verificarlimiteprocedimento($procedimento_tuss_id, $medico_id,$empresa_id);
                    
        $turno = ""; 
        if(strtotime($inicio) >= strtotime('08:00:00') && strtotime($inicio) <=  strtotime('12:00:00') ){
              $turno = "manha";
        }elseif(strtotime($inicio) >= strtotime('13:00:00') && strtotime($inicio) <= strtotime('19:00:01')){
              $turno = "tarde";
        }else{
              $turno = "noite";
        }   
       
        $retornos = $this->exame->listarexameshorarioretorno($data,$medico_id,$procedimento_tuss_id,$empresa_id,$turno);
        $id = 0;       
        if(count($re) > 0){       
               if(count($retornos) >= $re[0]->quantidade && $re[0]->quantidade > 0){
                  $mensagem = "O profissional selecionado ja atingiu o limite estabelecido para o dia:\n\n\n";
                  $mensagem .= "Procedimento: ".$re[0]->procedimento."\n\n\n";
                  $mensagem .= "Empresa: ".$re[0]->empresa."\n\n\n";
                  $mensagem .= "Quantidade limite: ".$re[0]->quantidade."\n\n";
                  $id = -1;
               }else{
                  $mensagem = "";  
               }
        }else{
              $mensagem = "";  
        }
         
        $Array = Array("mensagem"=> $mensagem,"id"=>$id);
        
        return json_encode($Array);   
        
    }
    
    function buscarfaixaetaria($data){  
         $agenda_exames_id = $data->AVAILABILITY_LID;
         $paciente_id = $data->USER_LID;
         $Agenda = $this->tuotempo->listarAgenda($agenda_exames_id);
         $medico_id = $Agenda[0]->medico_agenda;
         $empresa_id = $Agenda[0]->empresa_id; 
      
         $res = $this->paciente_m->pacientelog($paciente_id);
         $faixa = $this->operador_m->listaroperador($medico_id);
         $empresa = $this->operador_m->listarempresaconvenioorigem($empresa_id);  
          
         $faixa_inicial = $faixa[0]->faixa_etaria;
         $faixa_final = $faixa[0]->faixa_etaria_final;  
         $dataFuturo2 = date("Y-m-d");
         $dataAtual2 = $res[0]->nascimento;
         $date_time2 = new DateTime($dataAtual2);
         $diff2 = $date_time2->diff(new DateTime($dataFuturo2)); 
         $teste2 = $diff2->format('%Y'); 
         $id = 0;
          
         if($faixa_final > 0 && $faixa_inicial > 0){
            if($teste2 <= $faixa_final && $teste2 >= $faixa_inicial){
               $mensagem = "";  
            }else{
               $mensagem = "Profissional só atende Idade nessa Faixa Etária\n";  
               $mensagem .=  $faixa_inicial." à ".$faixa_final." Anos\n" ;  
               $mensagem .=  "Empresa: ".$empresa[0]->nome; 
               $id = -1;
            }
         }else{
               $mensagem = ""; 
         }  
       
        $Array = Array("mensagem"=> $mensagem,"id"=>$id);
        
        return json_encode($Array);  
        
    }
     
}

     
