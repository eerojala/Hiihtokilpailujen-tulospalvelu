INSERT INTO Operator (username, password) VALUES ('admin', '12345');
INSERT INTO Operator (username, password) VALUES ('joq', '67879');

INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('eero', 
DATE '1994-11-22', 'Suomi');
INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('joq',
DATE '1111-11-11', 'Ruotsi');

INSERT INTO Competition (competitionName, location, splitAmount, startsAt, endsAt) 
VALUES ('kisa1', 'Espoo, Suomi', 5, TIMESTAMP '2015-09-12 12:00:00', TIMESTAMP '2015-09-12 17:00:00');

INSERT INTO Competition (competitionName, location, splitAmount, startsAt, endsAt) 
VALUES ('kisa2', 'Mesta, Suomi', 3, TIMESTAMP '1999-12-31 23:00:00', TIMESTAMP '2000-01-01 01:00:00');

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'eero'
    ), 1
);

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'kisa1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'joq'
    ), 2
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    (
    SELECT Participant.id FROM Participant, Competitor
    WHERE Competitor.id = Participant.competitorId and Competitor.competitorName = 'eero'
    ), 1, INTERVAL '00:28:22.43' 
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    (
    SELECT Participant.id FROM Participant, Competitor
    WHERE Competitor.id = Participant.competitorId and Competitor.competitorName = 'eero'
    ), 2, INTERVAL '00:57:34.79' 
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    (
    SELECT Participant.id FROM Participant, Competitor
    WHERE Competitor.id = Participant.competitorId and Competitor.competitorName = 'joq'
    ), 1, INTERVAL '00:27:16.01'
);
