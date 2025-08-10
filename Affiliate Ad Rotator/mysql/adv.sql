CREATE TABLE advertisers (
  account char(25) DEFAULT 'account' NOT NULL,
  startdate char(50) DEFAULT 'startdate' NOT NULL,
  username char(12) DEFAULT 'username' NOT NULL,
  password char(12) DEFAULT 'password' NOT NULL,
  email char(50) DEFAULT 'email' NOT NULL,
  expiretype char(25) DEFAULT 'type' NOT NULL,
  startjulean int(11),
  stopjulean int(11),
  priceperclick char(25) DEFAULT 'priceperclick' NOT NULL,
  priceperimpression char(25) DEFAULT 'priceperimp' NOT NULL,
  priceperday char(25) DEFAULT 'priceperday' NOT NULL,
  imageurl char(250) DEFAULT 'imageurl' NOT NULL,
  clickthroughurl char(250) DEFAULT 'clickthroughurl' NOT NULL,
  rimpressions int(25),
  rclickthroughs int(25),
  uimpressions int(25),
  uclickthroughs int(25),
  expire int(25),
  PRIMARY KEY (account)
);

CREATE TABLE system (
  startdate char(25) DEFAULT 'startdate' NOT NULL,
  rimpressions int(25),
  rclickthroughs int(25),
  uimpressions int(25),
  uclickthroughs int(25),
  startjulean int(11),
  accountcounter int(11),
  PRIMARY KEY (startdate)
);
