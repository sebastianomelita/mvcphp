CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    authlevel INT DEFAULT 0,
    come varchar(45),
    cognome varchar(45),
    datanascita DATE,
    genere varchar(45), 
    indirizzo varchar(45),
    cap varchar(10),
    tel varchar(15), 
    cell varchar(15),
    mail varchar(45), 
    pec varchar(45), 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)Engine=InnoDB;

create table Cliente(
    -> Id_Cliente int not null,
    -> primary key(Id_Cliente),
	-> FOREIGN KEY (id) REFERENCES users(id))
    -> engine=InnoDB;

create table Pizzaiolo(
    -> Id_Pizzaiolo int not null,
    -> Anni_esperienza varchar(45),
    -> Certificati_merito varchar(45),
    -> primary key(Id_Pizzaiolo),
	-> FOREIGN KEY (id) REFERENCES users(id))
    -> engine=InnoDB;

create table Pizze(
    -> Id_Pizza int not null,
    -> Nome_pizza varchar(45),
    -> Costo varchar(45),
    -> PesoPizza varchar(45),
    -> Adatta_Celiaci varchar(45),
    -> Adatta_IntolleantiLattosio varchar(45),
    -> Img varchar(45),
    -> primary key(Id_Pizza))
    -> engine=InnoDB;

 create table Ristoranti(
    -> Id_Ristorante int not null,
    -> Nome varchar(45),
    -> Indirizzo varchar(45),
    -> Valutazione_TripAdvisor varchar(45),
    -> Numero_posti varchar(45),
    -> Pagamenti_carta varchar(45),
    -> Pagamenti_Contactless varchar(45),
    -> Pagamenti_PayaPall varchar(45),
    -> primary key(Id_Ristorante))
    -> engine=InnoDB;

create table Prenotazioni(
    -> Id_Prenotazione int not null,
    -> Id_Cliente int not null,
    -> Orario DATETIME,
	-> OrarioRitiro DATETIME,
    -> FOREIGN KEY (Id_Cliente) REFERENCES Cliente(Id_Cliente),
    -> primary key(Id_Prenotazione))
    -> engine=InnoDB;
	
create table DettagliPrenotazioni(
    -> Id_Prenotazione int not null,
    -> Id_Pizza int not null,
	-> quantita int not null,
	-> aggiunte varchar(45),
    -> FOREIGN KEY (Id_Cliente) REFERENCES Cliente(Id_Cliente),
    -> primary key(Id_Prenotazione))
    -> engine=InnoDB;

create table Ingredienti(
    -> Id_Ingrediente int not null,
    -> Nome varchar(45),
    -> DataScadenza varchar(45),
    -> primary key(Id_Ingrediente))
    -> engine=InnoDB;

	create table Categorie(
    -> Id_Categoria int not null,
    -> Nome varchar(45),
    -> primary key(Id_Categoria))
    -> engine=InnoDB;

create table Composizioni(
    -> Id_Composizione int not null,
    -> Id_Pizza int not null,
    -> Id_Ingrediente int not null,
    -> Quantità varchar(45), 
    -> FOREIGN KEY (Id_Pizza) REFERENCES Pizze(Id_Pizza),
    -> FOREIGN KEY (Id_Ingrediente) REFERENCES Ingredienti(Id_Ingrediente),
    -> primary key(Id_Composizione))
    -> engine=InnoDB;


