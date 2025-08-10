CREATE TABLE stats (
	username char(12) DEFAULT 'char' NOT NULL,
	clicks int,
	sales int,
	commission int,
	sdate char(6) DEFAULT 'char' NOT NULL,
	mix char(14) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE byuser (
	username char(12) DEFAULT 'char' NOT NULL,
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
	username char(12) DEFAULT 'char' NOT NULL,
	commission int,
	tracer char(40) DEFAULT 'char' NOT NULL,
	sdate char(6) DEFAULT 'char' NOT NULL,
	period char(2) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(tracer)
);