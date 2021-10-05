DROP TABLE IF EXISTS "settings";
CREATE TABLE "settings" (
    "key" VARCHAR(254) NOT NULL,
    "value" TEXT NOT NULL
);

INSERT INTO settings ("key", "value")
VALUES (
            "points_increased_messages",
            json_array(
                "¡Felicidades, {user}! Ahora tienes {score} puntos.",
                "¡Lo tienes! Ahora tienes {score} puntos.",
                "Bravo. Ahora tienes {score} puntos.",
                "Bien hecho. Ahora tienes {score} puntos.",
                "¡Gran trabajo, {user}! Ahora tienes {score} puntos.",
                "Exquisito, {user}. Ahora tienes {score} puntos.",
                "Encantador, {user}. Ahora tienes {score} puntos.",
                "Soberbio, {user}. Ahora tienes {score} puntos.",
                "¡Clásico! Ahora tienes {score} puntos, {user}.",
                "Notorio. Ahora tienes {score} puntos, {user}.",
                "¡Bien, bien {user}! Ahora tienes {score} puntos.",
                "Bien jugado {user}. Ahora tienes {score} puntos.",
                "Mis más sinceras felicitaciones, {user}. Ahora tienes {score} puntos.",
                "Delicioso. Ahora tienes {score} puntos.",
                ":nice: {user} ahora tienes {score} puntos.",
                ":nyan: {user} ahora tienes {score} puntos.",
                ":party: {user} ahora tienes {score} puntos."
            )
        ),
       (
            "points_decreased_messages",
            json_array(
               "¿De verdad?, Aún tienes {score} puntos {user}.",
               "Oh :slightly_frowning_face: aún tienes {score} puntos {user}.",
               "Ya veo. Aún tienes {score} puntos {user}.",
               "Ouch. Aún tienes {score} puntos {user}.",
               "Eso duele. Aún tienes {score} puntos {user}.",
               "Oh. Aún tienes {score} puntos {user}.",
               "Que mal. Aún tienes {score} puntos {user}.",
               "Mis condolencias, {user}. Aún tienes {score} puntos.",
               "Suerte para la próxima, {user}, aún tienes {score} puntos.",
               ":trollface: Aún tienes {score} puntos {user}.",
               ":sadpanda: Aún tienes {score} puntos {user}."
            )
       ),
       (
            "points_not_allowed_messages",
            json_array(
               "{user}, hahahahahahaha no.",
               "{user}, nope.",
               "{user}, no. Simplemente no.",
               ":facepalm: {user}",
               ":wat: {user}"
           )
       );
