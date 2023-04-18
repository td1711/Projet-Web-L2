CREATE DATABASE marmisomon;
USE marmisomon;

--cours_bd_SQL_2
CREATE TABLE tag(
    nom varchar(30) not null
);

alter table tag
ADD CONSTRAINT PK_tag primary key(nom);

CREATE TABLE ingredient(
    nom varchar(30) not null,
    image varchar(40)
);

alter table ingredient
ADD CONSTRAINT PK_ingredient primary key(nom);

CREATE TABLE contientTag(
    nom_recette varchar(30) not null,
    nom_tag varchar(30) not null
);

CREATE TABLE contientIngredient(
    nom_recette varchar(30) not null,
    nom_Ingredient varchar(30) not null
);

CREATE TABLE recette(
    titre varchar(30) not null,
    description MEDIUMTEXT not null,
    image varchar(30)
);

alter table recette
ADD CONSTRAINT PK_recette primary key(titre);

--cours_bd_SQL_3

insert into tag(nom) values ("dessert");
insert into tag(nom) values ("petite faim");

insert into ingredient(nom, image) values ("farine", "farine.png");
insert into ingredient(nom, image) values ("chocolat", "chocolat.png");

insert into recette(titre, description, image) values ("cookie", "miam des bons cookies au chocolat", "cookie.png");

insert into contientTag(nom_recette, nom_tag) values ("cookie", "dessert");
insert into contientTag(nom_recette, nom_tag) values ("cookie", "petite faim");

insert into contientIngredient(nom_recette, nom_Ingredient) values ("cookie", "farine");
insert into contientIngredient(nom_recette, nom_Ingredient) values ("cookie", "chocolat");

ALTER TABLE recette DROP PRIMARY KEY;
ALTER TABLE recette ADD id INT PRIMARY KEY NOT NULL AUTO_INCREMENT;

ALTER TABLE tag DROP PRIMARY KEY;
ALTER TABLE tag ADD id INT PRIMARY KEY not null AUTO_INCREMENT;

ALTER TABLE ingredient DROP PRIMARY KEY;
ALTER TABLE ingredient ADD id INT PRIMARY KEY not null AUTO_INCREMENT;

ALTER TABLE recette ADD instruction MEDIUMTEXT not null;
ALTER TABLE ingredient ADD saison VARCHAR(10);

DROP TABLE contientIngredient;

CREATE TABLE contientIngredient(
   id_recette int not null,
   id_ingredient int not null
);

DROP TABLE contientTag;

CREATE TABLE contientTag(
    id_recette int not null,
    id_tag int not null
);