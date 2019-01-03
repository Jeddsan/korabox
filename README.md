# Korabox
This is an open source bridge for managing Philips Hue and myStrom devices in your home.

Korabox was a project in 2016. It enables the integration of smart home devices, which currently does not have a connection to the cloud for remote control.

## How to install Korabox
To install the Korabox, just simply create an Apache server with at least PHP 7.0 installed on a 24/7 running server in your home (That could be a Raspberry Pi or a NAS). After that also install the latest MySQL database and run the SQL file `install.sql` in a new database called `data`.

## Setup the port forwarding
Now on your router, setting up an port forwarding is different from provider to provider. Please read how to do this for your router in the manual. Important is that your router has to be accessible from the internet. For the best practice, register a DynDNS on your router as well.

## Get started with configuration
You are now be able to register your myStrom and Philips Hue devices via the control panel which can be reached when you lookup the hostname of the Korabox.

After you have connected your devices, you only are one step away to control them via the Kora assistant. Open for that the address: `http://<your-dyndns-address>/pages/connect.php`. Important here is that you are using the external access (your previous port forwarding configuration maybe together with DynDNS) to connect to Jeddsan.
