INSERT INTO Operator (username, password) VALUES ('eero', '12345');
INSERT INTO Operator (username, password) VALUES ('joq', '67879');

INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('eero', 
DATE '1994-11-22', 'Suomi');
INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('joq', 7
DATE '1111-11-11', 'Ruotsi');

INSERT INTO Competition (competitionName, location, startsAt, endsAt, finished) 
VALUES ('kisa1', 'Espoo, Suomi', TIMESTAMP '2015-09-12 12:00:00', 
TIMESTAMP '2015-09-12 17:00:00', FALSE);

INSERT INTO Competition (competitionName, location, startsAt, endsAt, finished) 
VALUES ('kisa2', 'Mesta, Suomi', TIMESTAMP '1999-12-31 23:00:00',
TIMESTAMP '2000-01-01 01:00:00', TRUE);

INSERT INTO Participant (competitionId, competitorId, competitorNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa2';
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'eero';
    ), 1
);

INSERT INTO Participant (competitionId, competitorId, competitorNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa2';
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'joq';
    ), 2
);

INSERT INTO Results (participantId, participantNumber, firstSplit, secondSplit, 
finalSplit, standing) VALUES(
    (
    SELECT id FROM Participant
    WHERE participantNumber=1
    ),
    (
    SELECT participantNumber FROM Participant
    WHERE participantNumber = 1
    ), TIME '00:27:16:01' , TIME '01:35:56:44', TIME '02:12:01:98', 1
);

INSERT INTO Results (participantId, participantNumber, firstSplit, secondSplit,
finalSplit, standing) VALUES(
    (
    SELECT id FROM Participant
    WHERE participantNumber=2
    ),
    (
    SELECT participantNumber FROM Participant
    WHERE participantNumber = 2
    ), TIME '00:25:99:14', TIME '01:29:44:78', TIME '02:11:32:00', 2
);
