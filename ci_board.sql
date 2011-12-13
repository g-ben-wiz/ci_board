create role ci_board password 'mypassword' login;
create database ci_board_db owner ci_board encoding 'UTF8';
\connect ci_board_db;

--include the pgcrypto library--
--depends on where your 'pgcrypto.sql' file is located
\i /usr/share/postgresql/8.4/contrib/pgcrypto.sql

set role ci_board;

create table categories (id serial primary key, 
                 name varchar(255) not null);

create table password_requests(id integer primary key,
                     request_date timestamp,
                          user_id integer not null);

create table posts (id serial primary key, 
           category_id integer not null,
        author_user_id integer not null,
        parent_post_id integer not null,
                 title varchar(255) not null,
                 image bytea,
             thumbnail bytea,
               imgtype text,
             post_date timestamp,
                  text text);

create table users (id serial primary key,
                  name varchar(255) not null,
              password text not null,
             join_date timestamp not null,
                 email varchar(255) not null,
      permission_level smallint not null,
        userban_reason text);

---insert a board admin account---
insert into users (name, password, join_date, email, permission_level) 
    values ('admin', crypt('mypassword', gen_salt('bf', 5)), now(), 'fakename@example.com', 2);

--set up the database for sessions
--http://codeigniter.com/user_guide/libraries/sessions.html
create table ci_sessions (
    session_id varchar(40) DEFAULT '0' NOT NULL PRIMARY KEY,
    ip_address varchar(16) DEFAULT '0' NOT NULL,
    user_agent varchar(120) NOT NULL,
    last_activity integer DEFAULT 0 NOT NULL,
    user_data text 
);
create index last_activity_idx ON ci_sessions (last_activity);

