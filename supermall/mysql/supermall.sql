CREATE TABLE mallcounters (
	customerid INT NOT NULL,
	orderid INT,
	vendorid INT,
	categoryid INT,
	itemnumber INT,
	bookmarknumber INT,
	orderqueid INT,
PRIMARY KEY(customerid)
);

INSERT into mallcounters values ('1','1','1','1','1','1','1');

CREATE TABLE admindata (
	adminusername char(25) DEFAULT 'adminusername' NOT NULL,
	adminpassword char(25) DEFAULT 'adminpassword' NOT NULL,
	authnetuser char(25) DEFAULT 'authnetuser' NOT NULL,
	authnetpass char(25) DEFAULT 'authnetpass' NOT NULL,
	PRIMARY KEY(adminusername)
);

INSERT into admindata values ('admin','admin','admin','admin');


CREATE TABLE itemcategories (
	categoryid INT NOT NULL,
	categoryname char(50) DEFAULT 'categoryname' NOT NULL,
	categorydescription TEXT,
	PRIMARY KEY(categoryid)
);

CREATE TABLE customerbookmarks (
	bookmarknumber INT NOT NULL,
	customerid INT,
	itemnumber INT,
	PRIMARY KEY(bookmarknumber)
);

CREATE TABLE dailystats (
	malldate char(25) DEFAULT 'malldate' NOT NULL,
	pageviews INT,
	bookmarks INT,
	ordersconfirmed INT,
	grosscash char(100) DEFAULT 'grosscash' NOT NULL,
	shipping char(100) DEFAULT 'shipping' NOT NULL,
	tax char(100) DEFAULT 'tax' NOT NULL,
	PRIMARY KEY(malldate)
);

CREATE TABLE vendorbase (
	vendorid INT NOT NULL,
	vendorstatus char(15) DEFAULT 'online' NOT NULL,
	vendorcompanyname char(100) DEFAULT 'vendorcompanyname' NOT NULL,
	vendorcontactname char(100) DEFAULT 'vendorcontactname' NOT NULL,
	vendoraddress char(150) DEFAULT 'vendoraddress' NOT NULL,
	vendorcity char(50) DEFAULT 'vendorcity' NOT NULL,
	vendorstate char(50) DEFAULT 'vendorstate' NOT NULL,
	vendorzip char(12) DEFAULT 'vendorzip' NOT NULL,
	vendorcountry char(50) DEFAULT 'vendorcountry' NOT NULL,
	vendorphone char(25) DEFAULT 'vendorphone' NOT NULL,
	vendorfax char(25) DEFAULT 'vendorfax' NOT NULL,
	vendoremail char(100) DEFAULT 'vendoremail' NOT NULL,
	vendorurl char(150) DEFAULT 'vendorurl' NOT NULL,
	vendorinfo TEXT,
	vendorsalescounter INT,
	PRIMARY KEY(vendorid)
);

CREATE TABLE itembase (
	itemnumber INT NOT NULL,
	vendorid INT,
	categoryid INT,
	itemname char(100) DEFAULT 'itemname' NOT NULL,
	itemdescription TEXT,
	itemstatus char(12) DEFAULT 'online' NOT NULL,
	unitwholesaleprice char(12) DEFAULT 'unitwprice' NOT NULL,
	unitretailprice char(12) DEFAULT 'unitrprice' NOT NULL,
	unitweight char(12) DEFAULT 'unitweight' NOT NULL,
	voldiscount char(5) DEFAULT 'voldiscount' NOT NULL,
	itemtaxstatus char(12) DEFAULT 'tax' NOT NULL,
	taxablestateid char(50) DEFAULT 'taxablestateid' NOT NULL,
	taxrate char(25) DEFAULT 'vendorphone' NOT NULL,
	shippingcalcmethod char(25) DEFAULT 'byqty' NOT NULL,
	shippingrateovernight char(25) DEFAULT 'overnight' NOT NULL,
	shippingratetwoday char(25) DEFAULT 'twoday' NOT NULL,
	shippingrateground char(25) DEFAULT 'ground' NOT NULL,
	itemsalescounter INT,
	PRIMARY KEY(itemnumber)
);

CREATE TABLE customerbase (
	customerid INT NOT NULL,
	customername char(100) DEFAULT 'customername' NOT NULL,
	customeremail char(100) DEFAULT 'customeremail' NOT NULL,
	customeraddress char(150) DEFAULT 'customeraddress' NOT NULL,
	customercity char(100) DEFAULT 'customercity' NOT NULL,
	customerstate char(50) DEFAULT 'customerstate' NOT NULL,
	customerzip char(12) DEFAULT 'customerzip' NOT NULL,
	customercountry char(50) DEFAULT 'customercountry' NOT NULL,
	customerphone char(25) DEFAULT 'customercustomerphone' NOT NULL,
	paymentmethod char(25) DEFAULT 'paymentmethod' NOT NULL,
	creditcardnumber char(25) DEFAULT 'creditcardnumber' NOT NULL,
	expirationdate char(25) DEFAULT 'expirationdate' NOT NULL,
	PRIMARY KEY(customerid)
);

CREATE TABLE orderbase(
	orderid INT NOT NULL,
	customerid INT,
	confirmationurl char(100) DEFAULT 'confirmationurl' NOT NULL,
	orderdate char(50) DEFAULT 'orderdate' NOT NULL,
	shippingmethod char(50) DEFAULT 'shippingmethod' NOT NULL,
	trackingnumber char(100) DEFAULT 'trackingnumber' NOT NULL,
	orderstatus char(100) DEFAULT 'customerstate' NOT NULL,
	ordercomments TEXT,
	PRIMARY KEY(orderid)
);

CREATE TABLE orderque(
	orderqueid INT NOT NULL,
	customerid INT,
	itemnumber INT,
	qty INT,
	vendorque INT,
	PRIMARY KEY(orderqueid)
);