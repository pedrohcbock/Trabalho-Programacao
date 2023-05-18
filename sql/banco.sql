CREATE DATABASE trabalhopw

CREATE TABLE usuarios(
    id int not null AUTO_INCREMENT,
    nome varchar(250) not null,
    email varchar(250) not null,
    senha varchar(250) not null,
    PRIMARY KEY (id));

CREATE TABLE documentos (
    id int NOT NULL AUTO_INCREMENT,
    usuario_id int NOT NULL,
    nome varchar(250) NOT NULL,
    tipo varchar(250) NOT NULL,
    caminho varchar(250) NOT NULL,
    data DATE NOT NULL DEFAULT CURDATE(),
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id));

CREATE TABLE compartilhamentos (
    id int NOT NULL AUTO_INCREMENT,
    documento_id int NOT NULL,
    usuario_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (documento_id) REFERENCES documentos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id));