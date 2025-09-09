-- Generated on 2025-09-09T21:32:42.765893
SET foreign_key_checks = 0;
TRUNCATE user;
TRUNCATE user_profile;
TRUNCATE membership;
TRUNCATE address;
TRUNCATE user_security_event;
TRUNCATE user_token;
SET foreign_key_checks = 1;

INSERT INTO user (username, email, hashed_password, role, is_verified) VALUES
                                                                           ('vikramabdul1', 'vikramabdul1@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 0),
                                                                           ('omarismail2', 'omarismail2@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('yenwong3', 'yenwong3@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('zulyap4', 'zulyap4@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 1),
                                                                           ('mingyap5', 'mingyap5@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('sitibakar6', 'sitibakar6@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'STAFF', 0),
                                                                           ('nadiabakar7', 'nadiabakar7@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('qinsubramaniam8', 'qinsubramaniam8@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 1),
                                                                           ('lilyhassan9', 'lilyhassan9@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('putriyap10', 'putriyap10@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1),
                                                                           ('haziqjohari11', 'haziqjohari11@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('lilymok12', 'lilymok12@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1),
                                                                           ('omarkamil13', 'omarkamil13@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 1),
                                                                           ('vikramsubramaniam14', 'vikramsubramaniam14@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('vikramjohari15', 'vikramjohari15@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('nadiahassan16', 'nadiahassan16@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('haziqusman17', 'haziqusman17@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 0),
                                                                           ('aliciaismail18', 'aliciaismail18@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'STAFF', 0),
                                                                           ('omarusman19', 'omarusman19@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 1),
                                                                           ('adamwong20', 'adamwong20@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1);
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 10-7370 8295', '1973-07-24' FROM user WHERE username='vikramabdul1';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 15-2506 4169', '1974-07-27' FROM user WHERE username='omarismail2';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-1839 6883', '1997-09-15' FROM user WHERE username='yenwong3';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 14-6061 9487', '1976-05-11' FROM user WHERE username='zulyap4';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 11-6323 8326', '1976-10-24' FROM user WHERE username='mingyap5';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-8452 1628', '1990-11-17' FROM user WHERE username='sitibakar6';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 10-3234 4125', '1976-10-16' FROM user WHERE username='nadiabakar7';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 13-5226 1161', '1990-01-16' FROM user WHERE username='qinsubramaniam8';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-4585 4886', '1998-06-17' FROM user WHERE username='lilyhassan9';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-3241 8130', '1998-04-24' FROM user WHERE username='putriyap10';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 14-7032 5797', '1965-07-06' FROM user WHERE username='haziqjohari11';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 13-4819 1133', '1990-05-12' FROM user WHERE username='lilymok12';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-9846 9606', '2003-04-06' FROM user WHERE username='omarkamil13';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 18-8669 6044', '1992-05-06' FROM user WHERE username='vikramsubramaniam14';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 14-1930 5866', '1989-05-10' FROM user WHERE username='vikramjohari15';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 16-1397 8883', '2001-08-24' FROM user WHERE username='nadiahassan16';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 14-6103 8161', '1995-08-12' FROM user WHERE username='haziqusman17';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-2507 1029', '1981-07-16' FROM user WHERE username='aliciaismail18';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-5349 1747', '2007-07-15' FROM user WHERE username='omarusman19';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-7923 5079', '1981-10-17' FROM user WHERE username='adamwong20';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '113 Lebuh', NULL, NULL, 'Kuala Lumpur', '07260', 'Malaysia' FROM user WHERE username='vikramabdul1';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '64 Taman', 'Blok 12', 'Unit 13', 'Kedah', '59973', 'Malaysia' FROM user WHERE username='omarismail2';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '64 Taman', NULL, NULL, 'Pahang', '37713', 'Malaysia' FROM user WHERE username='omarismail2';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '164 Lebuh', 'Blok 19', 'Unit 17', 'Labuan', '09507', 'Malaysia' FROM user WHERE username='omarismail2';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '154 Lebuh', 'Blok 5', NULL, 'Negeri Sembilan', '81960', 'Malaysia' FROM user WHERE username='yenwong3';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '166 Lebuh', 'Blok 21', 'Unit 45', 'Johor', '68060', 'Malaysia' FROM user WHERE username='yenwong3';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '17 Lebuh', 'Blok 2', 'Unit 11', 'Putrajaya', '77739', 'Malaysia' FROM user WHERE username='zulyap4';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '59 Jalan', 'Blok 6', NULL, 'Selangor', '36359', 'Malaysia' FROM user WHERE username='zulyap4';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '22 Taman', 'Blok 17', 'Unit 23', 'Johor', '92358', 'Malaysia' FROM user WHERE username='mingyap5';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '58 Taman', 'Blok 20', NULL, 'Kedah', '87555', 'Malaysia' FROM user WHERE username='mingyap5';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '109 Jalan', 'Blok 17', NULL, 'Negeri Sembilan', '67686', 'Malaysia' FROM user WHERE username='sitibakar6';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '131 Taman', NULL, NULL, 'Pahang', '82477', 'Malaysia' FROM user WHERE username='sitibakar6';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '85 Lebuh', 'Blok 22', 'Unit 10', 'Pahang', '18041', 'Malaysia' FROM user WHERE username='nadiabakar7';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '161 Lebuh', NULL, NULL, 'Putrajaya', '24880', 'Malaysia' FROM user WHERE username='qinsubramaniam8';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '192 Lorong', NULL, NULL, 'Kelantan', '82913', 'Malaysia' FROM user WHERE username='lilyhassan9';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '179 Lebuh', 'Blok 2', NULL, 'Kelantan', '72871', 'Malaysia' FROM user WHERE username='lilyhassan9';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '88 Lorong', 'Blok 13', NULL, 'Kuala Lumpur', '22133', 'Malaysia' FROM user WHERE username='putriyap10';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '172 Lorong', NULL, NULL, 'Kuala Lumpur', '82359', 'Malaysia' FROM user WHERE username='putriyap10';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '77 Lebuh', NULL, 'Unit 49', 'Putrajaya', '60305', 'Malaysia' FROM user WHERE username='haziqjohari11';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '183 Persiaran', NULL, 'Unit 2', 'Putrajaya', '25004', 'Malaysia' FROM user WHERE username='haziqjohari11';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '168 Lorong', NULL, 'Unit 44', 'Penang', '50224', 'Malaysia' FROM user WHERE username='lilymok12';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '129 Lebuh', 'Blok 23', NULL, 'Selangor', '73995', 'Malaysia' FROM user WHERE username='lilymok12';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '13 Taman', 'Blok 4', NULL, 'Putrajaya', '80708', 'Malaysia' FROM user WHERE username='lilymok12';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '170 Lorong', 'Blok 1', 'Unit 4', 'Kedah', '74776', 'Malaysia' FROM user WHERE username='omarkamil13';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '68 Lebuh', NULL, 'Unit 45', 'Perak', '87449', 'Malaysia' FROM user WHERE username='vikramsubramaniam14';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '67 Jalan', NULL, NULL, 'Negeri Sembilan', '34575', 'Malaysia' FROM user WHERE username='vikramsubramaniam14';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '129 Lebuh', 'Blok 27', 'Unit 1', 'Labuan', '73023', 'Malaysia' FROM user WHERE username='vikramsubramaniam14';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '136 Jalan', NULL, 'Unit 33', 'Negeri Sembilan', '97062', 'Malaysia' FROM user WHERE username='vikramjohari15';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '95 Jalan', NULL, 'Unit 30', 'Negeri Sembilan', '41055', 'Malaysia' FROM user WHERE username='vikramjohari15';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '107 Taman', 'Blok 30', NULL, 'Selangor', '89366', 'Malaysia' FROM user WHERE username='vikramjohari15';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '46 Jalan', 'Blok 30', NULL, 'Selangor', '53968', 'Malaysia' FROM user WHERE username='nadiahassan16';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '171 Lorong', 'Blok 19', 'Unit 25', 'Perlis', '53135', 'Malaysia' FROM user WHERE username='nadiahassan16';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '67 Lebuh', NULL, NULL, 'Malacca', '20870', 'Malaysia' FROM user WHERE username='haziqusman17';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '128 Persiaran', 'Blok 24', 'Unit 17', 'Labuan', '36612', 'Malaysia' FROM user WHERE username='haziqusman17';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '179 Jalan', 'Blok 20', 'Unit 44', 'Pahang', '21539', 'Malaysia' FROM user WHERE username='haziqusman17';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '46 Jalan', 'Blok 9', NULL, 'Sabah', '37330', 'Malaysia' FROM user WHERE username='aliciaismail18';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '127 Lebuh', 'Blok 14', 'Unit 23', 'Kuala Lumpur', '86212', 'Malaysia' FROM user WHERE username='aliciaismail18';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '110 Lebuh', 'Blok 24', 'Unit 49', 'Penang', '51824', 'Malaysia' FROM user WHERE username='omarusman19';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '161 Lebuh', NULL, NULL, 'Labuan', '14774', 'Malaysia' FROM user WHERE username='adamwong20';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '60 Persiaran', 'Blok 13', NULL, 'Sabah', '37868', 'Malaysia' FROM user WHERE username='adamwong20';
-- Done.
