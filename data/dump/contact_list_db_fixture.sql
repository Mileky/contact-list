--
-- PostgreSQL database dump
--

-- Dumped from database version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: address_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.address_status (id, name) VALUES (1, 'home');
INSERT INTO public.address_status (id, name) VALUES (2, 'job');


--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.address (id, status_id, address_data) VALUES (19, 2, 'Test, 133');
INSERT INTO public.address (id, status_id, address_data) VALUES (20, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (21, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (22, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (23, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (24, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (25, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (26, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (27, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (28, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (29, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (30, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (31, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (32, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (1, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (2, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (4, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (5, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (6, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (7, 1, 'Бусыгина, 9');
INSERT INTO public.address (id, status_id, address_data) VALUES (8, 1, 'Пушкина, 23');
INSERT INTO public.address (id, status_id, address_data) VALUES (3, 1, 'null');
INSERT INTO public.address (id, status_id, address_data) VALUES (10, 1, 'Test, 16');
INSERT INTO public.address (id, status_id, address_data) VALUES (11, 1, 'Test, 16');
INSERT INTO public.address (id, status_id, address_data) VALUES (12, 1, 'Test, 1');
INSERT INTO public.address (id, status_id, address_data) VALUES (13, 2, 'Test, 20');
INSERT INTO public.address (id, status_id, address_data) VALUES (44, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (46, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (48, 1, 'test, 5');
INSERT INTO public.address (id, status_id, address_data) VALUES (50, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (52, 2, 'testtttt, 23');
INSERT INTO public.address (id, status_id, address_data) VALUES (54, 1, 'еуы, 2');
INSERT INTO public.address (id, status_id, address_data) VALUES (56, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (58, 1, 'Плотникова, 3');
INSERT INTO public.address (id, status_id, address_data) VALUES (60, 1, 'Плотникова, 3');


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (1, 'Осипов Геннадий Иванович', '1985-06-15', 'Системный администратор', 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (2, 'Тамара', '1990-06-06', 'null', 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (3, 'Дамир Авто', '1990-12-01', 'Автомеханик', 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (4, 'Катя', '1989-03-08', 'null', 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (5, 'Шипенко Леонид Иосифович', '1969-02-07', 'Слесарь', 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (6, 'Дед', '1945-06-04', 'Столяр', 'kinsfolk');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (7, 'Калинин Пётр Александрович', '1983-06-04', 'Фитнес тренер', 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (8, 'Васин Роман Александрович', '1977-01-04', 'Фитнес тренер', 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (9, 'Стрелецкая Анастасия Виктоовна', '1980-12-30', 'Админимстратор фитнес центра', 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (10, 'Шатов Александр Иванович', '1971-12-02', 'null', 'colleagues');
INSERT INTO public.contacts (id, full_name, birthday, profession, category) VALUES (11, 'Наташа', '1984-05-10', 'null', 'colleagues');


--
-- Data for Name: address_to_contact; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (1, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (2, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (4, 3);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (5, 4);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (6, 6);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (7, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (8, 2);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (3, 2);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (1, 3);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (8, 4);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (10, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (10, 3);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (11, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (11, 3);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (12, 3);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (12, 10);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (13, 2);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (19, 8);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (20, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (21, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (22, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (23, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (24, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (25, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (26, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (27, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (28, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (29, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (30, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (31, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (32, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (44, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (46, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (48, 1);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (48, 2);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (50, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (52, 2);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (52, 11);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (52, 10);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (54, 6);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (54, 11);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (56, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (58, 9);
INSERT INTO public.address_to_contact (address_id, recipient_id) VALUES (60, 9);


--
-- Data for Name: contact_list; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (4, 4, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (6, 6, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (7, 7, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (8, 8, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (9, 9, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (10, 10, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (11, 11, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (1, 1, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (5, 5, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (3, 3, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (2, 2, true);


--
-- Data for Name: contacts_colleagues; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts_colleagues (id, department, "position", room_number) VALUES (10, 'Дирекция', 'Директор', 405);
INSERT INTO public.contacts_colleagues (id, department, "position", room_number) VALUES (11, 'Дирекция', 'Секретарь', 404);


--
-- Data for Name: contacts_customers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts_customers (id, contract_number, average_transaction_amount, discount, time_to_call) VALUES (7, 5684, 2500, '5%', 'С 9:00 до 13:00 в будни');
INSERT INTO public.contacts_customers (id, contract_number, average_transaction_amount, discount, time_to_call) VALUES (8, 5683, 9500, '10%', 'С 12:00 до 16:00 в будни');
INSERT INTO public.contacts_customers (id, contract_number, average_transaction_amount, discount, time_to_call) VALUES (9, 5682, 15200, '10%', 'С 17:00 до 19:00 в будни');


--
-- Data for Name: contacts_kinsfolk; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts_kinsfolk (id, status, ringtone, hotkey) VALUES (6, 'Дед', 'Bells', 1);


--
-- Data for Name: contacts_recipients; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts_recipients (id, nickname) VALUES (1, NULL);
INSERT INTO public.contacts_recipients (id, nickname) VALUES (2, NULL);
INSERT INTO public.contacts_recipients (id, nickname) VALUES (3, NULL);
INSERT INTO public.contacts_recipients (id, nickname) VALUES (4, NULL);
INSERT INTO public.contacts_recipients (id, nickname) VALUES (5, NULL);


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DD\ContactList\Migrations\Version20220406073038', '2022-04-06 10:30:46', 225);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DD\ContactList\Migrations\Version20220406073413', '2022-04-06 10:40:55', 26);


--
-- Data for Name: messengers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.messengers (id, id_recipient, type_messenger, username) VALUES (1, 1, 'telegram', 'osipov');


--
-- Data for Name: phone_number; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (1, 1, '+79052321548', 'мтс');
INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (2, 2, '+79052325119', 'мтс');
INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (3, 3, '+79045218367', 'теле2');
INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (4, 4, '+79805214963', 'Мегафон');
INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (5, 3, '+79805218111', 'Мегафон');
INSERT INTO public.phone_number (id, id_recipient, phone_number, operator) VALUES (6, 5, '+76005218551', 'Билайн');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, login, password) VALUES (1, 'admin', '$2y$10$0MCiHPuzu020zv2gn0nPduLwW/PPTitoR/6KjcCCZbfCwwCHgxzcC');


--
-- Name: address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.address_id_seq', 60, true);


--
-- Name: address_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.address_status_id_seq', 16, true);


--
-- Name: contact_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_list_id_seq', 1, false);


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contacts_id_seq', 1, false);


--
-- Name: messengers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.messengers_id_seq', 1, true);


--
-- Name: phone_number_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.phone_number_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

