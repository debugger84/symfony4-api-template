SET names 'UTF8';

--country
REPLACE INTO country (id, name) VALUES
(1, 'Ukraine');

--region
REPLACE INTO region (id, name, country_id) VALUES
(1, 'Kyiv area', 1);
REPLACE INTO region (id, name, country_id) VALUES
(2, 'Lviv area', 1);

--city
REPLACE INTO city (id, name, region_id) VALUES
(1, 'Kyiv', 1);
REPLACE INTO city (id, name, region_id) VALUES
(2, 'Lviv', 2);
