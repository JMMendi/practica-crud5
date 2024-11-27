create table provincias(
    id int auto_increment PRIMARY KEY,
    nombre varchar(50) not null,
    color varchar(50) not null
);

create table users (
    id int auto_increment primary key,
    username varchar(24) UNIQUE NOT NULL,
    email varchar(100) UNIQUE not null,
    imagen varchar(240) not null,
    provincia_id int,
    constraint fk_user_prov FOREIGN KEY(provincia_id) references provincias(id) on delete cascade
);