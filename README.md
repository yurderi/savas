# savas - an api-first application version service

- docs template: https://github.com/xriley/CoderDocs-Theme
- backend: symfony
- frontend: quasar
- versioning: https://semver.org/

# entities

default fields: created, changed

- app (id: string, technical_name: string, label: string)
- app_version (id: string, app_id: string, active: bool, value: string)
- app_version_file (id: string, version_id: string, file_id: string, platform_id: string)
- file (id: string, name: string, type: string, size: int, path: string)
- platform (id: string, technical_name: string, label: string)
- api (id: string, active: bool, key: string, description: string)
- api_log (id: string, api_id: string, ip: string, request: json)