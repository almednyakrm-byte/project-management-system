<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use App\Entity\Materials;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمواد extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(MaterialsRepository::class);
        $this->controller = new MaterialsController($this->repository);
    }

    public function testGetMaterials(): void
    {
        $materials = [
            new Materials(1, 'Material 1'),
            new Materials(2, 'Material 2'),
        ];

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($materials);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($stmt);

        $response = $this->controller->getMaterials();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getBody()->getContents());
    }

    public function testCreateMaterial(): void
    {
        $material = new Materials(1, 'Material 1');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO materials (id, name) VALUES (:id, :name)')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => $material->getId(), 'name' => $material->getName()]);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($stmt);

        $response = $this->controller->createMaterial($material);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateMaterial(): void
    {
        $material = new Materials(1, 'Material 1');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE materials SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => $material->getName(), 'id' => $material->getId()]);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($stmt);

        $response = $this->controller->updateMaterial($material);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteMaterial(): void
    {
        $material = new Materials(1, 'Material 1');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM materials WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => $material->getId()]);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($stmt);

        $response = $this->controller->deleteMaterial($material);
        $this->assertEquals(200, $response->getStatusCode());
    }
}