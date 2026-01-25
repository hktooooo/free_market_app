.PHONY: init fresh up down restart cache stop wait-db

init:
	docker compose up -d --build
	@make wait-db
	docker compose exec php composer install
	cp -n src/.env.example src/.env
	mkdir -p src/storage/app/public/chat_images
	mkdir -p src/storage/app/public/product_images
	mkdir -p src/storage/app/public/profile_images
	cp src/public/images/sample_images/*.jpg src/storage/app/public/product_images
	docker compose exec php php artisan key:generate
	docker compose exec php php artisan storage:link
	docker compose exec php chmod -R 775 storage bootstrap/cache
	@make fresh

fresh:
	docker compose exec php php artisan migrate:fresh --seed

wait-db:
	@echo "⏳ Waiting for MySQL to be ready..."
	@until docker compose exec mysql mysqladmin ping -h mysql --silent; do \
		sleep 2; \
	done
	@echo "✅ MySQL is ready"

restart:
	@make down
	@make up

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker compose exec php php artisan cache:clear
	docker compose exec php php artisan config:clear
	docker compose exec php php artisan config:cache

stop:
	docker compose stop
