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
| lon_min | `true` if any of `lon_max`, `lat_min`, `lat_max` attribute is present | `string` | `api/vessels/tracks?lon_min=14.35212000&lon_max=18.99567000&lat_min=40.68598000&lat_max=41.45607000` |Return only vessels tracks of vessels within the given range|
| lon_max | `true`  `true` if any of `lon_min`, `lat_min`, `lat_max` attribute is present | `string` | `api/vessels/tracks?lon_min=14.35212000&lon_max=18.99567000&lat_min=40.68598000&lat_max=41.45607000` |Return only vessels tracks of vessels within the given range|
| lat_min | `true`  `true` if any of `lon_min`, `lon_max`, `lat_max` attribute is present | `string` | `api/vessels/tracks?lon_min=14.35212000&lon_max=18.99567000&lat_min=40.68598000&lat_max=41.45607000` |Return only vessels tracks of vessels within the given range|
| lat_max | `true`  `true` if any of `lon_min`, `lon_max`, `lat_min` attribute is present | `string` | `api/vessels/tracks?lon_min=14.35212000&lon_max=18.99567000&lat_min=40.68598000&lat_max=41.45607000` |Return only vessels tracks of vessels within the given range|
| from | `true` if the `to` attribute is present | `int` | `api/vessels/tracks?from=1372700520&to=1372700580` |Return only vessels tracks of vessels within the given position timestamp|
| to | `true` if the `from` attribute is present | `int` | `api/vessels/tracks?from=1372700520&to=1372700580` |Return only vessels tracks of vessels within the given position timestamp|
