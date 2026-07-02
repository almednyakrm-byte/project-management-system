<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProjectsController;
use App\Repository\ProjectsRepository;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\QueryException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testالمشاريع extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock('Doctrine\DBAL\Driver\Connection');
        $this->entityManager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->repository = $this->createMock('App\Repository\ProjectsRepository');
        $this->controller = new ProjectsController($this->repository, $this->entityManager);
    }

    public function testGetProjects()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Project('project1', 'description1'),
                new Project('project2', 'description2'),
            ]);

        $response = $this->controller->getProjects();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateProject()
    {
        $project = new Project('project1', 'description1');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($project);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode(['name' => 'project1', 'description' => 'description1']));

        $response = $this->controller->createProject($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateProject()
    {
        $project = new Project('project1', 'description1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($project);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($project);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode(['name' => 'project1', 'description' => 'description2']));

        $response = $this->controller->updateProject(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteProject()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Project('project1', 'description1'));

        $response = $this->controller->deleteProject(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}