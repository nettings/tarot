# tarot
A block device duplicator with web frontend, to help you create multiple clones of SD cards and other media in parallel.

# Status
*tarot* is functional but not very well tested yet, and not at all outside
of a RaspiOS environment. Buyer beware.

**Specifically, since tarot writes data to raw devices, do not use it on an
important system or on a system with important data devices connected to
it.**


# Software requirements
*tarot* requires systemd, dcfldd, lsblk and a web server environment with PHP.

The systemd requirement could be replaced by an inotify handler if you're
so inclined. I like systemd.

# Hardware requirements
*tarot* is being tested and developed on a Raspberry Pi 4B and a 13-way IcyBox
USB3 hub containing 13 Transcend SD and µSD card reader/writers.

It is quite Linux-specific, but should should run on any distribution on any
platform.

# Installation
All configuration is done in web/includes/config.php. Change as required.

There is an install script that should work, and if it doesn't in your case, 
reading it will tell you what to do.

# Usage
After installing, point your browser at http://**yourhost.net**/tarot. You
will be asked to scan for image files in the image folder you selected, and
to scan for devices. Having selected exactly one image and one or more
devices, you can proceed to write the image to the device(s), which will
happen in parallel using `dcfldd`.

# Design and development
tarot tries very hard to prevent you from accidentally nuking your important
data partitions while detecting useful devices on-the-fly without manual
configuration. It does this by running `lsblk` over all devices and
partitions, and parsing the devices into a backend state object, if and only
if:
* the device is not mounted
* none of its partitions are mounted
* is is not marked read-only
* is is marked as hot-pluggable

During runtime, tarot will also try to ensure that you will only write to
devices big enough to hold your image.

While you should only use tarot in a secure environment (don't want to
expose dd to the world, do we?), it tries to be reasonably secure against
tampering: the only state that is ever accepted from the web client are
"selected/unselected". The corresponding lists are created and stored in a
global state object on the server.
So while Eve could nuke all of Alice's hotplugged but unused devices easily,
she will not be able to attack other devices.

Tarot is single user and will try to ensure that only one IP ever accesses
it, although it allows for explicit session stealing.

You can pass ?debug to the URL to enable some debugging feature:
* `/dev/zero` will be made available as an "image"
* '/dev/null' will be made available as a card device for dry-run testing
* A button to reset the session IP is added, to test session stealing.

# TODO
* add verifcation of images after writing.
* add feature to trigger a partprobe if lsblk fails to find new devices

# Kudos

tarot has been inspired by [Aaron Nguyen's
osid-python3](https://github.com/aaronnguyen/osid-python3), which in turn
draws on earlier work by [Rock &
Scissor](https://github.com/rockandscissor/osid).
The web gui uses [Skeleton](https://getskeleton.com).