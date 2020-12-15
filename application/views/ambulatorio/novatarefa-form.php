<style>
    #observacaotarefa{
        width:45%;
        height: 60px;
    }
</style>
<div class="content ficha_ceatox">
    <div>

        <div class="bt_link_new">
            <h3 class="singular"><a href="#">Nova Tarefa</a></h3>
        </div>
        <div>
           
               <?// form_open_multipart(base_url() . 'ambulatorio/exametemp/gravartarefa'); ?>
               <form method="POST" action="<?=base_url() . 'ambulatorio/exametemp/gravartarefa'?>"  enctype="multipart/form-data">
                <fieldset>
                    <legend>Dados do Paciente</legend>
                    <div>
                        <label>Nome</label>                      
                        <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente['0']->nome; ?>" readonly/>
                        <input type="hidden" id="txtpaciente_id" name="txtpaciente_id"  value="<?= $paciente_id; ?>"/>
                    </div>
                    <div>
                        <label>Sexo</label>
                        <input type="text" id="txtSexo" name="sexo"  class="texto05" value="<?
                        if ($paciente['0']->sexo == "M"):echo 'Masculino';
                        endif;
                        if ($paciente['0']->sexo == "F"):echo 'Feminino';
                        endif;
                        if ($paciente['0']->sexo == "O"):echo 'Outro';
                        endif;
                        ?>" readonly/>
                    </div>

                    <div>
                        <label>Nascimento</label>
                        <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr($paciente['0']->nascimento, 8, 2) . '/' . substr($paciente['0']->nascimento, 5, 2) . '/' . substr($paciente['0']->nascimento, 0, 4); ?>" readonly/>
                    </div>

                    <div>
                        <label>Idade</label>
                        <input type="text" name="idade" id="txtIdade" class="texto01" alt="numeromask" value="<?= $paciente['0']->idade; ?>" readonly />

                    </div>
                </fieldset>

                <fieldset>
                    <table>
                        <tr>
                            <td  width="7%">Médico</td>
                            <td><select name="medico" required="">
                                    <option value="">Selecione</option>
                                    <?php
                                    foreach ($medicos as $item) {
                                        ?>
                                        <option value="<?= $item->operador_id; ?>"><?= $item->nome; ?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td  width="13%">Nome da Tarefa</td>
                            <td><input type="text" name="nome" id="txtNome" required=""></td>
                        </tr>
                        <tr>
                            <td  width="9%">Arquivo</td>
                            <td><input type="file" multiple="" name="arquivos[]" id="txtArquivo"></td>
                        </tr>
                    </table>
                    <hr/>
                    Descrição<br>
                    <textarea name="observacaotarefa" id="observacaotarefa" required="" ></textarea><br>
 
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <a href="<?= base_url() ?>ambulatorio/exametemp/listartarefas/<?= $paciente_id; ?>"><button type="button" name="voltar">Voltar</button></a>
                </fieldset>
            </form>
          <?// form_close(); ?>  
        </div> 
    </div> 
</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<style>
    /*.chosen-container{ margin-top: 5pt;}*/
    /*#procedimento1_chosen a { width: 130px; }*/
</style>

<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>