COMPOSER = composer
CONSOLE = bin/console
DOCKER = docker
PHP_SERVICE = php

#nooooo
#install:
    #$(COMPOSER) install

start-all-container:
	@$(DOCKER) compose up -d

stop-all:
	@$(DOCKER) stop $$(docker ps -aq)

restart: stop-all start-all-container

stop-phpmyadmin:
	@$(DOCKER) stop dfe76050c6ad719d25e4be45e9ae3c1675dc1d9a2d258ef7f8764440f07b1acc

stop-db:
	@$(DOCKER) stop cd863dc31f8072cb5f690e3b0fd164efe7b0db676aaaef3dd3f5fddaf9a48e06

run:
    @$(CONSOLE) server:run

cache-clear:
    @$(CONSOLE) cache:clear

create-database:
	@$(DOCKER) compose exec $(PHP_SERVICE) $(CONSOLE) doctrine:database:drop --force --if-exists
	@$(DOCKER) compose exec $(PHP_SERVICE) $(CONSOLE) doctrine:database:create
	@$(DOCKER) compose exec $(PHP_SERVICE) $(CONSOLE) doctrine:migrations:migrate -n

#Run fixtures
fixtures: create-database
	@$(DOCKER) compose exec $(PHP_SERVICE) $(CONSOLE) doctrine:fixtures:load -n
	@echo 'la création des fixtures a bien été effectué'
#pensé a ajouter du styke

