# PHPUnit: Testing with a Bite

Well hi there! This repository holds the code and script
for the [PHPUnit: Testing with a Bite](https://knpuniversity.com/screencast/phpunit) course on KnpUniversity.

## Setup

If you've just downloaded the code, congratulations!

To get it working, follow these steps:

**Setup parameters.yml**

First, make sure you have an `app/config/parameters.yml`
file (you should). If you don't, copy `app/config/parameters.yml.dist`
to get it.

Next, look at the configuration and make any adjustments you
need (like `database_password`).

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Setup the Database**

Again, make sure `app/config/parameters.yml` is setup
for your computer. Then, create the database and the
schema!

```
php bin/console doctrine:database:create
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.

**Start the built-in web server**

You can use Nginx or Apache, but the built-in web server works
great:

```
php bin/console server:run
```

Now check out the site at `http://localhost:8000`

Have fun!

**(optional) Add bash alias for better DX**

For better DX to avoid having to use `./vendor/bin/phpunit` all the time create a bash alias:

```bash
alias phpunit=./vendor/bin/phpunit
```

From now on you will be able to run local PHPUnit from your project directory by executing `phpunit` command. Add alias command to your bash profile if you don't want to run it every time you enter a new terminal.

## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, please feel free to
open an issue on this repository or comment on the course
itself. We're watching both :).

## Thanks!

And as always, thanks so much for your support and letting
us do what we love!

<3 Your friends at KnpUniversity


UNIT TESTS: Fake any database connections
Test one specific function on a class

INTEGRATION TEST: Just like a unit test
Except it uses the real database connection

FUNCTIONAL TEST: 

If it scares me, i test it

**TDD**

First, create the test. Second, write the minimum amount of code to get that test to pass. 
And third, now that your tests are passing, you can safely refactor your code to make it fancier.

**setUp method**
PHPUnit will automatically call it before each test (before each test method)

Each test should be completely independent of each other. You never want one test to rely on something 
a different test set up first

**execute just one test at a time**


**tearDown method**


**onNotSuccesfulTest**

To print some info..


**assertGreaterThanOrEqual**
    
    /**
    * you need to read it backwards
     * $actualSize is greater or equal to $minExpectedSize
     */
    $this->assertGreaterThanOrEqual($minExpectedSize, $actualSize);

    
**Mocks**
When you create a mock, it creates a new class in memory that extends the original, 
but overrides every method and simply returns null

A mock is a Zombie version of the original object

    protected function createMock($originalClassName)
    {
        return $this->getMockBuilder($originalClassName)
                    ->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->disableArgumentCloning()
                    ->disallowMockingUnknownTypes()
                    ->getMock();
    }

The constructor is skipped

By default, all methods are mocked. But you can use the setMethods() function to only mock some methods

Techcnically, there are a number of different terms: dummies, stubs, spies and mocks. They all mean
sligthtly different things. Technically, what we've created is a dummy

http://www.ifdattic.com/dummy-test-double-using-prophecy/



