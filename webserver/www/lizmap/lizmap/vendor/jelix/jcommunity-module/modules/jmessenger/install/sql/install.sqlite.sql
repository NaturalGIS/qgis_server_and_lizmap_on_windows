CREATE TABLE  IF NOT EXISTS  %%PREFIX%%jmessenger (
  id INTEGER NOT NULL PRIMARY KEY,
  id_from int(11) NOT NULL default '0',
  id_for int(11) NOT NULL default '0',
  date datetime NOT NULL,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  isSeen tinyint(4) NOT NULL,
  isArchived tinyint(4) NOT NULL,
  isReceived tinyint(4) NOT NULL,
  isSend tinyint(4) NOT NULL
) ;
