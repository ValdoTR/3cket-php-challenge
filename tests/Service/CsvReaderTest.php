<?php
namespace App\Tests\Service;

use App\Service\CsvReader;
use PHPUnit\Framework\TestCase;
use App\Dto\Event;
use App\Dto\Address;

class CsvReaderTest extends TestCase
{
    private string $csvFile;

    protected function setUp(): void
    {
        // We use a temporary CSV file for our tests
        $this->csvFile = sys_get_temp_dir() . '/test_seeds.csv';

        $csvContent = <<<CSV
            Event1,Porto,41.1621376,-8.6569731
            Event2,Lisboa,38.7243148,-9.1499468
            CSV;

        file_put_contents($this->csvFile, $csvContent);
    }

    protected function tearDown(): void
    {
        // Wipe out the temp file at the end
        if (file_exists($this->csvFile)) {
            unlink($this->csvFile);
        }
    }

    public function testReadEventsReturnsCorrectData()
    {
        $reader = new CsvReader($this->csvFile);
        $events = $reader->readEvents();

        $this->assertCount(2, $events);

        $this->assertInstanceOf(Event::class, $events[0]);
        $this->assertEquals('Event1', $events[0]->name);
        $this->assertEquals('Porto', $events[0]->location);
        $this->assertEquals(41.1621376, $events[0]->address->latitude);
        $this->assertEquals(-8.6569731, $events[0]->address->longitude);

        $this->assertInstanceOf(Event::class, $events[1]);
        $this->assertEquals('Event2', $events[1]->name);
        $this->assertEquals('Lisboa', $events[1]->location);
        $this->assertEquals(38.7243148, $events[1]->address->latitude);
        $this->assertEquals(-9.1499468, $events[1]->address->longitude);
    }

    public function testThrowsExceptionIfFileNotFound()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('CSV not found');

        $reader = new CsvReader('/wrong/path/seeds.csv');
        $reader->readEvents();
    }

    public function testHandlesNonNumericAddress()
    {
        $csvContent = <<<CSV
            Event1,Porto,lat,long
            CSV;

        file_put_contents($this->csvFile, $csvContent);
        $reader = new CsvReader($this->csvFile);
        $events = $reader->readEvents();

        $this->assertCount(1, $events);
        $this->assertEquals(0.0, $events[0]->address->latitude);
        $this->assertEquals(0.0, $events[0]->address->longitude);
    }

    public function testIgnoresRowsWithMissingFields()
    {
        $csvContent = <<<CSV
            Event1,Porto,41.1
            Event2,Lisboa,38.7,-9.1
            CSV;

        file_put_contents($this->csvFile, $csvContent);
        $reader = new CsvReader($this->csvFile);
        $events = $reader->readEvents();

        $this->assertCount(1, $events);
        $this->assertEquals('Event2', $events[0]->name);
    }

    public function testIgnoresExtraColumns()
    {
        $csvContent = <<<CSV
            Event1,Porto,41.1,-8.6,Extra1,Extra2
            Event2,Lisboa,38.7,-9.1,Extra3
            CSV;

        file_put_contents($this->csvFile, $csvContent);
        $reader = new CsvReader($this->csvFile);
        $events = $reader->readEvents();

        $this->assertCount(2, $events);
        $this->assertEquals('Event1', $events[0]->name);
        $this->assertEquals('Event2', $events[1]->name);
    }
}
