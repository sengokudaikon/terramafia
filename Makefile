list:
	@echo ""
	@echo "Useful targets:"
	@echo ""
	@echo "  - 'make build' > Build images"
	@echo "  - 'make up' > Run project"
	@echo "  - 'make stop' > Stop project"
	@echo "  - 'make php' > Shell to app container"
	@echo "  - 'make refresh' > Refresh cache, db, seeds and swagger (for exmpl: after change branch)"
	@echo "  - 'make linter' > Fix code style"
	@echo "  - 'make test	' > Run tests"
	@echo "  - 'make db-refresh	' > Refresh database"
	@echo "  - 'make production	' > Run on production server"
	@echo ""




build:
	docker-compose build
up:
	docker-compose up -d
stop:
	docker-compose down
php:
	docker-compose exec app bash
test:
	docker-compose exec app php artisan test
swagger:
	docker-compose exec app php artisan l5-swagger:generate
optimize:
	docker-compose exec app php artisan optimize
refresh:
	docker-compose exec app php artisan optimize
	docker-compose exec app composer dump-autoload
	docker-compose exec app php artisan doctrine:migrations:refresh
	docker-compose exec app php artisan db:seed
	docker-compose exec app php artisan l5-swagger:generate
	docker-compose exec app rm -rf storage/app/public/tmp/
	docker-compose exec app rm -rf public/storage
	docker-compose exec app php artisan storage:link
linter:
	docker-compose exec app php ./vendor/bin/phpcbf --standard=PSR2 app/
	docker-compose exec app php ./vendor/bin/phpcbf --standard=PSR2 Modules/
	docker-compose exec app php ./vendor/bin/phpcs --extensions=php --runtime-set ignore_warnings_on_exit true --standard=PSR2 app
	docker-compose exec app php ./vendor/bin/phpcs --extensions=php --runtime-set ignore_warnings_on_exit true --standard=PSR2 Modules
production:
	docker-compose down
	docker-compose build
	docker-compose up -d
	docker-compose exec app composer install
	docker-compose exec app php artisan optimize
	docker-compose exec app php artisan doctrine:migrations:migrate
db-refresh:
	docker-compose exec app php artisan doctrine:migrations:refresh
	docker-compose exec app php artisan db:seed