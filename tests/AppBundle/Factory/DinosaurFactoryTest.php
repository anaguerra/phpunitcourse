<?php

namespace Tests\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class DinosaurFactoryTest extends TestCase
{

    /**
    * @var DinosaurFactory
    */
    private $factory;

    /**
     * @var MockObject
     */
    private $lengthDeterminator;


    /**
     * $this->lengthDeterminator al ser un mock, por default todos sus métodos
     * devuelven null (o 0, o string vacío, según el tipo de dato que devuelva)
     */
    public function setUp()
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }


    public function testItGrowsAVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);

        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());

        $this->assertSame(5, $dinosaur->getLength());
    }


    public function testItGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }


    /**
     * test skip
     */
    public function testItGrowsAbabyVelociraptor()
    {
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby');
        }

        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());
    }


    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromASpecification(string $spec, bool $expectedIsCarnivorous)
    {
        /**
         * MOCK lengthDeterminator
         * al llamar a setUp, this->lengthDeterminator es un mock de DinosaurLengthDeterminator,
         * por tanto sobreescribe todos sus métodos y devuelven nulo, 0 o string vacía
         */

         /**
         * if the method is not called once or is called with different arguments, the test will fail.
         */
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous());
        $this->assertSame(20, $dinosaur->getLength());
    }



    /**
     * provide the different tests cases we want to try
     */
    public function getSpecificationTests()
    {
        return [
          // specification, is carnivorous

          ['large carnivorous dinosaur',  true],
          'default response' => ['give me all the cookies!!',  false],
          ['large herbivore', false],
        ];
    }


}

