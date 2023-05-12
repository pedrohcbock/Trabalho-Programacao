CREATE DATABASE trabalhopw

CREATE TABLE usuarios(
    id int not null AUTO_INCREMENT,
    nome varchar(250) not null,
    email varchar(250) not null,
    senha varchar(250) not null,
    PRIMARY KEY (id))

CREATE TABLE arquivos (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(250) NOT NULL,
    tipo VARCHAR(250) NOT NULL,
    conteudo LONGTEXT NOT NULL,
    usuario VARCHAR(250) NOT NULL,
    data_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id))