CREATE TABLE dataprofiles (
profilenumber char(25) DEFAULT 'profilenumber' NOT NULL,
AdHeadline char(35) DEFAULT 'AdHeadline' NOT NULL,
EmailAddress char(50) DEFAULT 'EmailAddress' NOT NULL,
TelephoneAreaCode char(3) DEFAULT 'TelephoneAreaCode' NOT NULL,
City char(50) DEFAULT 'City' NOT NULL,
StateProvince char(50) DEFAULT 'StateProvince' NOT NULL,
Zip char(6) DEFAULT 'Zip' NOT NULL,
Country char(50) DEFAULT 'Country' NOT NULL,
RelationshipPreference char(50) DEFAULT 'RelationshipPreference' NOT NULL,
SexualPreference char(50) DEFAULT 'SexualPreference' NOT NULL,
Username char(12) DEFAULT 'Username' NOT NULL,
Password char(12) DEFAULT 'Password' NOT NULL,
VerifyPassword char(12) DEFAULT 'VerifyPassword' NOT NULL,
SmokingPreference char(50) DEFAULT 'SmokingPreference' NOT NULL,
DrinkingPreference char(50) DEFAULT 'DrinkingPreference' NOT NULL,
MaritialStatus char(50) DEFAULT 'MaritialStatus' NOT NULL,
HaveChildren char(50) DEFAULT 'HaveChildren' NOT NULL,
BodyBuild char(50) DEFAULT 'BodyBuild' NOT NULL,
Height char(50) DEFAULT 'Height' NOT NULL,
Religion char(50) DEFAULT 'Religion' NOT NULL,
Race char(50) DEFAULT 'Race' NOT NULL,
AstrologicalSign char(50) DEFAULT 'AstrologicalSign' NOT NULL,
Age char(3) DEFAULT 'Age' NOT NULL,
Occupation char(50) DEFAULT 'Occupation' NOT NULL,
MiscComments char(250) DEFAULT 'MiscComments' NOT NULL,
PRIMARY KEY(Username,EmailAddress)
);

CREATE TABLE bookmarks (
Username char(12) DEFAULT 'Username' NOT NULL,
Bookmark char(12) DEFAULT 'Bookmark' NOT NULL,
mix char(25) DEFAULT 'mix' NOT NULL,
PRIMARY KEY(mix)
);

CREATE TABLE emails (
messagenumber char(25) DEFAULT 'messagenumber' NOT NULL,
Sender char(12) DEFAULT 'Sender' NOT NULL,
Recipient char(12) DEFAULT 'Recipient' NOT NULL,
Date char(25) DEFAULT 'Date' NOT NULL,
Subject char(75) DEFAULT 'Subject' NOT NULL,
Message char(250) DEFAULT 'Message' NOT NULL,
PRIMARY KEY(messagenumber)
);

