# Vessels Tracks API

A RESTful API built with the Laravel framework that serves vessel tracks from a raw vessel positions data-source.

## System Requirements

* php ^8.1
* mbstring extension
* mysql

## PreRequisites

* Create database `vessels_tracks`

## Installation

* Clone repository
* run `composer install`
* rename `.env.test` (found in projects root directory) to `.env`
* edit `.env`, set `DB_USERNAME` to mysql username and `DB_PASSWORD` to mysql password (if any)
* run `php artisan key:generate`
* run `php artisan migrate`
* run `php artisan db:seed`

## Testing

* run `php artisan test`

## Get all vessels tracks

* URL - `api/vessels/tracks`
* Method - `GET`
* Params

| Name | Required | Type | Example | Description |
| --- | --- | --- | --- | --- |
| mmsi | `false` | `string` | `api/vessels/tracks?mmsi=247039300,311486000`|Return only specified vessels tracks. The mmsi parameter should be a comma seperated string of integers|
| lon | `true` if the `lat` attribute is present | `string` | `api/vessels/tracks?lon=15.44150000&lat=42.75178000` |Return only vessels tracks of vessels within the given range|
| lat | `true` if the `lon` attribute is present | `string` | `api/vessels/tracks?lon=15.44150000&lat=42.75178000` |Return only vessels tracks of vessels within the given range|
| from | `true` if the `to` attribute is present | `int` | `api/vessels/tracks?from=1372700520&to=1372700580` |Return only vessels tracks of vessels within the given position timestamp|
| to | `true` if the `from` attribute is present | `int` | `api/vessels/tracks?from=1372700520&to=1372700580` |Return only vessels tracks of vessels within the given position timestamp|
