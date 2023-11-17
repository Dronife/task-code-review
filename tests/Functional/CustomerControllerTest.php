<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CustomerControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldNotifyCustomer(): void
    {
        $client = static::createClient();

        $client->request('POST', 'api/customer/test_code/notifications', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'body' => 'notification text',
        ]));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Message was sent successfully', $client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function shouldGetBadRequestWhenBodyKeyIsEmpty(): void
    {
        $client = static::createClient();

        $client->request('POST', 'api/customer/test_code/notifications', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('The body key is missing in the data array.', $client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function shouldGetNotFoundWhenCustomerCodeNotProvided(): void
    {
        $client = static::createClient();

        $client->request('POST', 'api/customer/notifications', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'body' => 'notification text',
        ]));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function shouldGetBadRequestWhenCanNotFindCustomerByCode(): void
    {
        $client = static::createClient();

        $client->request('POST', 'api/customer/no_customer123/notifications', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'body' => 'notification text',
        ]));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Customer not found.', $client->getResponse()->getContent());
    }
}