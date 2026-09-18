-- ================================================================
-- Schema  : finance
-- Database: finance
-- Extracted: 2026-09-13_09-50-52
-- Host    : postgres:5432
-- ================================================================
--
-- PostgreSQL database dump
--

\restrict YiqoaIuezTGcPyQraeptvJqv8SFpy7PzAOZTsMiyHmvcndppvitTuZ86NEEAWbI

-- Dumped from database version 16.13
-- Dumped by pg_dump version 16.13

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
-- Name: accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.accounts (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(50) DEFAULT 'cash'::character varying NOT NULL,
    balance numeric(15,2) DEFAULT 0.00 NOT NULL,
    description text,
    is_active smallint DEFAULT 1 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.accounts_id_seq OWNED BY public.accounts.id;


--
-- Name: budget_periods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.budget_periods (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    month smallint NOT NULL,
    year smallint NOT NULL,
    total_budget numeric(15,2) DEFAULT 0.00 NOT NULL,
    notes text,
    status character varying(10) DEFAULT 'active'::character varying NOT NULL,
    created_by bigint,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT budget_periods_status_check CHECK (((status)::text = ANY ((ARRAY['active'::character varying, 'closed'::character varying])::text[])))
);


--
-- Name: budget_periods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.budget_periods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: budget_periods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.budget_periods_id_seq OWNED BY public.budget_periods.id;


--
-- Name: budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.budgets (
    id bigint NOT NULL,
    budget_period_id bigint NOT NULL,
    category_id bigint NOT NULL,
    amount numeric(15,2) DEFAULT 0.00 NOT NULL,
    used_amount numeric(15,2) DEFAULT 0.00 NOT NULL,
    notes text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.budgets_id_seq OWNED BY public.budgets.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(10) NOT NULL,
    color character varying(7) DEFAULT '#6366f1'::character varying NOT NULL,
    icon character varying(100) DEFAULT NULL::character varying,
    description text,
    is_active smallint DEFAULT 1 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT categories_type_check CHECK (((type)::text = ANY ((ARRAY['income'::character varying, 'expense'::character varying])::text[])))
);


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: finance_sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45) DEFAULT NULL::character varying,
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: finance_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(10) DEFAULT 'bendahara'::character varying NOT NULL,
    phone character varying(20) DEFAULT NULL::character varying,
    is_active smallint DEFAULT 1 NOT NULL,
    last_login_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    remember_token character varying(100) DEFAULT NULL::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT finance_users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'bendahara'::character varying])::text[])))
);


--
-- Name: finance_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_users_id_seq OWNED BY public.finance_users.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    code character varying(30) NOT NULL,
    account_id bigint NOT NULL,
    category_id bigint NOT NULL,
    budget_period_id bigint,
    type character varying(10) NOT NULL,
    amount numeric(15,2) NOT NULL,
    transaction_date date NOT NULL,
    description character varying(500) NOT NULL,
    notes text,
    reference character varying(100) DEFAULT NULL::character varying,
    attachment character varying(255) DEFAULT NULL::character varying,
    created_by bigint,
    created_by_name character varying(255) DEFAULT NULL::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted_at timestamp without time zone,
    CONSTRAINT transactions_type_check CHECK (((type)::text = ANY ((ARRAY['income'::character varying, 'expense'::character varying])::text[])))
);


--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: wa_notification_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.wa_notification_logs (
    id bigint NOT NULL,
    transaction_id bigint,
    phone character varying(20) NOT NULL,
    message text NOT NULL,
    status character varying(10) DEFAULT 'pending'::character varying NOT NULL,
    response text,
    sent_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT wa_notification_logs_status_check CHECK (((status)::text = ANY ((ARRAY['sent'::character varying, 'failed'::character varying, 'pending'::character varying])::text[])))
);


--
-- Name: wa_notification_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.wa_notification_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: wa_notification_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.wa_notification_logs_id_seq OWNED BY public.wa_notification_logs.id;


--
-- Name: wa_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.wa_settings (
    id bigint NOT NULL,
    fonnte_token character varying(255) NOT NULL,
    admin_phone character varying(20) NOT NULL,
    device_number character varying(20) DEFAULT NULL::character varying,
    target_phones text,
    notify_income smallint DEFAULT 1 NOT NULL,
    notify_expense smallint DEFAULT 1 NOT NULL,
    notify_budget_warning smallint DEFAULT 1 NOT NULL,
    notify_on_transaction smallint DEFAULT 1 NOT NULL,
    notify_on_budget_exceeded smallint DEFAULT 1 NOT NULL,
    budget_alert_threshold smallint DEFAULT 80 NOT NULL,
    budget_warning_pct smallint DEFAULT 80 NOT NULL,
    is_active smallint DEFAULT 1 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: wa_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.wa_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: wa_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.wa_settings_id_seq OWNED BY public.wa_settings.id;


--
-- Name: accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accounts ALTER COLUMN id SET DEFAULT nextval('public.accounts_id_seq'::regclass);


--
-- Name: budget_periods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budget_periods ALTER COLUMN id SET DEFAULT nextval('public.budget_periods_id_seq'::regclass);


--
-- Name: budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets ALTER COLUMN id SET DEFAULT nextval('public.budgets_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: finance_users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_users ALTER COLUMN id SET DEFAULT nextval('public.finance_users_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: wa_notification_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wa_notification_logs ALTER COLUMN id SET DEFAULT nextval('public.wa_notification_logs_id_seq'::regclass);


--
-- Name: wa_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wa_settings ALTER COLUMN id SET DEFAULT nextval('public.wa_settings_id_seq'::regclass);


--
-- Name: accounts accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accounts
    ADD CONSTRAINT accounts_pkey PRIMARY KEY (id);


--
-- Name: budget_periods budget_periods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budget_periods
    ADD CONSTRAINT budget_periods_pkey PRIMARY KEY (id);


--
-- Name: budgets budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: finance_sessions finance_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_sessions
    ADD CONSTRAINT finance_sessions_pkey PRIMARY KEY (id);


--
-- Name: finance_users finance_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_users
    ADD CONSTRAINT finance_users_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- Name: transactions uq_code; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT uq_code UNIQUE (code);


--
-- Name: finance_users uq_email; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_users
    ADD CONSTRAINT uq_email UNIQUE (email);


--
-- Name: budget_periods uq_month_year; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budget_periods
    ADD CONSTRAINT uq_month_year UNIQUE (month, year);


--
-- Name: budgets uq_period_category; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT uq_period_category UNIQUE (budget_period_id, category_id);


--
-- Name: wa_notification_logs wa_notification_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wa_notification_logs
    ADD CONSTRAINT wa_notification_logs_pkey PRIMARY KEY (id);


--
-- Name: wa_settings wa_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wa_settings
    ADD CONSTRAINT wa_settings_pkey PRIMARY KEY (id);


--
-- Name: idx_fs_last_activity; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fs_last_activity ON public.finance_sessions USING btree (last_activity);


--
-- Name: idx_fs_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fs_user_id ON public.finance_sessions USING btree (user_id);


--
-- Name: budgets fk_budgets_category; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT fk_budgets_category FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: budgets fk_budgets_period; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT fk_budgets_period FOREIGN KEY (budget_period_id) REFERENCES public.budget_periods(id) ON DELETE CASCADE;


--
-- Name: budget_periods fk_period_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budget_periods
    ADD CONSTRAINT fk_period_user FOREIGN KEY (created_by) REFERENCES public.finance_users(id) ON DELETE SET NULL;


--
-- Name: transactions fk_trx_account; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_trx_account FOREIGN KEY (account_id) REFERENCES public.accounts(id);


--
-- Name: transactions fk_trx_category; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_trx_category FOREIGN KEY (category_id) REFERENCES public.categories(id);


--
-- Name: transactions fk_trx_period; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_trx_period FOREIGN KEY (budget_period_id) REFERENCES public.budget_periods(id) ON DELETE SET NULL;


--
-- Name: transactions fk_trx_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_trx_user FOREIGN KEY (created_by) REFERENCES public.finance_users(id) ON DELETE SET NULL;


--
-- Name: wa_notification_logs fk_walog_trx; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wa_notification_logs
    ADD CONSTRAINT fk_walog_trx FOREIGN KEY (transaction_id) REFERENCES public.transactions(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict YiqoaIuezTGcPyQraeptvJqv8SFpy7PzAOZTsMiyHmvcndppvitTuZ86NEEAWbI

