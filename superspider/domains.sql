CREATE TABLE domains (
	domain char(50) DEFAULT 'domain' NOT NULL,
	PRIMARY KEY(domain)
);
CREATE TABLE targets (
	targets char(250) DEFAULT 'targets' NOT NULL,
	PRIMARY KEY(targets)
);
