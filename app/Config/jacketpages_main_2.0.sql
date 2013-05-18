CREATE DATABASE IF NOT EXISTS JACKETPAGES;
USE JACKETPAGES;
-- DROP DATABASE JACKETPAGES;

/*LOCATIONS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS LOCATIONS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (id),
    addr_line_1 varchar(100) NOT NULL DEFAULT '' COMMENT 'A user\'s address.',
    UNIQUE KEY (id),
    addr_line_2 varchar(100) NOT NULL DEFAULT '' COMMENT 'A user\'s address contd.',
    country varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s country.',
    city varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s city.',
    state varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s state.',
    zip varchar(50) NOT NULL DEFAULT '' COMMENT 'A user\'s zip code.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of locations related to organizations and users.';

/*USERS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS USERS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (id),
    sga_id int(11),
    gt_user_name varchar(50) NOT NULL COMMENT 'The Georgia Tech email prefix passed from OIT.',
    UNIQUE KEY gt_user_name (gt_user_name) COMMENT 'The Georgia Tech email prefix passed from OIT.',
    phone varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s phone number.',
    home_phone varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s home phone number.',
    email varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s email address.',
    alt_email varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s alternate email address.',
    first_name varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s first name.',
    last_name varchar(50) NOT NULL DEFAULT '' COMMENT 'The user\'s last name.',
    level varchar(50) DEFAULT '' COMMENT 'The user\'s level. (admin, power, user)',
    local_addr int(11) DEFAULT 1 COMMENT 'The user\'s local address.',
    FOREIGN KEY (LOCAL_ADDR) REFERENCES LOCATIONS(id),
    home_addr int(11) DEFAULT 1 COMMENT 'The user\'s home address. (Foreign Key to Locations Table)',
    FOREIGN KEY (home_addr) REFERENCES LOCATIONS(id),
    last_login TIMESTAMP COMMENT 'The date that the user last logged into Jacketpages.',
    profile_picture varchar(200) NULL COMMENT 'The path to the user\'s profile picture.',
    profile_text text NULL COMMENT 'The user\'s text for their profile.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of user information.'; -- Reset auto-increment when tables are transferred

/*SGA_PEOPLE TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS SGA_PEOPLE(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    user_id int(11) NOT NULL DEFAULT 0 COMMENT 'The SGA Person\'s id. (Foreign Key to Users Table)',
    FOREIGN KEY (user_id) REFERENCES USERS(id),
    house varchar(50) NOT NULL DEFAULT '' COMMENT 'The SGA Person\'s House. (Graduate or Undergraduate)',
    department varchar(50) NOT NULL DEFAULT '' COMMENT 'The SGA Person\'s Georgia Tech department.',
    status varchar(15) DEFAULT 'Active' COMMENT 'The SGA Person\'s current status. (Active or Inactive)',
	 profile_picture varchar(200) NULL COMMENT 'The path to the SGA Person\'s profile picture.',
    profile_text text NULL COMMENT 'The SGA Person\'s text for their profile.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of SGA representatives information.';

/*BILL_AUTHORS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_AUTHORS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (id),
    bill_id int(11),
    grad_auth_id int(11) DEFAULT 0 COMMENT 'The bill\'s Graduate author. (Foreign Key to Users Table)',
    -- FOREIGN KEY (grad_auth_id) REFERENCES SGA_PEOPLE(id),
    grad_auth_appr bit DEFAULT 0 COMMENT 'The bill\'s Graduate author\'s approval. (Yes[1] or No[0])',
    undr_auth_id int(11) DEFAULT 0 COMMENT 'The bill\'s Undergraduate author. (Foreign Key to Users Table)',
    -- FOREIGN KEY (undr_auth_id) REFERENCES SGA_PEOPLE(id),
    undr_auth_appr bit DEFAULT 0 COMMENT 'The bill\'s Undergraduate author\'s approval. (Yes[1] or No[0])',
    grad_pres_id int(11) DEFAULT 0 COMMENT 'The bill\'s Graduate President\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (GRAD_PRES_ID) REFERENCES USERS(id),
    grad_secr_id int(11) DEFAULT 0 COMMENT 'The bill\'s Graduate Secretary\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (GRAD_SECR_ID) REFERENCES USERS(id),
    undr_pres_id int(11) DEFAULT 0 COMMENT 'The bill\'s Undergraduate President\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (UNDR_PRES_ID) REFERENCES USERS(id),
    undr_secr_id int(11) DEFAULT 0 COMMENT 'The bill\'s Undergaduate Secretary\'s Signature. (Foreign Key to Users Table)',
    -- FOREIGN KEY (UNDR_SECR_ID) REFERENCES USERS(id),
    vp_fina_id int(11) DEFAULT 0 COMMENT 'The bill\'s Vice President of Finance\'s Signature. (Foreign Key to Users Table)'
    -- FOREIGN KEY (VP_FINA_ID) REFERENCES USERS(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s authors.';
-- DROP TABLE BILL_AUTHORS;

/*BILL_VOTES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_VOTES(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
    PRIMARY KEY(id),
    bill_id int(11),
    date date DEFAULT NULL COMMENT 'The date a bill\'s votes were entered into Jacketpages.',
    yeas int(11) DEFAULT NULL COMMENT 'The number of yea votes on the bill.',
    nays int(11) DEFAULT NULL COMMENT 'The number of nay votes on the bill.',
    abstains int(11) DEFAULT NULL COMMENT 'The number of abstain votes on the bill.',
    comments text COMMENT 'Any comments that need to be considered with the bill.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s votes.';

/*ORGANIZATIONS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS ORGANIZATIONS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
    PRIMARY KEY(id),
    name varchar(200) NOT NULL DEFAULT 'NAME ME!' COMMENT 'The name of the organization.',
    description text COMMENT 'A description of the organization and it\'s purpose.',
    category int(2) NOT NULL DEFAULT 1 COMMENT 'The organization\'s category. (Foreign Key to Categories Table. Default = General)',
    status varchar(50) NOT NULL DEFAULT '' COMMENT 'An organization\'s current status. (Active, Inactive, Frozen)',
    dues varchar(100) NOT NULL DEFAULT '' COMMENT 'The dues that member\'s of the organization pay.',
    logo_path varchar(200) NULL COMMENT 'The path to the organization\'s logo.',
    short_name varchar(50) NOT NULL DEFAULT '' COMMENT 'A short name used to identify the organization.',
    website varchar(100) NOT NULL DEFAULT '' COMMENT 'The URL for an organization\'s external website.',
    org_email varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public email.',
    addr_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization\'s address. (Foreign Key to Locations Table)',
    FOREIGN KEY (addr_id) REFERENCES LOCATIONS(id),
    phone_number varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public phone number.',
    fax_number varchar(100) NOT NULL DEFAULT '' COMMENT 'The organization\'s public fax number.',
    contact_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization\'s contact. (Foreign Key to the Users Table)',
    FOREIGN KEY (CONTACT_ID) REFERENCES USERS(id),
    annual_events varchar(1000) NOT NULL DEFAULT '' COMMENT 'Information about the organization\'s annual events.',
    charter int(11) NOT NULL DEFAULT 0 COMMENT 'The organzation\'s charter. (Foreign Key to Charters Table)',
    charter_date date NOT NULL DEFAULT '0000-00-00' COMMENT 'The date that the organzation was chartered.',
    elections varchar(50) NOT NULL DEFAULT '' COMMENT 'Information about an organzation\'s elections.',
    meeting_information varchar(1000) NOT NULL DEFAULT '' COMMENT 'The organization\'s meeting information.',
    meeting_frequency varchar(50) NOT NULL DEFAULT '' COMMENT 'How frequently the organization meets.',
    alcohol_form date NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of an organization\'s information.';

/*BILL_STATUSES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILL_STATUSES(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    name varchar(15) DEFAULT '' COMMENT 'The bill\'s status or state.',
    description varchar(100) DEFAULT '' COMMENT 'An explanation of the bill status or state.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of a bill\'s status or state.';
    
/*BILLS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS BILLS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY (id),
    title varchar(60) COMMENT 'The bill\'s title.',
    description text COMMENT 'A description of the bill.',
    submit_date date DEFAULT NULL COMMENT 'The date that the bill was submitted.',
    dues varchar(50) DEFAULT NULL COMMENT 'The dues that the organization associated with the bill collects.',
    fundraising text COMMENT 'The fundraising effort associated with the bill.',
    number varchar(50) DEFAULT NULL COMMENT 'The bill\'s identification number.',
    type varchar(50) DEFAULT NULL COMMENT 'The bill\'s type. (Finance Request, Resolution, Budget)',
    category varchar(50) DEFAULT NULL COMMENT 'The bill\'s category. (Graduate, Undergraduate, Joint)',
    status int(11) DEFAULT 0 COMMENT 'The bill\'s status or state. (Foreign Key to Bill_Status table)',
    FOREIGN KEY (status) REFERENCES BILL_STATUSES(id),
    jfc_recommendations text,
    submitter int(11) NOT NULL DEFAULT 0 COMMENT 'The user who submitted the bill. (Foreign Key to Users Table)',
    FOREIGN KEY (submitter) REFERENCES USERS(id),
    last_mod_by int(11) NOT NULL DEFAULT 0 COMMENT 'The user who last modified the bill. (Foreign Key to Users Table)',
    FOREIGN KEY (last_mod_by) REFERENCES USERS(id),
    last_mod_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The date on which the bill was last modified.',
    org_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization that submitted the bill. (Foreign Key to Organizations Table)',
    FOREIGN KEY (org_id) REFERENCES ORGANIZATIONS(id),
    auth_id int(11) NOT NULL DEFAULT 0 COMMENT 'The authors who approved or signed the bill. (Foreign Key to Bill_Authors Table)',
    FOREIGN KEY (auth_id) REFERENCES BILL_AUTHORS(id),
    gss_id int(11) NOT NULL DEFAULT 0 COMMENT 'The Graduate Student Senate\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (GSS_ID) REFERENCES BILL_VOTES(id),
    uhr_id int(11) NOT NULL DEFAULT 0 COMMENT 'The Undergraduate House of Representative\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (UHR_ID) REFERENCES BILL_VOTES(id),
    gcc_id int(11) NOT NULL DEFAULT 0 COMMENT 'The Graduate Conference Committee\'s votes on the bill. (Foreign Key to Bill_Votes Table)',
    -- FOREIGN KEY (GCC_ID) REFERENCES BILL_VOTES(id),
    ucc_id int(11) NOT NULL DEFAULT 0 COMMENT 'The Undergraduate Conference Committee\'s votes on the bill. (Foreign Key to Bill_Votes Table)'
    -- FOREIGN KEY (UCC_ID) REFERENCES BILL_VOTES(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of bills submitted to SGA.'; -- Reset auto-increment when tables are transferred  
    
/*CATEGORIES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS CATEGORIES(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    name varchar(50) NOT NULL DEFAULT '' COMMENT 'Organization\'s categories.',
    description varchar(100) DEFAULT NULL COMMENT 'The description of the organziation category.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of the different overarching organization categories.';

/*LINE_ITEMS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS LINE_ITEMS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
	 line_number int(11) NOT NULL DEFAULT 1 COMMENT 'The line item\'s number.',
    bill_id int(11) NOT NULL COMMENT 'The line item\'s corresponding bill id. (Foreign Key to Bills Table)',
    FOREIGN KEY (bill_id) REFERENCES BILLS(id) ON DELETE CASCADE,
    parent_id int(11) DEFAULT NULL COMMENT 'The line item\'s reference to it\'s previous or parent version.',
    FOREIGN KEY (parent_id) REFERENCES LINE_ITEMS(id),
    state varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s state. (Submitted, JFC, Undergraduate, Graduate, Conference, Final)',
    name varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s name.',
    cost_per_unit DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The cost per one unit of the line item.',
    quantity int(7) NOT NULL DEFAULT 0 COMMENT 'The quantity of that line item.',
    total_cost DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The total cost of that line item. (COST_PER_UNIT * QUANTITY)',
    amount DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The amount requested.',
    account varchar(50) NOT NULL DEFAULT '' COMMENT 'The account the line item falls under. (Prior Year, Capital Outlay, ULR, GLR)',
    type varchar(50) NOT NULL DEFAULT '' COMMENT 'The type of line item.',
    comments text COMMENT 'Any comments that need to be considered with the line item.',
    struck bit COMMENT 'The line item has been struck, has been deleted but deletion needs to be recorded on actual bill.',
    last_mod_by int(11) NOT NULL DEFAULT 0 COMMENT 'The user who last modified the line item. (Foreign Key to Users Table)',
	 last_mod_date timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The date on which the line item was last modified.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of the line items for a specific bill.';
 
/*MEMBERSHIPS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS MEMBERSHIPS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    user_id int(11) NOT NULL DEFAULT 0 COMMENT 'The user to which this membership is connected.',
    FOREIGN KEY (user_id) REFERENCES USERS(id),
    org_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization to which this membership is connected.',
    FOREIGN KEY (org_id) REFERENCES ORGANIZATIONS(id),
    role varchar(50) NOT NULL DEFAULT '' COMMENT 'The role of the membership. (President, Treasurer, Officer, Advisor, Member)',
    title varchar(50) NOT NULL DEFAULT '' COMMENT 'The title of the membership.',
    start_date date NOT NULL DEFAULT '0000-00-00' COMMENT 'The start date of the membership.',
    end_date date NULL COMMENT 'The end date of the membership.',
    dues_paid date NULL COMMENT 'The date the user paid his dues.',
    status varchar(25) NOT NULL COMMENT 'The status of the membership. (Active, Inactive, Pending)',
    room_reserver varchar(3) NOT NULL DEFAULT '' COMMENT 'Denotes whether the membership has room reserver status or not.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps records of the memberships within an organization.';

/*MESSAGES TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS MESSAGES(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    begin_date date NOT NULL COMMENT 'The date the message was created.',
    end_date date NOT NULL COMMENT 'The date the message was created.',
    message text COMMENT 'The announcement or message.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of any public announcements.';

/*DOCUMENTS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS DOCUMENTS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    org_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization to which the document refers.',
    FOREIGN KEY (org_id) REFERENCES ORGANIZATIONS(id),
    name varchar(100) NOT NULL DEFAULT '' COMMENT 'The name of the document.',
    path varchar(200) NOT NULL DEFAULT '' COMMENT 'The path to the document.',
    last_updated TIMESTAMP NOT NULL COMMENT 'The date the document was last updated.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of organization\'s documents.';

/*LINE_ITEM_REVISIONS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS LINE_ITEM_REVISIONS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
	line_item_id int(11) NOT NULL COMMENT 'The line items old id.',
	line_number int(11) NOT NULL DEFAULT 1 COMMENT 'The line item\'s number.',
    bill_id int(11) NOT NULL COMMENT 'The line item\'s corresponding bill id. (Foreign Key to Bills Table)',
    FOREIGN KEY (bill_id) REFERENCES BILLS(id),
    parent_id int(11) DEFAULT NULL COMMENT 'The line item\'s reference to it\'s previous or parent version.',
    FOREIGN KEY (parent_id) REFERENCES LINE_ITEMS(id),
    state varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s state. (Submitted, JFC, Undergraduate, Graduate, Conference, Final)',
    name varchar(50) NOT NULL DEFAULT '' COMMENT 'The line item\'s name.',
    cost_per_unit DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The cost per one unit of the line item.',
    quantity DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The quantity of that line item.',
    total_cost DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The total cost of that line item. (COST_PER_UNIT * QUANTITY)',
    amount DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'The amount requested.',
    account varchar(50) NOT NULL DEFAULT '' COMMENT 'The account the line item falls under. (Prior Year, Capital Outlay, ULR, GLR)',
    type varchar(50) NOT NULL DEFAULT '' COMMENT 'The type of line item.',
    comments text COMMENT 'Any comments that need to be considered with the line item.',
	 revision int(2) NOT NULL DEFAULT 1 COMMENT 'The line item\'s revision number.',
	 struck bit COMMENT 'The line item has been struck, has been deleted but deletion needs to be recorded on actual bill.',
	 deleted bit DEFAULT 0 COMMENT 'The line item has been deleted permanently from the original bill.',
	 mod_by int(11) NOT NULL DEFAULT 0 COMMENT 'The user who modified the line item. (Foreign Key to Users Table)',
	 mod_date timestamp NOT NULL DEFAULT '0000-00-00' COMMENT 'The date on which the line item was modified.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of the line item revisions for a specific bill.';

/*ORG_DOCUMENTS TABLE STRUCTURE*/
CREATE TABLE IF NOT EXISTS ORG_DOCUMENTS(
    id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
    PRIMARY KEY(id),
    org_id int(11) NOT NULL DEFAULT 0 COMMENT 'The organization to which the document refers.',
    FOREIGN KEY (org_id) REFERENCES ORGANIZATIONS(id),
    name varchar(50) NOT NULL DEFAULT '' COMMENT 'The name of the document.',
    path varchar(200) NOT NULL DEFAULT '' COMMENT 'The path to the document.',
    last_updated TIMESTAMP NOT NULL COMMENT 'The date the document was last updated.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'Keeps record of organization\'s documents.';