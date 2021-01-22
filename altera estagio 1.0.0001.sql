
ALTER TABLE ponto.tb_paciente ADD COLUMN status_estagio VARCHAR(50) DEFAULT 'INCOMPLETO';

ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN area VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN situacao VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN instituicao VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN nivel VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN pre_requisitos VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN convenio VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN concedente VARCHAR(50); 
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN observacao VARCHAR(50);
ALTER TABLE ponto.tb_forma_entradas_saida ADD COLUMN vaga VARCHAR(50);


