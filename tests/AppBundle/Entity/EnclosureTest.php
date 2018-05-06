<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Exception\DinosaursAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use PHPUnit\Framework\TestCase;


class EnclosureTest extends TestCase
{

    public function testItHasNoDinosaursBy()
    {
        $enclosure = new Enclosure();

//        $this->assertCount(0, $enclosure->getDinosaurs());
        $this->assertEmpty($enclosure->getDinosaurs());
    }


    /**
     *  in order to test one class - Enclosure - we need an object of a different class - Dinosaur.
     *  A unit test is supposed to test one class in complete isolation from all other classes.
     *  We want to test the logic of Enclosure, not Dinosaur.
     *
     * This is why mocking exists. With mocking, instead of instantiating and passing real objects -
     * like a real Dinosaur object - you create a "fake" object that looks like a Dinosaur, but isn't.
     * As you'll see in a few minutes, a mock object gives you a lot of control.
     *
     * https://knpuniversity.com/screencast/phpunit/dependent-objects
     *
     * Mock services, but don't mock simple model objects
     * Our entities are model classes
     * A service class is a class whose main job is to do work, but it doesn't hold much data, other than
     * maybe some configuration
     *
     * DinosaurFactory is a service class
     * As a rule, you will want to mock service classes, but you do not need to mock model classes
     * You can but usually it's overkill (exagerado)
     */
    public function testItAddsDinosaurs()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur());

        $this->assertCount(2, $enclosure->getDinosaurs());
    }



    public function testItDoesNotAllowCarnivorousDinosToMixWithHerbivores()
    {
        $enclosure = new Enclosure(true);
        $enclosure->addDinosaur(new Dinosaur());

        // expectException, add before calling the final code
        $this->expectException(NotABuffetException::class);
        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
    }



    /**
     * @expectedException \AppBundle\Exception\NotABuffetException
     */
    public function testItDoesNotAllowToAddNonCarnivorousDinosaursToCarnivorousEnclosure()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
        $enclosure->addDinosaur(new Dinosaur());
    }


    public function testItDoesNotAllowToAddDinosaursToUnsecureEnclosures()
    {
        $enclosure = new Enclosure();

        $this->expectException(DinosaursAreRunningRampantException::class);
        $this->expectExceptionMessage('Are you craaazy?¡?');

        $enclosure->addDinosaur(new Dinosaur());

    }


}