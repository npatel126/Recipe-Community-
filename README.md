# Recipe-Community-

## This is the repo for group 5's 4900 group project

## More information coming soon!

# !!!WARNING!!!

**Recipe Community is currently in ACTIVE DEVELOPMENT**

Currently things are not production ready when it comes to certain things; such as security.
Certain information will be hard-coded (or empty) to ease development, this will change for release. 
Of course if you are running this locally at any time in a non-development capacity you are free to alter these things to your liking.

# How to use Recipe Community

Recipe Community currently runs on a LAMP stack hosted inside of a Docker container cluster.
If you already have Docker installed on your system, you are ready to go to the next step.
If not, you can refer to the below links for instructions on how to install Docker on your machine.

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

Navigate to the directory that your Recipe Community source code is located in and do the following in a terminal:

The first time that you launch the docker containers you will need to build the images. To do so, do the following:

`$ sudo docker-compose up --build`

All subsequent runs can use one of the following commands:

`$ sudo docker-compose up `

or

`$ sudo docker-compose up -d`

The second command behaves exactly like the first with the only difference being that `-d` flag runs the containers in the background instead of the foreground.
This option can be useful if you plan to use that terminal for other things.

It should also be possible to launch the containers from the Docker Desktop application, however this has not been tested at this time.

At this point you should be able to connect to the app through your browser via localhost.

To shutdown the Docker containers simply do the following:

Press `ctrl+c` to end the process, then run the command `sudo docker-compose down`.

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
    |_ [application files; html, php]

```

The `db` directory contains the SQL file that will be used for initializing the DB when the containers start.

The `www` directory contains the HTML and PHP files that make up the body of the application.

The files that relate to the Docker containers are located in the root directory of the project.

# Notes

Recipe Community is currently under early development.
It will be iterated upon and improved.
