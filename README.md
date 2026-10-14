# tp-mongodb

Objectifs : 

1. implémenter un crud sur une base MongoDB
2. accélérer l'application à l'aide de Redis et effectuer un test de charge avec k6 (+InfluxDB et Grafana pour visualiser)
   
Lancement des tests : 

```
docker compose up -d
docker compose run k6 run /scripts/load_test.js
```

3. ajouter un moteur de recherche à l'aide d'ElasticSearch