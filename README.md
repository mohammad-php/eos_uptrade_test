# eos.uptrade Test

### Requirments
#### PHP@8.1
#### mysql@8

### Setup
##### Change DB config  in .env file.
#### Run below commands:
```
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- composer install
- symfony server:start
```
###### There is also file in the root directory called eos.sql you can use it to create Databases then run (php bin/console doctrine:migrations:migrate)
#### Or you can run the project via Docker by running below command:
```
- docker compose up -d --build
```

### API Documentation
Import file in root directory (eos.uptrade.testaufgabe.postman_collection.json) into postman collection.

#### Changing Datasource
##### You can choose the Datasource(Database, Json) from services.yaml file under App\Adapter\UserAdapterManager section, below you can see the available adapters:
```
$adapter: '@App\Adapter\DataSource\DatabaseAdapter'
$adapter: '@App\Adapter\DataSource\JsonAdapter'
```

#### Unit Testing
##### You can switch Datasources by assign dataSourceAdapter in setUp() method in UserAPITest Class, as below
```
// $this->dataSourceAdapter = new DatabaseAdapter($this->entityManager);
$this->dataSourceAdapter = new JsonAdapter($kernel->getProjectDir().'/Users.json');
```
##### I could also make seperated Unit testing for every data source.