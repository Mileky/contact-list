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

ALTER TABLE ONLY public.address DROP CONSTRAINT fk_d4e6f816bf700bd;
ALTER TABLE ONLY public.address_to_contact DROP CONSTRAINT fk_ba675353f5b7af75;
ALTER TABLE ONLY public.address_to_contact DROP CONSTRAINT fk_ba675353e92f8f78;
ALTER TABLE ONLY public.messengers DROP CONSTRAINT fk_6cebd5dee831476e;
ALTER TABLE ONLY public.contact_list DROP CONSTRAINT fk_6c377ae7e831476e;
ALTER TABLE ONLY public.phone_number DROP CONSTRAINT fk_6b01bc5be831476e;
ALTER TABLE ONLY public.contacts_customers DROP CONSTRAINT fk_64f0abbbbf396750;
ALTER TABLE ONLY public.contacts_colleagues DROP CONSTRAINT fk_5828c9dbbf396750;
ALTER TABLE ONLY public.contacts_recipients DROP CONSTRAINT fk_4baeb7bbf396750;
ALTER TABLE ONLY public.contacts_kinsfolk DROP CONSTRAINT fk_21bfa37bbf396750;
DROP INDEX public.users_login_unq;
DROP INDEX public.uniq_6c377ae7e831476e;
DROP INDEX public.phone_number_phone_number_unq;
DROP INDEX public.phone_number_operator_idx;
DROP INDEX public.phone_number_id_recipient_idx;
DROP INDEX public.messengers_username_unq;
DROP INDEX public.idx_ba675353f5b7af75;
DROP INDEX public.idx_ba675353e92f8f78;
DROP INDEX public.contacts_profession_idx;
DROP INDEX public.contacts_kinsfolk_status_idx;
DROP INDEX public.contacts_kinsfolk_ringtone_idx;
DROP INDEX public.contacts_kinsfolk_hotkey_unq;
DROP INDEX public.contacts_full_name_idx;
DROP INDEX public.contacts_customers_time_to_call_idx;
DROP INDEX public.contacts_customers_discount_idx;
DROP INDEX public.contacts_customers_contract_number_unq;
DROP INDEX public.contacts_customers_average_transaction_amount_idx;
DROP INDEX public.contacts_colleagues_room_number_idx;
DROP INDEX public.contacts_colleagues_position_idx;
DROP INDEX public.contacts_colleagues_department_idx;
DROP INDEX public.contacts_category_idx;
DROP INDEX public.contacts_birthday_idx;
DROP INDEX public.address_status_name_unq;
DROP INDEX public.address_status_id_idx;
DROP INDEX public.address_address_data_idx;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
ALTER TABLE ONLY public.phone_number DROP CONSTRAINT phone_number_pkey;
ALTER TABLE ONLY public.messengers DROP CONSTRAINT messengers_pkey;
ALTER TABLE ONLY public.doctrine_migration_versions DROP CONSTRAINT doctrine_migration_versions_pkey;
ALTER TABLE ONLY public.contacts_recipients DROP CONSTRAINT contacts_recipients_pkey;
ALTER TABLE ONLY public.contacts DROP CONSTRAINT contacts_pkey;
ALTER TABLE ONLY public.contacts_kinsfolk DROP CONSTRAINT contacts_kinsfolk_pkey;
ALTER TABLE ONLY public.contacts_customers DROP CONSTRAINT contacts_customers_pkey;
ALTER TABLE ONLY public.contacts_colleagues DROP CONSTRAINT contacts_colleagues_pkey;
ALTER TABLE ONLY public.contact_list DROP CONSTRAINT contact_list_pkey;
ALTER TABLE ONLY public.address_to_contact DROP CONSTRAINT address_to_contact_pkey;
ALTER TABLE ONLY public.address_status DROP CONSTRAINT address_status_pkey;
ALTER TABLE ONLY public.address DROP CONSTRAINT address_pkey;
DROP SEQUENCE public.users_id_seq;
DROP TABLE public.users;
DROP SEQUENCE public.phone_number_id_seq;
DROP TABLE public.phone_number;
DROP SEQUENCE public.messengers_id_seq;
DROP TABLE public.messengers;
DROP TABLE public.doctrine_migration_versions;
DROP TABLE public.contacts_recipients;
DROP TABLE public.contacts_kinsfolk;
DROP SEQUENCE public.contacts_id_seq;
DROP TABLE public.contacts_customers;
DROP TABLE public.contacts_colleagues;
DROP TABLE public.contacts;
DROP SEQUENCE public.contact_list_id_seq;
DROP TABLE public.contact_list;
DROP TABLE public.address_to_contact;
DROP SEQUENCE public.address_status_id_seq;
DROP TABLE public.address_status;
DROP SEQUENCE public.address_id_seq;
DROP TABLE public.address;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: address; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.address (
    id integer NOT NULL,
    status_id integer,
    address_data character varying(255) NOT NULL
);


ALTER TABLE public.address OWNER TO postgres;

--
-- Name: address_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.address_id_seq OWNER TO postgres;

--
-- Name: address_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.address_status (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.address_status OWNER TO postgres;

--
-- Name: address_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.address_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.address_status_id_seq OWNER TO postgres;

--
-- Name: address_to_contact; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.address_to_contact (
    address_id integer NOT NULL,
    recipient_id integer NOT NULL
);


ALTER TABLE public.address_to_contact OWNER TO postgres;

--
-- Name: contact_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact_list (
    id integer NOT NULL,
    id_recipient integer,
    blacklist boolean NOT NULL
);


ALTER TABLE public.contact_list OWNER TO postgres;

--
-- Name: contact_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contact_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contact_list_id_seq OWNER TO postgres;

--
-- Name: contacts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts (
    id integer NOT NULL,
    full_name character varying(255) NOT NULL,
    birthday date NOT NULL,
    profession character varying(100) NOT NULL,
    category character varying(255) NOT NULL
);


ALTER TABLE public.contacts OWNER TO postgres;

--
-- Name: COLUMN contacts.birthday; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.contacts.birthday IS '(DC2Type:date_immutable)';


--
-- Name: contacts_colleagues; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts_colleagues (
    id integer NOT NULL,
    department character varying(40) NOT NULL,
    "position" character varying(40) NOT NULL,
    room_number integer NOT NULL
);


ALTER TABLE public.contacts_colleagues OWNER TO postgres;

--
-- Name: contacts_customers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts_customers (
    id integer NOT NULL,
    contract_number integer NOT NULL,
    average_transaction_amount integer NOT NULL,
    discount character varying(5) NOT NULL,
    time_to_call character varying(35) NOT NULL
);


ALTER TABLE public.contacts_customers OWNER TO postgres;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contacts_id_seq OWNER TO postgres;

--
-- Name: contacts_kinsfolk; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts_kinsfolk (
    id integer NOT NULL,
    status character varying(30) NOT NULL,
    ringtone character varying(30) DEFAULT NULL::character varying,
    hotkey integer
);


ALTER TABLE public.contacts_kinsfolk OWNER TO postgres;

--
-- Name: contacts_recipients; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contacts_recipients (
    id integer NOT NULL,
    nickname character varying(40) DEFAULT NULL::character varying
);


ALTER TABLE public.contacts_recipients OWNER TO postgres;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(1024) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO postgres;

--
-- Name: messengers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messengers (
    id integer NOT NULL,
    id_recipient integer,
    type_messenger character varying(50) NOT NULL,
    username character varying(50) NOT NULL
);


ALTER TABLE public.messengers OWNER TO postgres;

--
-- Name: messengers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.messengers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messengers_id_seq OWNER TO postgres;

--
-- Name: phone_number; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.phone_number (
    id integer NOT NULL,
    id_recipient integer,
    phone_number character varying(12) NOT NULL,
    operator character varying(20) NOT NULL
);


ALTER TABLE public.phone_number OWNER TO postgres;

--
-- Name: phone_number_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.phone_number_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.phone_number_id_seq OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    login character varying(50) NOT NULL,
    password character varying(60) NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: address address_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (id);


--
-- Name: address_status address_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address_status
    ADD CONSTRAINT address_status_pkey PRIMARY KEY (id);


--
-- Name: address_to_contact address_to_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address_to_contact
    ADD CONSTRAINT address_to_contact_pkey PRIMARY KEY (address_id, recipient_id);


--
-- Name: contact_list contact_list_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_list
    ADD CONSTRAINT contact_list_pkey PRIMARY KEY (id);


--
-- Name: contacts_colleagues contacts_colleagues_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_colleagues
    ADD CONSTRAINT contacts_colleagues_pkey PRIMARY KEY (id);


--
-- Name: contacts_customers contacts_customers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_customers
    ADD CONSTRAINT contacts_customers_pkey PRIMARY KEY (id);


--
-- Name: contacts_kinsfolk contacts_kinsfolk_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_kinsfolk
    ADD CONSTRAINT contacts_kinsfolk_pkey PRIMARY KEY (id);


--
-- Name: contacts contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: contacts_recipients contacts_recipients_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_recipients
    ADD CONSTRAINT contacts_recipients_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: messengers messengers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messengers
    ADD CONSTRAINT messengers_pkey PRIMARY KEY (id);


--
-- Name: phone_number phone_number_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.phone_number
    ADD CONSTRAINT phone_number_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: address_address_data_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX address_address_data_idx ON public.address USING btree (address_data);


--
-- Name: address_status_id_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX address_status_id_idx ON public.address USING btree (status_id);


--
-- Name: address_status_name_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX address_status_name_unq ON public.address_status USING btree (name);


--
-- Name: contacts_birthday_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_birthday_idx ON public.contacts USING btree (birthday);


--
-- Name: contacts_category_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_category_idx ON public.contacts USING btree (category);


--
-- Name: contacts_colleagues_department_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_colleagues_department_idx ON public.contacts_colleagues USING btree (department);


--
-- Name: contacts_colleagues_position_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_colleagues_position_idx ON public.contacts_colleagues USING btree ("position");


--
-- Name: contacts_colleagues_room_number_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_colleagues_room_number_idx ON public.contacts_colleagues USING btree (room_number);


--
-- Name: contacts_customers_average_transaction_amount_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_customers_average_transaction_amount_idx ON public.contacts_customers USING btree (average_transaction_amount);


--
-- Name: contacts_customers_contract_number_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX contacts_customers_contract_number_unq ON public.contacts_customers USING btree (contract_number);


--
-- Name: contacts_customers_discount_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_customers_discount_idx ON public.contacts_customers USING btree (discount);


--
-- Name: contacts_customers_time_to_call_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_customers_time_to_call_idx ON public.contacts_customers USING btree (time_to_call);


--
-- Name: contacts_full_name_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_full_name_idx ON public.contacts USING btree (full_name);


--
-- Name: contacts_kinsfolk_hotkey_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX contacts_kinsfolk_hotkey_unq ON public.contacts_kinsfolk USING btree (hotkey);


--
-- Name: contacts_kinsfolk_ringtone_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_kinsfolk_ringtone_idx ON public.contacts_kinsfolk USING btree (ringtone);


--
-- Name: contacts_kinsfolk_status_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_kinsfolk_status_idx ON public.contacts_kinsfolk USING btree (status);


--
-- Name: contacts_profession_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contacts_profession_idx ON public.contacts USING btree (profession);


--
-- Name: idx_ba675353e92f8f78; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_ba675353e92f8f78 ON public.address_to_contact USING btree (recipient_id);


--
-- Name: idx_ba675353f5b7af75; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_ba675353f5b7af75 ON public.address_to_contact USING btree (address_id);


--
-- Name: messengers_username_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX messengers_username_unq ON public.messengers USING btree (id_recipient);


--
-- Name: phone_number_id_recipient_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX phone_number_id_recipient_idx ON public.phone_number USING btree (id_recipient);


--
-- Name: phone_number_operator_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX phone_number_operator_idx ON public.phone_number USING btree (operator);


--
-- Name: phone_number_phone_number_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX phone_number_phone_number_unq ON public.phone_number USING btree (phone_number);


--
-- Name: uniq_6c377ae7e831476e; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX uniq_6c377ae7e831476e ON public.contact_list USING btree (id_recipient);


--
-- Name: users_login_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX users_login_unq ON public.users USING btree (login);


--
-- Name: contacts_kinsfolk fk_21bfa37bbf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_kinsfolk
    ADD CONSTRAINT fk_21bfa37bbf396750 FOREIGN KEY (id) REFERENCES public.contacts(id) ON DELETE CASCADE;


--
-- Name: contacts_recipients fk_4baeb7bbf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_recipients
    ADD CONSTRAINT fk_4baeb7bbf396750 FOREIGN KEY (id) REFERENCES public.contacts(id) ON DELETE CASCADE;


--
-- Name: contacts_colleagues fk_5828c9dbbf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_colleagues
    ADD CONSTRAINT fk_5828c9dbbf396750 FOREIGN KEY (id) REFERENCES public.contacts(id) ON DELETE CASCADE;


--
-- Name: contacts_customers fk_64f0abbbbf396750; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contacts_customers
    ADD CONSTRAINT fk_64f0abbbbf396750 FOREIGN KEY (id) REFERENCES public.contacts(id) ON DELETE CASCADE;


--
-- Name: phone_number fk_6b01bc5be831476e; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.phone_number
    ADD CONSTRAINT fk_6b01bc5be831476e FOREIGN KEY (id_recipient) REFERENCES public.contacts(id);


--
-- Name: contact_list fk_6c377ae7e831476e; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_list
    ADD CONSTRAINT fk_6c377ae7e831476e FOREIGN KEY (id_recipient) REFERENCES public.contacts(id);


--
-- Name: messengers fk_6cebd5dee831476e; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messengers
    ADD CONSTRAINT fk_6cebd5dee831476e FOREIGN KEY (id_recipient) REFERENCES public.contacts(id);


--
-- Name: address_to_contact fk_ba675353e92f8f78; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address_to_contact
    ADD CONSTRAINT fk_ba675353e92f8f78 FOREIGN KEY (recipient_id) REFERENCES public.contacts(id) ON DELETE CASCADE;


--
-- Name: address_to_contact fk_ba675353f5b7af75; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address_to_contact
    ADD CONSTRAINT fk_ba675353f5b7af75 FOREIGN KEY (address_id) REFERENCES public.address(id);


--
-- Name: address fk_d4e6f816bf700bd; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT fk_d4e6f816bf700bd FOREIGN KEY (status_id) REFERENCES public.address_status(id);


--
-- PostgreSQL database dump complete
--

