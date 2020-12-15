<?php

class certificadoapi_model extends Model {

    function Certificadoapi_model($ambulatorio_pacientetemp_id = null) {
        parent::Model();
//        $this->load->library('utilitario');
    }

    function autenticacao($ambulatorio_laudo_id, $cod_BirdID){
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $this->db->select('ag.medico_parecer1,
                            o.nome,
                            o.cpf,
                            o.certificado_digital');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1');
        $this->db->where("ag.ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $query2 = $this->db->get();
        $value = $query2->result();

        $url = $link.'oauth/pwd_authorize';



        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                // 'client_id' => '18824545000188',
                // 'client_secret' => 'AmlldbchhREtFS',
                'client_id' => '18824545000188',
                'client_secret' => 'QRafdhheuYTREok',
                'username' => $value[0]->cpf,
                'password' => $cod_BirdID,
                'grant_type' => 'password',
                'scope' => 'signature_session',
                'lifetime' => '432000'
            ]
        ]);

    $response = curl_exec($curl);

    curl_close($curl);

        // $data = array(
        //     'client_id' => '18824545000188',
        //     'client_secret' => 'AmlldbchhREtFS',
        //     'username' => $value[0]->cpf,
        //     'password' => $value[0]->certificado_digital,
        //     'grant_type' => 'password',
        //     'scope' => 'signature_session',
        //     'lifetime' => '432000');

        // $options = array(
        // 'http' => array(
        // 'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        // 'method'  => 'POST',
        // 'content' => http_build_query($data)
        // )
        // );
        // $context  = stream_context_create($options);

        //$json_post = json_decode(file_get_contents($url, false, $context));

        return json_decode($response);
    }

    function authorize(){
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $url = $link.'oauth/authorize';

        $data = array(
            'response_type' => 'code',
            'client_id' => '18824545000188',
            'scope' => 'signature_session',
            'lifetime' => '432000');

        $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
        'content' => http_build_query($data)
        )
        );
        $context  = stream_context_create($options);

        return $novo = file_get_contents($url, false, $context);
    }


    function signature($Authorization){
        // print_r($Authorization);
        // die;
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $url = $link.'signature-service';

        $json = '{ 
            "certificate_alias": "",
            "type": "PDFSignature",
            "hash_algorithm": "SHA256",
            "auto_fix_document": true,
            "signature_settings": [{
                "id": "default",
                "contact": "123456789",
                "location": "Ceara - CE",
                "reason": "Assinatura_Documento",
                "visible_sign_page": -1,
                "visible_sign_x": 0,
                "visible_sign_y": 0,
                "visible_sign_width": 300,
                "visible_sign_height": 1620
                }],
            "documents_source": "UPLOAD_REFERENCE"
            }';

        $options = array(
            'http' => array(
             'header'  => "Authorization: Bearer ".$Authorization."\r\n".
                          "Accept: application/json\r\n".
                          "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => $json
            )
        );


        $context  = stream_context_create($options);
 
         $resposta = file_get_contents($url, null, $context);            
        return json_decode($resposta);
    }

    function filetopdf($tcn, $ambulatorio_laudo_id){
        // print_r($pdf);
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $url = $link.'file-transfer/'.$tcn.'/eot/default';

        $ch = curl_init($url);

        $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;

        curl_setopt_array($ch, [    
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [          
              'document[0]' => curl_file_create($local_salvamento.'/laudo.pdf')
            ]
        ]);
        
        $resposta = curl_exec($ch);
        curl_close($ch);

            return json_decode($resposta);
    }


    function filetopdf_2($tcn, $ambulatorio_laudo_id){
        // print_r($pdf);
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $url = $link.'file-transfer/'.$tcn.'/eot/default';

        $ch = curl_init($url);

        $local_salvamento = './upload/novoatendimento/'.$ambulatorio_laudo_id;
        
        $documentos = "";
        $totaldocumentos = count($_POST['Imprimir_Assinar']);
        $i = 0;
        $arrayteste = [];
        foreach($_POST['Imprimir_Assinar'] as $nome){
            $i++;
            if($i != $totaldocumentos){
                $a = $i - 1;
             $documentos .= "'document[$a]' => curl_file_create($local_salvamento/$nome),";
            }else{
                $a = $i - 1;
                $documentos .= "'document[$a]' => curl_file_create($local_salvamento/$nome)";
            }
                $cfile = curl_file_create($local_salvamento.'/'.$nome);
                $arrayteste['document['.$a.']'] = $cfile;

        }

        curl_setopt_array($ch, [    
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $arrayteste
        ]);
        
        $resposta = curl_exec($ch);
        curl_close($ch);

            return json_decode($resposta);
    }


    function assinatura_status($tcn){
        // print_r($pdf);
        $this->db->select('link_certificado');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $query = $this->db->get();
        $return = $query->result();
        $link = $return[0]->link_certificado;

        $url = $link.'signature-service/'.$tcn;


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
          
        return json_decode($response);
    }

}