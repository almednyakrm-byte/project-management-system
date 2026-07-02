<?php

namespace App\Tests\Controller;

use App\Controller\ProjectsController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمشاريع extends TestCase
{
    private $controller;
    private $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->controller = new ProjectsController($this->pdo);
    }

    public function testGetProjects()
    {
        $expectedResponse = ['projects' => []];
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مشاريع')
            ->willReturn($this->createMock(PDOStatement::class));
        $response = $this->controller->getProjects();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateProject()
    {
        $projectData = ['name' => 'Test Project', 'description' => 'Test Description'];
        $expectedResponse = ['message' => 'Project created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مشاريع (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($projectData);
        $response = $this->controller->createProject($projectData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateProject()
    {
        $projectId = 1;
        $projectData = ['name' => 'Updated Project', 'description' => 'Updated Description'];
        $expectedResponse = ['message' => 'Project updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مشاريع SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($projectData);
        $response = $this->controller->updateProject($projectId, $projectData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteProject()
    {
        $projectId = 1;
        $expectedResponse = ['message' => 'Project deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مشاريع WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $projectId]);
        $response = $this->controller->deleteProject($projectId);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the following scenarios:

1.  `testGetProjects`: Tests the `getProjects` method of the `ProjectsController` class by verifying that it returns an array with an empty 'projects' key when the `query` method of the PDO object is called with the correct SQL query.
2.  `testCreateProject`: Tests the `createProject` method of the `ProjectsController` class by verifying that it returns an array with a success message when the `prepare` and `execute` methods of the PDO object are called with the correct SQL query and project data.
3.  `testUpdateProject`: Tests the `updateProject` method of the `ProjectsController` class by verifying that it returns an array with a success message when the `prepare` and `execute` methods of the PDO object are called with the correct SQL query and project data.
4.  `testDeleteProject`: Tests the `deleteProject` method of the `ProjectsController` class by verifying that it returns an array with a success message when the `prepare` and `execute` methods of the PDO object are called with the correct SQL query and project ID.

These tests use the `createMock` method to create mock objects for the PDO class, allowing us to control the behavior of the PDO object during the tests. The `expects` method is used to specify the expected behavior of the PDO object, and the `willReturn` method is used to specify the return value of the PDO object when the expected behavior is met.