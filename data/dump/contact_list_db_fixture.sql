--
-- PostgreSQL database dump
--

-- Dumped from database version 14.2
-- Dumped by pg_dump version 14.2

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

INSERT INTO public.address (id, address_data, status, status_id) VALUES (19, 'Test, 133', NULL, 2);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (1, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (2, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (4, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (5, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (6, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (7, 'Бусыгина, 9', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (8, 'Пушкина, 23', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (3, 'null', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (10, 'Test, 16', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (11, 'Test, 16', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (12, 'Test, 1', 'home', 1);
INSERT INTO public.address (id, address_data, status, status_id) VALUES (13, 'Test, 20', 'job', 2);


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (1, 'Осипов Геннадий Иванович', '1985-06-15', 'Системный администратор', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (2, 'Тамара', '1990-06-06', 'null', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (3, 'Дамир Авто', '1990-12-01', 'Автомеханик', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (4, 'Катя', '1989-03-08', 'null', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (5, 'Шипенко Леонид Иосифович', '1969-02-07', 'Слесарь', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'recipients');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (6, 'Дед', '1945-06-04', 'Столяр', 'Дед', 'Bells', '1', NULL, NULL, 'null', 'null', 'null', 'null', NULL, 'kinsfolk');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (7, 'Калинин Пётр Александрович', '1983-06-04', 'Фитнес тренер', 'null', 'null', 'null', 5684, 2500, '5%', 'С 9:00 до 13:00 в будни', 'null', 'null', NULL, 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (8, 'Васин Роман Александрович', '1977-01-04', 'Фитнес тренер', 'null', 'null', 'null', 5683, 9500, '10%', 'С 12:00 до 16:00 в будни', 'null', 'null', NULL, 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (9, 'Стрелецкая Анастасия Виктоовна', '1980-12-30', 'Админимстратор фитнес центра', 'null', 'null', 'null', 5682, 15200, '10%', 'С 17:00 до 19:00 в будни', 'null', 'null', NULL, 'customers');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (10, 'Шатов Александр Иванович', '1971-12-02', 'null', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'Дирекция', 'Директор', 405, 'colleagues');
INSERT INTO public.contacts (id, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, category) VALUES (11, 'Наташа', '1984-05-10', 'null', 'null', 'null', 'null', NULL, NULL, 'null', 'null', 'Дирекция', 'Секретарь', 404, 'colleagues');


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


--
-- Data for Name: contact_list; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (1, 1, true);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (2, 2, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (3, 3, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (4, 4, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (5, 5, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (6, 6, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (7, 7, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (8, 8, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (9, 9, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (10, 10, false);
INSERT INTO public.contact_list (id, id_recipient, blacklist) VALUES (11, 11, false);


--
-- Data for Name: messengers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.messengers (type_messenger, username, id_recipient) VALUES ('telegram', 'osipov', 1);


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

SELECT pg_catalog.setval('public.address_id_seq', 19, true);


--
-- Name: address_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.address_status_id_seq', 2, true);


--
-- Name: contact_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_list_id_seq', 1, false);


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contacts_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

