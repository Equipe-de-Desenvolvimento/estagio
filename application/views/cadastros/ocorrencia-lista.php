
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>cadastros/pacientes/carregarcampo/0">
            Nova Ocorrência
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Campos Ocorrência</a></h3>
        <div>
            <table>
                <thead> 
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Tipo</th> 
                        <th class="tabela_header">Data criação</th> 
                        <th class="tabela_header">Operador alteração</th> 
                        <th class="tabela_header" colspan="2"><center>Detalhes</center></th>
                   </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->paciente->listartemplateocorrencia($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $lista = $this->paciente->listartemplateocorrencia($_GET)->limit($limit, $pagina)->orderby("nome_template")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) { 
                            $ArrayTipo = Array();  
                            $json  = json_decode($item->template); 
                            if ($json ==  "") {
                                $json = Array();
                            }
                            
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome_template; ?></td>  
                                <td class="<?php echo $estilo_linha; ?>"> <? 
                                foreach($json as $key => $value){
                                    $ArrayTipo[$value->tipo] = $value->tipo;  
                                 } 
                                 $i = 0;
                                foreach($ArrayTipo as  $value){ 
                                    $i++;
                                    $texto= "";
                                   if ($value == "textoCurto") {
                                       $texto =  "Texto Curto";
                                   }
                                   if ($value == "textoLongo") {
                                        $texto =  "Texto Longo";
                                        
                                   }
                                   if ($value == "textoNumero") {
                                        $texto =  "Númerico";
                                   }
                                   if ($value == "textoDecimal") {
                                        $texto =  "Númerico Decimal";
                                   }
                                   if ($value == "select") {
                                        $texto =  "Selecionar de Lista";
                                   }
                                   if ($value == "checkbox") {
                                        $texto =  "Caixa de checagem";
                                   }
                                   if ($value == "textoTelefone") {
                                       $texto =  "Telefone";
                                   }
                                   echo $texto; 
                                   
                                   if ($i != count($ArrayTipo)) {  
                                       echo ","; 
                                   } 
                                   
                                    
                                 }
                                 
                                ?></td> 
                                <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime($item->data_cadastro));?></td> 
                                <td class="<?php echo $estilo_linha; ?>"> <?= $item->op_atualizacao; ?> </td> 
                                
                                <td style="width: 120px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a href="<?= base_url() ?>cadastros/pacientes/carregarcampo/<?= $item->template_ocorrencia_id; ?>">Editar</a></div>
                                </td>
                                 <?php if($item->ativo == "t"){ ?>
                                <td style="width: 120px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a onclick="javascript: return confirm('Você deseja realmente desativar a ocorrência?');" href="<?= base_url() ?>cadastros/pacientes/excluirtemplateocorrencia/<?= $item->template_ocorrencia_id; ?>">Desativar</a></div>
                                </td>
                                 <?php }else{ ?>
                                <td style="width: 120px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a onclick="javascript: return confirm('Você deseja realmente ativar a ocorrência?');" href="<?= base_url() ?>cadastros/pacientes/reativartemplateocorrencia/<?= $item->template_ocorrencia_id; ?>">Ativar</a></div>
                                </td>
                                 <?php }?> 
                                <?
                                $perfil_id = $this->session->userdata('perfil_id');
                                $operador_id = $this->session->userdata('operador_id');
                                if ($perfil_id == 1):
                                    ?>
                                    
                               <? endif; ?>
                                <?
//                                $perfil_id = $this->session->userdata('perfil_id');
                                if ($operador_id == 1):
                                    ?>
                                    
                               <? endif; ?>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="8">
<?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>
