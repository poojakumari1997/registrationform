<?php

declare(strict_types=1);

namespace tourBaseTests\Tests\Stubs;

use \PHPUnit\Framework\TestCase;
use \DavidLienhard\Database\Parameter as DBParam;
use \DavidLienhard\Database\Stub as Database;
use \DavidLienhard\Database\DatabaseInterface;

require_once dirname(__DIR__) . "/src/Stub.php";

class DatabaseStubTest extends TestCase
{
    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            Database::class,
            new Database
        );
    }

    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testDatabaseImplementsDatabaseInterface(): void
    {
        $this->assertInstanceOf(
            DatabaseInterface::class,
            new Database
        );
    }

    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testCanAddPayloadFromArray(): void
    {
        $database = new Database;
        $database->addPayload([
            "test" => "test"]);
        $this->assertTrue(true);
    }

    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testCannotAddPayloadFromString(): void
    {
        $database = new Database;

        $this->expectException(\TypeError::class);
        $database->addPayload("test");
    }

    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testCanConnect(): void
    {
        $database = new Database;

        // can connect with correct attributes
        $this->assertTrue(
            $database->connect(
                "hostname",
                "username",
                "password",
                "database"
            )
        );

        // get exception without attributes
        $this->expectException(\TypeError::class);
        $database->connect();
    }

    /**
     * @covers \DavidLienhard\Database\Stub
     * @test
    */
    public function testCanCloseConnection(): void
    {
        $database = new Database;
        $this->assertTrue($database->close());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::autocommit()
     * @test
    */
    public function testCanSetAutocommit(): void
    {
        $database = new Database;
        $this->assertTrue($database->autocommit(true));
        $this->assertTrue($database->autocommit(false));

        // get exception without attributes
        $this->expectException(\TypeError::class);
        $database->autocommit();

        // get exception with wrong attribute type
        $this->expectException(\TypeError::class);
        $database->autocommit("true");
    }

    /**
     * @covers \DavidLienhard\Database\Stub::begin_transaction()
     * @test
    */
    public function testCanSetBeginTransaction(): void
    {
        $database = new Database;
        $this->assertTrue($database->begin_transaction());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::commit()
     * @test
    */
    public function testCanCommit(): void
    {
        $database = new Database;
        $this->assertTrue($database->commit());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::rollback()
     * @test
    */
    public function testCanRollback(): void
    {
        $database = new Database;
        $this->assertTrue($database->rollback());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::query()
     * @test
    */
    public function testCanQuery(): void
    {
        $database = new Database;
        $this->assertTrue($database->query("test"));

        $this->assertTrue(
            $database->query(
                "test",
                new DBParam("s", "test")
            )
        );

        $this->assertTrue(
            $database->query(
                "test",
                new DBParam("s", "test"),
                new DBParam("s", "test")
            )
        );

        $this->expectException(\TypeError::class);
        $database->query();

        $this->expectException(\TypeError::class);
        $database->execute("test", "test", 5);
    }

    /**
     * @covers \DavidLienhard\Database\Stub::execute()
     * @test
    */
    public function testCanExecuteExistingQuery(): void
    {
        $database = new Database;
        $this->assertTrue($database->execute());

        $this->assertTrue(
            $database->execute(
                new DBParam("s", "test")
            )
        );

        $this->assertTrue(
            $database->execute(
                new DBParam("s", "test"),
                new DBParam("s", "test")
            )
        );
    }

    /**
     * @covers \DavidLienhard\Database\Stub::num_rows()
     * @test
    */
    public function testCanGetNumberOfRows(): void
    {
        $database = new Database;
        $result = $database->query("query");
        $this->assertIsInt($database->num_rows($result));

        $this->expectException(\TypeError::class);
        $database->num_rows();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::result()
     * @test
    */
    public function testCanGetResult(): void
    {
        $database = new Database;

        $result = $database->query("query");

        $this->assertIsString(
            $database->result(
                $result,
                0,
                "column"
            )
        );

        $this->expectException(\TypeError::class);
        $database->result();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::free_result()
     * @test
    */
    public function testCanFreeResult(): void
    {
        $database = new Database;

        $result = $database->query("query");

        $this->assertEquals(null, $database->free_result($result));

        $this->expectException(\TypeError::class);
        $database->free_result();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::fetch_array()
     * @test
    */
    public function testCanFetchArray(): void
    {
        $database = new Database;

        $payload = [
            "user" => "hamu",
            "surename" => "Hans",
            "lastname" => "Muster"
        ];

        $result = $database->query("query");

        // empty array by default
        $this->assertEquals(
            [ ],
            $database->fetch_array($result)
        );

        // payload if set
        $database->addPayload($payload);
        $this->assertEquals(
            $payload,
            $database->fetch_array($result)
        );

        $this->expectException(\TypeError::class);
        $database->fetch_array();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::fetch_assoc()
     * @test
    */
    public function testCanFetchAssoc(): void
    {
        $database = new Database;

        $payload = [
            "user" => "hamu",
            "surename" => "Hans",
            "lastname" => "Muster"
        ];

        $result = $database->query("query");

        // empty array by default
        $this->assertEquals(
            [ ],
            $database->fetch_assoc($result)
        );

        // payload if set
        $database->addPayload($payload);
        $this->assertEquals(
            $payload,
            $database->fetch_assoc($result)
        );

        $this->expectException(\TypeError::class);
        $database->fetch_assoc();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::fetch_row()
     * @test
    */
    public function testCanFetchRow(): void
    {
        $database = new Database;

        $payload = [
            "user" => "hamu",
            "surename" => "Hans",
            "lastname" => "Muster"
        ];

        $result = $database->query("query");

        // empty array by default
        $this->assertEquals(
            [ ],
            $database->fetch_row($result)
        );

        // payload if set
        $database->addPayload($payload);
        $this->assertEquals(
            $payload,
            $database->fetch_row($result)
        );

        $this->expectException(\TypeError::class);
        $database->fetch_row();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::insert_id()
     * @test
    */
    public function testCanGetInsertId(): void
    {
        $database = new Database;
        $this->assertIsInt($database->insert_id());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::data_seek()
     * @test
    */
    public function testCanSeekData(): void
    {
        $database = new Database;

        $result = $database->query("query");

        // empty array by default
        $this->assertTrue($database->data_seek($result, 0));

        $this->expectException(\TypeError::class);
        $database->data_seek();
    }

    /**
     * @covers \DavidLienhard\Database\Stub::affected_rows()
     * @test
    */
    public function testCanGetAffectedRows(): void
    {
        $database = new Database;
        $this->assertIsInt($database->affected_rows());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::esc()
     * @test
    */
    public function testCanEscapeString(): void
    {
        $database = new Database;
        $input = "test-string";
        $this->assertEquals($input, $database->esc($input));
    }

    /**
     * @covers \DavidLienhard\Database\Stub::client_info()
     * @test
    */
    public function testCanGetClientInfo(): void
    {
        $database = new Database;
        $this->assertIsString($database->client_info());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::host_info()
     * @test
    */
    public function testCanGetHostInfo(): void
    {
        $database = new Database;
        $this->assertIsString($database->host_info());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::proto_info()
     * @test
    */
    public function testCanGetProtoInfo(): void
    {
        $database = new Database;
        $this->assertIsString($database->proto_info());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::server_info()
     * @test
    */
    public function testCanGetServerInfo(): void
    {
        $database = new Database;
        $this->assertIsString($database->server_info());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::size()
     * @test
    */
    public function testCanGetDbSize(): void
    {
        $database = new Database;
        $this->assertIsInt($database->size());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::errno()
     * @test
    */
    public function testCanGetErrno(): void
    {
        $database = new Database;
        $this->assertIsInt($database->errno());
    }

    /**
     * @covers \DavidLienhard\Database\Stub::errstr()
     * @test
    */
    public function testCanGetErrstr(): void
    {
        $database = new Database;
        $this->assertIsString($database->errstr());
    }
}
