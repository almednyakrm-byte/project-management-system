<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\DomCrawler\Crawler;
use App\Repository\مواردRepository;
use App\Entity\موارد;
use Mockery;
use Mockery\MockInterface;

class Testموارد extends PantherTestCase
{
    private $client;
    private $repository;

    protected function setUp(): void
    {
        $this->client = static::createPantherClient();
        $this->repository = Mockery::mock(MواردRepository::class);
    }

    public function testGetAll(): void
    {
        $this->repository->shouldReceive('findAll')->andReturn([
            new مواد('1', 'موارد 1'),
            new مواد('2', 'موارد 2'),
        ]);

        $this->client->request('GET', '/api/موارد');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(json_encode([
            'data' => [
                ['id' => '1', 'name' => 'موارد 1'],
                ['id' => '2', 'name' => 'موارد 2'],
            ],
        ]));
    }

    public function testGetOne(): void
    {
        $this->repository->shouldReceive('find')->with('1')->andReturn(new مواد('1', 'موارد 1'));

        $this->client->request('GET', '/api/موارد/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(json_encode(['id' => '1', 'name' => 'موارد 1']));
    }

    public function testCreate(): void
    {
        $this->repository->shouldReceive('save')->with(new مواد('3', 'موارد 3'))->andReturn(true);

        $this->client->request('POST', '/api/موارد', [
            'name' => 'موارد 3',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains(json_encode(['id' => '3', 'name' => 'موارد 3']));
    }

    public function testUpdate(): void
    {
        $this->repository->shouldReceive('find')->with('1')->andReturn(new مواد('1', 'موارد 1'));
        $this->repository->shouldReceive('save')->with(new مواد('1', 'موارد 1 updated'))->andReturn(true);

        $this->client->request('PUT', '/api/موارد/1', [
            'name' => 'موارد 1 updated',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains(json_encode(['id' => '1', 'name' => 'موارد 1 updated']));
    }

    public function testDelete(): void
    {
        $this->repository->shouldReceive('find')->with('1')->andReturn(new مواد('1', 'موارد 1'));
        $this->repository->shouldReceive('remove')->with(new مواد('1', 'موارد 1'))->andReturn(true);

        $this->client->request('DELETE', '/api/موارد/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\مواردRepository.php
namespace App\Repository;

use App\Entity\موارد;

interface موادRepository
{
    public function findAll(): array;
    public function find(string $id): ?موارد;
    public function save(موارد $موارد): bool;
    public function remove(موارد $موارد): bool;
}



// App\Entity\موارد.php
namespace App\Entity;

class مواد
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}