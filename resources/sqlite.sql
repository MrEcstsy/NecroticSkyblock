-- #!sqlite
-- # { players
-- #  { initialize
CREATE TABLE IF NOT EXISTS players (
    uuid VARCHAR(36) PRIMARY KEY,
    username VARCHAR(16),
    balance INT DEFAULT 0,
    giftcards INT DEFAULT 0,
    kills INT DEFAULT 0,
    deaths INT DEFAULT 0,
    bounty INT DEFAULT 0,
    cooldowns TEXT,
    title TEXT,
    flighttime INT DEFAULT 0,
    mysterydust INT DEFAULT 0
    );

CREATE TABLE IF NOT EXISTS cooldowns (
                                         uuid VARCHAR(36),
    entry VARCHAR,
    timestamp INT,
    PRIMARY KEY (uuid, entry),
    FOREIGN KEY (uuid) REFERENCES players(uuid) ON DELETE CASCADE
    );
-- # }

-- #  { select
SELECT *
FROM players;
-- #  }

-- #  { create
-- #      :uuid string
-- #      :username string
-- #      :balance int
-- #      :giftcards int
-- #      :kills int
-- #      :deaths int
-- #      :bounty int
-- #      :cooldowns string
-- #      :title string
-- #      :flighttime int
-- #      :mysterydust int
INSERT OR REPLACE INTO players(uuid, username, balance, giftcards, kills, deaths, bounty, cooldowns, title, flighttime, mysterydust)
VALUES (:uuid, :username, :balance, :giftcards, :kills, :deaths, :bounty, :cooldowns, :title, :flighttime, :mysterydust);
-- #  }

-- #  { update
-- #      :uuid string
-- #      :username string
-- #      :balance int
-- #      :giftcards int
-- #      :kills int
-- #      :deaths int
-- #      :bounty int
-- #      :cooldowns string
-- #      :title string
-- #      :flighttime int
-- #      :mysterydust int
UPDATE players
SET username=:username,
    balance=:balance,
    giftcards=:giftcards,
    kills=:kills,
    deaths=:deaths,
    bounty=:bounty,
    cooldowns=:cooldowns,
    title=:title,
    flighttime=:flighttime,
    mysterydust=:mysterydust
WHERE uuid=:uuid;
-- #  }

-- #  { delete
-- #      :uuid string
DELETE FROM players
WHERE uuid=:uuid;
-- #  }

-- # { homes
-- #  { initialize
CREATE TABLE IF NOT EXISTS homes (
                                     uuid VARCHAR(36),
    home_name VARCHAR(32),
    world_name VARCHAR(32),
    x INT,
    y INT,
    z INT,
    max_homes INT DEFAULT 3,
    PRIMARY KEY (uuid, home_name)
    );
-- #  }
-- # { select
SELECT *
FROM homes;
-- # }

-- #  { create
-- #      :uuid string
-- #      :home_name string
-- #      :world_name string
-- #      :x int
-- #      :y int
-- #      :z int
-- #      :max_homes int
INSERT OR REPLACE INTO homes(uuid, home_name, world_name, x, y, z, max_homes)
VALUES (:uuid, :home_name, :world_name, :x, :y, :z, :max_homes);
-- #  }

-- #  { delete
-- #      :uuid string
-- #      :home_name string
DELETE FROM homes
WHERE uuid = :uuid AND home_name = :home_name;
-- #  }

-- #  { update
-- #      :uuid string
-- #      :max_homes int
UPDATE homes
SET max_homes = :max_homes
WHERE uuid = :uuid;
-- #   }
-- #  }
-- # }