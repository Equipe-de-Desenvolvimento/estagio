
<div> 
    <form>
    
 <!--        Chamando o Script para a Webcam   -->
         <script src="<?= base_url() ?>js/webcam.js"></script>
            
            <?
            
            if (@$empresapermissoes[0]->campos_cadastro != '') {
                $campos_obrigatorios = json_decode(@$empresapermissoes[0]->campos_cadastro);
            } else {
                $campos_obrigatorios = array();
            }
            $tecnico_recepcao_editar = @$empresapermissoes[0]->tecnico_recepcao_editar;
            $perfil_id = $this->session->userdata('perfil_id');

            ?>

        <div class="alert alert-info">Cadastro de vagas</div>
            <div  class="panel-body infodados">
                <table>
                    <thead>
                        <tr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div >
                                        <label>Situação</label>
                                        <input type="text" id="txtSituacao" name="situacao" class="form-control" value="<?= @$obj[0]->situacao; ?>" required="true"  placeholder="">
                                    </div>
                                    <div>
                                        <label>Instituição</label>
                                        <input type="text" id="txtInstituicao" name="instituicao" class="form-control" value="<?= @$obj[0]->instituicao; ?>" required="true"  placeholder="">
                                    </div>  
                                    <div>
                                        <label>Observa&ccedil;&otilde;es</label>
                                        <textarea cols="70" rows="2" class="form-control" name="observacao" placeholder="" id="observacao"><?= @$obj->_observacao; ?></textarea><br/>
                                    </div>
                                </div>
                                <div  class="col-lg-3">
                                    <div>
                                        <label>Pré-requisitos</label>
                                        <input type="text" id="txtPre_requisitos" name="pre_requisitos" class="form-control" value="<?= @$obj[0]->pre_requisitos; ?>" required="true"  placeholder="">
                                    </div>
                                    <div>
                                        <label>Concedente</label>
                                        <input type="text" id="txtConcedente" name="concedente" class="form-control" value="<?= @$obj[0]->concedente; ?>" required="true"  placeholder="">
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div>
                                        <div>
                                            <label>Nível</label>
                                        </div>
                                        <select name="nivel"  class="form-control" id="txtNivel" class="size2">
                                            <option value="0">selecione</option>
                                            <option>Graduado(a)</option>
                                            <option>Pós graduado(a)</option>
                                            <option>técnico(a)</option>
                                            <option>Residência</option>
                                            <option>Especialização</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Tipo de Estágio</label>
                                    </div>
                                    <div>
                                        <select name="estagio"  class="form-control" id="txtEstagio">
                                            <option value="0">selecione</option>
                                            <option>Internato</option>
                                            <option>Não internato</option>
                                            <option>Obrigatório</option>
                                            <option>Não obrigatório</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-gl-2">
                                    <div>
                                        <label>Àrea</label>
                                    </div>
                                    <div>
                                        <select name="area"  class="form-control" id="area">
                                            <option value="0">selecione</option>
                                            <option>Suporte</option>
                                            <option>Programação</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Vaga</label>
                                    </div>
                                    <div>
                                        <select name="vaga"  class="form-control" id="txtVaga">
                                            <option value="0">selecione</option>
                                            <option>Ocupado</option>
                                            <option>Em aberto</option>
                                        </select>
                                    </div>
                                    <div>
                                        <div>
                                            <label>Horários/Turnos</label>
                                        </div>
                                        <select name="horario"  class="form-control" id="txtHorario">
                                            <option value="0">selecione</option>
                                            <option>Turno da Manhã</option>
                                            <option>Turno da tarde</option>
                                            <option>Turno Integral</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    </thead>
                    <tbody>
                       
                            <div class="panel panel-default btngrp">
                                <div class="panel-body">
                                    <?if($tecnico_recepcao_editar == 't' || $perfil_id != 15){?>
                                        <button type="reset" class="btn btn-warning btn-sm">Limpar</button>
                                        <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                                            <?}?>
                                        <a href="<?= base_url() ?>cadastros/pacientes/pesquisarMapaGestao/">
                                            <button type="button" id="btnVoltar" class="btn btn-secondary btn-sm">Voltar</button>
                                        </a>

                                </div>
                            </div>   
                    </tbody>            
                </table>
            </div>
        </div>
    </form>
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>
