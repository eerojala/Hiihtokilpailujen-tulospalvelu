INSERT INTO Operator (username, password) VALUES ('eero', '12345');
INSERT INTO Operator (username, password) VALUES ('joq', '67879');

INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('eero', 
DATE '1994-11-22', 'Suomi');
INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('joq',
DATE '1111-11-11', 'Ruotsi');

INSERT INTO Competition (competitionName, location, startsAt, endsAt, finished) 
VALUES ('kisa1', 'Espoo, Suomi', TIMESTAMP '2015-09-12 12:00:00', 
TIMESTAMP '2015-09-12 17:00:00', TRUE);

INSERT INTO Competition (competitionName, location, startsAt, endsAt, finished) 
VALUES ('kisa2', 'Mesta, Suomi', TIMESTAMP '1999-12-31 23:00:00',
TIMESTAMP '2000-01-01 01:00:00', FALSE);

INSERT INTO Participant (competitionId, competitorId) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'eero'
    )
);

INSERT INTO Participant (competitionId, competitorId) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'joq'
    )
);

INSERT INTO Results (participantId, participantNumber, firstSplit, secondSplit, 
finalSplit, standing) VALUES(
    (
    SELECT Participant.id FROM Participant, Competitor
    WHERE Competitor.id = Participant.competitorId and Competitor.competitorName = 'eero'
    ), 1, TIMESTAMP '2015-09-12 12:27:16.01' , TIMESTAMP '2015-09-12 13:35:56.02', TIMESTAMP '2015-09-12 14:12:01.03', 1
);

INSERT INTO Results (participantId, participantNumber, firstSplit, secondSplit,
finalSplit, standing) VALUES(
    (
    SELECT Participant.id FROM Participant, Competitor
    WHERE Competitor.id = Participant.competitorId and Competitor.competitorName = 'joq'
    ), 2, TIMESTAMP '2015-09-12 12:25:00.04', TIMESTAMP '2015-09-12 13:29:44.05', TIMESTAMP '2015-09-12 14:11:32.06', 2
);
