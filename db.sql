-- SQL file with commands to rebuild database if container is destroyed
DROP DATABASE covidDB;
CREATE DATABASE covidDB;

USE covidDB;

CREATE TABLE Patient (
    OHIP        VARCHAR(12) PRIMARY KEY, -- Confirm length
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL,
    dateOfBirth DATE        NOT NULL
);

CREATE TABLE Spouse (
    OHIP        VARCHAR(12) PRIMARY KEY, -- Confirm length
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL,
    phone       VARCHAR(10) NOT NULL, -- Should be number?
    
    patientOHIP    VARCHAR(12) NOT NULL, -- * invalid character?

    FOREIGN KEY (patientOHIP) REFERENCES Patient(OHIP)
        ON UPDATE CASCADE -- If patient OHIP changes, update spouse reference
        ON DELETE CASCADE -- Don't keep spouse without patient
);

CREATE TABLE Company (
    name        VARCHAR(32) PRIMARY KEY,
    address     VARCHAR(64) NOT NULL
);

CREATE TABLE Site (
    name        VARCHAR(32) PRIMARY KEY,
    address     VARCHAR(64) NOT NULL
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
    doses       SMALLINT    NOT NULL,  -- Allows up to 32k doses

    company    VARCHAR(32) NOT NULL,
    site       VARCHAR(32),

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
    phone       VARCHAR(10) NOT NULL -- Data type?
);

CREATE TABLE HealthcareWorker (
    ID          INTEGER     PRIMARY KEY AUTO_INCREMENT, -- Should be UUID/randomly assigned for security?
    firstName   VARCHAR(32) NOT NULL,
    lastName    VARCHAR(32) NOT NULL
);

CREATE TABLE Nurse (
    ID          INTEGER     PRIMARY KEY,

    FOREIGN KEY (ID) REFERENCES HealthcareWorker(ID)
        ON UPDATE CASCADE -- Update if ID changed
        ON DELETE CASCADE -- Delete if parent instance deleted
);

CREATE TABLE Doctor (
    ID         INTEGER     PRIMARY KEY,

    practice   VARCHAR(32) NOT NULL,

    FOREIGN KEY (ID) REFERENCES HealthcareWorker(ID)
        ON UPDATE CASCADE -- Update if ID Changed
        ON DELETE CASCADE, -- Delete if parent instance deleted
    FOREIGN KEY (practice) REFERENCES Practice(name)
        ON UPDATE CASCADE -- Update if name changed
        ON DELETE RESTRICT -- Prevent deletion of the practice if doctor exists
);

CREATE TABLE HCWCredential (
    ID          INTEGER     NOT NULL,
    credential  VARCHAR(4)  NOT NULL, -- How long are creds?

    PRIMARY KEY (ID, credential),

    FOREIGN KEY (ID) REFERENCES HealthcareWorker(ID)
        ON UPDATE CASCADE -- If HCW ID changes update relationship
        ON DELETE CASCADE -- If HCW removed update relationship
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