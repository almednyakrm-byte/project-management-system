<?php

namespace App\Tests\Controller;

use App\Controller\ موظفينController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testموظفين extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new موظفينController($this->pdoMock);
    }

    public function testGetAll()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM موظفين')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM موظفين WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO موظفين (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name'])
            ->willReturn($this->pdoMock);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':email', $data['email'])
            ->willReturn($this->pdoMock);

        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE موظفين SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name'])
            ->willReturn($this->pdoMock);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':email', $data['email'])
            ->willReturn($this->pdoMock);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id)
            ->willReturn($this->pdoMock);

        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM موظفين WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}