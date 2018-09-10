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

    public function testNonExistingKey()
    {
        $testVal = $this->config->get('non-existing-key');
        $this->assertEquals($testVal, null);

        $testVal = $this->config->get('non-existing-key', 'default');
        $this->assertEquals($testVal, 'default');
    }

    public function testDotNotation()
    {
        $testVal = $this->config->get('person.address');
        $this->assertEquals($testVal, $this->data['person']['address']);

        $testVal = $this->config->get('person.address.zip');
        $this->assertEquals($testVal, $this->data['person']['address']['zip']);

        $testVal = $this->config->get('person.hobbies.2');
        $this->assertEquals($testVal, $this->data['person']['hobbies'][2]);

        $testVal = $this->config->get('person.very.very.long.entry.value');
        $this->assertEquals($testVal, $this->data['person']['very']['very']['long']['entry']['value']);

        $testVal = $this->config->get('person.address.notextisting');
        $this->assertEquals($testVal, null);

        $testVal = $this->config->get('person.not-extisting.street');
        $this->assertEquals($testVal, null);
    }
}
