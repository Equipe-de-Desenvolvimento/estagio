<div class="panel-body">
    <div class="alert alert-primary"><b>Cadastrar Documentação</b></div>

    <form action="<?=base_url()?>cadastros/pacientes/gravardocumentacaoestagio" method="POST">
    <div class="row">
        <div class="col-lg-4">
            <label for="">Tipo</label>
            <input type="hidden" class="form-control" name="documentacao_profissional_id" value="<?=@$documentacao_profissional_id?>">
            <input type="text" class="form-control" name="tipo" required value="<?=@$obj[0]->nome?>">
        </div>
    </div>

    <br>
        <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>
        <button class="btn btn-outline-default btn-round btn-sm" type="reset">Limpar</button>
    
    </form>
</div>