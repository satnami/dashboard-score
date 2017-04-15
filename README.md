## Run Locally
```
php -S localhost:8080 -t web
```
## Run on Heroku
1. Create heroku account.
2. Install git.
3. Install heroku cli.
```
heroku create
git add . && git commit -m "init"
git push heroku master 
```

## DB Setup
```
heroku addons:create heroku-postgresql:hobby-dev
heroku pg:psql

$ create table users (id serial PRIMARY KEY, username VARCHAR(20) not null, password VARCHAR(20) not null, score Integer DEFAULT 0, highscore Integer DEFAULT 0);
```

## Local DB Setup 
Create sqlite db called game.db
```
create table users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , username CHAR(50), password CHAR(50), score INTEGER, highscore INTEGER);
```
