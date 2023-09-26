<h1>Gekota</h1>

Welcome to the Gekota shop app. This Symfony 5.4 application allows to manage and operate an online shop for exotic animals.

<h2>Getting Started</h2>

These instructions will help you set up and run the Gekota app on your local machine for development and testing purposes.

<h4>Prerequisites</h4>

Before you begin, make sure you have the following software installed on your system:

    PHP (>=7.4)
    Composer (https://getcomposer.org/download/)
    Symfony CLI (https://symfony.com/download)
    SQLite or any database system compatile with Doctrine DBAL

<h4>Installation</h4>

Follow these steps to set up the Gekota Shop App:

1. Clone the repository to your local machine:
   
       git clone https://github.com/OskarLukaszewicz/gekota.pl.git
   
2. Navigate to the project directory:

        cd gekota.pl

3. Install the project dependencies using Composer:

        composer install

4. Create database (SQLite needed), migration, migrate and load data fixtures:

       php bin/console app:setup --create-database --make-migration --run-migration --load-fixtures

5. Start the Symfony development server:

       symfony server:start

6. Access the app in your web browser:

        http://127.0.0.1:8000

<h2>Usage</h2>

Visit the homepage to explore the Blog and Offer sections.

Use the admin panel (accessible at '/admin') to manage products, blog, events, galleries, images and users.

CRUD controllers provide the CRUD operations for Doctrine ORM entities. Each of these controllers is associated to one entity and one dashboard.

<h4>Features</h4>

The app is optimized according to SEO standards (also for dynamic resources).

Sitemaps are automatically generated both for static and dynamic resources (thanks to PrestaSitemapBundle).

src/Service/GoogleApiDataProvider class provided data from Google Analytics API. It's disabled in this version and uses the src/Service/FakeChartDataProvider instead to generate data. You can easily change this behavior, Google Cloud API Interfaces credentials are needed.

The provided data is being used by src/Service/ChartBuilder (using Symfony UX Chart.js) class to generate charts that are visible at default '/admin' path.

There are many minor features working inside of controllers. 




<h4>Thanks for Reading!<h4>
