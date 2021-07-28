/* SQL_PARSER_OFFSET */

/* foreign keys */
ALTER TABLE wcf1_lotto_draw_winclass ADD FOREIGN KEY (drawID) REFERENCES wcf1_lotto_draw (drawID) ON DELETE CASCADE;
ALTER TABLE wcf1_lotto_ticket_field ADD FOREIGN KEY (ticketID) REFERENCES wcf1_lotto_ticket (ticketID) ON DELETE CASCADE;