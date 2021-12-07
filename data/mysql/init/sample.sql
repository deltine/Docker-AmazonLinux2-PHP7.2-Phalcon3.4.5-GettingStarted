use mydb;
DROP TABLE IF EXISTS sample;
create table if not exists sample
(
    id int auto_increment not null primary key,
    name varchar(10),
    age int,
    sample_date date
    );

INSERT INTO sample(name, age, sample_date) VALUES ('hoge', 10, now());
INSERT INTO sample(name, age, sample_date) VALUES ('fuga', 20, now());
INSERT INTO sample(name, age, sample_date) VALUES ('foo', 30, now());
INSERT INTO sample(name, age, sample_date) VALUES ('bar', 40, now());
select * from sample;