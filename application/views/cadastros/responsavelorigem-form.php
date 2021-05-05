<div class="panel-body">
    <form action="<?=base_url()?>cadastros/pacientes/gravarresponsavelorigem" method="POST">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?=@$obj[0]->nome?>" required>
                <input type="hidden" name="responsavel_origem_id" value="<?=$responsavel_origem_id?>">
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
        <div class="row">
        <div class="col-lg-3" >
            <label for="">Instituição de Origem</label>
            <select name="instituicao_id" id="instituicao_id" class="form-control" >
                  <option value="">Selecione</option>
                  <?php foreach($instituicao as $item){?>
                      <option value="<?=$item->instituicao_id?>" <?=(@$obj[0]->instituicao_id == $item->instituicao_id)?'selected':''?>><?= $item->nome; ?></option>
                  <?php }?>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">Curso</label>
            <select name="curso" id="" class="form-control" >
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'curso'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->informacaovaga_id == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

      
 
        </div>
        <br>
        
        <button type="submit" class="btn btn-outline-default btn-sm">Enviar</button>
        <button type="reset" class="btn btn-outline-default btn-sm">Limpar</button>
    </form>
</div>