CREATE DATABASE IF NOT EXISTS frete_db;
USE frete_db;

DROP TABLE IF EXISTS frete_produtos;
DROP TABLE IF EXISTS fretes;
DROP TABLE IF EXISTS transportadores;
DROP TABLE IF EXISTS enderecos;
DROP TABLE IF EXISTS produtos;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_produto VARCHAR(50) NOT NULL,
    descricao VARCHAR(150) NOT NULL,
    especie VARCHAR(50),
    peso DECIMAL(10,2) NOT NULL,
    valor DECIMAL(10,2) NOT NULL
);

CREATE TABLE enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cep VARCHAR(10) NOT NULL,
    tipo_logradouro VARCHAR(30) NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(20),
    bairro VARCHAR(80),
    municipio VARCHAR(80) NOT NULL,
    estado VARCHAR(2) NOT NULL
);

CREATE TABLE transportadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_transportadora VARCHAR(50) NOT NULL,
    cnpj VARCHAR(20) NOT NULL,
    razao_social VARCHAR(150) NOT NULL,
    nome_fantasia VARCHAR(150),
    telefone VARCHAR(20),
    cep VARCHAR(10),
    tipo_logradouro VARCHAR(30),
    logradouro VARCHAR(150),
    numero VARCHAR(20),
    bairro VARCHAR(80),
    municipio VARCHAR(80),
    estado VARCHAR(2)
);

CREATE TABLE fretes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transportador_id INT NOT NULL,
    origem_id INT NOT NULL,
    destino_id INT NOT NULL,
    valor_frete DECIMAL(10,2) NOT NULL,
    data_frete DATE NOT NULL,
    FOREIGN KEY (transportador_id) REFERENCES transportadores(id),
    FOREIGN KEY (origem_id) REFERENCES enderecos(id),
    FOREIGN KEY (destino_id) REFERENCES enderecos(id)
);

CREATE TABLE frete_produtos (
    frete_id INT NOT NULL,
    produto_id INT NOT NULL,
    PRIMARY KEY (frete_id, produto_id),
    FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
