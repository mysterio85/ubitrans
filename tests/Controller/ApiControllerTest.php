<?php

namespace App\Tests\Controller;


use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;

class ApiControllerTest extends WebTestCase
{
    Const URI = "http://localhost/api/students";

    Const DELETE = "DELETE";
    Const POST = "POST";
    Const PATCH = "PATCH";
    Const GET = "GET";

    private $doctrine;
    private $loader;
    private $idWithoutExist;
    private $client;

    public function setUp()
    {

        parent::setup();
        self::bootKernel();

        $this->doctrine = self::$container->get('doctrine');
        $this->loader = self::$container->get('fidry_alice_data_fixtures.loader.doctrine');

        $connection = $this->doctrine->getConnection();
        $connection->executeQuery('ALTER TABLE student AUTO_INCREMENT = 1');

        $this->loader->load([
            './fixtures/test.yaml'
        ]);

        $result = $this->doctrine->getManager()->getRepository(Student::class)->findAll();
        $this->idWithoutExist = count($result) + 1;

        self::ensureKernelShutdown();

        $this->client = self::createClient();

    }

    public function tearDown()
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        $this->doctrine->getManager()->close();

    }

    /**
     * Test Student creation with correct data
     */
    public function testAddStudentSuccess()
    {

        $data = [
            "firstName" => "Joe",
            "lastName" => "Jhon",
            "birthDate" => "1985-01-01"
        ];

        $this->client->request(self::POST, self::URI, $data);

        $this->assertResponseStatusCodeSame(201);

    }


    /**
     * Test Student creation with bad data
     */
    public function testAddStudentBadData()
    {
        $data = [
            "firstName" => "Toto",
            "lastName" => "tata",
            "birthDate" => "bad data"
        ];

        $this->client->request(self::POST, self::URI, $data);

        $this->assertResponseStatusCodeSame(422);

    }

    /**
     * Test student update with correct data
     */
    public function testUpdateStudentSuccess()
    {
        $data = [
            "firstName" => "Update Firstname "
        ];

        $endPoint = self::URI . '/1';

        $this->client->request(self::PATCH, $endPoint, $data);

        $this->assertResponseStatusCodeSame(200);

    }

    /**
     * Test the update of a nonexistent student with correct data
     */
    public function testUpdateStudentNotExistError()
    {

        $data = [
            "firstName" => "student not exist "
        ];

        $endPoint = self::URI . '/' . $this->idWithoutExist;

        $this->client->request(self::PATCH, $endPoint, $data);

        $this->assertResponseStatusCodeSame(404);

    }

    /**
     * Test student update with bad data
     */
    public function testUpdateStudentBadData()
    {
        $data = [
            "birthDate" => "Bad data "
        ];

        $endPoint = self::URI . '/1';

        $this->client->request(self::PATCH, $endPoint, $data);

        $this->assertResponseStatusCodeSame(422);

    }

    /**
     * Student deletion test
     */
    public function testRemoveStudentSuccess()
    {
        $endPoint = self::URI . '/1';

        $this->client->request(self::DELETE, $endPoint);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Nonexistent Student deletion test
     */
    public function testRemoveStudentNotExist()
    {

        $endPoint = self::URI . '/' . $this->idWithoutExist;

        $this->client->request(self::DELETE, $endPoint);

        $this->assertResponseStatusCodeSame(404);
    }


    /**
     * Test Overall Class Average
     */
    public function testOverallClassAverageSuccess()
    {
        $endPoint = self::URI . '/averages';

        $this->client->request(self::GET, $endPoint);

        $response = $this->client->getResponse()->getContent();

        $this->assertEquals('{"average":"13"}', $response);

        $this->assertResponseStatusCodeSame(200);

    }

    /**
     * Test Overall Class Average error
     */
    public function testOverallClassAverageError()
    {
        $endPoint = self::URI . '/averages';

        $this->client->request(self::GET, $endPoint);

        $response = $this->client->getResponse()->getContent();

        $this->assertNotEquals('{"average":"20"}', $response);

        $this->assertResponseStatusCodeSame(200);

    }


    /**
     * Test Student Average
     */
    public function testStudentAverageSuccess()
    {
        $endPoint = self::URI . '/4/averages';

        $this->client->request(self::GET, $endPoint);

        $response = $this->client->getResponse()->getContent();

        $this->assertEquals('{"average":"12"}', $response);

        $this->assertResponseStatusCodeSame(200);

    }

    /**
     * Test Student Average error
     */
    public function testStudentAverageError()
    {
        $endPoint = self::URI . '/1/averages';

        $this->client->request(self::GET, $endPoint);

        $response = $this->client->getResponse()->getContent();

        $this->assertNotEquals('{"average":"12"}', $response);

        $this->assertResponseStatusCodeSame(200);

    }

    /**
     * Test Nonexistent Student Average
     */
    public function testStudentNotExistAverage()
    {
        $endPoint = self::URI . '/' . $this->idWithoutExist . '/averages';

        $this->client->request(self::GET, $endPoint);

        $this->assertResponseStatusCodeSame(404);
    }


    /**
     * test Add Student Grade
     */
    public function testAddStudentGradeSuccess()
    {
        $data = [
            "subject" => "Java",
            "mark" => 19
        ];
        $endPoint = self::URI . '/1/grades';

        $this->client->request(self::POST, $endPoint, $data);

        $this->assertResponseStatusCodeSame(201);
    }


    /**
     * test Add Nonexistent Student Grade
     */
    public function testAddStudentNotExistGrade()
    {
        $data = [
            "subject" => "Science not exist",
            "mark" => 12
        ];
        $endPoint = self::URI . '/' . $this->idWithoutExist . '/grades';

        $this->client->request(self::POST, $endPoint, $data);

        $this->assertResponseStatusCodeSame(404);
    }


    /**
     * test Add Student Grade with bad Data
     */
    public function testAddStudentGradeBadData()
    {
        $data = [
            "subject" => "Science bad data",
            "mark" => 21
        ];
        $endPoint = self::URI . '/1/grades';

        $this->client->request(self::POST, $endPoint, $data);

        $this->assertResponseStatusCodeSame(422);
    }


}