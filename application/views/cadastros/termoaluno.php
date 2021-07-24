<html>
    <head>
        <title>Termo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            @media print {

                td{
                    font-family: arial;
                }
                .fundo{
                    background-color: #17365d;
                }
                .fundo2{
                    background-color: #dbe5f1;
                    font-weight: bold;  
                }
                .textobranco{
                    color:white;
                    font-weight: bold;  
                    text-align: center;
                    font-size: 11px;
                }
                .tamanho1{
                    font-size: 12px;
                }
                
                #tabela1, td {
                     border: 1px solid black;
                     border-spacing: 0px;
                     padding: 0px;
                 }
                 
                 #negrito{
                     font-weight: bold;
                     /*font-size: 10px;*/
                 }
                 
                 td{
                     padding: 2px;
                 }
                 
                 .tamanhopadrao{ 
                    font-size: 10px; 
                 }  
                
            }
            
        </style>
    </head>
    <body>
        <table id="tabela1" width="100%">
<!--            <tr>
                <td colspan="4"><img width="90%;" src="<?= base_url(); ?>img/termoEstagio.jpeg"></td> 
            </tr>-->
            <tr class="fundo" >
                <td class="textobranco" width="280px;">Dados da Instituição Concedente</td>  
                <td class="textobranco"  width="280px;">Dados da Instituição Conveniada</td>  
                <td class="textobranco" colspan="2">Dados do Aluno(a)</td>   
            </tr>
            <tr class="fundo2">
                <td id="negrito" class="tamanhopadrao">Razão Social:</td>
                <td id="negrito" class="tamanhopadrao">Razão Social</td>
                <td  id="negrito" class="tamanhopadrao" colspan="2">Nome Completo</td>
            </tr>
            <tr >
                <td class="tamanhopadrao"><?= $instituicao[0]->representante; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->intituição; ?></td>
                <td class="tamanhopadrao" colspan="2"><?= $instituicao[0]->paciente; ?></td>
            </tr>
            <tr class="fundo2">
                <td id="negrito" class="tamanhopadrao">CNPJ</td>
                <td id="negrito" class="tamanhopadrao">CNPJ</td>
                <td id="negrito" class="tamanhopadrao">RG</td>
                <td id="negrito" class="tamanhopadrao">CPF</td>
            </tr>
             <tr>
                <td class="tamanhopadrao"></td>
                <td class="tamanhopadrao"><? 
                $cpf_cnpj = $instituicao[0]->cnpjempresa; 
                $bloco_1 = substr($cpf_cnpj,0,2);
                $bloco_2 = substr($cpf_cnpj,2,3);
                $bloco_3 = substr($cpf_cnpj,5,3);
                $bloco_4 = substr($cpf_cnpj,8,4);
                $digito_verificador = substr($cpf_cnpj,-2);
                echo $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador; 
                ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->rg; ?></td>
                <td class="tamanhopadrao"><?  
                    $nbr_cpf = $instituicao[0]->cpf;
                    $parte_um     = substr($nbr_cpf, 0, 3);
                    $parte_dois   = substr($nbr_cpf, 3, 3);
                    $parte_tres   = substr($nbr_cpf, 6, 3);
                    $parte_quatro = substr($nbr_cpf, 9, 2);

                    $monta_cpf = "$parte_um.$parte_dois.$parte_tres-$parte_quatro"; 
                    echo $monta_cpf; 
                    ?></td>
            </tr>
            <tr class="fundo2">
                <td id="negrito" class="tamanhopadrao">Representante Institucional</td>
                <td id="negrito" class="tamanhopadrao">Representante Institucional</td>
                <td id="negrito" class="tamanhopadrao">Curso</td>
                <td id="negrito" class="tamanhopadrao">Matrícula</td>
            </tr>
            <tr>
                <td class="tamanhopadrao"></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->representante_unidade; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->curso; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->matricula; ?></td>
            </tr> 
            <tr class="fundo2 tamanho1">
                <td id="negrito" class="tamanhopadrao">Cargo do(a) Representante Institucional</td>
                <td id="negrito" class="tamanhopadrao">Cargo do(a) Representante Institucional</td> 
                <td rowspan="6" colspan="2" style="background-color: white;"></td>
            </tr>
            <tr>
                <td class="tamanhopadrao"><?= $instituicao[0]->cargo; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->representante_unidade_cargo; ?></td> 
            </tr>
            <tr class="fundo2 tamanho1">
                <td id="negrito" class="tamanhopadrao">Telefone  institucional relativo ao estágio</td>
                <td id="negrito" class="tamanhopadrao">Telefone institucional relativo ao estágio/estagiário</td> 
            </tr>
            <tr>
                <td class="tamanhopadrao"><?= $instituicao[0]->telefone_ifj; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->telefone_instituicao; ?></td> 
            </tr>
            <tr class="fundo2 tamanho1">
                <td id="negrito" class="tamanhopadrao">Email institucional relativo ao estágio</td>
                <td id="negrito" class="tamanhopadrao">Email institucional relativo ao estágio/estagiário</td> 
            </tr>
            <tr>
                <td class="tamanhopadrao"><?= $instituicao[0]->email_representante; ?></td>
                <td class="tamanhopadrao"><?= $instituicao[0]->email_instituicao; ?></td> 
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">Celebram entre si o presente <b>TERMO DE COMPROMISSO DE ESTÁGIO</b>, de acordo com o estabelecido na Lei Federal nº 11.788, de 25 de setembro de 2008 e no Termo de Convênio já firmado entre a CONCEDENTE e a CONVENIADA e, ainda, obedecendo às condições e cláusulas a seguir especificadas:</td>
            </tr>
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 1ª – Da natureza do estágio:</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">Este Termo de Compromisso tem como objetivo a realização de estágio curricular obrigatório da disciplina, do curso de graduação em <?= $instituicao[0]->curso; ?></td>
            </tr>
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 2ª – Da duração e carga horária do estágio:</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">O estágio terá início em <b><?= date('d/m/Y',strtotime($instituicao[0]->data_inicio)); ?></b> e término em <b><?= date('d/m/Y',strtotime($instituicao[0]->data_final)); ?></b> e será cumprido de acordo com o calendário abaixo discriminado, estando o mesmo sujeito a ajustes entre Estagiário e Supervisor de Estágio, desde que respeitados o Plano de Atividades do Estagiário e a carga-horária semanal máxima de quarenta horas de efetivo trabalho (descontados os períodos de intervalo e descanso):</td>
            </tr>
            <tr  align="top">
                <td class="tamanhopadrao" colspan="4" style="height: 520px;"  valign="top"> 
                  <table style="text-align: center;"> 
                        <tr>
                        <?php
                        $i= 0;
                        foreach($cargahoraria as $carga){ 
                            $i++;?>   
                            <td style="border: 0px solid white; font-size: 9px;">
                                  <b>
                                        <?php 
                                        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                        date_default_timezone_set('America/Sao_Paulo'); 
                                        $dia = ""; 
                                        switch (strftime('%A', strtotime($carga->data))) {
                                            case "segunda":
                                                $dia = "Seg";
                                                break;
                                            case "terça":
                                                $dia = "Ter";
                                                break;
                                            case "quarta":
                                                $dia = "Qua";
                                                break;
                                            case "quinta":
                                                $dia = "Qui";
                                                break;
                                            case "sexta":
                                                $dia = "Sex";
                                                break;
                                            case "sábado":
                                                $dia = "Sáb";
                                                break;
                                            case "domingo":
                                                $dia = "Dom";
                                                break;
                                        }   
                                        ?>
                                    <?= date('d/m',strtotime($carga->data))." - ".$dia?> 
                                      <br><?= $carga->horario_inicial?> as <?= $carga->horario_final?></b>
                                </td>  
                        <?php
                        if($i == 9){
                            echo "<tr>&nbsp;</tr>";
                            $i = 0;
                        }  
                       }?>  
                           </tr>
                    </table>
                </td>
            </tr>  
        </table>
         
        <div style='page-break-before: always;'></div>
        <table id="tabela1">
            
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 3ª – Do Plano de Atividades do Estagiário:</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">As atividades desenvolvidas pelo(a) Estagiário(a) deverão ser compatíveis com sua área de formação e com o Plano de Atividades do Estagiário:</td>
            </tr>
             <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Atividades propostas</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4" style="height:370px;" valign="top" >
                   <?= $instituicao[0]->objetivo;  ?>
                </td>
            </tr>
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 4ª – Das Competências da Unidade Concedente:</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">
                    <b>a)</b> Ofertar instalações satisfatórias para a realização do Plano de Atividades do Estagiário; <br>
                    <b>b)</b> Designar como Supervisor(es) deste estágio o(s) profissional(is) de nosso quadro pessoal abaixo nomeado(s), com formação e experiência profissional na área de conhecimento desenvolvida no curso de <?= $instituicao[0]->curso; ?>, com o objetivo de acompanhar o Plano de Atividades do Estagiário;<br>
                    <b>c)</b> Definir, juntamente com a Instituição de Ensino Conveniada, as tarefas que serão programadas e executadas pelo (a) Estagiário (a), constantes e integrantes do Plano de Atividades do Estagiário;<br>
                    <b>d)</b> Enviar à instituição de ensino, com periodicidade mínima de 6 (seis) meses, Relatório de Atividades do Estagiário, com vista obrigatória  ao (a) Estagiário (a).<br>
                </td>
            </tr>
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 5ª – Das Competências da Unidade Conveniada:</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">
                    <b>a)</b> Verificar as condições de adequação do estágio à proposta pedagógica do curso, à etapa e modalidade da formação escolar do estudante e ao horário e calendário escolar;<br>
                    <b>b)</b> Verificar as instalações da Instituição Concedente do estágio e sua adequação à formação cultural e profissional do educando;<br>
                    <b>c)</b> Preparar em nível preliminar os educandos para o estágio;<br>
                    <b>d)</b> Designar o Professor Orientador abaixo nomeado, a quem caberá o acompanhamento, orientação e avaliação do(a) Estagiário(a) , bem como a articulação com o Supervisor de estágio da unidade concedente;<br>
                    <b>e)</b> Manter atualizadas os documentos e informações cadastrais relativas à Unidade Conveniada e ao (a) Estagiário (a);<br>
                    <b>f)</b> Zelar pelo cumprimento do termo de compromisso, reorientando o (a) Estagiário (a) para outro local em caso de descumprimento de suas normas;<br>
                    <b>g)</b> Elaborar normas complementares e instrumentos de avaliação dos estágios de seus educandos;<br>
                    <b>h)</b> Contratar em favor do (a) Estagiário (a), seguro de acidentes pessoais, de acordo com o parágrafo único do Artigo 9º da Lei Federal nº 11.788, de 25 de setembro de 2008: Vigência do Seguro: de <?= date('d/m/Y',strtotime($instituicao[0]->data_inicial_vigencia)); ?>
                    até <?= date('d/m/Y',strtotime($instituicao[0]->data_final_vigencia)); ?> Apólice do Seguro Nº: <?= $instituicao[0]->num_apolice; ?>. Empresa Seguradora: <?= $instituicao[0]->seguradora; ?>
                
                </td>
            </tr>
            <tr class="fundo2">
                <td class="tamanhopadrao" colspan="4"><b>Cláusula 6ª – Das Competências do (a) Estagiário (a):</b></td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="4">
                  <b>a)</b> Obedecer as normas internas da Unidade Concedente;<br>
                  <b>b)</b> Responder por perdas e danos conseqüentes da inobservância das normas internas da Unidade Concedente ou das constantes deste Termo de Compromisso, seja por dolo ou culpa;<br>
                  <b>c)</b> Seguir a orientação do Supervisor de estágio e do Professor orientador, designados pelas Unidade Concedente e Unidade Conveniada;<br>
                  <b>d)</b> Realizar as tarefas previstas no Plano de Atividades do Estagiário e, na impossibilidade eventual do cumprimento de algum item dessa programação, comunicar, por escrito, ao Supervisor de estágio da Unidade Concedente;<br>
                  <b>e)</b> Apresentar os relatórios que lhes forem solicitados pela Unidade Concedente e pela Instituição de Ensino Conveniada.<br>
                 </td>
            </tr>
    
             
            <tr class="fundo2">
                <td colspan="4" class="tamanhopadrao"><b>Cláusula 7ª – Das disposições gerais:</b></td>
            </tr>
        </table><br>
       
        <div style='page-break-before: always;'></div> 
         <table id="tabela1">
            <tr>
                <td class="tamanhopadrao" colspan="4" >
                    <table>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;"  valign="top"><b>a)</b></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">O (A) Estagiário (a) deverá informar de imediato e por escrito à Unidade Concedente qualquer fato que interrompa, suspenda ou cancele sua matrícula na Instituição de Ensino Conveniada, ficando ele (a) responsável por quaisquer danos causados pela ausência dessa informação;<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"><b>b)</b></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">O (A) Estagiário (a) não terá, para quaisquer efeitos, vínculo empregatício com a Unidade Concedente, conforme o Artigo 3º da Lei Federal nº 11.788, de 25 de setembro de 2008.<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"><b>c)</b></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">As partes aqui signatárias comprometem-se a aceitar como válidos meios alternativos de comprovação da autoria e integridade deste documento em forma eletrônica, tais como - mas não limitados a -: geolocalização, endereço IP do equipamento utilizado,  carimbo do tempo, código de acesso, e-mail ou SMS de confirmação, fotografia,  confirmação de dados pessoais, upload de documentos, desenho da assinatura manuscrita ou outras confirmações e aceites mediados por senhas pessoais.<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"><b>d)</b></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">Acarretam a imediata rescisão deste Termo de Compromisso de Estágio:<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">I) A conclusão, abandono, desistência ou trancamento de matrícula ou da disciplina objeto deste termo;<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">II) Transferência para outro curso;<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">III) O não cumprimento de qualquer cláusula do presente instrumento e;<br></td>
                        </tr>
                        <tr>
                            <td class="tamanhopadrao" style="border:0px solid white;" valign="top"></td>
                            <td class="tamanhopadrao" style="border:0px solid white;">IV) Comportamento inadequado, imoral ou indisciplinado do estagiário.     </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
          
            <tr>
                <td class="tamanhopadrao" colspan="4">E, por estarem devidamente ajustados com as condições aqui estipuladas, a Unidade Concedente, a Unidade Conveniada e o (a) Estagiário (a), nas datas e horários abaixo discriminados,  firmam, por meio eletrônico, o presente Termo de igual teor e forma para que o mesmo produza seus devidos efeitos legais.
                
                    <br><br> <?= $empresa[0]->municipio; ?>, <?= date('d'); ?> de março de <?= date('Y'); ?>.<br>
                    <br>
                    Aceito pela Instituição Conveniada às _______________ de ____/____/________<br>
                    Aceito pelo(a) Estagiário(a) às _______________ de <br>
                    Aceito pela Instituição Concendente às _______________ de ____/____/________<br> 
                </td>
            </tr>
            <tr>
                <td class="tamanhopadrao" colspan="" style="height: 100px;">&nbsp;</td>
                <td class="tamanhopadrao" colspan="">&nbsp;</td>
                <td class="tamanhopadrao" colspan="">&nbsp;</td>
            </tr>  
        </table>
        <table>
            <tr>
                <td style="height: 440px;border:0px solid white;"></td>
            </tr>
        </table>
        
    </body>
</html>



 
















