# Project: PHP Blogs (Blogy)

A blog with categories and articles, written in plain PHP without a framework.

**Stack:** PHP 8.3 · MySQL 8.0 · Smarty 5 · nginx · Docker · SCSS

---

## Getting started

Docker and Docker Compose are the only requirements.

```bash
git clone https://github.com/Doppamine/php_blogs.git
cd php_blogs
make init (or similar make command)
```

The site will be available at `http://localhost:8080`.

`make init` creates `.env` from the example, starts the containers, installs dependencies, compiles the CSS, applies the
database schema and seeds demo data.

> **On Linux:** set `UID` and `GID` in `.env` to your own values (`id -u` and
> `id -g`) before running.

### Commands

| Command                 | Description                           |
|-------------------------|---------------------------------------|
| `make init`             | full setup from scratch               |
| `make up` / `make down` | start / stop containers               |
| `make schema`           | recreate the database schema          |
| `make seed`             | fill the database with demo data      |
| `make assets`           | compile SCSS into CSS                 |
| `make logs`             | tail the PHP container logs           |
| `make sh`               | open a shell inside the PHP container |

---

## Pages

- `/` — every category that has articles, with its three most recent posts
- `/category/{id}` — articles of a category, with sorting and pagination
- `/article/{id}` — the full article, a view counter and a block of similar articles

---

## Project layout

```
bin/console.php          CLI: db:seed, assets:build
bootstrap.php            autoloading, .env, configuration
config/config.php        application settings
database/schema.sql      database schema
docker/                  Dockerfile, nginx config, php.ini
public/                  directory exposed to the web server
  assets/                compiled CSS and images
  index.php              front controller
resources/scss/app.scss  style sources
src/
  Commands/              CLI commands
  Controllers/           page controllers
  Core/                  Router, View, Database, Paginator
  Repositories/          data access
templates/               Smarty templates
  errors/                error pages
  layouts/               base template
  partials/              reusable template fragments
var/                     compiled templates and cache
```

---

## Database schema

```
categories              articles                article_category
──────────              ────────                ────────────────
id                      id                      article_id  ─┐
name                    title                   category_id ─┤ PK
description             description                          │
created_at              content                              │
                        image                                │
                        views_count                          │
                        published_at                         │
                        created_at                           │
```

A many-to-many relationship: an article can belong to several categories.

The schema is created by a single `schema.sql` with `DROP` + `CREATE`.

---

## Decisions

### Architecture

**Router** built on a route table with a single `{id}` placeholder. A non-numeric identifier is rejected before it
reaches a controller, so there is never a database lookup for article 0.

**Repositories instead of ORM.** The project is small enough that a full ORM would be overkill. Besides the task
specifically asks for a solution without a framework, and making an ORM would be similar to building own framework.

### Category page

**A tie-breaker on `id`** in both sort orders. Without it the order of rows sharing a publication date or a view count
is undefined.

### Article page

**Similar articles** are ranked by the number of categories they share with the current one. A self-join on the pivot
table provides both halves: the categories of the current article, and every article sitting in those categories. After
grouping, each row represents exactly one shared category, so
`COUNT(*)` is the similarity metric itself. Results are ordered by the number of matches first and by recency second.

### Security

- **SQL injection.** All values travel through prepared statements. The only place where raw user input enters the SQL
  is
  `ORDER BY`, and what goes there comes from a whitelist (`AVAILABLE_SORTS` array).
- **XSS.** Smarty escapes output by default (`setEscapeHtml(true)`), and nothing in the project opts out.
- **Execution of uploaded files.** The nginx `.php` handler carries
  `try_files $uri =404` so that request like `/assets/photo.jpg/x.php`
  is not executed as a PHP script.
- **Errors.** Details are shown only when `APP_ENV=local`.

### Styles

SCSS is compiled with `scssphp/scssphp`, a Sass compiler written in PHP and kept in dev dependencies. This keeps the
project on a single runtime: the build works for anyone who has Docker running, with no Node.js installation.

---

## Use of AI

The project was mainly made without the use of AI. However, an LLM AI was used to: discuss the initial project folder
structure, discuss the use of ORM vs Repositories, discuss the use of scssphp instead of dart sass, provide guidance on
the nginx and php-fpm configuration, assist in writing boilerplate code (such as templates, and scss styling), assist in
writing and formatting this README file.

On top of that, the project was partially written with the help of a PhpStorm intellisense code completion feature,
which is a form of AI assistance.

Though every decision in the project was made deliberately, and I am happy to walk through any line of it.

---

## Demo data

`make seed` creates 6 categories and 100 articles. Generation is deterministic (Faker runs with a fixed seed), so the
dataset is reproducible.

One category (Politics) is intentionally left without articles, to make it visible that the home page only renders
categories that have content.

Images come from [Unsplash](https://unsplash.com/license) and are free for commercial and non-commercial use with no
attribution required.