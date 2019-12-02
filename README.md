# EE_iot_Proj

This is an assignment project for my coursework, it is a temeprature & humidity sensoring program.
The structure is like this:

(LoRa shield + Arduino board + Sensor) -> Dragino LG01 Gateway -> Internet -> Thinspeak IOT platform -> Google cloud platform

The code here is simply for setting up the webserver in google cloud (I use appengine typically).

The server will handle the requests from the customer end (for whom has opened the server web page), that users can see the statistics from the server page, also send request such as clear all data. 
Other functionality is the server will handle the request from the IoT Node side, which could be the temeprature or humidity error (too high or too low), the request will be made from IoT platform, the server will display warning messages as well as record them into admin logs.
