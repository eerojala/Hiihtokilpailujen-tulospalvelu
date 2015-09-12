CREATE TABLE Operator(
    id SERIAL PRIMARY KEY,
    username varchar(50) NOT null,
    password varchar(50) NOT null
);

CREATE TABLE Competitor(
    id SERIAL PRIMARY KEY,
    competitorName varchar(50) NOT null,
    birthdate DATE,
    country varchar(30) NOT null
);

CREATE TABLE Competition(
    id SERIAL PRIMARY KEY,
    competitionName varchar(100) NOT null,
    location varchar(100) NOT null,
    startsAt timestamp,
    endsAt timestamp,
    finished boolean
);

CREATE TABLE Participant(
    id SERIAL PRIMARY KEY,
    competitionId INTEGER REFERENCES Competition(id),
    competitorId INTEGER REFERENCES Competitor(id)
);

CREATE TABLE Results(
    participantId INTEGER PRIMARY KEY REFERENCES Participant(id),
    participantNumber INTEGER,
    firstSplit TIMESTAMP,
    secondSplit TIMESTAMP,
    finalSplit TIMESTAMP,
    standing INTEGER
);