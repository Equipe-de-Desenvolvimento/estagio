<div class="panel-body">
    <form action="<?=base_url()?>cadastros/pacientes/gravarresponsavelifj" method="POST">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Nome</label>
                <input type="text"  placeholder="Nome"  name="nome" class="form-control" value="<?=@$obj[0]->nome?>" required>
                <input type="hidden" name="responsavel_ifj_id" value="<?=$responsavel_ifj_id?>">
            </div>

            <div class="col-lg-3">
                <label for=""> Email </label>
                <input type="text"  placeholder="Email"  name="email" class="form-control" value="<?=@$obj[0]->email?>" required>
            </div>  
            <div class="col-lg-3">
                <label for=""> Telefone </label>
               <input type="text"  placeholder="(99)9999-99999" id="telefone_ifj" name="telefone_ifj" class="form-control celular"  value="<?= @$obj[0]->telefone_ifj; ?>" />
            </div>  
            <div class="col-lg-3">
                <label for=""> Cargo </label>
                <input type="text"  placeholder="Cargo" name="cargo" class="form-control" value="<?=@$obj[0]->cargo?>" required>
            </div>
        </div>
        <div class="row">
           <div class="col-lg-2"> 
            <label for="">Setor</label>
            <select name="setor" id="" class="form-control" >
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'setor'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->setor == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
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


<script>
        jQuery("#telefone_ifj")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });
    </script>