# Plus sans ascenseur

Projet libre permettant de signaler des pannes d'ascenseur pour le collectif Plus Sans Ascenseur

## Index de geolocalisation

db.Ascenseur.createIndex({ "localisation.coordinates" : "2dsphere" })
