<h1 align="left">Менеджер турниров</h1>

### Использовано

* PHP 8.2
* Symfony 7.0
* MySQL 8.0

### 📝 Быстрая инструкция
1. Клонировать репозиторий
2. Иметь в наличии докер
3. Запустить команду `docker-compose up --build -d`
4. Перейти на `http://localhost:8008`

### 🚀 Запуск fixtures для экономии времени (если необходимо конечно)
````
docker-compose exec tournaments_symfony_app php bin/console doctrine:fixtures:load --no-interaction
````