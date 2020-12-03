# API

When you don't know how to work with the api, here's how.

## Table of Contents
- [Introduction](#introduction)
- [GET Request](#get-request)
- [POST Request](#post-request)
- [PUT Request](#put-request)
- [PATCH Request](#patch-request)
- [DELETE Request](#delete-request)

### Introduction
This api basically supports the REST concept. It is built to work with all registered entities.

The supported entities are:
- app
- app_version
- app_version_file
- file
- platform

To support  an entity, it must implement the `\App\Component\Api\ModelInterface` interface.

### GET Request
Path: `/api/v1/search/:entity`

Example request body
```json
{
	"offset": 0,
	"limit": 2,
	"order": {
		"created": "ASC"
	},
	"associations": [
		"versions.files.file",
		"versions.files.platform"
	],
	"filter": {
		"technicalName": "savas"
	}
}
```

Example response body
```json
{
  "total": 1,
  "data": [
    {
      "id": "00000000-0000-0000-0000-000000000000",
      "technicalName": "savas",
      "label": "Savas",
      "created": 1607032351,
      "changed": 1607032351,
      "versions": [
        {
          "id": "00000000-0000-0000-0000-000000000000",
          "appId": "00000000-0000-0000-0000-000000000000",
          "active": true,
          "value": "1.0.0",
          "created": 1606937485,
          "changed": null,
          "files": [
            {
              "id": "00000000-0000-0000-0000-000000000000",
              "versionId": "00000000-0000-0000-0000-000000000000",
              "fileId": "00000000-0000-0000-0000-000000000000",
              "platformId": "00000000-0000-0000-0000-000000000000",
              "created": 1606940203,
              "changed": null,
              "platform": {
                "id": "00000000-0000-0000-0000-000000000000",
                "technicalName": "linux",
                "label": "Linux",
                "created": 1606940152,
                "changed": null
              },
              "file": {
                "id": "00000000-0000-0000-0000-000000000000",
                "name": "savas.png",
                "type": "image\/png",
                "size": 1337,
                "path": "ab\/cd\/ef\/savas.png",
                "created": 1606940186,
                "changed": null
              }
            }
          ]
        }
      ]
    }
  ]
}
```

### POST Request
Path: `/api/v1/create/:entity`

> This request is used to create a new row. The id will be generated automatically, if you don't pass one.

Example request body:
```json
{
	"technicalName": "test2",
	"label": "Test Application"
}
```

Example response body:
```json
{
  "id": "4f26657a-4745-4af4-88e4-202cfeadbb23",
  "technicalName": "test2",
  "label": "Test Application",
  "created": 1607032138,
  "changed": 1607032138
}
```

### PUT Request
Path: `/api/v1/create/:entity`

> This request is used to either create or update a row. The id will be generated automatically, if you don't pass one.

Example request body:
```json
{
	"technicalName": "my_new_app",
	"label": "My new app"
}
```

Example response body:
```json
{
  "id": "ad68575c-170c-4ad8-9865-3cdcc0c54bfa",
  "technicalName": "my_new_app",
  "label": "My new app",
  "created": 1607033991,
  "changed": 1607033991
}
```

### PATCH Request
Path: `/api/v1/create/:entity`

> This request is used to update an existing row.

Example request body:
```json
{
	"id": "00000000000000000000000000000000",
	"label": "I am dumb."
}
```

Example response body:
```json
{
  "id": "00000000-0000-0000-0000-000000000000",
  "technicalName": "savas",
  "label": "I am dumb.",
  "created": 1607032351,
  "changed": 1607032351
}
```

### DELETE Request
Path: `/api/v1/create/:entity`

Example request body:
```json
{
	"id": "d0ab95a5-b4b5-47b2-a516-8958505c03b0"
}
```

This request has no response body.
