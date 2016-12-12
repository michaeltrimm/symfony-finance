# Finance Demo App

This application demonstrates a working knowledge of `Symfony 3` and `PHP 7` development by building a demo application that provides basic information relating to investment portfolios.

## Prompt

This application was built to the specifications provided below. It is not intended to be used in a production environment.

### Task

Create an app based on PHP and Symfony with the functionality below:

- Sign up / sign in for new users.
- Ability to create portfolio of stocks for registered users (standard CRUD would be fine). User should be able to add/remove any stock from the portfolio.
- Use Yahoo Finance to get the data for stocks.
- Show the plot "cost of portfolio vs time" for 2 last years.

Requirements

- The app should be published at github & should pass all Symfony coding's standards.
- README file should have the description how to install the app + timesheet: how much time was spent per each stage of the development.
- Use everywhere PHP annotations instead of Yaml/XML (except DI component).


## Installation

1. Clone the repository

        git clone https://github.com/michaeltrimm/symfony-finance.git

2. Install required packages using `composer`:

        cd symfony-finance
        composer install

3. Open the `app/config/parameters.yml` and edit the `database_host`, `database_port`, `database_name`, `database_user`, `database_password` values and add a new value `hmac_key` with a random alphanumerical password of at least 32 characters:

4. Install Database Components

        php bin/console doctrine:database:create
        php bin/console doctrine:schema:update --force

5. Run The Application

        php bin/console server:run

## Usage

1. Before managing your portfolio, you must sign in or register - new systems require an initial registration.

    http://localhost:8000/user/sign_up

2. Once an account has been created, the system will automatically sign you in.

3. Click on "Portfolio" in the navigation menu, or click on:

    http://localhost:8000/portfolio

4. Click the button "Add Investment" to add a new mutual fund, ETF, or stock to your portfolio, or click: 

    http://localhost:8000/portfolio/new

5. To create a new investment, enter the 3 bits of data then press the green "Add Investment" button

    - Investment Symbol (_like AAPL or TSLA or TWTR_)
    - Price Paid (_float value greater than 0.0_)
    - Quantity Purchased (_float value greater than 0.0_)

6. To View 2-Year Price History Of An Investment, click on the investment symbol on the Portfolio index page

    http://localhost:8000/portfolio/1/show

## Notes

- Invalid investment symbols will yield a `$null` Latest Price, `$0` Value, and `$(1)` Performance result on the Portfolio index page.
- Invalid investment symbols clicked on (to show details e.g. /portfolio/1/show) will yield a javascript error in the console `TypeError: data.query.results is null`
- Task number four was unclear since the metric of `cost of portfolio` was not fully defined. As a result of timing, availability to ask questions, etc. - this functionality was implemented using a _two year historial interactive `highstocks` line graph_ because the definition of `cost of portfolio` could have been interpreted several different ways since the prompt never required the portfolio manager to keep track of transactions inside the portfolio, thus `symbol` + `date added` doesn't provide any real information:

                              2016.12.11
        cost of portfolio =     ∑ entity(1)(pricePaid * quantity) + entity(n)(..., ...)... 
                              2014.12.11
        
                              2016.12.11
        cost of portfolio =     ∑ entity(1)(latestPrice * quantity) + entity(n)(..., ...)...
                              2014.12.11

- *Important Note* You must add a new configuration directive inside `app/config/parameters.yml` called `hmac_key` with an alphanum string to encrypt the hash_hmac inputs. The application will fail without this parameter set.


## Timesheet

| Time | Task |
|------|------|
| 12:10PM-12:25PM (`15m`) | Generated `Symfony 3` app from CLI |
| 1:30PM-2:10PM (`40m`) | Added `Bootstrap 3` template to app |
| 2:30PM-3:41PM (`71m`) | Implemented `/user/sign_in` and `/user/sign_up` and `/user/sign_out` |
| 3:41PM-3:53PM (`12m`) | Generated `Portfolio` entity |
| 6:25PM-7:07PM (`42m`) | Implemented `Portfolio` controller and necessary views to link into Yahoo Finance |
| 8:07PM-8:22PM (`15m`) | Wrote `README.md` documentation | 

