CREATE DATABASE trabalhopw

CREATE TABLE usuarios(
    id int not null AUTO_INCREMENT,
    nome varchar(250) not null,
    email varchar(250) not null,
    senha varchar(250) not null,
    PRIMARY KEY (id))

CREATE TABLE documentos(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(250) NOT NULL,
    tipo VARCHAR(250) NOT NULL,
    caminho VARCHAR(250) NOT NULL,
    usuario_id VARCHAR(250) NOT NULL,
    data DATE NOT NULL DEFAULT CURDATE(),
    PRIMARY KEY (id))
