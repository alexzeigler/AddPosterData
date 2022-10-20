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
    <a href="https://github.com/alexzeigler/addposterdata"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/alexzeigler/addposterdata">View Demo</a>
    ·
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
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project
This is a custom made PHP script that works by taking posters downloaded (manually) from https://theposterdb.com/ and sorts them into their respective folder in the Plex media folder.
![image](https://user-images.githubusercontent.com/11970623/196852667-74e8439d-09fa-47dd-95b4-83d3762c11de.png)




Here's a blank template to get started: To avoid retyping too much info. Do a search and replace with your text editor for the following: `alexzeigler`, `addposterdata`, `twitter_handle`, `alex-zeigler`, `email_client`, `email`, `project_title`, `a script to automate the process of adding artwork to a plex media server`

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

This script only requires a valid PHP install. 

<b>Note: </b> This script was developed in the windows environment. If you are running anything other than Windows, you will neeed to edit the config to change the paths to a valid path for your environment. More info can be found [here](https://example.com).


### Installation

1. Get a free API Key at [https://example.com](https://example.com)
2. Clone the repo
   ```sh
   git clone https://github.com/alexzeigler/addposterdata.git
   ```
3. Install NPM packages
   ```sh
   npm install
   ```
4. Enter your API in `config.js`
   ```js
   const API_KEY = 'ENTER YOUR API';
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## Usage

Use this space to show useful examples of how a project can be used. Additional screenshots, code examples and demos work well in this space. You may also link to more resources.

_For more examples, please refer to the [Documentation](https://example.com)_

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ROADMAP -->
## Roadmap

- [ ] Feature 1
- [ ] Feature 2
- [ ] Feature 3
    - [ ] Nested Feature

See the [open issues](https://github.com/alexzeigler/addposterdata/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTACT -->
## Contact

Your Name - [@twitter_handle](https://twitter.com/twitter_handle) - email@email_client.com

Project Link: [https://github.com/alexzeigler/addposterdata](https://github.com/alexzeigler/addposterdata)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

* []()
* []()
* []()

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->


# AddPosterData


# How it works
The script assumes the posters are in a folder. A future change will be to look at individual files also. The input directory should look something like this:

	/{WATCHEDFOLDER}
		/Divergent Collection/
			Allegiant (2016).jpeg
			Divergent (2014).jpeg
			Divergent Collection.jpeg
			Insurgent (2015).jpeg


For now, the script will look at each folder in the watched directory and analyze each 



  1. The posters are placed in a "Watched folder". This folder can be configured in the config.json, under "sourcePath". 
