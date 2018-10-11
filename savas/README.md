
# savas  
Yet another package manager.  
  
### Features  
- Hosted on your own server  
- HTTP/HTTPS Protocol  
- Serves all type of packages  
- Usability as good as functionality  
- Infinite group nesting  
- Minimalistic  
  
Below you will find the idea/definition of the project before it was developed.  
  
---  
  
### Entities  
  
Default columns every normal entity has.

|Technical Field Name|Description|Validation|
|--|--|--|
|id|The unique id of the row|required, unique (auto-generated)|
|created|The timestamp of the creation|required
|changed|The timestamp when the row were changed|

##### Package  
Holds the technical name and description of your package. It also has custom API keys for external access.

|Technical Field Name|Description|Validation|
|--|--|--|
|name|Technical package name|required, unique value (by namespace, where the namespace is the username)|
|description|Package description in Markdown-Format|
|visibility|Package visibility|required, expects "public" or "private"|
|access_token|In case the package is private the access_token is required to check for updates and download them|required is visibility equals "private" but the value is auto-generated|
|private_token|An access token for external manipulation of the package (creating releases etc)|auto-generated|

The package entity is associated to a user entity using a separate entity (named user_package) to share a package between users. (n packages, n users)

##### Release
A release basically holds the package version and description. It also provides a "channel" of the release. This can be for example "alpha" or "beta" or similar. The channel entity is described somewhere below.

|Technical Field Name|Description|Validation|
|--|--|--|
|active|Whether the release should be available for update checks. This can be useful when the release is planned but not yet released||
|version|The release version|required, unique per channel|
|description|The release notes||

The release entity is directly associated to one parent package entity.(1 package, n releases)

##### Release File
A release file contains information about files which are associated to the package. Usually it is one file per platform. Platforms are defined in an extra entity defined somewhere below.

|Technical Field Name|Description|Validation|
|--|--|--|
|filename|The filename used to locate the file in the filesystem|required, must_exists
|displayName|The original filename displayed for the user|required|
|size|The size of the file|required
|mime_type|The file mime type|required
|extension|The file extension

The file entity is always associated to one release entity (1 release, n files)

##### Channel
A channel defines in which stage the package should be released.

|Technical Field Name|Description|Validation|
|--|--|--|
|name|The technical channel name|required, unique per user|
|short|The shortcut for the channel|
|default|Whether this channel should be the default channel|

A channel is basically associated to a user since every user should define their own channels. (1 user, n channels)

##### Platforms
A platform is used for release files to determine which files should be downloaded per file.

|Technical Field Name|Description|Validation|
|--|--|--|
|name|The technical platform name|required, unique per user|

A platform is basically associated to a user since every user should define their own platforms. (1 user, n platforms)