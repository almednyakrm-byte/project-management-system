<?php

namespace App\Tests\Controller;

use App\Controller\IndividusController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestIndividus extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new IndividusController($this->pdoMock);
    }

    public function testGetIndividus()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM individus')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getIndividus();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostIndividus()
    {
        $data = [
            'nom' => 'John Doe',
            'prenom' => 'Jane Doe',
            'dateNaissance' => '1990-01-01',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO individus (nom, prenom, dateNaissance) VALUES (:nom, :prenom, :dateNaissance)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->postIndividus(new Request(), $data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutIndividus()
    {
        $data = [
            'nom' => 'John Doe',
            'prenom' => 'Jane Doe',
            'dateNaissance' => '1990-01-01',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE individus SET nom = :nom, prenom = :prenom, dateNaissance = :dateNaissance WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->putIndividus(1, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteIndividus()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM individus WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteIndividus(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}