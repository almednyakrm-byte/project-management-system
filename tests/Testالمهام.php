<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TasksController;
use App\Repository\TasksRepository;
use App\Service\TasksService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالمهام extends TestCase
{
    private $tasksController;
    private $tasksRepository;
    private $tasksService;
    private $router;

    protected function setUp(): void
    {
        $this->tasksRepository = $this->createMock(TasksRepository::class);
        $this->tasksService = $this->createMock(TasksService::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tasksController = new TasksController($this->tasksRepository, $this->tasksService, $this->router);
    }

    public function testGetTasks(): void
    {
        $this->tasksRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'title' => 'Task 1'],
                ['id' => 2, 'title' => 'Task 2'],
            ]);

        $request = new Request();
        $response = $this->tasksController->getTasks($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateTask(): void
    {
        $this->tasksService->expects($this->once())
            ->method('createTask')
            ->with(['title' => 'Task 1'])
            ->willReturn(['id' => 1, 'title' => 'Task 1']);

        $request = new Request([], [], ['title' => 'Task 1']);
        $response = $this->tasksController->createTask($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateTask(): void
    {
        $this->tasksService->expects($this->once())
            ->method('updateTask')
            ->with(1, ['title' => 'Task 1'])
            ->willReturn(['id' => 1, 'title' => 'Task 1']);

        $request = new Request([], [], ['title' => 'Task 1']);
        $response = $this->tasksController->updateTask(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteTask(): void
    {
        $this->tasksService->expects($this->once())
            ->method('deleteTask')
            ->with(1);

        $request = new Request();
        $response = $this->tasksController->deleteTask(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// TasksController.php
namespace App\Controller;

use App\Repository\TasksRepository;
use App\Service\TasksService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TasksController
{
    private $tasksRepository;
    private $tasksService;
    private $router;

    public function __construct(TasksRepository $tasksRepository, TasksService $tasksService, RouterInterface $router)
    {
        $this->tasksRepository = $tasksRepository;
        $this->tasksService = $tasksService;
        $this->router = $router;
    }

    public function getTasks(Request $request): Response
    {
        $tasks = $this->tasksRepository->findAll();
        return new Response(json_encode($tasks));
    }

    public function createTask(Request $request): Response
    {
        $task = $this->tasksService->createTask($request->request->all());
        return new Response('', Response::HTTP_CREATED);
    }

    public function updateTask(int $id, Request $request): Response
    {
        $task = $this->tasksService->updateTask($id, $request->request->all());
        return new Response(json_encode($task));
    }

    public function deleteTask(int $id, Request $request): Response
    {
        $this->tasksService->deleteTask($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// TasksService.php
namespace App\Service;

class TasksService
{
    public function createTask(array $data): array
    {
        // Implement task creation logic here
        return ['id' => 1, 'title' => $data['title']];
    }

    public function updateTask(int $id, array $data): array
    {
        // Implement task update logic here
        return ['id' => $id, 'title' => $data['title']];
    }

    public function deleteTask(int $id): void
    {
        // Implement task deletion logic here
    }
}



// TasksRepository.php
namespace App\Repository;

class TasksRepository
{
    public function findAll(): array
    {
        // Implement task retrieval logic here
        return [
            ['id' => 1, 'title' => 'Task 1'],
            ['id' => 2, 'title' => 'Task 2'],
        ];
    }
}