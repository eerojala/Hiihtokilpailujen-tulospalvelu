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
    endsAt timestamp
);

CREATE TABLE Participant(
    id SERIAL PRIMARY KEY,
    competitionId INTEGER REFERENCES Competition(id) ON DELETE CASCADE,
    competitorId INTEGER REFERENCES Competitor(id) ON DELETE CASCADE,
    participantNumber INTEGER
);

CREATE TABLE Results(
    participantId INTEGER PRIMARY KEY REFERENCES Participant(id) ON DELETE CASCADE,
    firstSplit TIMESTAMP,
    secondSplit TIMESTAMP,
    finalSplit TIMESTAMP,
    standing INTEGER
);