<?php

namespace UnitTests\Http\Action\Users;

use http\Actions\Users\FindByUsername;
use http\ErrorResponse;
use http\Request;
use http\SuccessfullResponse;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use my\Model\UUID;
use my\Repositories\UserRepository;

class FindByUsernameTest extends TestCase
{
    private PDO $pdoMock;
    private PDOStatement $stmtMock;
    private UserRepository $repo;

    protected function setUp(): void
    {
        $this->pdoMock = $this->getMockBuilder(PDO::class)->getMock();
        $this->stmtMock = $this->getMockBuilder(PDOStatement::class)->getMock();
        $this->repo = new UserRepository($this->pdoMock);
    }

    public function testItReturnsErrorIfParamUserNotFound(): void
    {
        $this->prepareStmtMock(false);

        $action = new FindByUsername($this->repo);
        $response = $action->handle(new Request([], []));

        $this->assertErrorResponse($response, '{"success":false,"reason":"Incorrect param for query"}');
    }

    public function testItReturnsErrorIfUserNotFound(): void
    {
        $this->prepareStmtMock(false);

        $action = new FindByUsername($this->repo);
        $response = $action->handle(new Request(['username' => 'Ivan'], []));

        $this->assertErrorResponse($response, '{"success":false,"reason":"Cannot get user: Ivan"}');
    }

    public function testItReturnsUserByName(): void
    {
        $uuid = UUID::random();
        $mockUserData = [
            'uuid' => $uuid,
            'username' => 'ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
        ];

        $this->prepareStmtMock($mockUserData);

        $action = new FindByUsername($this->repo);
        $response = $action->handle(new Request(['username' => 'Ivan'], []));

        $this->assertSuccessResponse($response, '{"success":true,"data":{"username":"ivan","name":"Ivan Ivanov"}}');
    }

    private function prepareStmtMock($fetchResult): void
    {
        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())->method('fetch')->willReturn($fetchResult);
    }

    private function assertErrorResponse($response, $expectedOutput): void
    {
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString($expectedOutput);
        $response->send();
    }

    private function assertSuccessResponse($response, $expectedOutput): void
    {
        $this->assertInstanceOf(SuccessfullResponse::class, $response);
        $this->expectOutputString($expectedOutput);
        $response->send();
    }
}