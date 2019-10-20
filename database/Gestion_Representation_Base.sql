-- Base de données créé le 03.06.2019 par Sylvain Bourbousse --
-- Suppression des tables --
 drop table if exists Personnalite; 
 drop table if exists Suppleant; 
 drop table if exists Titulaire; 
 drop table if exists Representation; 
 drop table if exists Type_Assemble; 
 drop table if exists Service_CG; 
 drop table if exists Personne; 
 drop table if exists Elu; 
 drop table if exists Utilisateur; 
 
-- Creation des tables --
create table Representation(
    representationId mediumint unsigned primary key not null auto_increment,
    representationIntitule varchar(200),
    typeAssembleId tinyint unsigned,
    representationDateAssemble date,
    representationNumDelib smallint unsigned,
    representationFondementJuridique text,
    representationObservation text,
    serviceCGNom varchar(70),
    serviceDirectionNom varchar(70),
    servicePoleNom varchar(50)
)engine=innodb charset=utf8;

create table Titulaire(
    representationId mediumint unsigned,
    eluId smallint unsigned
)engine=innodb charset=utf8;

create table Suppleant(
    representationId mediumint unsigned,
    eluId smallint unsigned
)engine=innodb charset=utf8;

create table Personnalite(
    representationId mediumint unsigned,
    personneId smallint unsigned
)engine=innodb charset=utf8;

create table Elu(
    eluId smallint unsigned primary key not null auto_increment,
    eluNom varchar(45),
    eluPrenom varchar(30),
    eluCivilite char(1),-- h = homme , f = femme --
    eluActif bool,
    eluImage varchar(30)
)engine=innodb charset=utf8;

create table Personne(
    personneId smallint unsigned primary key not null auto_increment,
    personneNom varchar(45),
    personnePrenom varchar(30),
    personneFonction varchar(80),
    personneCivilite char(1)-- h = homme , f = femme --
)engine=innodb charset=utf8;

create table Type_Assemble(
    typeAssembleId tinyint unsigned primary key not null auto_increment,
    typeAssembleNom varchar(40)
)engine=innodb charset=utf8;

create table Service_CG(
    servicePoleNom varchar(70),
    serviceDirectionNom varchar(120),
    serviceCGNom varchar(120),
    serviceActif bool,
    primary key(servicePoleNom,serviceDirectionNom,serviceCGNom)
)engine=innodb charset=utf8;

create table Utilisateur(
    utilisateurIdentifiant varchar(50) primary key,
    utilisateurMail varchar(50),
    utilisateurMotDePasse char(32),
    utilisateurGestionService bool,
    utilisateurGestionRepresentation bool,
    utilisateurGestionElu bool,
    utilisateurGestionPersonne bool
)engine=innodb charset=utf8;

alter table Representation add foreign key (typeAssembleId) references Type_Assemble(typeAssembleId);
alter table Titulaire add foreign key (eluId) references Elu(eluId),
    add foreign key (representationId) references Representation(representationId);
alter table Suppleant add foreign key (eluId) references Elu(eluId),
    add foreign key (representationId) references Representation(representationId);
alter table Personnalite add foreign key (personneId) references Personne(personneId),
    add foreign key (representationId) references Representation(representationId);