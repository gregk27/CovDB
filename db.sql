-- SQL file with commands to rebuild database if container is destroyed
DROP DATABASE covidDB;
CREATE DATABASE covidDB;

USE covidDB;

CREATE TABLE Patient (
    OHIP        VARCHAR(12)    PRIMARY KEY, -- 0123456789AB format
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL,
    dateOfBirth DATE
);

CREATE TABLE Spouse (
    OHIP        VARCHAR(12) PRIMARY KEY, 
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL,
    phone       char(10), -- 0123456789 FORMAT
    
    patientOHIP    char(12) NOT NULL,

    FOREIGN KEY (patientOHIP) REFERENCES Patient(OHIP)
        ON UPDATE CASCADE -- If patient OHIP changes, update spouse reference
        ON DELETE CASCADE -- Don't keep spouse without patient
);

CREATE TABLE Company (
    name        VARCHAR(32) PRIMARY KEY,
    street      VARCHAR(33),
    city        VARCHAR(32),
    province    ENUM('AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'NT', 'NU', 'ON', 'PE', 'QC', 'SK', 'YT'),
    postalCode  CHAR(6) -- A1B2C3 format
);

CREATE TABLE Site (
    name        VARCHAR(32) PRIMARY KEY,
    street      VARCHAR(33) NOT NULL,
    city        VARCHAR(32) NOT NULL,
    province    ENUM('AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'NT', 'NU', 'ON', 'PE', 'QC', 'SK', 'YT') NOT NULL,
    postalCode  CHAR(6)     NOT NULL -- A1B2C3 format
);

CREATE TABLE SiteDate (
    site        VARCHAR(32) NOT NULL,
    date        DATE        NOT NULL,

    PRIMARY KEY (site, date),

    FOREIGN KEY (site) REFERENCES Site(name)
        ON UPDATE CASCADE -- If site name changes update relationshi
        ON DELETE CASCADE -- If site removed update relationship
);

CREATE TABLE Lot (
    number      VARCHAR(6)  PRIMARY KEY,
    productionDate  DATE    NOT NULL,
    expiryDate  DATE        NOT NULL,
    doses       INTEGER     NOT NULL,  -- Allows up to 32k doses

    company     VARCHAR(32) NOT NULL,
    site        VARCHAR(32),

    FOREIGN KEY (company)  REFERENCES Company(name)
        ON UPDATE CASCADE   -- If company name changes, update reference
        ON DELETE RESTRICT, -- Don't allow deletion of companies with lots
    FOREIGN KEY (site)     REFERENCES Site(name)
        ON UPDATE CASCADE   -- If site name changes, update reference
        ON DELETE RESTRICT  -- Don't allow deletion of site with lots delivered
);

CREATE TABLE Vaccination (
    patient     VARCHAR(12) NOT NULL,
    site        VARCHAR(32) NOT NULL,
    lot         VARCHAR(6)  NOT NULL,
    datetime    DATETIME    NOT NULL,

    PRIMARY KEY (patient, site, lot),

    FOREIGN KEY (patient) REFERENCES Patient(OHIP)
        ON UPDATE CASCADE  -- Update vax record if patient updated
        ON DELETE CASCADE, -- Delete vax record if patient deleted
    FOREIGN KEY (site)  REFERENCES Site(name)
        ON UPDATE CASCADE   -- Update vax record if site updated
        ON DELETE RESTRICT, -- Prevent the deletion of sites if they have vaccinations
    FOREIGN KEY (lot)   REFERENCES Lot(number)
        ON UPDATE CASCADE  -- Update vax record if lot updated
        ON DELETE RESTRICT -- Prevent the deletion of lots if they have vaccinations
);

CREATE TABLE Practice (
    name        VARCHAR(32) PRIMARY KEY,
    phone       CHAR(10)
);

CREATE TABLE Nurse (
    ID          INTEGER     PRIMARY KEY AUTO_INCREMENT,
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL
);

CREATE TABLE Doctor (
    ID          INTEGER     PRIMARY KEY AUTO_INCREMENT,
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL,

    practice   VARCHAR(32) NOT NULL,

    FOREIGN KEY (practice) REFERENCES Practice(name)
        ON UPDATE CASCADE -- Update if name changed
        ON DELETE RESTRICT -- Prevent deletion of the practice if doctor exists
);

CREATE TABLE NurseCredential (
    ID          INTEGER     NOT NULL,
    credential  VARCHAR(4)  NOT NULL,

    PRIMARY KEY (ID, credential),

    FOREIGN KEY (ID) REFERENCES Nurse(ID)
        ON UPDATE CASCADE -- If Nurse ID changes update relationship
        ON DELETE CASCADE -- If Nurse removed update relationship
);
CREATE TABLE DoctorCredential (
    ID          INTEGER     NOT NULL,
    credential  VARCHAR(4)  NOT NULL,

    PRIMARY KEY (ID, credential),

    FOREIGN KEY (ID) REFERENCES Doctor(ID)
        ON UPDATE CASCADE -- If Doctor ID changes update relationship
        ON DELETE CASCADE -- If Doctor removed update relationship
);

CREATE TABLE NurseWorksAt ( -- How to implement the participation constraint?
    nurse       INTEGER     NOT NULL,
    site        VARCHAR(32) NOT NULL,

    PRIMARY KEY (nurse, site),

    FOREIGN KEY (nurse) REFERENCES Nurse(ID)
        ON UPDATE CASCADE -- If nurse ID changes update relationship
        ON DELETE CASCADE,-- If nurse removed update relationship
    FOREIGN KEY (site) REFERENCES Site(name)
        ON UPDATE CASCADE -- If site name changes update relationship
        ON DELETE CASCADE -- If site removed update relationship
);

CREATE TABLE DoctorWorksAt (
    doctor      INTEGER     NOT NULL,
    site        VARCHAR(32) NOT NULL,

    PRIMARY KEY (doctor, site),

    FOREIGN KEY (doctor) REFERENCES Doctor(ID)
        ON UPDATE CASCADE -- If doctor ID changes update relationship
        ON DELETE CASCADE,-- If doctor removed update relationship
    FOREIGN KEY (site) REFERENCES Site(name)
        ON UPDATE CASCADE -- If site name changes update relationship
        ON DELETE CASCADE -- If site removed update relationship
);

-- DATA

-- Create some sites, generated by https://www.bestrandoms.com/random-address-in-ca
INSERT INTO Site (name, street, city, province, postalCode) VALUES 
    ("Site 1", "4563 Cork St", "Guelph", "ON", "N1H2W8"),
    ("Site 2", "1325 De la Providence Avenue", "Ste Agathe", "QC", "J8C1J5"),
    ("Site 3", "3505 Heritage Drive", "Calgary", "AB", "T2V2W2");

-- Give the sites some dates, generated by https://www.random.org/calendar-dates/
INSERT INTO SiteDate (site, date) VALUES
    ("Site 1", "2021-06-03"),
    ("Site 2", "2021-06-29"),
    ("Site 3", "2021-07-07"),
    ("Site 1", "2021-07-10"),
    ("Site 2", "2021-07-13"),
    ("Site 1", "2021-07-22"),
    ("Site 2", "2021-07-23"),
    ("Site 3", "2021-07-30"),
    ("Site 3", "2021-08-04"),
    ("Site 1", "2021-08-19"),
    ("Site 1", "2021-09-14"),
    ("Site 2", "2021-09-28"),
    ("Site 3", "2021-10-06"),
    ("Site 1", "2021-11-05"),
    ("Site 2", "2021-11-15"),
    ("Site 3", "2021-11-17"),
    ("Site 2", "2021-11-17"),
    ("Site 3", "2021-12-07"),
    ("Site 1", "2021-12-08"),
    ("Site 2", "2021-12-17"),
    ("Site 3", "2021-12-27"),
    ("Site 1", "2022-01-17"),
    ("Site 2", "2022-02-09"),
    ("Site 3", "2022-02-21");

-- Create some nurses with credentials and sites, names are randomly generated
-- Force the ID values to ensure future operations are consistent
INSERT INTO Nurse (ID, firstName, lastName) VALUES
    (1, "Richard", "Coleman"),
    (2, "Dorothy", "Torres"),
    (3, "Kathy",   "Miller"),
    (4, "Kathryn", "White"),
    (5, "Jessica", "Parker"),
    (6, "Phyllis", "Jenkins"),
    (7, "Donna",   "Taylor"),
    (8, "Gloria",  "Bailey"),
    (9, "David",   "Garcia"),
    (10, "Todd",   "Diaz");

INSERT INTO NurseCredential (ID, credential) VALUES
    (1, "RN"),
    (2, "NP"),
    (3, "RPN"),
    (5, "RPN"),
    (5, "NP"),
    (6, "RN"),
    (6, "NP"),
    (7, "NP"),
    (7, "RN"),
    (7, "RPN");

INSERT INTO NurseWorksAt (nurse, site) VALUES 
    (1,  "Site 1"),
    (2,  "Site 2"),
    (3,  "Site 3"),
    (4,  "Site 1"),
    (4,  "Site 2"),
    (5,  "Site 2"),
    (5,  "Site 3"),
    (6,  "Site 1"),
    (6,  "Site 3"),
    (7,  "Site 1"),
    (7,  "Site 2"),
    (7,  "Site 3"),
    (8,  "Site 1"),
    (8,  "Site 2"),
    (9,  "Site 1"),
    (9,  "Site 3"),
    (10, "Site 1"),
    (10, "Site 2"),
    (10, "Site 3");

-- Create practices for doctors to work at
INSERT INTO Practice (name, phone) VALUES
    ("Practice 1", "0123456789"),
    ("Practice 2", "4567890123"),
    ("Practice 3", "7890123456");

-- Create some doctors with credentials and sites, names are randomly generated
-- Force the ID values to ensure future operations are consistent
INSERT INTO Doctor (ID, firstName, lastName, practice) VALUES
    (1, "Michelle", "Murphy", "Practice 1"),
    (2, "George",   "Nelson", "Practice 2"),
    (3, "Steve",    "Gray",   "Practice 3"),
    (4, "Brian",    "Hall",   "Practice 2"),
    (5, "Tina",     "Watson", "Practice 3");

INSERT INTO DoctorCredential (ID, credential) VALUES
    (1, "MD"),
    (2, "CM"),
    (3, "MD"),
    (3, "CM"),
    (5, "MB");

INSERT INTO DoctorWorksAt (doctor, site) VALUES 
    (1, "Site 1"),
    (2, "Site 2"),
    (3, "Site 3"),
    (4, "Site 1"),
    (4, "Site 2"),
    (4, "Site 3");

-- Create Vaccine Companies
INSERT INTO Company (name, street, city, province, postalCode) VALUES 
    ("Pfizer", "17300 Trans-Canada Hwy", "Kirkland", "QC", "H9J2M5"),
    ("Moderna", NULL, NULL, NULL, NULL),
    ("Astrazeneca", "1004 Middlegate Rd", "Mississauga", "ON", "L4Y1M4"),
    ("Johnson & Johnson", "88 McNabb St", "Markham", "ON", "L3R4N6");

-- Create lots of vaccine
-- TODO: How big should lots be?
-- TODO: Should lots be delivered to one or many sites
INSERT INTO Lot (number, doses, company, productionDate, expiryDate, site) VALUES 
    ("PF0097", 35, "Pfizer",            "2021-06-09", "2021-12-23", "Site 1"),
    ("MO0045", 50, "Moderna",           "2021-06-22", "2022-02-18", "Site 2"),
    ("AZ0056", 81, "Astrazeneca",       "2021-07-09", "2022-02-23", "Site 3"),
    ("JJ0415", 25, "Johnson & Johnson", "2021-07-12", "2022-03-04", "Site 2"),
    ("MO0079", 93, "Moderna",           "2021-08-02", "2022-03-10", "Site 3"),
    ("AZ0253", 45, "Astrazeneca",       "2021-08-23", "2022-04-06", "Site 1"),
    ("PF0684", 35, "Pfizer",            "2021-09-03", "2022-04-21", "Site 3"),
    ("JJ0531", 78, "Johnson & Johnson", "2021-09-22", "2022-08-16", "Site 1"),
    ("AZ0656", 35, "Astrazeneca",       "2021-09-27", "2022-08-19", "Site 2"),
    ("PF1347", 34, "Pfizer",            "2021-10-18", "2022-09-28", "Site 1"),
    ("MO0981", 46, "Moderna",           "2021-11-03", "2022-10-14", "Site 2"),
    ("JJ1324", 86, "Johnson & Johnson", "2021-11-26", "2022-11-16", "Site 3"),
    ("PF1985", 37, "Pfizer",            "2022-01-24", "2022-12-20", NULL), -- Most recent doeses undelivered
    ("MO1258", 68, "Moderna",           "2022-01-28", "2022-12-21", NULL),
    ("AZ1722", 86, "Astrazeneca",       "2022-01-31", "2022-12-30", NULL);

-- Generate patients, again everything is randomly generated
INSERT INTO Patient (OHIP, firstName, lastName, dateOfBirth) VALUES
    ("9105776123WQ", "Marguerite","Bass",    "1965-02-14"),
    ("9312153103FV", "Russell",   "Wade",    "1967-06-09"),
    ("5032767284HN", "Teri",      "Elliott", "1967-08-31"),
    ("5073928910QW", "Nick",      "Ellis",   "1968-12-28"),
    ("6807962640IK", "Christine", "Coleman", "1972-01-06"),
    ("6617139937Z" , "Gregg",     "Carroll", "1972-05-02"),
    ("7462257828HY", "Randolph",  "Burke",   "1974-07-21"),
    ("5946121838CV", "Kelly",     "Osborne", "1976-12-01"),
    ("2808599886"  , "Rebecca",   "Hoffman", "1977-11-30"),
    ("1723284246HY", "Kara",      "Francis", "1977-12-12"),
    ("3148613050IK", "Moses",     "Schwartz","1978-11-13"),
    ("9276866509IK", "Jeffrey",   "Garza",   "1980-01-26"),
    ("5593795262HN", "Bryant",    "Copeland","1980-06-18"),
    ("3949215294ZX", "Samantha",  "Payne",   "1983-04-28"),
    ("3041640915"  , "Patricia",  "Mcbride", "1984-01-13"),
    ("1148960149UI", "Alfredo",   "Taylor",  "1988-04-09"),
    ("6893711174QW", "Jim",       "Rice",    "1990-04-04"),
    ("2313661373Z" , "Tami",      "Austin",  "1991-10-16"),
    ("1282866856HN", "Herbert",   "Fox",     "1994-09-13"),
    ("8156639125QW", "Virginia",  "Ballard", "1995-10-21"),
    ("3671530046"  , "Andy",      "Dawson",  "1997-07-15"),
    ("2740181330UI", "Doris",     "Franklin","1998-01-03"),
    ("7328810635UI", "Levi",      "Clayton", "2001-06-09"),
    ("3120625906Z" , "Sonja",     "Rodgers", "2002-01-11"),
    ("6491122755QW", "Jody",      "Horton",  "2003-07-19");

-- Create spouses for patients
INSERT INTO Spouse (OHIP, patientOHIP, firstName, lastName, phone) VALUES
    ("7869351874WQ", "9105776123WQ", "Colin",   "Graham",   "2047743518"),
    ("6166055520HN", "9312153103FV", "Rebecca", "Little",   "5879279685"),
    ("1256279414IK", "5032767284HN", "Tomas",   "Barnes",   "2506775807"),
    ("8775273832Z" , "5073928910QW", "Lorene",  "Mckinney", "5148841279"),
    ("5685194580HY", "6807962640IK", "Clinton", "Lopez",    "5194043581"),
    ("8824434929WQ", "6617139937Z" , "Sharon",  "Flowers",  "6133311904"),
    ("2433934773"  , "7462257828HY", "Katie",   "Goodwin",  "6043053282"),
    ("9380595193Z" , "5946121838CV", "Robin",   "Ball",     "7809192524"),
    ("8474312793HN", "2808599886"  , "Winifred","Mann",     "3062476936"),
    ("3572967692IK", "1723284246HY", "Gordon",  "Shelton",  "2894832451"),
    ("3037094262HY", "3148613050IK", "Cornelius","Stephens","6044971886"),
    ("6193515011WQ", "9276866509IK", "Jeremiah", "Lewis",   "2503686224");

-- Create vaccination records for patients
-- NOTE: Dates should correspond with site dates and vaccine production/expiry, but distance between sites is ignored
INSERT INTO Vaccination (patient, lot, site, datetime) VALUES
    ("9105776123WQ", "PF0097", "Site 1", "2021-07-10"),
    ("9105776123WQ", "PF0684", "Site 3", "2021-10-06"),
    ("9105776123WQ", "MO0981", "Site 2", "2021-12-17"),
    ("9312153103FV", "MO0045", "Site 2", "2021-07-13"),
    ("9312153103FV", "MO0981", "Site 2", "2021-12-17"),
    ("5032767284HN", "AZ0056", "Site 3", "2021-07-30"),
    ("5032767284HN", "PF0684", "Site 3", "2021-10-06"),
    ("5032767284HN", "MO0981", "Site 2", "2021-12-17"),
    ("5073928910QW", "PF0097", "Site 1", "2021-07-10"),
    ("5073928910QW", "PF0684", "Site 3", "2021-10-06"),
    ("5073928910QW", "JJ1324", "Site 3", "2022-01-21"),
    ("6807962640IK", "MO0045", "Site 2", "2021-07-13"),
    ("6807962640IK", "PF0684", "Site 3", "2021-10-06"),
    ("6807962640IK", "JJ1324", "Site 3", "2022-01-21"),
    ("6617139937Z" , "AZ0056", "Site 3", "2021-07-30"),
    ("6617139937Z" , "PF0684", "Site 3", "2021-11-17"),
    ("7462257828HY", "AZ0253", "Site 1", "2021-09-14"),
    ("7462257828HY", "JJ1324", "Site 3", "2021-12-27"),
    ("5946121838CV", "AZ0656", "Site 2", "2021-09-28"),
    ("5946121838CV", "MO0981", "Site 2", "2022-01-09"),
    ("2808599886"  , "PF0684", "Site 3", "2021-10-06"),
    ("2808599886"  , "MO0981", "Site 2", "2022-01-09"),
    ("1723284246HY", "AZ0253", "Site 1", "2021-09-14"),
    ("1723284246HY", "JJ1324", "Site 3", "2021-12-27"),
    ("3148613050IK", "AZ0656", "Site 2", "2021-09-28"),
    ("3148613050IK", "JJ1324", "Site 3", "2021-12-27"),
    ("9276866509IK", "PF0684", "Site 3", "2021-10-06"),
    ("9276866509IK", "MO0981", "Site 2", "2022-01-09"),
    ("5593795262HN", "PF0097", "Site 1", "2021-07-10"),
    ("5593795262HN", "PF0684", "Site 3", "2021-11-17"),
    ("3949215294ZX", "JJ0415", "Site 2", "2021-07-13"),
    ("3949215294ZX", "PF0684", "Site 3", "2021-11-17"),
    ("3041640915"  , "AZ0056", "Site 3", "2021-07-30"),
    ("3041640915"  , "PF0684", "Site 3", "2021-11-17"),
    ("1148960149UI", "MO0981", "Site 2", "2021-11-15"),
    ("6893711174QW", "PF0684", "Site 3", "2021-11-17"),
    ("2313661373Z" , "JJ1324", "Site 3", "2021-12-07"),
    ("1282866856HN", "PF1347", "Site 1", "2021-12-08"),
    ("8156639125QW", "MO0981", "Site 2", "2021-12-17"),
    ("3671530046"  , "JJ1324", "Site 3", "2021-12-07"),
    ("2740181330UI", "PF1347", "Site 1", "2021-12-08"),
    ("7328810635UI", "MO0981", "Site 2", "2021-12-17"),
    ("3120625906Z" , "MO0981", "Site 2", "2022-01-09"),
    ("6491122755QW", "JJ1324", "Site 3", "2022-01-21");