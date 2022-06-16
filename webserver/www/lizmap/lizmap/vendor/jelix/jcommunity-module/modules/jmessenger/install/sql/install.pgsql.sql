CREATE TABLE IF NOT EXISTS %%PREFIX%%jmessenger (
    id serial NOT NULL,
    id_from integer DEFAULT 0 NOT NULL,
    id_for integer DEFAULT 0 NOT NULL,
    date timestamp without time zone NOT NULL,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    "isSeen" smallint NOT NULL,
    "isArchived" smallint NOT NULL,
    "isReceived" smallint NOT NULL,
    "isSend" smallint NOT NULL,
    CONSTRAINT jmessenger_pkey PRIMARY KEY (id)
);


