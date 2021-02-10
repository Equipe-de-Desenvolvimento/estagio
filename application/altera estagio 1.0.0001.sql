
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

INSERT INTO ponto.tb_perfil (perfil_id, nome, ativo) VALUES (9, 'INSTITUIÇÃO', TRUE);
ALTER TABLE ponto.tb_operador ADD COLUMN instituicao_id INTEGER;
ALTER TABLE ponto.tb_paciente ADD COLUMN instituicao_id INTEGER;
ALTER TABLE ponto.tb_operador ADD COLUMN aluno_id INTEGER;



CREATE TABLE ponto.tb_vagas_empresas(
vaga_id serial primary key NOT NULL,
nome_vaga varchar(100),
tipo_vaga varchar(100),
qtde_vagas integer,
qtde_inicial integer,
operador_cadastro integer,
ativo BOOLEAN DEFAULT TRUE,
data_cadastro timestamp without time zone,
operador_atualizacao integer,
data_atualizacao timestamp without time zone
)WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_vagas_empresas
  OWNER TO postgres;

ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN instituicao_id INTEGER;


CREATE TABLE ponto.tb_solicitacao_vagas_empresas(
solicitacao_vaga_id serial primary key NOT NULL,
nome_vaga varchar(100),
tipo_vaga varchar(100),
qtde_vagas integer,
instituicao_id integer,
status_vaga varchar(50),
operador_cadastro integer,
ativo BOOLEAN DEFAULT TRUE,
data_cadastro timestamp without time zone,
operador_atualizacao integer,
data_atualizacao timestamp without time zone
)WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_solicitacao_vagas_empresas
  OWNER TO postgres;

ALTER TABLE ponto.tb_instituicao ADD COLUMN valor_por_estagio NUMERIC(10,2);

ALTER TABLE ponto.tb_paciente ADD COLUMN associado_a_vaga BOOLEAN DEFAULT FALSE;


CREATE TABLE ponto.tb_aluno_estagio(
aluno_estagio_id serial primary key NOT NULL,
aluno_id integer,
instituicao_id integer,
vaga_id integer,
status_estagio varchar(100),
ativo BOOLEAN DEFAULT TRUE,
operador_cadastro integer,
data_cadastro timestamp without time zone,
operador_atualizacao integer,
data_atualizacao timestamp without time zone
)WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_aluno_estagio
  OWNER TO postgres;

ALTER TABLE ponto.tb_aluno_estagio ADD COLUMN data_inicio_estagio timestamp without time zone;
