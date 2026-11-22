# Database migrations

Run each migration against the `badminton` database before using the feature it supports.

For an existing Compose database, open phpMyAdmin at `http://localhost:8080`, select the `badminton` database, and import the migration file. The customer equipment migration creates one `customer_equipment` table, migrates records from the previous racket, shoe, and stringing tables when present, then removes those old tables. It can be safely re-run.

- `20261121_customer_equipment.sql`: stores rackets, shoes, and per-racket stringing history in `customer_equipment`.
