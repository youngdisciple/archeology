-- =====================================================================
-- Sample data for the archaeology database (MariaDB)
-- Run db_schema_mariadb.sql FIRST, then this file.
-- Select the target database (e.g. `archeology`) before running.
--
-- NOTE: This is illustrative test data. Real place and museum names are
-- used for flavour, but artifact details, dates, budgets, values and all
-- scientists are made up. Do not cite any of it as fact.
--
-- Rows are inserted parents-first so foreign keys are always satisfied.
-- Explicit IDs are used so the cross-references below are easy to follow.
-- =====================================================================

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- museum
-- ---------------------------------------------------------------------
INSERT INTO museum (museum_id, museum_name, city, foundation_date, website, museum_type) VALUES
(1, 'British Museum',                   'London',      '1753-06-07', 'https://www.britishmuseum.org', 'History and Antiquities'),
(2, 'Louvre Museum',                    'Paris',       '1793-08-10', 'https://www.louvre.fr',         'Art and Antiquities'),
(3, 'Egyptian Museum',                  'Cairo',       '1902-11-15', NULL,                            'Archaeology'),
(4, 'National Museum of Anthropology',  'Mexico City', '1964-09-17', 'https://www.mna.inah.gob.mx',   'Anthropology'),
(5, 'Metropolitan Museum of Art',       'New York',    '1870-04-13', 'https://www.metmuseum.org',     'Art'),
(6, 'Royal Ontario Museum',             'Toronto',     '1914-03-19', 'https://www.rom.on.ca',         'Natural History and Culture'),
(7, 'Pointe-à-Callière',                'Montréal',    '1992-05-17', 'https://pacmusee.qc.ca',        'Archaeology and History');

-- ---------------------------------------------------------------------
-- historical_period  (negative years = BCE)
-- ---------------------------------------------------------------------
INSERT INTO historical_period (period_id, period_name, description, start_year, end_year, dating_method) VALUES
(1, 'Paleolithic',            'Old Stone Age: hunter-gatherer societies using chipped stone tools.',            -2500000, -10000, 'Radiometric dating'),
(2, 'Neolithic',              'New Stone Age: the rise of farming, herding and permanent settlements.',            -10000,  -3000, 'Radiocarbon dating'),
(3, 'Bronze Age',             'Bronze metallurgy, early cities, and the first writing systems.',                    -3300,  -1200, 'Radiocarbon dating'),
(4, 'Iron Age',               'Widespread use of iron for tools and weapons.',                                      -1200,   -550, 'Typology and stratigraphy'),
(5, 'Classical Antiquity',    'The Greek and Roman world around the Mediterranean.',                                 -800,    476, 'Historical records and typology'),
(6, 'Medieval',               'Post-Roman centuries in Europe, including the Norse expansion.',                       476,   1500, 'Dendrochronology'),
(7, 'Classic Maya Period',    'Height of Maya city-states in Mesoamerica.',                                           250,    900, 'Inscriptions and radiocarbon dating'),
(8, 'Egyptian New Kingdom',   'Egypt at the peak of its power, from Ahmose I to Ramesses XI.',                      -1550,  -1070, 'Historical records and king lists');

-- ---------------------------------------------------------------------
-- cultures
-- ---------------------------------------------------------------------
INSERT INTO cultures (culture_id, culture_name, is_well_documented, technology, religion, population_est, period_id) VALUES
(1, 'Ancient Egyptians',                 TRUE,  'Copper and bronze tools, irrigation, monumental stone construction.',        'Polytheism centred on gods such as Ra, Osiris and Isis.',         3000000, 8),
(2, 'Ancient Greeks',                    TRUE,  'Iron tools, advanced ceramics, triremes, and marble architecture.',         'Polytheistic pantheon with civic cults and oracles.',             8000000, 5),
(3, 'Romans',                            TRUE,  'Concrete, aqueducts, road networks, and standardised military equipment.', 'Polytheistic state religion, later Christianity.',                60000000, 5),
(4, 'Classic Maya',                      TRUE,  'Stone architecture, calendrical astronomy, hieroglyphic writing.',           'Polytheism with royal ancestor veneration and ritual bloodletting.', 10000000, 7),
(5, 'Çatalhöyük Community',              FALSE, 'Obsidian tools, mud-brick houses, early farming and herding.',               'Possible household ritual and ancestor burials beneath floors.',     8000, 2),
(6, 'Indus Valley Civilization',         FALSE, 'Planned cities, standardised weights, drainage systems, seal making.',      'Unknown; script remains undeciphered.',                           5000000, 3),
(7, 'Norse',                             TRUE,  'Iron working, clinker-built longships, timber halls.',                       'Norse polytheism, gradually replaced by Christianity.',           1000000, 6),
(8, 'Sumerians',                         TRUE,  'Cuneiform writing, irrigation, the wheel, bronze casting.',                  'Polytheism with city gods and ziggurat temples.',                  600000, 3),
(9, 'Minoans',                           FALSE, 'Palace-centred economy, fine pottery, faience, seafaring trade.',            'Possible goddess worship; Linear A script remains undeciphered.',  250000, 3);

-- ---------------------------------------------------------------------
-- scientist  (museum_id may be NULL for independent researchers)
-- ---------------------------------------------------------------------
INSERT INTO scientist (scientist_id, first_name, last_name, degree, specialization, museum_id, years_of_experience, is_active, biography) VALUES
(1, 'Amira',       'Haddad',   'PhD', 'Egyptology',                    3,    18, TRUE,  'Focuses on New Kingdom tomb architecture and funerary goods.'),
(2, 'Lucas',       'Moreau',   'PhD', 'Classical Archaeology',         2,    22, TRUE,  'Specialises in Roman domestic life and Greek public spaces.'),
(3, 'Elena',       'Vasquez',  'PhD', 'Mesoamerican Archaeology',      4,    15, TRUE,  'Leads fieldwork on Classic Maya elite burials and ceramics.'),
(4, 'James',       'Whitfield','PhD', 'Near Eastern Archaeology',      1,    30, TRUE,  'Long career on Bronze Age urban sites and early writing.'),
(5, 'Priya',       'Nair',     'MSc', 'Archaeometry and Dating',       5,     9, TRUE,  'Applies radiocarbon and materials analysis to excavated finds.'),
(6, 'Marc-André',  'Tremblay', 'MA',  'Norse and Colonial Archaeology',7,    12, TRUE,  'Works on Norse sites in North America and early Montréal.'),
(7, 'Hannah',      'Fischer',  'PhD', 'Prehistoric Archaeology',       6,    27, TRUE,  'Researches Neolithic settlements and stone tool technology.'),
(8, 'Robert',      'Alvarez',  'PhD', 'Field Archaeology',             NULL, 35, FALSE, 'Retired independent field director, known for meticulous site records.');

-- ---------------------------------------------------------------------
-- archeological_site
-- period_id / culture_id may be NULL when unknown or not applicable
-- ---------------------------------------------------------------------
INSERT INTO archeological_site
    (site_id, site_name, site_type, discovery_date, estimated_age, longitude, latitude, country, region, description, is_protected, period_id, culture_id) VALUES
(1,  'Tomb KV62',                       'Tomb',        '1922-11-04', 3300,  32.601400, 25.740200, 'Egypt',    'Luxor Governorate',        'Small royal tomb in the Valley of the Kings with an intact burial assemblage.',              TRUE,  8,    1),
(2,  'Pompeii',                         'City',        '1748-01-01', 1945,  14.498900, 40.746200, 'Italy',    'Campania',                 'Roman town buried by volcanic ash, preserving streets, homes and workshops.',               TRUE,  5,    3),
(3,  'Tikal',                           'City',        '1848-01-01', 1300, -89.623700, 17.222000, 'Guatemala','Petén',                    'Major Classic Maya city with temple pyramids and palace complexes in the rainforest.',      TRUE,  7,    4),
(4,  'Çatalhöyük',                      'Settlement',  '1958-01-01', 9000,  32.828100, 37.666700, 'Turkey',   'Konya Province',           'Dense Neolithic mound settlement of tightly packed mud-brick houses.',                      TRUE,  2,    5),
(5,  'Mohenjo-daro',                    'City',        '1922-01-01', 4500,  68.135000, 27.324400, 'Pakistan', 'Sindh',                    'Planned Bronze Age city with a grid layout, wells and covered drains.',                     TRUE,  3,    6),
(6,  'L''Anse aux Meadows',             'Settlement',  '1960-01-01', 1000, -55.530000, 51.596000, 'Canada',   'Newfoundland and Labrador','Norse outpost in North America with timber-framed buildings and a smithy.',                 TRUE,  6,    7),
(7,  'Ur',                              'City',        '1853-01-01', 4500,  46.103100, 30.962500, 'Iraq',     'Dhi Qar Governorate',      'Sumerian city-state with a ziggurat and a royal cemetery.',                                 TRUE,  3,    8),
(8,  'Athenian Agora',                  'Public space','1931-01-01', 2500,  23.721700, 37.974800, 'Greece',   'Attica',                   'Civic heart of ancient Athens: markets, law courts and public buildings.',                  TRUE,  5,    2),
(9,  'Knossos',                         'Palace',      '1878-01-01', 3700,  25.163100, 35.298000, 'Greece',   'Crete',                    'Large Bronze Age palace complex with storerooms, workshops and frescoes.',                  TRUE,  3,    9),
(10, 'Pointe-à-Callière (Old Montréal)','Settlement',  '1989-01-01',  384, -73.554000, 45.503000, 'Canada',   'Québec',                   'Layered remains of early French colonial Montréal beneath the modern old town.',            TRUE,  NULL, NULL),
(11, 'Riverbend Survey Site 14B',       'Survey area', '2026-05-01', NULL, -73.900000, 45.400000, 'Canada',   'Québec',                   'Fictional survey area used for test data; period and culture not yet determined.',          FALSE, NULL, NULL);

-- ---------------------------------------------------------------------
-- artifacts  (museum_id may be NULL for items still in field storage)
-- estimated_value may be NULL for priceless or unappraised items
-- NOTE: `condition` needs backticks everywhere; it is a reserved word.
-- ---------------------------------------------------------------------
INSERT INTO artifacts
    (artifact_id, artifact_name, estimated_value, artifact_type, museum_id, `condition`, material, weight_in_g, height_in_cm) VALUES
(1,  'Gold Funerary Mask',              NULL,      'Funerary object', 3,    'Excellent', 'Gold, lapis lazuli, glass paste', 10230.00, 54.00),
(2,  'Alabaster Canopic Jar',           85000.00,  'Funerary object', 3,    'Mint',      'Alabaster (calcite)',             3200.00,  38.50),
(4,  'Bronze Oil Lamp',                 1800.00,   'Household item',  1,    'Good',      'Bronze',                           340.00,   7.00),
(5,  'Carved Jade Ear Flare',           45000.00,  'Ornament',        4,    'Excellent', 'Jade',                              85.50,   5.20),
(7,  'Obsidian Blade',                  900.00,    'Tool',            6,    'Good',      'Obsidian',                          42.00,  12.30),
(8,  'Terracotta Seated Figurine',      12000.00,  'Figurine',        6,    'Fair',      'Baked clay',                       780.00,  17.00),
(9,  'Steatite Seal with Unicorn Motif',8000.00,   'Seal',            1,    'Excellent', 'Steatite',                          12.00,   3.00),
(10, 'Bronze Ringed Pin',               2500.00,   'Ornament',        7,    'Good',      'Bronze',                            18.00,   9.00),
(11, 'Soapstone Spindle Whorl',         600.00,    'Tool',            7,    'Mint',      'Soapstone',                         22.00,   2.50),
(12, 'Cuneiform Clay Tablet',           15000.00,  'Document',        1,    'Fair',      'Clay',                             320.00,  11.00),
(15, 'Inscribed Ostrakon',              3500.00,   'Document',        2,    'Good',      'Pottery sherd',                     95.00,   8.00),
(17, 'Clay Pipe Bowl',                  150.00,    'Household item',  7,    'Poor',      'Clay',                              14.00,   4.50),
(19, 'Chert Projectile Point',          NULL,      'Tool',            NULL, 'Good',      'Chert',                              6.50,   4.10);

-- ---------------------------------------------------------------------
-- excavations  (site_id and scientist_id are required)
-- end_date NULL = still ongoing
-- ---------------------------------------------------------------------
INSERT INTO excavations
    (excavation_id, project_name, start_date, end_date, budget, area_excavated_m2, team_size, notes, site_id, scientist_id) VALUES
(1,  'Royal Tomb Conservation Survey',   '2019-01-10', '2019-06-30', 1250000.00,  320.50, 24, 'Focused on documenting and stabilising chamber contents.',            1,  1),
(2,  'Pompeii Regio V Excavation',       '2017-03-01', '2018-10-31', 2100000.00, 1450.00, 36, 'Two seasons in residential and commercial blocks.',                    2,  2),
(3,  'Northern Acropolis Project',       '2018-05-15', '2019-08-15',  980000.00,  860.00, 28, 'Trenching through successive temple platforms.',                       3,  3),
(4,  'South Area Field Season',          '2016-06-01', '2016-09-15',  410000.00,  275.00, 40, 'Excavated several house interiors with well-preserved floors.',        4,  7),
(5,  'Street Grid Mapping Campaign',     '2014-11-01', '2015-03-31',  540000.00, 1200.00, 30, 'Combined survey and limited excavation along main streets.',           5,  4),
(6,  'Boundary and Outbuilding Survey',  '2021-07-05', '2021-08-27',  220000.00,  180.00, 14, 'Short summer season near the smithy area.',                            6,  6),
(7,  'Royal Cemetery Re-examination',    '2013-02-01', '2013-05-30',  380000.00,  150.00, 18, 'Re-opened earlier trenches; dating samples collected.',               7,  5),
(8,  'Agora Well Deposits Project',      '2004-05-10', '2005-09-30',  300000.00,  400.00, 20, 'Cleared several wells containing dense pottery deposits.',            8,  8),
(9,  'Palace West Wing Excavation',      '2016-09-05', '2017-01-20',  600000.00,  310.00, 22, 'Explored storerooms along the western side of the palace.',           9,  4),
(10, 'Place Royale Field School',        '2022-05-09', '2022-08-19',  150000.00,   95.00, 16, 'Training excavation combined with public outreach.',                  10, 6),
(11, 'Riverbend Survey Season 1',        '2026-05-04', NULL,           65000.00,   40.00,  8, 'Ongoing test excavation; results still being processed.',             11, 7);

-- ---------------------------------------------------------------------
-- discovery  (links artifacts to the excavation that found them)
-- depth_found is in metres
-- ---------------------------------------------------------------------
INSERT INTO discovery (discovery_id, discovery_date, depth_found, notes, excavation_id, artifact_id) VALUES
(1,  '2019-02-14', 2.30, 'Found near the burial chamber entrance.',          1,  1),
(2,  '2019-03-02', 1.80, 'Recovered from an antechamber deposit.',           1,  2),
(3,  '2018-04-19', 0.90, 'Found inside a bakery oven.',                      2,  3),
(4,  '2017-09-12', 0.60, 'Recovered from a household shrine niche.',         2,  4),
(5,  '2018-07-22', 3.10, 'Part of an elite burial offering.',                3,  5),
(6,  '2019-02-05', 3.40, 'Found alongside a burial in the acropolis.',       3,  6),
(7,  '2016-07-11', 1.20, 'Found in a house floor deposit.',                  4,  7),
(8,  '2016-08-03', 1.45, 'Recovered beside a plastered wall.',               4,  8),
(9,  '2015-01-18', 2.10, 'Found in a collapsed street-side room.',           5,  9),
(10, '2021-07-20', 0.40, 'Found in the hearth area of a timber hall.',       6,  10),
(11, '2021-08-09', 0.35, 'Found near the smithy.',                           6,  11),
(12, '2013-03-10', 4.50, 'Found in a burial pit.',                           7,  12),
(13, '2013-04-22', 5.20, 'Recovered from a disturbed grave shaft.',          7,  13),
(14, '2004-08-16', 2.75, 'Found in a filled well.',                          8,  14),
(15, '2005-03-07', 2.10, 'Found among pottery sherds in a well.',            8,  15),
(16, '2016-11-14', 1.60, 'Found in a sealed storage room.',                  9,  16),
(17, '2022-06-14', 1.10, 'Recovered from a colonial-period rubbish pit.',    10, 17),
(18, '2022-07-27', 1.35, 'Found in a cellar fill layer.',                    10, 18),
(19, '2026-06-02', 0.25, 'Surface-adjacent find in test unit 3.',            11, 19);

-- ---------------------------------------------------------------------
-- Quick sanity checks (optional): row counts per table
-- ---------------------------------------------------------------------
-- SELECT 'museum' AS tbl, COUNT(*) FROM museum
-- UNION ALL SELECT 'historical_period', COUNT(*) FROM historical_period
-- UNION ALL SELECT 'cultures', COUNT(*) FROM cultures
-- UNION ALL SELECT 'scientist', COUNT(*) FROM scientist
-- UNION ALL SELECT 'archeological_site', COUNT(*) FROM archeological_site
-- UNION ALL SELECT 'artifacts', COUNT(*) FROM artifacts
-- UNION ALL SELECT 'excavations', COUNT(*) FROM excavations
-- UNION ALL SELECT 'discovery', COUNT(*) FROM discovery;
