<?php

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{

    public function setUp()
    {
        self::bootKernel();

        $this->truncateEntities();
    }


    public function testItBuildsEnclosureWithDefaultSpecification()
    {
        /** Normal mocking. Uncomment this for test with normal mocking */
        /** @var  EnclosureBuilderService $enclosureBuilderService */
//        $enclosureBuilderService = self::$kernel->getContainer()
//            ->get('test.' .EnclosureBuilderService::class);


        /** Init partial mocking
         * Comment this for normal mocking
         */
        $dinosaurFactory = $this->createMock(DinosaurFactory::class);
        $dinosaurFactory->expects($this->any())
            ->method('growFromSpecification')
            ->willReturnCallback(function ($spec) {
                return new Dinosaur();
        });
        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $dinosaurFactory
        );
        /** Finish partial mocking */


        $enclosureBuilderService->buildEnclosure();

        $em = $this->getEntityManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, 'Amount of security systems is not the same');


        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaur systems is not the same');

    }



    /**
     * This loops over all of your entity objects and deletes them one by one.
     * It will even delete them in the correct order to avoid foreign key problems.
     * But, if you have two entities that both have foreign keys pointing at each other, you may still have problems.
     */
    private function truncateEntities()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }



    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}