DROP TABLE IF EXISTS wcf1_lotto_ticket_field;
CREATE TABLE wcf1_lotto_ticket_field (
    ticketID INT(11),
    fieldID INT(2) NOT NULL DEFAULT 0,
    number1 INT(2) NOT NULL DEFAULT 0,
    number2 INT(2) NOT NULL DEFAULT 0,
    number3 INT(2) NOT NULL DEFAULT 0,
    number4 INT(2) NOT NULL DEFAULT 0,
    number5 INT(2) NOT NULL DEFAULT 0,
    number6 INT(2) NOT NULL DEFAULT 0,
    winCoins INT(10) NOT NULL DEFAULT 0,
    UNIQUE KEY ticketID (ticketID, fieldID)
);