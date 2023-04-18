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