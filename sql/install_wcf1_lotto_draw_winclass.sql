DROP TABLE IF EXISTS wcf1_lotto_draw_winclass;
CREATE TABLE wcf1_lotto_draw_winclass (
    drawID INT(10),
    winClass INT(2) NOT NULL DEFAULT 0,
    pro INT(10) NOT NULL DEFAULT 0,
    counts INT(10) NOT NULL DEFAULT 0,
    UNIQUE KEY drawID (drawID, winClass)
);