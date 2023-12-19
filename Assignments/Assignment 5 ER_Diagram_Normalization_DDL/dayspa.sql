/*
    Creates table for Person
*/
CREATE TABLE Person(
    NAME Char(20) NOT NULL,
    PHONE_# Char(10) NOT NULL,
    ADDRESS Char(30) NOT NULL,
    EMAIL Char(20) NOt NULL,

    Primary Key(NAME)
);

/*
    Creates table for Employees who work at the Day Spa
*/
CREATE TABLE Employee(
    NAME Char(20) NOT NULL,
    PHONE_# Char(10) NOT NULL,
    ADDRESS Char(30) NOT NULL,
    EMAIL Char(20) NOt NULL,
    JOB_TITLE Char(20) NOT NULL,
    PAY_RATE Decimal(4,2) NOT NULL,

    Primary Key(NAME),
    Foreign Key(NAME) References Person(NAME)
);

/*
    Creates table for Clients of the Day Spa
*/
CREATE TABLE Client(
    NAME Char(20) NOT NULL,
    PHONE_# Char(10) NOT NULL,
    ADDRESS Char(30) NOT NULL,
    EMAIL Char(20) NOt NULL,

    Primary Key(NAME),
    Foreign Key(NAME) References Person(NAME)
);

/*
    Creates table for Day Spa Services
*/
CREATE TABLE Service(
    TYPE Char(20) NOT NULL,
    PRICE Decimal(4,2) NOT NULL,
    NAME Char(20) NOT NULL,

    Primary Key(TYPE),
    Foreign Key(NAME) References Person(NAME)
);

/*
    Creates table for Employee's Scheduled work days
*/
CREATE TABLE Scheduled(
    NAME Char(20) NOT NULL,
    DATE Char(9) NOT NULL,

    Primary Key(NAME, DATE),
    Foreign Key(NAME) References Person(NAME)
);

/*
    Creates table for the Employees' Specialties
*/
CREATE TABLE Specializes_In(
    NAME Char(20) NOT NULL,
    TYPE Char(20) NOT NULL,

    Primary Key(NAME),
    Foreign Key(NAME) References Person(NAME),
    Foreign Key(TYPE) References Service(TYPE)
);

/*
    Creates table for what Employee a Client prefers to do a Service for them
*/
CREATE TABLE Prefers(
    NAME Char(20) NOT NULL,
    TYPE Char(20) NOT NULL,

    Primary Key(NAME, TYPE),
    Foreign Key(NAME) References Person(NAME),
    Foreign Key(TYPE) References Service(TYPE)
);

/*
    Creates table for Appointments made by Clients
*/
CREATE TABLE Appointment(
    NAME Char(20) NOT NULL,
    DATE Char(9) NOT NULL,
    TYPE Char(20) NOT NULL,

    Primary Key(NAME, DATE, TYPE),
    Foreign Key(NAME) References Person(NAME),
    Foreign Key(TYPE) References Service(TYPE)
);