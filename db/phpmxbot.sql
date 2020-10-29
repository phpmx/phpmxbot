-- Adminer 4.7.7 SQLite 3 dump

DROP TABLE IF EXISTS "leaderboard";
CREATE TABLE "leaderboard" (
  "token" text NOT NULL,
  "points" integer NOT NULL DEFAULT '0'
);

CREATE UNIQUE INDEX "leaderboard_token" ON "leaderboard" ("token");


DROP TABLE IF EXISTS "login";
CREATE TABLE "login" (
  "username" text NOT NULL,
  "password" text NOT NULL
);

CREATE UNIQUE INDEX "login_username" ON "login" ("username");

INSERT INTO "login" ("username", "password") VALUES ('admin',	'%%PASSWORD_HASH%%');

--
