# savas
An self-hosted release system for everyone

***Note***: This project is work in progress and is may not suitable for production system.

### What?
Developers may know it: Developing applications and serving those to their customers is easy. But what when it comes to updates? Usually you would build a small service which serves updates for your application - and that probably every time for a new application.

Personally I had this problem and `savas` solved my problem.

Using `savas` you can serve updates for your applications easily. All you need to create an application in `savas`, create a new release by providing the version and release notes and upload necessary files - done! Then next time the application is starting it can communicate through a simple API with savas to check for new updates and download them.

### How?
`savas` will come in 4 packages:
- The plugin for the ProVallo CMS as the service (do host the release system yourself)
- A cli tool to release a new update from the terminal
- A node.js integration for easy communication with the http-service
- A vue-electron integration to tell the user easily for new updates

# License
MIT
