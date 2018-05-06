<?php

namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;
use Throwable;

class DefaultControllerTest extends WebTestCase
{

    private $content;


    public function testEnclosuresAreShownOnTheHomepage()
    {
        /**
         * Load fixtures to Test Database (SQLite)
         * To load fixtures to real database (dev) --> sy doc:fixtures:load
         */
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);


        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');

        // Inside the table we expect there to be 3 rows
        $this->assertCount(3, $table->filter('tbody tr'));
    }


    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ])->getReferenceRepository();

        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        // only for onNotSuccessfulTest
        $this->content = $client->getResponse()->getContent();

        $enclosure = $fixtures->getReference('carnivorous-enclosure');

        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }



    public function testItGrowsADinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);

        $client = $this->makeClient();

        /** Normally, when our app redirects, Symfony's Client does not follow the redirect.
         * Sometimes that's useful... but this line makes it behave like a normal browser.*/
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        /** @var \Symfony\Component\DomCrawler\Form */
        $form = $crawler->selectButton('Grow dinosaur')->form();



        /** In case of use Symfony Form,
         * "name" would be like dinosaur[enclosure]*/
        $form['enclosure']->select(3);
        $form['specification']->setValue('large hervibore');

        $client->submit($form);

        // Redirect to homepage with a nice message explaining what just happened
        $this->assertContains(
            'Grew a large hervibore in enclosure #3',
            $client->getResponse()->getContent()
        );

    }






//    public function onNotSuccessfulTest(Throwable $t)
//    {
//        print_r($t->getMessage());
//        print_r([__LINE__,__METHOD__, $this->content]);
//    }

}