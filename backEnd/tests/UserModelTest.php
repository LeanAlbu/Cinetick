<?php

use PHPUnit\Framework\TestCase;

// Inclui o arquivo do modelo para que ele possa ser testado
require_once BASE_PATH . '/app/core/Model.php'; // Adicionado para carregar a classe Model
require_once BASE_PATH . '/app/models/UserModel.php';
require_once BASE_PATH . '/app/core/Database.php'; // Necessário para o mock do PDO

class UserModelTest extends TestCase
{
    protected $userModel;
    protected $pdoMock;
    protected $stmtMock;

    protected function setUp(): void
    {
        // Cria um mock para a classe PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Cria um mock para a classe PDOStatement
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Configura o mock do PDO para retornar o stmtMock quando prepare() for chamado
        $this->pdoMock->method('prepare')
                      ->willReturn($this->stmtMock);

        // Configura o stmtMock para retornar true quando execute() for chamado
        $this->stmtMock->method('execute')
                 ->willReturn(true);

        // Instancia o UserModel passando o mock do PDO
        $this->userModel = new UserModel($this->pdoMock);
    }

    public function testFindByEmailReturnsUser()
    {
        // Configura o mock do stmtMock para retornar um usuário para este teste
        $this->stmtMock->method('fetch')
                 ->willReturn(['id' => 1, 'email' => 'test@example.com', 'name' => 'Test User', 'password' => 'hashed_password']);

        $email = 'test@example.com';
        $user = $this->userModel->findByEmail($email);

        $this->assertIsArray($user);
        $this->assertEquals('test@example.com', $user['email']);
        $this->assertEquals('Test User', $user['name']);
    }

    public function testFindByEmailReturnsNullIfNotFound()
    {
        // Configura o mock do stmtMock para retornar false para este teste
        $this->stmtMock->method('fetch')
                 ->willReturn(false); // Simula que nenhum usuário foi encontrado

        $email = 'notfound@example.com';
        $user = $this->userModel->findByEmail($email);

        $this->assertFalse($user);
    }
}
