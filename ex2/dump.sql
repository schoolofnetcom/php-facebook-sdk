create database curso_php_fb;
use curso_php_fb;

create table posts(id int auto_increment primary key, pageid varchar(255), message text, access_token varchar(255), publish_date datetime, published int);
