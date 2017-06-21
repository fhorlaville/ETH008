This is my PHP library for controlling a Robot Electronics ETH008 unit.

http://www.robot-electronics.co.uk/htm/eth008tech.htm

This unit has eight programmable relays and an Ethernet connector.

Here are the files:

- functions.php
    This is the library with the functions you need to call
    
- tests.php
    These are the unit tests for the functions library ; it shows how you use the functions
    
- index.html
    This is a sample page with two buttons asynchronously controlling four relays. 
 
- ajax.php
    This is the back end for the sample.
    Button 1 turns on relays 1,7,8 (blue, yellow, green)
    Button 2 turns on relays 1,4,8 (blue, red, green)
