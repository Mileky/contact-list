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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: address; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.address (
    id integer,
    id_recipient integer,
    address character varying(255),
    status character varying(4)
);


ALTER TABLE public.address OWNER TO postgres;

--
-- Name: contacts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts (
    id_recipient integer,
    full_name character varying(255),
    birthday date,
    profession character varying(100),
    status character varying(30),
    ringtone character varying(30),
    hotkey character varying(5),
    contract_number integer,
    average_transaction_amount integer,
    discount character varying(5),
    time_to_call character varying(35),
    department character varying(40),
    "position" character varying(40),
    room_number integer,
    type character varying(15)
);


ALTER TABLE public.contacts OWNER TO postgres;

--
-- Name: phone_number; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.phone_number (
    id_phone_number integer,
    id_recipient integer,
    phone_number character varying(12),
    operator character varying(20)
);


ALTER TABLE public.phone_number OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer,
    login character varying(50),
    password character varying(60)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.address (id, id_recipient, address, status) FROM stdin;
\.


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contacts (id_recipient, full_name, birthday, profession, status, ringtone, hotkey, contract_number, average_transaction_amount, discount, time_to_call, department, "position", room_number, type) FROM stdin;
\.


--
-- Data for Name: phone_number; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.phone_number (id_phone_number, id_recipient, phone_number, operator) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, password) FROM stdin;
\.


--
-- PostgreSQL database dump complete
--

