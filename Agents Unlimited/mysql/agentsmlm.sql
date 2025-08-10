CREATE TABLE stats (
	username char(8) DEFAULT 'char' NOT NULL,
	clicks int,
	sales int,
	commission int,
	sdate char(6) DEFAULT 'char' NOT NULL,
	mix char(14) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE byuser (
	username char(8) DEFAULT 'char' NOT NULL,
	clicks int,
	sales int,
	commission int,
	period char(2) DEFAULT 'char' NOT NULL,
	mix char(14) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE overview (
	clicks int,
	sales int,
	commission int,
	period char(2) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(period)
);

CREATE TABLE bydate (
	clicks int,
	sales int,
	commission int,
	sdate char(6) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(sdate)
);

CREATE TABLE bysale (
	username char(8) DEFAULT 'char' NOT NULL,
	commission int,
	tracer char(40) DEFAULT 'char' NOT NULL,
	sdate char(6) DEFAULT 'char' NOT NULL,
	period char(2) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(tracer)
);

CREATE TABLE geneology (
  a char(12) DEFAULT 'a' NOT NULL,
  b char(12) DEFAULT 'b' NOT NULL,
  c char(12) DEFAULT 'c' NOT NULL,
  d char(12) DEFAULT 'd' NOT NULL,
  e char(12) DEFAULT 'e' NOT NULL,
  f char(12) DEFAULT 'f' NOT NULL,
  g char(12) DEFAULT 'g' NOT NULL,
  h char(12) DEFAULT 'h' NOT NULL,
  i char(12) DEFAULT 'i' NOT NULL,
  j char(12) DEFAULT 'j' NOT NULL,
  mix char(25) DEFAULT 'char' NOT NULL,
  PRIMARY KEY (mix)
);

CREATE TABLE mcounter (
  	mcounter char(25) DEFAULT '1000' NOT NULL,
	PRIMARY KEY(mcounter)
);

INSERT INTO mcounter VALUES ('1000');
