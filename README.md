# Tigris MongoDB compatibility and Python FastAPI example

## Introduction

Welcome to this [Tigris MongoDB compatibility](https://www.tigrisdata.com/docs/concepts/mongodb-compatibility/) and PHP example app. This repo aims to show a basic example of how you can use the power of Tigris MongoDB compatibility with PHP.

This project uses [MongoDB PHP driver](https://docs.mongodb.com/drivers/php/) version 1.15.0 by default. Although you can change the driver version, the provided code example was only tested against the default version of MongoDB driver.
Please note that the MongoDB PHP driver consists of two components: the [extension](https://github.com/mongodb/mongo-php-driver) and [library](https://docs.mongodb.com/php-library/current/).

Tigris MongoDB compatibility supports the MongoDB 6.0+ wire protocol, so any drivers or other components must support this version.

## Prerequisites

- [Docker](https://docs.docker.com/install/)
- A [Tigris Cloud account](https://console.preview.tigrisdata.cloud/signup) or you can [self-host Tigris](https://www.tigrisdata.com/docs/concepts/platform/self-host/)

## Preparing Tigris

1. Create a project in Tigris.
1. Create an application key, and copy the Project Name, Client ID, and Client Secret values.

Copy the `.env.example` to a `.env` file:

```sh
cp .env.example .env
```

And update the values with your Tigris Project Name, Client ID, and Client Secret.

## Build and run using a new Docker image

```shell
DOCKER_BUILDKIT=0  docker build . -t tigris-mongodb-php-local
```

```shell
./get-started-local.sh
```

## Build and run using the pre-existing Docker image

```shell
./get-started.sh
```

## Where next?

- Find out more about [Tigris MongoDB compatibility](https://www.tigrisdata.com/docs/concepts/mongodb-compatibility/)
- Join the [Tigris Discord](https://www.tigrisdata.com/discord/)
