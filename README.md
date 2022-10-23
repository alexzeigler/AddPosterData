<a name="readme-top"></a>
[![Minimum PHP version: 7.4.0](https://img.shields.io/badge/php-7.4.0%2B-blue.svg?label=PHP)](https://php.net)
[![issues]](https://img.shields.io/github/downloads/alexzeigler/AddPosterData/total)
[![license]](https://img.shields.io/github/license/alexzeigler/AddPosterData)
[![forks]](https://img.shields.io/github/forks/alexzeigler/AddPosterData)
[![stars]](https://img.shields.io/github/stars/alexzeigler/AddPosterData)
[![linkedin](https://img.shields.io/badge/linkedin-alexander--zeigler-blue)](https://www.linkedin.com/in/alexander-zeigler/)

[stars]: https://img.shields.io/github/stars/alexzeigler/AddPosterData
[forks]: https://img.shields.io/github/forks/alexzeigler/AddPosterData
[issues]: https://img.shields.io/github/issues/alexzeigler/AddPosterData
[license]: https://img.shields.io/github/license/alexzeigler/AddPosterData
[linkedin]: https://img.shields.io/badge/linkedin-alexander--zeigler-blue

<!-- PROJECT LOGO -->
<br />
<div align="center">
<h3 align="center">AddPosterData</h3>
  <p align="center">
    a script to automate the process of adding artwork to a plex media server
    <br />
    <a href="https://github.com/alexzeigler/addposterdata/issues">Report Bug</a>
    ·
    <a href="https://github.com/alexzeigler/addposterdata/issues">Request Feature</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#plex">Plex</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <ul>
        <li><a href="#config">Config</a></li>
    </ul>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project
his is a custom made PHP script that works by taking posters downloaded (manually) from https://theposterdb.com/ and sorts them into their respective folder in the Plex media folder. When sorting, it deletes any folder that has a poster already in it, and replaces it with the new poster.


![image](https://user-images.githubusercontent.com/11970623/196852667-74e8439d-09fa-47dd-95b4-83d3762c11de.png)

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

This script only requires a valid PHP install. 

<b>Note: </b> This script was developed in the windows environment. If you are running anything other than Windows, you will neeed to edit the config to change the paths to a valid path for your environment. More info can be found [here](https://example.com).


### Plex

For this purpose, your plex agents should be ordered to prioritize Local Metadata:


![image](https://user-images.githubusercontent.com/11970623/197400905-6b492989-b1ab-4e2c-9c36-3f100f31669d.png)

As a way of forcing Plex to prioritize local metadata, the example above has external metadata sources disabled. This is not manditory for every configuration, but might cause some inintended behavior.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## Usage

**WARNING:** If you have files located in your movie folder named "poster" this script will delete them. Backup your metadata files before running this script. 

The script is designed to be run via command line on your platform of choice. The default behavior will not chose which files to move, and will move all files reguardless of type. 

Argument list:


| Parameter                   | Default       | Description   |	
| :------------------------   |:-------------:| :-------------|
| -h,  --help                 | -             | display the list help message. 
| -v,  --verbose              | off           | increase output and detail of messages 
| -t, 	--type                | all           | specify the type of posters to process. Types are: movie, tv, collections, seasons, all.
| -q,  --quiet     		        | off	          | do not show any output messages
| -d,  --debug 		            | off           | shows all output messages, but does not move or delete any files.

To run the script:  

    php /path/to/script/AddPosterData.php [-parameter[=value]]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## Config

The script comes default with a configuration json. 


    {
      "paths": {
        "Source": "V:\\.posters\\Watched Folder",
        "Movies": "V:\\Movies\\",
        "TV Shows": "V:\\TV Shows\\",
        "Collections": "V:\\.posters\\Collections\\"
      },
      "ignored_files": [
        "Thumbs.db",
        ""
      ]
    }

| Name                   | Type       | Description   |	
| :------------------------   |:-------------:| :-------------|
| Paths/Source                | String           | the path to scan for files
| Paths/Movies                | String           | the Plex movie library path
| Paths/TV Shows              | String           | the Plex movie library path
| Paths/Collections           | String           | a place to store your collection files
| Ignored Files    		        | Array	           | ignore these files when deleting source folder out of source path

<!-- ROADMAP -->
## Roadmap

- [ ] Give the script the ability to connect to Plex API
    - [ ] Automatically set art for collections
- [ ] Process individual files instead of files nested only a single folder deep. 
- [ ] Auto generate config file, so if it is lost, the script does not crash.
- [ ] Create bash and batch scripts to run the script easily with arguments. 

See the [open issues](https://github.com/alexzeigler/addposterdata/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- LICENSE -->
## License

Distributed under the Unlicensed. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>
