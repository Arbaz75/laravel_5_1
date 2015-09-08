ALTER TABLE interval_records ADD FOREIGN KEY ( event_id ) REFERENCES event( event_id )

ALTER TABLE event ADD FOREIGN KEY ( member_id ) REFERENCES members( member_id )

ALTER TABLE member_token ADD FOREIGN KEY ( member_id ) REFERENCES members( member_id )
