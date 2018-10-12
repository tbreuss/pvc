<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Config;

class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $data;

    public function setUp()
    {
        $this->data = [
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address' => [
                    'street' => '123 Main St',
                    'zip' => '17101',
                    'city' => 'Anytown',
                    'country' => 'USA'
                ],
                'hobbies' => [
                    'Tennis',
                    'Football',
                    'Music',
                    'Hiking'
                ],
                'very' => [
                    'very' => [
                        'long' => [
                            'entry' => [
                                'value' => 42
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->config = new Config($this->data);
    }

    public function testGetWithNonExistingKey()
    {
        $this->assertNull($this->config->get('non-existing-key'));
    }

    public function testGetWithNonExistingKeyAndDefault()
    {
        $this->assertEquals('default', $this->config->get('non-existing-key', 'default'));
    }

    public function testGetWithDotNotation()
    {
        $testVal = $this->config->get('person.address');
        $this->assertEquals($this->data['person']['address'], $testVal);

        $testVal = $this->config->get('person.address.zip');
        $this->assertEquals($this->data['person']['address']['zip'], $testVal);

        $testVal = $this->config->get('person.hobbies.2');
        $this->assertEquals($this->data['person']['hobbies'][2], $testVal);

        $testVal = $this->config->get('person.very.very.long.entry.value');
        $this->assertEquals($this->data['person']['very']['very']['long']['entry']['value'], $testVal);
    }

    public function testGetWithDotNotationAndNotExistingEndSegment()
    {
        $this->assertNull($this->config->get('person.address.notextisting'));
    }

    public function testGetWithDotNotationAndNotExistingMiddleSegment()
    {
        $this->assertNull($this->config->get('person.not-extisting.street'));
    }
}
