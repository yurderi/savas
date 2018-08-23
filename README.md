# savas
An self-hosted release system for everyone

***Note***: This project is work in progress and is may not suitable for production system.

### What?
Developers may know it: Developing applications and serving those to their customers is easy. But what when it comes to updates? Usually you would build a small service which serves updates for your application - and that probably every time for a new application.

Personally I had this problem and `savas` solved my problem.

Using `savas` you can serve updates for your applications easily. All you need to create an application in `savas`, create a new release by providing the version and release notes and upload necessary files - done! Then next time the application is starting it can communicate through a simple API with savas to check for new updates and download them.

### Packages
- `savas` itself as a plugin for the ProVallo CMS
- `savas-cli` as cli-tool to manage releases through the tmerinal
- `node-savas` a node.js integration to communicate with the savas http-service in node.js
- `vue-electron-savas` a vue-electron integration as a working updater for your electron.io application

# License
MIT
