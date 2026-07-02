<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\الموظفينController;
use App\Repository\الموظفينRepository;
use App\Entity\الموظفين;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالموظفين extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(الموظفينRepository::class);
        $this->controller = new الموظفينController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new الموظفين());

        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPost()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new الموظفين());

        $request = new Request([], [], ['json' => ['name' => 'John', 'email' => 'john@example.com']]);
        $response = $this->controller->post($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPut()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new الموظفين());

        $request = new Request([], [], ['json' => ['name' => 'John', 'email' => 'john@example.com']]);
        $response = $this->controller->put(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new الموظفين());

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getById(1);
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method by mocking the `findAll` method of the `الموظفينRepository` to return an empty array.
- `testGetById`: Tests the `getById` method by mocking the `find` method of the `الموظفينRepository` to return a new `الموظفين` object.
- `testPost`: Tests the `post` method by mocking the `save` method of the `الموظفينRepository` to save a new `الموظفين` object.
- `testPut`: Tests the `put` method by mocking the `find` method of the `الموظفينRepository` to return a new `الموظفين` object.
- `testDelete`: Tests the `delete` method by mocking the `find` method of the `الموظفينRepository` to return a new `الموظفين` object.
- `testNotFound`: Tests that a `NotFoundHttpException` is thrown when trying to retrieve a non-existent `الموظفين` object.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to create a `الموظفينController` class and a `الموظفينRepository` class to use with this test file.