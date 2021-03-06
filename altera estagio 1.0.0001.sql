
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

ALTER TABLE ponto.tb_convenio ADD COLUMN valor_por_estagio NUMERIC(10,2);


CREATE TABLE ponto.tb_convenio_instituicao
(
  convenio_instituicao_id serial NOT NULL,
  convenio_id integer,
  instituicao_id integer,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_convenio_instituicao_pkey PRIMARY KEY (convenio_instituicao_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_convenio_instituicao
  OWNER TO postgres;

ALTER TABLE ponto.tb_convenio ADD COLUMN valor_por_estagio NUMERIC(10,2);

ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN convenio_id INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN status_vaga TEXT;
ALTER TABLE ponto.tb_aluno_estagio ADD COLUMN convenio_id INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN convenio_id INTEGER;


CREATE TABLE ponto.tb_informacaovaga
(
  informacaovaga_id serial NOT NULL,
  descricao TEXT,
  tipo TEXT,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_informacaovaga_pkey PRIMARY KEY (informacaovaga_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_informacaovaga
  OWNER TO postgres;


ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN formacao INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN curso INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN periodo INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN periodicidade INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN setor INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN tipodavaga INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN disciplina INTEGER;


CREATE TABLE ponto.tb_responsavel_origem
(
  responsavel_origem_id serial NOT NULL,
  nome varchar(100),
  email varchar(100),
  cargo varchar(100),
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_responsavel_origem_pkey PRIMARY KEY (responsavel_origem_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_responsavel_origem
  OWNER TO postgres;


  CREATE TABLE ponto.tb_responsavel_ifj
(
  responsavel_ifj_id serial NOT NULL,
  nome varchar(100),
  email varchar(100),
  cargo varchar(100),
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_responsavel_ifj_pkey PRIMARY KEY (responsavel_ifj_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_responsavel_ifj
  OWNER TO postgres;

ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN objetivo TEXT;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN responsavel_origem INTEGER;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN responsavel_ijf INTEGER;


CREATE TABLE ponto.tb_paciente_dataprova
(
  paciente_dataprova_id serial NOT NULL,
  data_prova date,
  paciente_id integer,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_paciente_dataprova_pkey PRIMARY KEY (responsavel_ifj_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_paciente_dataprova
  OWNER TO postgres;

ALTER TABLE ponto.tb_aluno_estagio ADD COLUMN data_inicio date;
ALTER TABLE ponto.tb_aluno_estagio ADD COLUMN data_final date;
ALTER TABLE ponto.tb_aluno_estagio ADD COLUMN tipo_vaga varchar(100);

ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN disciplina INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN formacao INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN curso INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN setor INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN aluno_id INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN tipodavaga INTEGER;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN data_inicio date;
ALTER TABLE ponto.tb_solicitacao_vagas_empresas ADD COLUMN data_final date;


--17/03/2021

ALTER TABLE ponto.tb_paciente ADD COLUMN data_inicial date;
ALTER TABLE ponto.tb_paciente ADD COLUMN data_final date;

ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN data_inicio date;
ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN data_final date;

--18/03/2021
ALTER TABLE ponto.tb_empresa ADD COLUMN email_institucional character varying(200);
ALTER TABLE ponto.tb_empresa ADD COLUMN representante_unidade character varying(200);
ALTER TABLE ponto.tb_empresa ADD COLUMN cbo_ocupacao_id integer;

--20/03/2021

CREATE TABLE ponto.tb_carga_horario
(
  carga_horario_id serial NOT NULL,
  vaga_id integer,
  horario_inicial character varying(50),
  horario_final character varying(50),
  CONSTRAINT tb_carga_horario_pkey PRIMARY KEY (carga_horario_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_carga_horario
  OWNER TO postgres;

ALTER TABLE ponto.tb_carga_horario ADD COLUMN data_cadastro timestamp without time zone;
ALTER TABLE ponto.tb_carga_horario ADD COLUMN operador_cadastro integer;
ALTER TABLE ponto.tb_carga_horario ADD COLUMN data_atualizacao timestamp without time zone;
ALTER TABLE ponto.tb_carga_horario ADD COLUMN operador_atualizacao integer;

--22/03/2021
ALTER TABLE ponto.tb_carga_horario ADD COLUMN ativo boolean;
ALTER TABLE ponto.tb_carga_horario ALTER COLUMN ativo SET DEFAULT true;

ALTER TABLE ponto.tb_carga_horario ADD COLUMN data date;

--05/05/2021

ALTER TABLE ponto.tb_responsavel_origem ADD COLUMN instituicao_id integer;
ALTER TABLE ponto.tb_responsavel_origem ADD COLUMN informacaovaga_id integer;

ALTER TABLE ponto.tb_responsavel_ifj ADD COLUMN setor integer;

--01/06/2021

ALTER TABLE ponto.tb_responsavel_ifj ADD COLUMN telefone_ifj character varying(100);


--04/06/2021

ALTER TABLE ponto.tb_paciente ADD COLUMN curso_id integer;

--14/06/2021
ALTER TABLE ponto.tb_financeiro_credor_devedor ADD COLUMN convenio_id_financeiro integer;
