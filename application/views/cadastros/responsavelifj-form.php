<div class="panel-body">
    <form action="<?=base_url()?>cadastros/pacientes/gravarresponsavelifj" method="POST">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?=@$obj[0]->nome?>" required>
                <input type="hidden" name="responsavel_ifj_id" value="<?=$responsavel_ifj_id?>">
            </div>

            <div class="col-lg-3">
                <label for=""> Email </label>
                <input type="text" name="email" class="form-control" value="<?=@$obj[0]->email?>" required>
            </div>

            <div class="col-lg-3">
                <label for=""> Cargo </label>
                <input type="text" name="cargo" class="form-control" value="<?=@$obj[0]->cargo?>" required>
            </div>
        </div>
        <br>
        
        <button type="submit" class="btn btn-outline-default btn-sm">Enviar</button>
        <button type="reset" class="btn btn-outline-default btn-sm">Limpar</button>
    </form>
</div>