<div id="page-wrapper"> <!-- Inicio da DIV content -->
    <!--    <div class="bt_link_voltar">
            <a href="<?= base_url() ?>ponto/horariostipo">
                Voltar
            </a>
    
        </div>-->
    <div class="panel panel-default">
        <div class="alert alert-info">Atribuir Dados Gerencia Net</div>

        <div class="panel-body">
            <form name="form_exame" id="form_exame" action="<?= base_url() ?>cadastros/empresa/gravargerencianet" method="post">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">


                            <label>Valor Consulta APP</label>


                            <input type="text"  name="valor_consulta_app"  id="valor" class="form-control bestupper" value="<?= $obj->_valor_consulta_app ?>"/>

                        </div>    
                    </div>    
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">


                            <label>Modo de Testes GerenciaNet</label>


                            <input type="checkbox"  name="client_sandbox"  <? if ($obj->_client_sandbox == 't') echo "checked"; ?>/>

                        </div>    
                    </div>    
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">


                            <label> Client_Id GerenciaNet </label>


                            <input type="text"  name="client_id" class="form-control bestupper" value="<?= $obj->_client_id ?>"/>

                        </div>    
                    </div>    
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">


                            <label>Client_Secret GerenciaNet</label>


                            <input type="text"  name="client_secret" class="form-control bestupper" value="<?= $obj->_client_secret ?>"/>

                        </div>    
                    </div>    
                </div>



                <div class="row">
                    <div class="col-lg-3">

                        <p>
                                <button class="btn btn-outline btn-success btn-sm" type="submit" name="btnEnviar"><i class="fa fa-floppy-o fa-fw"></i> Enviar</button>
                                <button class="btn btn-outline btn-danger btn-sm" type="reset" name="btnLimpar">Limpar</button>
                            
                        </p>
                    </div>
                </div>
               
            </form>
        </div>
    </div>
</div> 