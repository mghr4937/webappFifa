CREATE DATABASE fifa_db;

USE fifa_db;

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(10) NOT NULL default '',
  active tinyint(4) default '1',
  PRIMARY KEY (id),
  UNIQUE (name)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE tournaments (
  id int(11) NOT NULL AUTO_INCREMENT,
  monthyear varchar(45) NOT NULL,
  place varchar(45) NOT NULL,
  name varchar(45) NOT NULL,
  has_final tinyint(4) DEFAULT 0,
  is_finish tinyint(4) DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY name_UNIQUE (name));

CREATE TABLE matchs (
  id_tournament int(11) NOT NULL,
  id_user_A int(11) NOT NULL,
  id_user_B int(11) NOT NULL,
  gol_A int(11) DEFAULT NULL,
  gol_B int(11) DEFAULT NULL,
  PRIMARY KEY (id_tournament,id_user_A,id_user_B),
  KEY fk_matchs_2_idx (id_user_A),
  KEY fk_matchs_3_idx (id_user_B),
  CONSTRAINT fk_matchs_1 FOREIGN KEY (id_tournament) REFERENCES tournaments (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT fk_matchs_2 FOREIGN KEY (id_user_A) REFERENCES users (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT fk_matchs_3 FOREIGN KEY (id_user_B) REFERENCES users (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE tournament_user (
  id_user int(11) NOT NULL,
  id_tournament int(11) NOT NULL,
  PRIMARY KEY (id_user,id_tournament),
  KEY fk_tournament_user_2_idx (id_tournament),
  CONSTRAINT fk_tournament_user_1 FOREIGN KEY (id_user) REFERENCES users (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT fk_tournament_user_2 FOREIGN KEY (id_tournament) REFERENCES tournaments (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

