# tarot
![tarot](/web/graphics/tarot.svg) lets you create multiple clones of SD
cards and other media in parallel, which is nice if you have to deploy
massive amounts of SBC computers or other embedded gadgets.

![Screenshot of main GUI v0.0.2](/doc/screenshot-main-v0.0.2.png)

## Status
**tarot** is functional but not very well tested yet, and not at all outside
of a RaspiOS environment. Buyer beware.

> Specifically, since tarot is about writing data to raw devices with root
> privileges, do not use it on an important system or on a system with
> important data devices connected to it.

## Installation
### Software requirements
You will need:
* systemd (this is the default init system on RaspiOS)
* [dcfldd](http://dcfldd.sourceforge.net/) (it's ancient, but still lovingly
maintained in Debian)
* lsblk (which is part of util-linux and available pretty much everywhere)
* a web server environment with PHP enabled (I'm using lighttpd.)

The systemd requirement could be replaced by an inotify handler if you're
so inclined. I like systemd.

### Hardware requirements
**tarot** is being tested and developed on a Raspberry Pi 4B and a 13-way
IcyBox USB3 hub containing 13 Transcend SD and µSD card reader/writers.

It is quite Linux-specific, but should run on any distribution and any
platform.

#### Hardware limitations
If you are considering building a fast USB3 duplicator using the latest
Raspberry Pi 4B (which is an excellent idea), keep in mind that there is a
hard limit of 32 devices on any one USB controller, and that both USB3 ports
on the Pi run on the same controller.
In my case, I was trying to combine two IcyBox 13-port USB3 hubs, only to find
that the system would recognize at most 17 out of 26 card readers, more or
less randomly.
This is due to the fact that the hub consists of several sub-hubs
internally, which all eat up device slots.
In the end, I built two Pis with one 13-port hub each, which works fine.

![Two Raspberry 4B-based µSD card copying stations](/doc/rpi4b-sd-copy-stations.jpg)

### Run installer
All configuration is done in [/web/includes/config.php](/web/includes/config.php). 
Change as required.

There is an install script that should work, and if it doesn't in your case, 
reading it will tell you what to do.
Kindly open an issue if you run into one.

Specifically, the systemd service file templates need to be populated from
the config file, and the person with the hammer will use PHP for that in a
PHP project.

## Usage
After installing, point your browser at `http://*yourhost.net*/tarot`. You
will be asked to scan for image files in the image folder you selected, and
to scan for devices. Having selected exactly one image and one or more
devices, you can proceed to write the image to the device(s).
If by mistake you happen to revisit the page while a job is running, you
should be redirected to the appropriate screen.

Note that scanning does not happen automatically, because it would
invalidate the selections you may have made before.

## Design and development
**tarot** tries very hard to prevent you from accidentally nuking your
important data partitions while detecting useful devices on-the-fly without
manual configuration. It does this by running `lsblk` over all devices and
partitions, and parsing candidate devices into a backend state object, if and
only if:
* the device is not mounted
* none of its partitions are mounted
* it is not marked read-only
* it is marked as hot-pluggable

As you select or change images, it  will also try to prevent you from 
writing to devices too small to hold the selected image and dynamically
unselect devices if necessary. They will not be re-selected when you switch
back to a smaller image.

Tarot is single user and will try to ensure that only one IP ever accesses
it, although it allows for explicit session stealing.

### Security
While you should only use tarot in a secure environment (don't want to
expose dd to the world, do we?), it tries to be reasonably secure against
tampering: the only state data that is ever accepted from the web client is
"selected/unselected". The corresponding lists are created and stored in a
global state object on the server.

Also, since the actual writing has to be done as `root`, the web environment
will only dump a magic trigger file to disk. The birth of this file is then
picked up by a systemd `tarot.path` unit, which in turn activates
`tarot.service`. It triggers a local handler process that has root rights.
This handler reads the user's choices from the state object and gets to
work. After it terminates, systemd will clear the magic trigger file.

So while Eve could nuke all of Alice's hotplugged but unused devices easily,
she will not be able to attack other devices.

### Debug mode
You can pass ?debug to the URL to enable some debugging feature:
* `/dev/zero` will be made available as an "image"
* `/dev/null` will be made available as a card device for dry-run testing
* a button to reset the session IP is added, to test session stealing
* a button to forget the session state and start over

### TODO
* add verifcation of images after writing.
* add feature to trigger a partprobe if lsblk fails to find new devices

### Kudos

tarot has been inspired by [Aaron Nguyen's
osid-python3](https://github.com/aaronnguyen/osid-python3), which in turn
draws on earlier work by [Rock &
Scissor](https://github.com/rockandscissor/osid).
The web gui uses [Skeleton](https://getskeleton.com).
