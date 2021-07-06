<form action="<?=base_url()?>cadastros/pacientes/salvarlogin" method="post">
<div class="panel-body">
    <div class="alert alert-primary"><b>Meu Usuario e Senha</b></div>
        <div class="row">
            <div class="col-lg-3">
                <label for="">Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" required>
                <input type="hidden" class="form-control" value="<?=$paciente_id?>" name="paciente_id" id="paciente_id">
            </div>

            <div class="col-lg-3">
                <label for="">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha" required>
            </div>
        </div>

        <br><br>
        <button type="submit" class="btn btn-success btn-sm"> Salvar </button>
</div>
</form>

