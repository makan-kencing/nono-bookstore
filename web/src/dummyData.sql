-- Generated on 2025-09-08T22:24:12.744555

INSERT INTO user (username, email, hashed_password, role, is_verified) VALUES
                                                                           ('ivyng1', 'ivyng1@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('carausman2', 'carausman2@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1),
                                                                           ('umaeng3', 'umaeng3@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('graceong4', 'graceong4@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('lilypang5', 'lilypang5@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'STAFF', 0),
                                                                           ('fahming6', 'fahming6@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 1),
                                                                           ('zulvoon7', 'zulvoon7@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'STAFF', 0),
                                                                           ('carang8', 'carang8@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('lilyismail9', 'lilyismail9@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'STAFF', 1),
                                                                           ('yenwong10', 'yenwong10@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('omarusman11', 'omarusman11@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'USER', 0),
                                                                           ('danielismail12', 'danielismail12@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 0),
                                                                           ('tanmok13', 'tanmok13@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1),
                                                                           ('ivyjohari14', 'ivyjohari14@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('adamusman15', 'adamusman15@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('beneng16', 'beneng16@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('nadiaabdul17', 'nadiaabdul17@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('aliciapang18', 'aliciapang18@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'OWNER', 1),
                                                                           ('sitizainal19', 'sitizainal19@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 0),
                                                                           ('sitichan20', 'sitichan20@example.test', 'e11ca16d3f77dbba1738278b5c91921e31f9a2d9b63780b4c0d9be14a30803ea', 'ADMIN', 1);

INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 18-2309 5003', '1995-01-29' FROM user WHERE username = 'ivyng1';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 17-3999 8298', '1992-05-22' FROM user WHERE username = 'carausman2';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 11-7298 6800', '1976-08-22' FROM user WHERE username = 'umaeng3';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-2618 4038', '1996-07-05' FROM user WHERE username = 'graceong4';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-3020 5429', '1970-10-20' FROM user WHERE username = 'lilypang5';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-5892 2590', '2005-07-19' FROM user WHERE username = 'fahming6';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 16-3638 4436', '2002-10-28' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 18-3364 5983', '1987-02-19' FROM user WHERE username = 'carang8';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 18-2160 4836', '2007-07-19' FROM user WHERE username = 'lilyismail9';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 16-5647 7812', '1997-05-16' FROM user WHERE username = 'yenwong10';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-3506 9024', '2003-10-26' FROM user WHERE username = 'omarusman11';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 14-2926 6278', '1968-08-15' FROM user WHERE username = 'danielismail12';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 11-3744 2410', '1968-09-09' FROM user WHERE username = 'tanmok13';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 13-7890 8277', '1999-07-24' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 15-5024 1916', '2004-11-02' FROM user WHERE username = 'adamusman15';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 16-1667 2844', '1977-12-10' FROM user WHERE username = 'beneng16';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 18-5748 2326', '1996-06-06' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 19-5948 7847', '1968-10-09' FROM user WHERE username = 'aliciapang18';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 15-3399 7720', '2006-02-19' FROM user WHERE username = 'sitizainal19';
INSERT INTO user_profile (user_id, contact_no, dob) SELECT id, '+60 12-7274 8542', '1979-06-11' FROM user WHERE username = 'sitichan20';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-60500771', '2024-10-29 17:31:27', '2026-01-17 08:28:58' FROM user WHERE username = 'ivyng1';
INSERT INTO membership (user_id, card_no, from_date,thru_date) SELECT id, 'PC-24286072', '2024-10-12 16:25:38', '2025-11-15 15:56:30' FROM user WHERE username = 'umaeng3';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-76375437', '2024-11-16 22:53:00', '2026-03-26 15:18:41' FROM user WHERE username = 'graceong4';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-90164081', '2024-08-26 00:08:00', '2025-12-04 15:48:25' FROM user WHERE username = 'lilypang5';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-20199393', '2025-04-04 08:21:47', '2025-10-30 05:37:35' FROM user WHERE username = 'fahming6';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-93661087', '2024-10-12 16:59:20', '2026-06-25 08:36:31' FROM user WHERE username = 'carang8';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-78434613', '2024-08-12 00:47:06', '2026-04-02 20:53:33' FROM user WHERE username = 'lilyismail9';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-16136477', '2025-03-11 06:07:37', '2026-03-16 03:26:15' FROM user WHERE username = 'yenwong10';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-50549967', '2025-06-05 18:12:14', '2026-03-24 12:04:14' FROM user WHERE username = 'omarusman11';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-15244073', '2025-01-20 10:59:13', '2026-06-28 07:49:11' FROM user WHERE username = 'danielismail12';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-44636014', '2024-09-15 14:58:55', '2026-01-17 15:13:29' FROM user WHERE username = 'adamusman15';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-15069203', '2025-05-26 00:10:51', '2025-12-29 23:26:10' FROM user WHERE username = 'aliciapang18';
INSERT INTO membership (user_id, card_no, from_date, thru_date) SELECT id, 'PC-11358126', '2024-08-24 05:49:08', '2025-09-22 15:09:51' FROM user WHERE username = 'sitizainal19';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '134 Persiaran', NULL, NULL, 'Negeri Sembilan', '28254', 'Malaysia' FROM user WHERE username = 'ivyng1';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '21 Taman', NULL, NULL, 'Negeri Sembilan', '45070', 'Malaysia' FROM user WHERE username = 'ivyng1';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '118 Jalan', 'Blok 12', NULL, 'Kelantan', '16611', 'Malaysia' FROM user WHERE username = 'carausman2';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '169 Lorong', NULL, NULL, 'Pahang', '91647', 'Malaysia' FROM user WHERE username = 'graceong4';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '39 Taman', NULL, NULL, 'Kelantan', '95297', 'Malaysia' FROM user WHERE username = 'graceong4';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '123 Taman', NULL, 'Unit 11', 'Penang', '26497', 'Malaysia' FROM user WHERE username = 'graceong4';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '41 Persiaran', 'Blok 15', 'Unit 2', 'Selangor', '79903', 'Malaysia' FROM user WHERE username = 'fahming6';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '6 Jalan', 'Blok 8', NULL, 'Sarawak', '32696', 'Malaysia' FROM user WHERE username = 'fahming6';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '93 Jalan', 'Blok 12', NULL, 'Kuala Lumpur', '20122', 'Malaysia' FROM user WHERE username = 'zulvoon7';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '74 Jalan', 'Blok 10', NULL, 'Pahang', '60148', 'Malaysia' FROM user WHERE username = 'zulvoon7';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '179 Lebuh', 'Blok 21', NULL, 'Sabah', '31833', 'Malaysia' FROM user WHERE username = 'zulvoon7';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '199 Jalan', NULL, NULL, 'Kelantan', '63402', 'Malaysia' FROM user WHERE username = 'carang8';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '68 Persiaran', 'Blok 23', 'Unit 46', 'Perak', '18657', 'Malaysia' FROM user WHERE username = 'carang8';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '91 Lebuh', 'Blok 2', NULL, 'Kelantan', '11110', 'Malaysia' FROM user WHERE username = 'yenwong10';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '134 Lorong', NULL, 'Unit 43', 'Negeri Sembilan', '83833', 'Malaysia' FROM user WHERE username = 'yenwong10';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '164 Lebuh', NULL, NULL, 'Pahang', '07985', 'Malaysia' FROM user WHERE username = 'omarusman11';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '71 Lorong', NULL, NULL, 'Kuala Lumpur', '58466', 'Malaysia' FROM user WHERE username = 'danielismail12';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '109 Taman', 'Blok 5', 'Unit 10', 'Kuala Lumpur', '15716', 'Malaysia' FROM user WHERE username = 'tanmok13';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '39 Jalan', 'Blok 15', NULL, 'Kelantan', '46014', 'Malaysia' FROM user WHERE username = 'beneng16';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '132 Taman', 'Blok 14', 'Unit 31', 'Sabah', '72338', 'Malaysia' FROM user WHERE username = 'beneng16';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '22 Lebuh', NULL, NULL, 'Kelantan', '73475', 'Malaysia' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '151 Lebuh', 'Blok 3', NULL, 'Negeri Sembilan', '47796', 'Malaysia' FROM user WHERE username = 'aliciapang18';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '84 Lorong', NULL, NULL, 'Labuan', '54484', 'Malaysia' FROM user WHERE username = 'sitizainal19';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '136 Persiaran', 'Blok 18', NULL, 'Selangor', '93779', 'Malaysia' FROM user WHERE username = 'sitizainal19';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '30 Jalan', 'Blok 18', NULL, 'Penang', '43345', 'Malaysia' FROM user WHERE username = 'sitizainal19';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '193 Persiaran', 'Blok 10', 'Unit 45', 'Johor', '07422', 'Malaysia' FROM user WHERE username = 'sitichan20';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '69 Lorong', NULL, NULL, 'Sarawak', '84767', 'Malaysia' FROM user WHERE username = 'sitichan20';
INSERT INTO address (user_id, address1, address2, address3, state, postcode, country) SELECT id, '18 Lorong', 'Blok 4', 'Unit 10', 'Sabah', '86378', 'Malaysia' FROM user WHERE username = 'sitichan20';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.114.254", "ua": "Chrome/96"}', '2025-08-17 14:42:19' FROM user WHERE username = 'ivyng1';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.196.34", "ua": "Safari/109"}', '2025-07-05 21:17:07' FROM user WHERE username = 'ivyng1';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.208.150", "ua": "Chrome/97"}', '2025-01-01 15:14:35' FROM user WHERE username = 'ivyng1';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.215.204", "ua": "Chrome/103"}', '2024-12-25 19:44:48' FROM user WHERE username = 'ivyng1';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.162.14", "ua": "Edge/106"}', '2024-11-09 03:10:20' FROM user WHERE username = 'carausman2';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.192.19", "ua": "Edge/97"}', '2024-11-06 12:48:54' FROM user WHERE username = 'carausman2';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.63.70", "ua": "Chrome/93"}', '2025-02-28 08:31:15' FROM user WHERE username = 'carausman2';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.239.192", "ua": "Safari/128"}', '2025-08-28 16:43:01' FROM user WHERE username = 'carausman2';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.14.44", "ua": "Chrome/125"}', '2025-05-31 19:49:06' FROM user WHERE username = 'carausman2';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.218.210", "ua": "Firefox/121"}', '2025-08-15 16:02:08' FROM user WHERE username = 'umaeng3';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.193.121", "ua": "Safari/109"}', '2024-09-29 08:14:30' FROM user WHERE username = 'umaeng3';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.234.50", "ua": "Safari/113"}', '2024-12-03 19:28:10' FROM user WHERE username = 'umaeng3';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.248.114", "ua": "Safari/104"}', '2025-05-24 17:54:46' FROM user WHERE username = 'graceong4';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.1.214", "ua": "Firefox/122"}', '2025-08-13 11:16:30' FROM user WHERE username = 'graceong4';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.189.207", "ua": "Edge/129"}', '2024-09-14 22:57:00' FROM user WHERE username = 'fahming6';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.82.143", "ua": "Edge/95"}', '2024-10-21 06:43:21' FROM user WHERE username = 'fahming6';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.177.52", "ua": "Safari/99"}', '2025-09-02 18:44:26' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.122.133", "ua": "Firefox/113"}', '2025-06-14 01:42:28' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.32.91", "ua": "Chrome/124"}', '2025-07-06 16:50:22' FROM user WHERE username = 'carang8';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.170.47", "ua": "Edge/105"}', '2024-12-02 05:59:38' FROM user WHERE username = 'carang8';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.198.163", "ua": "Edge/130"}', '2024-11-26 08:56:10' FROM user WHERE username = 'carang8';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.42.58", "ua": "Firefox/97"}', '2025-02-22 05:55:54' FROM user WHERE username = 'lilyismail9';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.22.201", "ua": "Firefox/126"}', '2025-06-19 08:29:01' FROM user WHERE username = 'omarusman11';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.137.16", "ua": "Edge/113"}', '2024-09-20 08:33:41' FROM user WHERE username = 'omarusman11';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.142.5", "ua": "Safari/104"}', '2024-11-17 21:23:48' FROM user WHERE username = 'omarusman11';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.164.135", "ua": "Firefox/101"}', '2024-12-16 12:25:48' FROM user WHERE username = 'omarusman11';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.237.218", "ua": "Safari/123"}', '2025-06-06 14:33:16' FROM user WHERE username = 'danielismail12';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.166.201", "ua": "Firefox/127"}', '2025-01-02 19:31:40' FROM user WHERE username = 'danielismail12';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.82.104", "ua": "Chrome/120"}', '2025-05-20 17:14:42' FROM user WHERE username = 'danielismail12';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.88.219", "ua": "Firefox/128"}', '2025-04-02 21:00:59' FROM user WHERE username = 'danielismail12';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.136.227", "ua": "Edge/121"}', '2025-01-12 22:41:23' FROM user WHERE username = 'tanmok13';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.193.220", "ua": "Edge/94"}', '2025-09-02 10:18:57' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.236.225", "ua": "Firefox/95"}', '2025-05-05 04:46:36' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.132.70", "ua": "Firefox/99"}', '2025-04-09 16:39:27' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.49.58", "ua": "Chrome/130"}', '2025-08-14 16:48:00' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.69.100", "ua": "Firefox/125"}', '2025-08-06 14:57:56' FROM user WHERE username = 'adamusman15';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.45.136", "ua": "Safari/129"}', '2024-09-08 09:42:24' FROM user WHERE username = 'adamusman15';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'ATTEMPTED_LOGIN', '{"ip": "192.168.220.229", "ua": "Edge/113"}', '2025-02-22 09:01:27' FROM user WHERE username = 'beneng16';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.79.43", "ua": "Safari/119"}', '2024-09-26 05:38:00' FROM user WHERE username = 'beneng16';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.126.149", "ua": "Firefox/95"}', '2025-09-07 01:31:08' FROM user WHERE username = 'beneng16';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.251.107", "ua": "Firefox/105"}', '2024-11-29 12:44:12' FROM user WHERE username = 'beneng16';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.11.51", "ua": "Safari/129"}', '2025-01-10 21:54:37' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.60.16", "ua": "Edge/91"}', '2025-05-13 23:00:32' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.170.98", "ua": "Chrome/113"}', '2024-11-17 11:36:07' FROM user WHERE username = 'aliciapang18';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_EMAIL', '{"ip": "192.168.171.96", "ua": "Chrome/124"}', '2024-09-29 13:13:52' FROM user WHERE username = 'aliciapang18';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.100.114", "ua": "Safari/108"}', '2025-07-02 16:52:32' FROM user WHERE username = 'sitizainal19';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.11.22", "ua": "Chrome/122"}', '2025-05-22 04:19:33' FROM user WHERE username = 'sitizainal19';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'LOGOUT', '{"ip": "192.168.42.165", "ua": "Edge/124"}', '2025-03-19 07:47:00' FROM user WHERE username = 'sitizainal19';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'CHANGED_PASSWORD', '{"ip": "192.168.1.169", "ua": "Chrome/106"}', '2025-03-30 19:34:40' FROM user WHERE username = 'sitichan20';
INSERT INTO user_security_event (user_id, event, data, created_at) SELECT id, 'SUCCESSFUL_LOGIN', '{"ip": "192.168.144.5", "ua": "Safari/126"}', '2024-09-09 14:24:32' FROM user WHERE username = 'sitichan20';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', 'FAFB48A8CCB3', '15D4BBC0A3C74F404A7B2B96083A3A5E', '2026-01-08 07:34:16' FROM user WHERE username = 'graceong4';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '6C01C8F68210', '18A316E5780ABE688820B8BF086BA404', '2026-01-01 20:50:09' FROM user WHERE username = 'lilypang5';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', '7097CA95357A', '09E7096DFEF2AF0FACD24D18BA52C20C', '2026-01-22 21:32:22' FROM user WHERE username = 'lilypang5';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '434B1168B818', '88C2FD4207EFDADA1F3287C182E87139', '2026-01-01 21:01:42' FROM user WHERE username = 'lilypang5';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', '45223E775F51', 'A7140CCC6F78908D8DC654D4D96B537E', '2026-02-13 21:47:23' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '8BA37037C12C', '28EC97D057610C4C41A7C2976B8F707C', '2026-01-17 09:06:45' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', 'E4FE657D862F', '19A8BD2D36B10D4B317C458F6EEBCAD9', '2026-02-04 23:27:47' FROM user WHERE username = 'zulvoon7';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', 'FF2D8CCF529E', 'DEED289F0391B27ECEC08351069C32C7', '2025-09-25 01:17:35' FROM user WHERE username = 'carang8';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', 'DAB299908E64', 'E8597040DA21B68400D7426A508A55E1', '2026-03-05 03:35:25' FROM user WHERE username = 'lilyismail9';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'B5BCCAEABB12', 'EA0B50A482913B4CF3832B10DF0DD18C', '2026-01-30 09:48:41' FROM user WHERE username = 'lilyismail9';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', 'C8461B039297', '947DE39E8395B0FD0598C590BFB67E9E', '2025-10-25 07:23:59' FROM user WHERE username = 'lilyismail9';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'A5187675FC81', '3BCEB20A6F7C6DB421347C7DF7EE6548', '2026-01-08 01:53:11' FROM user WHERE username = 'yenwong10';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'C9045BFB3C24', '9C2E24835A6418A5F763663B25610CB1', '2026-03-08 03:15:58' FROM user WHERE username = 'yenwong10';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'FA48F2CB4808', 'FD2304F0FD8E58C7CAFF07477AFF26AE', '2025-11-30 13:23:09' FROM user WHERE username = 'danielismail12';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', 'E7C3076DCF61', 'CBEEA44068BCB0DFA1EC53F2D0438C0F', '2025-09-12 21:26:44' FROM user WHERE username = 'tanmok13';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', '2FAE3BE075E5', '511D12C16BCD06054BF73D5FB74F20E2', '2026-02-13 20:53:02' FROM user WHERE username = 'tanmok13';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'EE60C0565757', '0973650B0FA4A1FF6C37FDCE4206251B', '2025-09-22 22:40:59' FROM user WHERE username = 'ivyjohari14';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', 'C5CC599E487A', '10F5E28818EE99B023F643271E718031', '2026-02-22 17:28:06' FROM user WHERE username = 'adamusman15';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', '98EBCD65C2CA', '05AB0CDA5009B3197EA38CF651784A1E', '2025-12-06 15:05:56' FROM user WHERE username = 'adamusman15';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', '99A3DB0BC0E5', '8D416045AD018E64B0F7FDC9C5455C43', '2026-01-02 21:30:09' FROM user WHERE username = 'adamusman15';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '633A4A61AA08', '2C4B9454FFE84304A988B592A5CD0C4F', '2026-01-23 23:22:54' FROM user WHERE username = 'beneng16';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'A045A87C1578', 'AF1B42786D264D3430EFF9A7E6A26F9B', '2025-10-11 17:38:06' FROM user WHERE username = 'beneng16';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '4CF0767FCE7F', 'A00B0CFF74CE1560E64C99A679ABD0B5', '2025-12-19 17:22:59' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', '885F8F5ABF4E', 'D3B78AF90F714CDBBE85390415BE9DF6', '2025-12-29 23:16:45' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', 'CA07A2A082C8', '87A50B9CA60C2FD3D77246CD8DB839A7', '2026-01-24 05:48:18' FROM user WHERE username = 'nadiaabdul17';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', '0CC61B1BE163', 'D52DE9C9D79ADC160B7847FF687179F6', '2025-12-08 15:16:48' FROM user WHERE username = 'aliciapang18';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'CONFIRM_EMAIL', '38596923FB22', '528E59448F3E0FDB0834E5BE3DEAFFA4', '2025-12-29 06:30:14' FROM user WHERE username = 'aliciapang18';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'RESET_PASSWORD', 'F534D5DC3E2E', 'D609DBEBBA3B19D6C88A9C6E10ACBC9C', '2025-12-23 08:45:17' FROM user WHERE username = 'sitichan20';
INSERT INTO user_token (user_id, type, selector, token, expires_at) SELECT id, 'REMEMBER_ME', '853F59307AE8', 'A63DAF0D4733B7CDF8BED6384BC5AF7A', '2025-12-23 13:48:55' FROM user WHERE username = 'sitichan20';
-- Done.
