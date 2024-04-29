# Recipe-Community-

## Table of Contents

[About Recipe-Community](#About-Recipe-Community)

[Try it out!](#Try-it-out)

[How to run Recipe Community](#How-to-run-Recipe-Community)

[How to launch Recipe Community](#How-to-launch-Recipe-Community)

[Directory structure](#Directory-structure)

[Recipe JSON structure](#Recipe-JSON-structure)

[Important Information and Warnings](#Important-Information-and-Warnings)

# About Recipe-Community

Recipe Community aims to be an app for cooking enthusiasts and food lovers who are looking for a user-friendly way to discover, save, and share recipes with other like-minded chefs. Recipe Community is a collaborative culinary platform that simplifies the process of finding and curating delicious dishes.

# Try it out!

Download and run Recipe Community, then use one of these demo users to try it out!

##### Demo user 1:
- Name: admin [no special privileges]
- Username: admin
- Password: pwd

##### Demo user 2:
- Name: Mia
- Username: miazine
- Password: webdev

##### Demo user 3:
- Name: Phil
- Username: philbert
- Password: procook

##### Demo user 4:
- Name: Kevyn
- Username: kevynwithay
- Password: 1234

##### Demo user 5:
- Name: Susan
- Username: SnoozeinSusan
- Password: zzz

#### Or you can create your own account!

Once you've logged into Recipe Community take a look around!
You can add recipes, create kitchens and cookbooks, and search for recipes that you want to add to your repertoire.
To add a cookbook to a kitchen or a recipe to a cookbook, simply select what you would like to add!
If you find a recipe that you like you can add it to your favorites as well!
You can also download and share recipes with your community! 
Recipes can be downloaded and uploaded using [JSON files](#Recipe-JSON-structure) so that your recipes remain easily readable and editable, even when not in the application.

Bon appétit!

# How to run Recipe Community

Recipe Community currently runs on a LAMP stack hosted inside of a Docker container cluster.
If you already have Docker installed on your system, you are ready to go to the next step after you clone the repository to your system.
If not, you can refer to the below links for instructions on how to install Docker on your machine.

## How to install Docker

### Windows

[Official Documentation](https://docs.docker.com/desktop/install/windows-install/)

### Mac

[Official Documentation](https://docs.docker.com/desktop/install/mac-install/)

### Linux

[Official Documentation](https://docs.docker.com/desktop/install/linux-install/)

## How to launch Recipe Community

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

**At this point you should be able to connect to the app through your browser via `localhost`.**

#### Note: 

The database container takes a bit to fully spin up to a usable state the first time you run the application.
Given this, certain functionality will return errors until it becomes accessible. 
After the first run the database should initialize much faster with locally stored data.

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
|   |_ init/ [contains the init schema and demo data]
|   |_ data/ [will be generated on first run]
|
|_ www
|   |_ [application files; html, php, css]
|
|_ Dockerfile
|
|_ docker-compose
|
|_ README
```

- The `db` directory contains the SQL files that will be used for initializing the DB when the containers start.
    - `init-rc` contains the database's table schemas.
    - Don't want the default users or data? Simply remove the `rc-data` file before initializing Recipe Community!

- The `www` directory contains the PHP, CSS, & HTML files that make up the body of the application.

- Files relating to the Docker containers themselves are located in the root directory of the project.

# Recipe JSON structure

```json
{
  "name": "test",
  "description": "test",
  "category": "test",
  "cuisine": "test",
  "ingredients": "test",
  "instructions": "test",
  "prep_time": 1,
  "cook_time": 2,
  "total_time": 3,
  "servings": 1,
  "creator_id": 1
}
```

# Important Information and Warnings

> [!WARNING]
> !!! EXPOSING THIS APPLICATION TO THE INTERNET IS NOT ADVISED !!!
>
> THE APPLICATION HAS NOT BEEN THOROUGHLY TESTED TO ENSURE ITS SAFETY & SECURITY
>
> DO SO AT YOUR OWN RISK

> [!WARNING]
> !!! KEEP THE `data/` DIRECTORY PRIVATE !!! 
>
> IT CONTAINS SECRETS 

> [!IMPORTANT]
> IT IS GENERALLY ADVISABLE THAT YOU REVIEW AND EDIT ALL OF THE APPLICATION'S DEFAULT VARIABLES TO SUIT YOUR INDIVIDUAL NEEDS
> 
> phpmyadmin is accessible on port 8080
>
> IT IS ADVISED THAT YOU ALTER THE ADMINISTRATOR CREDENTIALS 
>
> The default credentials are `admin:pwd`


# Recipe Community is Group 5's 4900 group project.
