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
    competitorId INTEGER REFERENCES Competitor(id),
    participantNumber INTEGER
);

CREATE TABLE Results(
    participantId INTEGER PRIMARY KEY,
    participantNumber INTEGER,
    firstSplit TIME,
    secondSplit TIME,
    finalSplit TIME,
    standing INTEGER,
    FOREIGN KEY (participantId, participantNumber) references Participant(id, participantNumber),
);