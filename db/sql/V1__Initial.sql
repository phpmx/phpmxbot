-- Adminer 4.7.7 SQLite 3 dump

DROP TABLE IF EXISTS "login";
CREATE TABLE "login" (
  "username" text NOT NULL,
  "password" text NOT NULL
);
CREATE UNIQUE INDEX "login_username" ON "login" ("username");
INSERT INTO "login" ("username", "password") VALUES ('admin',	'$2y$10$XO7ktu7ijKbeBwowKyVME.Xhn.9vgZCmJMyOklOx1Lh7FWUnKbBOu');

DROP TABLE IF EXISTS "leaderboard";
CREATE TABLE "leaderboard" (
  "token" text NOT NULL,
  "user" text NOT NULL,
  "points" integer(1) NOT NULL,
  "method" text NOT NULL,
  "timestamp" integer(10) NOT NULL
);

DROP TABLE IF EXISTS "leaderboard_month";
CREATE VIEW "leaderboard_month" AS
SELECT token, SUM(points) AS points
FROM "leaderboard"
WHERE timestamp >= strftime('%s', 'now', 'start of month')
AND timestamp < strftime('%s', 'now', 'start of month', '+1 month')
GROUP BY token;

--
