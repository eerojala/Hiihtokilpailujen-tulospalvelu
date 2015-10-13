INSERT INTO Operator (username, password) VALUES ('admin', '12345');
INSERT INTO Operator (username, password) VALUES ('joq', '67879');

INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('Testikilpailija 1', 
DATE '1994-11-22', 'Suomi');
INSERT INTO Competitor (competitorName, birthdate, country) VALUES ('Testikilpailija 2',
DATE '1990-2-13', 'Ruotsi');

INSERT INTO Competition (competitionName, location, splitAmount, startsAt, endsAt) 
VALUES ('Testikilpailu 1', 'Espoo, Suomi', 5, TIMESTAMP '2015-09-12 12:00:00', TIMESTAMP '2015-09-12 17:00:00');

INSERT INTO Competition (competitionName, location, splitAmount, startsAt, endsAt) 
VALUES ('Testikilpailu 2', 'Mesta, Suomi', 3, TIMESTAMP '1999-12-31 23:00:00', TIMESTAMP '2000-01-01 01:00:00');

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'Testikilpailu 1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'Testikilpailija 1'
    ), 1
);

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'Testikilpailu 1'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'Testikilpailija 2'
    ), 2
);

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'Testikilpailu 2'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'Testikilpailija 2'
    ), 1
);

INSERT INTO Participant (competitionId, competitorId, participantNumber) VALUES(
    (
    SELECT id FROM Competition
    WHERE competitionName = 'Testikilpailu 2'
    ),
    (
    SELECT id FROM Competitor
    WHERE competitorName = 'Testikilpailija 1'
    ), 2
);


INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    1, 1, INTERVAL '00:28:22.43' 
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    1, 2, INTERVAL '00:57:34.796' 
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    2, 1, INTERVAL '00:27:16.016'
);



INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    3, 1, INTERVAL '00:10:00.018'
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    3, 2, INTERVAL '00:20:00.789'
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    4, 1, INTERVAL '00:09:44.934'
);

INSERT INTO Split (participantId, splitNumber, splitTime) VALUES(
    4, 2, INTERVAL '00:19:44.555'
);


