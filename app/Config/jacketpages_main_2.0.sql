CREATE DATABASE IF NOT EXISTS JACKETPAGES;
USE JACKETPAGES;
-- DROP DATABASE JACKETPAGES;

/*LOCATIONS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS LOCATIONS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (ID),
    ADDR_LINE_1 varchar(100) NOT NULL DEFAULT '' COMMENT 'A user\'s address.',
    UNIQUE KEY (ID),
    ADDR_LINE_2 varchar(100) NOT NULL DEFAULT '' COMMENT 'A user\'s address contd.',
    COUNTRY varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s country.',
    CITY varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s city.',
    STATE varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s state.',
    ZIP varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s zip code.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of locations related to organizations and users.';

/*INSERT PLACEHOLDING LOCATION*/
INSERT INTO LOCATIONS VALUES (1,'','','','','',0);

/*GROUPS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS GROUPS(
	ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
	PRIMARY KEY(ID),
	GROUP_NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The name of a specific permission group.',
	DESCRIPTION varchar(100) DEFAULT NULL COMMENT 'The description of the permission group.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of organization\'s charters.';

INSERT INTO GROUPS VALUES (1, 'Admin', 'Permission group with unlimited access.');
INSERT INTO GROUPS VALUES (2, 'Power', 'Permission group with delete powers.');
INSERT INTO GROUPS VALUES (3, 'General', 'A general permission group.');

/*USERS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS USERS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (ID),
    GROUP_ID int(11) NOT NULL COMMENT 'A user\'s permission group.',
    FOREIGN KEY (GROUP_ID) REFERENCES GROUPS(ID),
    GT_USER_NAME varchar(50) NOT NULL COMMENT 'The Georgia Tech email prefix passed from OIT.',
    UNIQUE KEY GT_USER_NAME (GT_USER_NAME) COMMENT 'The Georgia Tech email prefix passed from OIT.',
    PHONE varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s phone number.',
    HOME_PHONE varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s home phone number.',
    EMAIL varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s email address.',
    ALT_EMAIL varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s alternate email address.',
    FIRST_NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s first name.',
    LAST_NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s last name.',
    LEVEL varchar(50) DEFAULT '' COMMENT 'The user\'s level. (admin, power, user)',
    LOCAL_ADDR int(11) DEFAULT 1 COMMENT 'The user\'s local address.',
    FOREIGN KEY (LOCAL_ADDR) REFERENCES LOCATIONS(ID),
    HOME_ADDR int(11) DEFAULT 1 COMMENT 'The user\'s home address. (Foreign Key to Locations Table)',
    FOREIGN KEY (HOME_ADDR) REFERENCES LOCATIONS(ID),
    LAST_LOGIN date COMMENT 'The date that the user last logged into Jacketpages.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of user information.'; -- Reset auto-increment when tables are transferred

/*SGA_PEOPLE TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS SGA_PEOPLE(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    USER_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The SGA Person\'s id. (Foreign Key to Users Table)',
    FOREIGN KEY (USER_ID) REFERENCES USERS(ID),
    HOUSE varchar(50) NOT NULL DEFAULT '' COMMENT 'The SGA Person\'s House. (Graduate or Undergraduate)',
    DEPARTMENT varchar(50) NOT NULL DEFAULT '' COMMENT 'The SGA Person\'s Georgia Tech department.',
    STATUS varchar(15) DEFAULT 'Active' COMMENT 'The SGA Person\'s current status. (Active or Inactive)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of SGA representatives information.';

/*BILL_AUTHORS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_AUTHORS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (ID),
    BILL_ID int(11),
    GRAD_AUTH_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Graduate author. (Foreign Key to Users Table)',
    FOREIGN KEY (GRAD_AUTH_ID) REFERENCES USERS(ID),
    GRAD_AUTH_APPR bit DEFAULT 0 COMMENT 'The bill\'s Graduate author\'s approval. (Yes[1] or No[0])',
    UNDR_AUTH_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Undergraduate author. (Foreign Key to Users Table)',
    FOREIGN KEY (UNDR_AUTH_ID) REFERENCES USERS(ID),
    UNDR_AUTH_APPR bit DEFAULT 0 COMMENT 'The bill\'s Undergraduate author\'s approval. (Yes[1] or No[0])',
    GRAD_PRES_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Graduate President\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (GRAD_PRES_ID) REFERENCES USERS(ID),
    GRAD_SECR_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Graduate Secretary\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (GRAD_SECR_ID) REFERENCES USERS(ID),
    UNDR_PRES_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Undergraduate President\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (UNDR_PRES_ID) REFERENCES USERS(ID),
    UNDR_SECR_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Undergaduate Secretary\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (UNDR_SECR_ID) REFERENCES USERS(ID),
    VP_FINA_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The bill\'s Vice President of Finance\'s Signature. (Foreign Key to Users Table)'
    -- FOREIGN KEY (VP_FINA_ID) REFERENCES USERS(ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s authors.';
-- DROP TABLE BILL_AUTHORS;

/*BILL_VOTES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_VOTES(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
    PRIMARY KEY(ID),
    BILL_ID int(11),
    DATE date DEFAULT NULL COMMENT 'The date a bill\'s votes were entered into Jacketpages.',
    YEAS int(11) DEFAULT NULL COMMENT 'The number of yea votes on the bill.',
    NAYS int(11) DEFAULT NULL COMMENT 'The number of nay votes on the bill.',
    ABSTAINS int(11) DEFAULT NULL COMMENT 'The number of abstain votes on the bill.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s votes.';

/*ORGANIZATIONS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS ORGANIZATIONS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
    PRIMARY KEY(ID),
    NAME varchar(200) NOT NULL DEFAULT 'NAME ME!' COMMENT 'The name of the organization.',
    DESCRIPTION text COMMENT 'A description of the organization and it\'s purpose.',
    CATEGORY int(2) NOT NULL DEFAULT 1 COMMENT 'The organization\'s category. (Foreign Key to Categories Table. Default = General)',
    STATUS varchar(50) NOT NULL DEFAULT '' COMMENT 'An organization\'s current status. (Active, Inactive, Frozen)',
    DUES varchar(100) NOT NULL DEFAULT '' COMMENT 'The dues that member\'s of the organization pay.',
    LOGO_NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The name of the organization\'s logo.',
    LOGO_SIZE int(11) NOT NULL DEFAULT 0 COMMENT 'The size of the organization\'s logo.',
    LOGO_TYPE varchar(255) NOT NULL DEFAULT '' COMMENT 'The type of the organization\'s logo.',
    LOGO blob COMMENT 'The organization\'s logo.',
    SHORT_NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'A short name used to identify the organization.',
    WEBSITE varchar(100) NOT NULL DEFAULT '' COMMENT 'The URL for an organization\'s external website.',
    WEBSITE_KEY varchar(100) NOT NULL DEFAULT '' COMMENT 'The website\'s key.',
    ORG_EMAIL varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public email.',
    ADDR_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The organization\'s address. (Foreign Key to Locations Table)',
    FOREIGN KEY (ADDR_ID) REFERENCES LOCATIONS(ID),
    PHONE_NUMBR varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public phone number.',
    FAX_NUMBR varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public fax number.',
    CONTACT_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The organization\'s contact. (Foreign Key to the Users Table)',
    FOREIGN KEY (CONTACT_ID) REFERENCES USERS(ID),
    ANNUAL_EVENTS varchar(1000) NOT NULL DEFAULT '' COMMENT 'Information about the organization\'s annual events.',
    CHARTER int(11) NOT NULL DEFAULT 0 COMMENT 'The organzation\'s charter. (Foreign Key to Charters Table)',
    CHARTER_DATE date NOT NULL DEFAULT '0000-00-00' COMMENT 'The date that the organzation was chartered.',
    ELECTIONS varchar(50) NOT NULL DEFAULT '' COMMENT 'Information about an organzation\'s elections.',
    MEETING_INFO varchar(1000) NOT NULL DEFAULT '' COMMENT 'The organization\'s meeting information.',
    MEETING_FREQUENCY varchar(50) NOT NULL DEFAULT '' COMMENT 'How frequently the organization meets.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of an organization\'s information.';

/*BILL_STATUSES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_STATUSES(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    NAME varchar(15) DEFAULT '' COMMENT 'The bill\'s status or state.',
    DESCRIPTION varchar(100) DEFAULT '' COMMENT 'An explanation of the bill status or state.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s status or state.';

/*INSERT THE 6 DIFFERENT BILL STATUSES/STATES*/
INSERT INTO BILL_STATUSES VALUES 
(1, 'Awaiting Author', 'The bill has just been created and is waiting for Graduate and Undgraduate author approval.'), 
(2, 'Authored', 'The bill has received Graduate and Undergraduate author approval.'), 
(3, 'Agenda', 'The bill has been placed on the Agenda to be reviewed and voted on.'), 
(4,'Passed', 'The bill has passed.'), 
(5,'Failed', 'The bill has failed.'), 
(6,'Tabled', 'The bill has been tabled or frozen for an undefined time period.');
    
/*BILLS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILLS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (ID),
    TITLE varchar(60) COMMENT 'The bill\'s title.',
    DESCRIPTION text COMMENT 'A description of the bill.',
    SUBMIT_DATE date DEFAULT CURDATE() COMMENT 'The date that the bill was submitted.',
    DUES varchar(50) DEFAULT NULL COMMENT 'The dues that the organization associated with the bill collects.',
    FUNDRAISING text COMMENT 'The fundraising effort associated with the bill.',
    NUMBER varchar(50) DEFAULT NULL COMMENT 'The bill\'s identification number.',
    TYPE varchar(50) DEFAULT NULL COMMENT 'The bill\'s type. (Finance Request, Resolution, Budget)',
    CATEGORY varchar(50) DEFAULT NULL COMMENT 'The bill\'s category. (Graduate, Undergraduate, Joint)',
    STATUS int(11) DEFAULT 0 COMMENT 'The bill\'s status or state. (Foreign Key to Bill_Status table)',
    FOREIGN KEY (STATUS) REFERENCES BILL_STATUS(ID),
    JFC_RECOMMENDATIONS text,
    SUBMITTER int(11) NOT NULL DEFAULT 0 COMMENT 'The user who submitted the bill. (Foreign Key to Users Table)',
    FOREIGN KEY (SUBMITTER) REFERENCES USERS(ID),
    LAST_MOD_BY int(11) NOT NULL DEFAULT 0 COMMENT 'The user who last modified the bill. (Foreign Key to Users Table)',
    FOREIGN KEY (LAST_MOD_BY) REFERENCES USERS(ID),
    LAST_MOD_DATE date NOT NULL DEFAULT '0000-00-00' COMMENT 'The date on which the bill was last modified.',
    ORG_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The organization that submitted the bill. (Foreign Key to Organizations Table)',
    FOREIGN KEY (ORG_ID) REFERENCES ORGANIZATIONS(ID),
    AUTH_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The authors who approved or signed the bill. (Foreign Key to Bill_Authors Table)',
    FOREIGN KEY (AUTH_ID) REFERENCES BILL_AUTHORS(ID),
    GSS_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The Graduate Student Senate\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (GSS_ID) REFERENCES BILL_VOTES(ID),
    UHR_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The Undergraduate House of Representative\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (UHR_ID) REFERENCES BILL_VOTES(ID),
    GCC_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The Graduate Conference Committee\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (GCC_ID) REFERENCES BILL_VOTES(ID),
    UCC_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The Undergraduate Conference Committee\'s votes on the bill. (Foreign Key to Bill_Votes Table)'
    -- FOREIGN KEY (UCC_ID) REFERENCES BILL_VOTES(ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of bills submitted to SGA.'; -- Reset auto-increment when tables are transferred  
    
/*CATEGORIES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS CATEGORIES(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'Organization\'s categories.',
    DESCRIPTION varchar(100) DEFAULT NULL COMMENT 'The description of the organziation category.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of the different overarching organization categories.';

/*LINE_ITEMS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS LINE_ITEMS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    BILL_ID int(11) NOT NULL COMMENT 'The line item\'s corresponding bill id. (Foreign Key to Bills Table)',
    FOREIGN KEY (BILL_ID) REFERENCES BILLS(ID),
    PARENT_ID int(11) DEFAULT NULL COMMENT 'The line item\'s reference to it\'s previous or parent version.',
    FOREIGN KEY (PARENT_ID) REFERENCES LINE_ITEMS(ID),
    STATE varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s state. (Submitted, JFC, Undergraduate, Graduate, Conference, Final)',
    NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s name.',
    COST_PER_UNIT float NOT NULL DEFAULT 0 COMMENT 'The cost per one unit of the line item.',
    QUANTITY float NOT NULL DEFAULT 0 COMMENT 'The quantity of that line item.',
    TOTAL_COST float NOT NULL DEFAULT 0 COMMENT 'The total cost of that line item. (COST_PER_UNIT * QUANTITY)',
    AMOUNT float NOT NULL DEFAULT 0 COMMENT 'The amount requested.',
    ACCOUNT varchar(50) NOT NULL DEFAULT '' COMMENT 'The account the line item falls under. (Prior Year, Capital Outlay, ULR, GLR)',
    TYPE varchar(50) NOT NULL DEFAULT '' COMMENT 'The type of line item.',
    COMMENTS text COMMENT 'Any comments that need to be considered with the line item.',
    LINE_NUMBER int(11) NOT NULL DEFAULT 1 COMMENT 'The line item\'s number.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of the line items for a specific bill.';
 
/*MEMBERSHIPS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS MEMBERSHIPS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    USER_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The user to which this membership is connected.',
    FOREIGN KEY (USER_ID) REFERENCES USERS(ID),
    ORG_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The organization to which this membership is connected.',
    FOREIGN KEY (ORG_ID) REFERENCES ORGANIZATIONS(ID),
    ROLE varchar(50) NOT NULL DEFAULT '' COMMENT 'The role of the membership. (President, Treasurer, Officer, Advisor, Member)',
    TITLE varchar(50) NOT NULL DEFAULT '' COMMENT 'The title of the membership.',
    START_DATE date NOT NULL DEFAULT '0000-00-00' COMMENT 'The start date of the membership.',
    END_DATE date NOT NULL DEFAULT '0000-00-00' COMMENT 'The end date of the membership.',
    DUES_PAID date NOT NULL DEFAULT '0000-00-00' COMMENT 'The date the user paid his dues.',
    STATUS varchar(25) NOT NULL DEFAULT '' COMMENT 'The status of the membership. (Active, Inactive, Pending)',
    ROOM_RESERVER varchar(3) NOT NULL DEFAULT '' COMMENT 'Denotes whether the membership has room reserver status or not.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps records of the memberships within an organization.';

/*MESSAGES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS MESSAGES(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    DATE date NOT NULL DEFAULT '0000-00-00' COMMENT 'The date the message was created.',
    MESSAGE text COMMENT 'The announcement or message.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of any public announcements.';

/*CHARTERS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS CHARTERS(
    ID int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(ID),
    ORG_ID int(11) NOT NULL DEFAULT 0 COMMENT 'The organization to which the charter refers.',
    FOREIGN KEY (ORG_ID) REFERENCES ORGANIZATIONS(ID),
    NAME varchar(50) NOT NULL DEFAULT '' COMMENT 'The name of the charter.',
    SIZE int(11) NOT NULL COMMENT 'The size of the charter file.',
    TYPE varchar(200) NOT NULL DEFAULT '' COMMENT 'The type of charter.',
    FILE mediumblob COMMENT 'The body or contents of the charter.',
    UPDATED TIMESTAMP NOT NULL COMMENT 'The date the charter was last updated.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of organization\'s charters.';