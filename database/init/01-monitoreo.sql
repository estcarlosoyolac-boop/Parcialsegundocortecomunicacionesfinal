CREATE EXTENSION IF NOT EXISTS file_fdw;

CREATE SERVER nginx_logs FOREIGN DATA WRAPPER file_fdw;

CREATE SCHEMA monitoreo;

CREATE FOREIGN TABLE monitoreo.nginx_access (
    ts                  timestamptz,
    client_ip           text,
    method              text,
    uri                 text,
    status              integer,
    bytes_sent          bigint,
    request_time        numeric,
    user_agent          text,
    connection_id       bigint,
    connection_requests integer
) SERVER nginx_logs
OPTIONS (filename '/var/log/nginx-joomla/access.tsv', format 'text', delimiter E'\t');