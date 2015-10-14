CREATE TABLE Operator(
    id SERIAL PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(50) NOT NULL,
    usertype VARCHAR(30),
    realName VARCHAR(50) NOT NULL
);

CREATE TABLE Competitor(
    id SERIAL PRIMARY KEY,
    competitorName VARCHAR(50) NOT NULL,
    birthdate DATE,
    country VARCHAR(30) NOT NULL
);

CREATE TABLE Competition(
    id SERIAL PRIMARY KEY,
    competitionName VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    splitAmount INTEGER NOT NULL,
    startsAt TIMESTAMP,
    endsAt TIMESTAMP
);

CREATE TABLE Recorder(
    id SERIAL PRIMARY KEY,
    competitionId INTEGER REFERENCES Competition(id) ON DELETE CASCADE,
    userId INTEGER REFERENCES Operator(id) ON DELETE CASCADE
);

CREATE TABLE Participant(
    id SERIAL PRIMARY KEY,
    competitionId INTEGER REFERENCES Competition(id) ON DELETE CASCADE,
    competitorId INTEGER REFERENCES Competitor(id) ON DELETE CASCADE,
    participantNumber INTEGER NOT NULL,
    standing INTEGER
);

CREATE TABLE Split(
    id SERIAL PRIMARY KEY,
    participantId INTEGER REFERENCES Participant(id) ON DELETE CASCADE,
    splitNumber INTEGER NOT NULL,
    splitTime INTERVAL NOT NULL
);