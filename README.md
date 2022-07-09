# Digital Store Script (digital-store)

Digital Store Script is an online digital marketplace script for the sales of airtime, data, result scratch cards (WAEC, BECE, NECO, NABTEB, etc), and possibly any digital asset. The script was build for **educational purposes.** It shows how to implement Slim4 PHP framework and Smarty Templating Engine. The thought behind the design to create interfaces that will define each product type, then classes can be built to implement those interfaces. In plain English, let's take MTN airtime for instance. You want to buy (resell) MTN airtime from Interswitch and Flutterwave. All you need to do is implement the `src/Interfaces/VTU/MtnAirtimeInterface.php` interface for each provider you wish to use, and add the classes in the providers list.

## Requirements

1. Apache Server
2. PHP >= 7.3
3. MySQL DB
4. Composer (for installing dependencies)

## Installation

The best way to install this script is by cloning this repository.

```bash
git clone https://github.com/onumahkalusamuel/digital-store.git
```

Then install composer dependencies

```bash
composer install
```

Create a database (in PHPMyAdmin).

Edit `/settings.php` with the right information.

Make a copy of `env.example` to `.env` and update values as necessary.

Run migrations:
```bash
php vendor/bin/phoenix migrate
```

You can start a local server for testing by running the `serve` script.

```bash
./serve
```

Then visit [http://localhost:8000](http://localhost:8000) to see the landing page.

## Administration

The first account created via the sign up page will be assigned as the admin of your site. The site can only have one admin. The email used will receive all critical notifications.

A link to how the administration panel works can be found ~here~ **coming soon**

## Contribution

The script is far from being completed. It needs to be restructured so that any frontend can use it. It also needs to have an API to serve as headless store. Therefore, contributions are welcomed. Create an issue with what you want to fix or add.

## Licensing

You are free to use the script for **educational purposes.** If you want to use this script in production, kindly contact me for proper permissions and licensing.
