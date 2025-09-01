-- Tabela para armazenar mensagens do formulário de contato
CREATE TABLE contatos (
    id SERIAL PRIMARY KEY,         -- ID único para cada contato
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    celular VARCHAR(20),
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para especialidades da confeitaria
CREATE TABLE especialidades (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    icone VARCHAR(50)                -- código do ícone bootstrap, ex: "bi-cake"
);

-- Tabela para imagens da galeria
CREATE TABLE galerias (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    caminho_imagem VARCHAR(255) NOT NULL   -- caminho do arquivo da imagem no servidor
);

-- Exemplo de inserção nas especialidades
INSERT INTO especialidades (titulo, descricao, icone) VALUES
('Bolos de Pote', 'Perfeitos para aniversários, casamentos e momentos especiais.', 'bi-cake'),
('Doces', 'Docinhos artesanais com apresentação impecável e sabor inesquecível.', 'bi-cup-straw'),
('Kits e Caixas', 'Presentes personalizados e cestas doces para surpreender.', 'bi-box-seam');

-- Inserir contato (exemplo)
INSERT INTO contatos (nome, email, celular, mensagem)
VALUES ('João Silva', 'joao@email.com', '11999999999', 'Gostaria de fazer uma encomenda.');

-- Inserir especialidade (caso queira adicionar via SQL)
INSERT INTO especialidades (titulo, descricao, icone)
VALUES ('Brigadeiros Gourmet', 'Deliciosos brigadeiros com sabores variados.', 'bi-cup-straw');

-- Inserir imagem na galeria
INSERT INTO galerias (titulo, descricao, caminho_imagem)
VALUES ('Bolo de Morango', 'Bolo decorado com morangos frescos', 'img/bolo_morango.jpg');

-- Buscar todos os contatos
SELECT * FROM contatos ORDER BY data_envio DESC;

-- Buscar todas especialidades
SELECT * FROM especialidades ORDER BY titulo;

-- Buscar todas imagens da galeria
SELECT * FROM galerias ORDER BY id DESC;

-- Deletar contato pelo ID
DELETE FROM contatos WHERE id = 1;

-- Deletar especialidade pelo ID
DELETE FROM especialidades WHERE id = 2;

-- Deletar imagem da galeria pelo ID
DELETE FROM galerias WHERE id = 3;
