# TEST CASE

--- 
After investigation the task, it was decided to use a mini framework because:
- > Anything we'll find in the code, we'll treat as if you'd write it yourself
- We have to use unit test, and use unit tests for one file not good idea
---
# about framework
- Using cache requests because of https://binlist.net/ allow only 5 requests per hour
- Using DI container because 
- > Code should be extendible – we should not need to change existing, already tested functionality
- Using ext-intl because php strongly recommend use it for formating money format
- Using vfsstream for create mocks
- Using curl for example of framework extensibility
---

# install

- Run docker-compose build && docker-compose up
- OR run commands from docker/images/php/Dockerfile if you don't use docker
- OR install php >= 8.3, libicu-dev, composer on your local env
- Run composer install
---

# usage

Commissions calculate:
> run php ./app.php files/input.txt

OR
> php ./app.php files/input.txt 1

Testing:
> ./vendor/bin/phpunit tests

---

> It must have unit tests. If you haven't written any previously, please take the time to learn it before making the task, you'll thank us later.
Unit tests must test the actual results and still pass even when the response from remote services change (this is quite normal, exchange rates change every day). This is best accomplished by using mocking.

Not all variants added to test, but I guess this is enough for test case

> As an improvement, add ceiling of commissions by cents. For example, 0.46180... should become 0.47.

Ceiling added (NumberFormatter::ROUND_CEILING)

> It should give the same result as original code in case there are no failures, except for the additional ceiling.

Failures exceptions. Of course, we can hide them and not throw exception. But this is bad idea do it on development process

> Code should be extendable – we should not need to change existing, already tested functionality to accomplish the following:
Switch our currency rates provider (different URL, different response format and structure, possibly some authentication);
Switch our BIN provider (different URL, different response format and structure, possibly some authentication);
Just to note – no need to implement anything additional. Just structure your code so that we could implement that later on without braking our tests;

You can add new Api service and include it to DI config. (see src/services/Request and src/config/di.php)
Also you can add new model implements for src/interfaces/BinModelInterface and return necessary values (see src/models/BinModel (getAlpha2 and getCountryName))
And add/change something in src/config/app.php

> It should look as you'd write it yourself in production – consistent, readable, structured. Anything we'll find in the code, we'll treat as if you'd write it yourself. Basically it's better to just look at the existing code and re-write it from scratch. For example, if 'yes'/'no', ugly parsing code or die statements are left in the solution, we'd treat it as an instant red flag.

That's why mini framework created

> Use composer to install testing framework and any needed dependencies you'd like to use, also for enabling autoloading

Additional libs described in "about framework" section

> Do not use....

OK ;)

