<?php

include_once("functions.php");


class testFunctions extends PHPUnit_Framework_TestCase {

    // Relays are numbered 87654321

    public function setUp()
    {
        setup();
    }

    public function testSetup(){
        $this->assertTrue(setup(), "default setup");
        $this->assertTrue(testConnect(), "default connect");
        $this->assertTrue(setup("192.168.12.252"));
        $this->assertTrue(testConnect(), "default connect");
        $this->assertTrue(setup("192.168.12.253"));
        $this->assertFalse(testConnect(), "default connect to wrong ip");
        $this->assertTrue(setup("192.168.12.252", 10000));
        $this->assertFalse(testConnect(), "default connect to wrong port");
        $this->assertTrue(setup("192.168.12.252", 17494));
        $this->assertTrue(testConnect(), "default connect to correct port");
    }

    public function testConnection(){
        $this->assertTrue(testConnect("192.168.12.252"), "connect by ip");
        $this->assertTrue(testConnect("192.168.12.252", 17494), "connect by ip and port");
        $this->assertFalse(testConnect("192.168.12.253", 17494), "connect wrong ip");
        $this->assertFalse(testConnect("192.168.12.252", 17495), "connect wrong port");
        $this->assertFalse(testConnect("192.168.12.253", 17495), "connect wrong ip and port");
    }

    public function testAllOn(){
        $this->assertEquals(0, setRelays('11111111'));
        $this->assertEquals("11111111", getRelays());
        sleep(3);
    }

    public function testSetRelays(){
        $this->assertEquals(0, setRelays('10101010'));
        $this->assertEquals("10101010", getRelays());
        sleep(3);
    }

    public function testMultiple(){
        $this->assertEquals(0, setRelays('00000000'));
        $this->assertEquals(0, setMultiple([ [1,1,50],[2,1,50],[7,1,50],[8,1,50] ]));
        $this->assertEquals("11000011", getRelays());
        sleep(7);
        $this->assertEquals("00000000", getRelays());
    }

    public function testMultipleWithTimes(){
        $this->assertEquals(0, setRelays('00000000'));
        $this->assertEquals(0, setMultiple([ [1,1,10],[2,1,20],[7,1,50],[8,1,50] ]));
        $this->assertEquals("11000011", getRelays());
        sleep(7);
        $this->assertEquals("00000000", getRelays());
    }

    public function testSingleOnWithDuration(){
        $this->assertEquals(0, setRelays('00000000'));
        $this->assertEquals(0, turnOn(1,10));
        $this->assertEquals("00000001", getRelays());
        sleep(2);
        $this->assertEquals("00000000", getRelays());
    }

    public function testSingleOnWithoutDuration(){
        $this->assertEquals(0, setRelays('00000000'));
        $this->assertEquals(0, turnOn(1,0));
        $this->assertEquals("00000001", getRelays());
        sleep(2);
        $this->assertEquals("00000001", getRelays());
        sleep(2);
        $this->assertEquals(0, setRelays('00000000'));
    }

    public function testMultipleOnOffs(){
        $this->assertEquals(0, setRelays('11111111'));
        $this->assertEquals(0, setMultiple([ [1,0,10],[2,1,20],[7,0,50],[8,1,50] ]));
        $this->assertEquals("10111110", getRelays());
        sleep(7);
        $this->assertEquals("01111101", getRelays());
    }

    public function testAllOff(){
        $this->assertEquals(0, setRelays('00000000'));
        $this->assertEquals("00000000", getRelays());
    }


//    public function testSetStringRelays(){
//        $this->assertEquals(0, setRelays('00000000'));
//        $this->assertEquals(0, setRelayString(':DOA,2,30 '));
//        $this->assertEquals("00000010", getRelays());
//    }
}

?>
(