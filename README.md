# Recipe-Community-

## This is the repo for group 5's 4900 group project

## More information coming soon!

# !!!WARNING!!!

**Recipe Community is currently in PRE-DEVELOPMENT**

This will change to development shortly, however for now the application in its entirety only contains placeholder values and information.
Currently, and during development certain things will not be production ready when it comes to things such as security.
Certain information will be hard-coded to ease development, this will change for release. 
Of course if you are running this locally at anytime in a nondevelopment capacity you are free to alter these things to your liking.

# How to use Recipe Community

Recipe Community currently runs on a LAMP stack hosted inside of a Docker container cluster.
If you already have Docker installed on your system you are ready to go to the next step.
If not you can refer to the below links for instructions on how to install Docker on your machine.

## How to install Docker

### Windows

[Official Documentation](https://docs.docker.com/desktop/install/windows-install/)

### Mac

[Official Documentation](https://docs.docker.com/desktop/install/mac-install/)

### Linux

[Official Documentation](https://docs.docker.com/desktop/install/linux-install/)

## How to run Recipe Community

Ensure that the Docker daemon is running.
Note: Administrator privileges will likely be required to run either of the above commands, hence the `sudo` command.

The command to start docker manually can be found below:

`$ sudo systemctl start docker`

Navigate to the directory that your Recipe Community source code is located in and run the following command in a terminal:

The first time that you launch the docker containers you will need to build the images. To do so do the following:

`$ sudo docker-compose up --build`

All subsequent runs can use one of the following commands:

`$ sudo docker-compose up `

or

`$ sudo docker-compose up -d`

The second command behaves exactly like the first with the only difference being that `-d` flag runs the containers in the background instead of the foreground.
This option can be useful if you plan to use that terminal for other things.

It should also be possible to launch the containers from the Docker Desktop application, however this has not been tested at this time.

To shutdown the Docker containers simply run the following in the terminal:

```
ctrl+c
sudo docker-compose down
```

You can also disable the Docker daemon with the following command:

`$ sudo systemctl stop docker`

# Directory structure

Currently the directory structure for Recipe Community is as follows:

```
rc
|
|_ db
|   |_ dump.sql
|
|_ www
    |_ index.html
    |_ test.php

```

# Notes

This is just an initial environment set up to enable development.
It will be iterated upon and improved.
