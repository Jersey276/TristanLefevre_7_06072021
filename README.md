# TristanLefevre_7_06072021 / mileBo project

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b1eb9d61875140919908521ddf2be679)](https://www.codacy.com/gh/Jersey276/TristanLefevre_7_06072021/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jersey276/TristanLefevre_7_06072021&amp;utm_campaign=Badge_Grade)

A api for a mobile business

## Installation

1. get package
2. Collect all composer dependencies with `composer require` or `composer update` and press enter
3. Update .env with your database information (domain name of server, db name, login, password). See .env for more detail.  
4. Use `php bin/console doctrine/migrations/migrate` for fill database with all table
5. (optionnal) for testing database, use commande `php bin/console doctrine:fixtures:load`
6. Use `php bin/console lexik:jwt:generate-keypair` for generate private/public key

## Content

This Api offer some services and functions such as :

- Authentification system and JWT Token
- Product listing and datail
- customer's user manager
- Documentation with sandbox
- Error manager

More detail and all list of api command on documentation at link :

{Domain name}/api/doc (you need to install this project)
