-- Import all of the User's information excluding location
INSERT INTO JACKETPAGES.USERS
SELECT ID, 1, GTUSERNAME, PHONE, HOME_PHONE_NUMBER, EMAIL, '', FIRST_NAME, LAST_NAME, LEVEL, 1, 1, NOW()
FROM JACKETPAGES_MAIN.USERS;

-- Import all of the User's Locations
INSERT INTO JACKETPAGES.LOCATIONS (ADDR_LINE_1, ADDR_LINE_2, COUNTRY, CITY, STATE, ZIP)
SELECT DISTINCT LOCAL_ADDRESS_LINE_1, LOCAL_ADDRESS_LINE_2, 'United States', LOCAL_CITY, LOCAL_STATE, LOCAL_ZIP
FROM JACKETPAGES_MAIN.USERS
WHERE LOCAL_ADDRESS_LINE_1 != '';
INSERT INTO JACKETPAGES.LOCATIONS (ADDR_LINE_1, ADDR_LINE_2, COUNTRY, CITY, STATE, ZIP)
SELECT DISTINCT HOME_ADDRESS_LINE_1, HOME_ADDRESS_LINE_2, 'United States', HOME_CITY, HOME_STATE, HOME_ZIP
FROM JACKETPAGES_MAIN.USERS
WHERE HOME_ADDRESS_LINE_1 != '';


-- Connects the Users with their locations
UPDATE JACKETPAGES.USERS U
JOIN JACKETPAGES_MAIN.USERS J 
ON U.ID = J.ID
JOIN JACKETPAGES.LOCATIONS L 
ON J.LOCAL_ADDRESS_LINE_1 = L.ADDR_LINE_1
AND J.LOCAL_ADDRESS_LINE_2 = L.ADDR_LINE_2 SET U.LOCAL_ADDR = L.ID;

UPDATE JACKETPAGES.USERS U
JOIN JACKETPAGES_MAIN.USERS J 
ON U.ID = J.ID
JOIN JACKETPAGES.LOCATIONS L 
ON J.HOME_ADDRESS_LINE_1 = L.ADDR_LINE_1
AND J.HOME_ADDRESS_LINE_2 = L.ADDR_LINE_2 SET U.HOME_ADDR = L.ID;

-- Import all of the organizations
INSERT INTO JACKETPAGES.ORGANIZATIONS
SELECT ID, NAME, DESCRIPTION, 0, STATUS, DUES, LOGO_NAME, LOGO_SIZE, LOGO_TYPE, LOGO, UCASE(SHORT_NAME),
WEBSITE, WEBSITE_KEY, ORGANIZATION_EMAIL, 1, PHONE_NUMBER, FAX_NUMBER, 0, ANNUAL_EVENTS, 0, CHARTER_DATE,
ELECTIONS, MEETING_INFO, MEETING_FREQUENCY
FROM JACKETPAGES_MAIN.ORGANIZATIONS;

-- Import the different organization categories
INSERT INTO JACKETPAGES.CATEGORIES (NAME)
SELECT DISTINCT CATEGORY
FROM JACKETPAGES_MAIN.CATEGORIES;

-- Set the category foreign key
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 1
WHERE C.CATEGORY = 'CPC Sorority';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 2
WHERE C.CATEGORY = 'Cultural/Diversity';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 3
WHERE C.CATEGORY = 'Departmental Sponsored';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 4
WHERE C.CATEGORY = 'Governing Boards';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 5
WHERE C.CATEGORY = 'Honor Society';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 6
WHERE C.CATEGORY = 'IFC Fraternity';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 7
WHERE C.CATEGORY = 'Institute Recognized';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 8
WHERE C.CATEGORY = 'MGC Chapter';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 9
WHERE C.CATEGORY = 'None';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 10
WHERE C.CATEGORY = 'NPHC Chapter';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 11
WHERE C.CATEGORY = 'Production/Performance/Publication';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 12
WHERE C.CATEGORY = 'Professional/Departmental';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 13
WHERE C.CATEGORY = 'Recreational/Sports/Leisure';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 14
WHERE C.CATEGORY = 'Religious/Spiritual';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 15
WHERE C.CATEGORY = 'Service/Political/Educational';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 16
WHERE C.CATEGORY = 'Student Government';
UPDATE JACKETPAGES.ORGANIZATIONS O
INNER JOIN JACKETPAGES_MAIN.ORGANIZATIONS J ON O.ID = J.ID
INNER JOIN JACKETPAGES_MAIN.CATEGORIES C ON C.ORGANIZATION_ID = J.ID SET O.CATEGORY = 17
WHERE C.CATEGORY = 'Umbrella';

-- Import all of the organization's memberships
INSERT INTO JACKETPAGES.MEMBERSHIPS 
SELECT ID, USER_ID, ORGANIZATION_ID, ROLE, TITLE, SINCE, END_DATE, DUESPAID, STATUS, RESERVER
FROM JACKETPAGES_MAIN.MEMBERSHIPS;

-- Import all of the sga_people
INSERT INTO JACKETPAGES.SGA_PEOPLE
SELECT * FROM JACKETPAGES_MAIN.SGA_PEOPLE;

INSERT INTO BILL_VOTES VALUES(1,NULL, NOW(),0,0,0);

-- Import all of the main bill information
INSERT INTO JACKETPAGES.BILLS (ID, TITLE, DESCRIPTION, SUBMIT_DATE, DUES, FUNDRAISING, NUMBER, TYPE, CATEGORY, JFC_RECOMMENDATIONS, SUBMITTER, ORG_ID, GSS_ID, GCC_ID)
SELECT ID, TITLE, DESCRIPTION, SUBMIT_DATE, DUES, FUNDRAISING, NUMBER, TYPE, CATEGORY, JFC_RECOMMENDATIONS, USER_ID, ORGANIZATION_ID, 1, 1
FROM JACKETPAGES_MAIN.BILLS;

-- Import all of the bill statuses
UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 1
WHERE J.STATUS = 'Awaiting Author';

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 2
WHERE J.STATUS = 'Authored';

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 3
WHERE J.STATUS = 'Agenda';

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 4
WHERE J.STATUS = 'Passed';

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 5
WHERE J.STATUS = 'Failed';

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES_MAIN.BILLS J
ON B.ID = J.ID
SET B.STATUS = 6
WHERE J.STATUS = 'Archived';

-- Import all of the bills votes
INSERT INTO JACKETPAGES.BILL_VOTES (BILL_ID, DATE, YEAS, NAYS, ABSTAINS)
SELECT ID, GSS_DATE, IFNULL(GSS_YEAS,0), IFNULL(GSS_NAYS,0), IFNULL(GSS_ABST,0)
FROM JACKETPAGES_MAIN.BILLS WHERE GSS_DATE IS NOT NULL;

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES.BILL_VOTES V
ON B.ID = V.BILL_ID
SET B.GSS_ID = V.ID;

UPDATE JACKETPAGES.BILL_VOTES
SET BILL_ID = NULL;

INSERT INTO JACKETPAGES.BILL_VOTES (BILL_ID, DATE, YEAS, NAYS, ABSTAINS)
SELECT ID, UHR_DATE, IFNULL(UHR_YEAS,0), IFNULL(UHR_NAYS,0), IFNULL(UHR_ABST,0)
FROM JACKETPAGES_MAIN.BILLS WHERE UHR_DATE IS NOT NULL;

UPDATE JACKETPAGES.BILLS B
INNER JOIN JACKETPAGES.BILL_VOTES V
ON B.ID = V.BILL_ID
SET B.UHR_ID = V.ID;

ALTER TABLE BILL_VOTES DROP BILL_ID;

-- Import all of the bills authors
INSERT INTO JACKETPAGES.BILL_AUTHORS (BILL_ID, GRAD_AUTH_ID, GRAD_AUTH_APPR, UNDR_AUTH_ID, UNDR_AUTH_APPR)
SELECT ID, GRADAUTHOR_ID, GRADAUTHORAPPROVED, UNDERAUTHOR_ID, UNDERAUTHORAPPROVED
FROM JACKETPAGES_MAIN.BILLS; -- THESE PROBABLY REFERENCE SGA IDS....

-- Import all of the charters
INSERT INTO JACKETPAGES.DOCUMENTS
SELECT *
FROM JACKETPAGES_MAIN.CHARTERS;

-- Import all of the line items
INSERT INTO JACKETPAGES.LINE_ITEMS
SELECT ID, 1, BILL_ID, PARENT_ID, STATE, NAME, COSTPERUNIT, QUANTITY, TOTALCOST, AMOUNT, ACCOUNT,  'General', NULL
FROM JACKETPAGES_MAIN.LINE_ITEMS;

UPDATE JACKETPAGES.ORGANIZATIONS O
JOIN JACKETPAGES_MAIN.ORGANIZATIONS J
ON O.ID = J.ID
JOIN JACKETPAGES_MAIN.USERS U
ON U.NAME = J.ORGANIZATION_CONTACT
SET O.CONTACT_ID = U.ID;

UPDATE JACKETPAGES.users 
JOIN JACKETPAGES_MAIN.sga_people
ON USERS.ID = SGA_PEOPLE.USER_ID
SET USERS.SGA_ID = SGA_PEOPLE.ID;