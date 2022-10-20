<?php
	
	use JetBrains\PhpStorm\NoReturn;
	
	include "printHandler.php";
	include "argumentParser.php";
	
	/**
	 * An enum to denote the level of output to print to console.
	 *
	 * - VERBOSE: All messages & debug messages.
	 * - QUIET: No messages & no debug messages.
	 * - NORMAL: All messages & no debug messages.
	 *
	 */
	enum printLevel {
		case VERBOSE;
		case QUIET;
		case NORMAL;
	}
	
	/**
	 * The possible types of media posters that this script can process.
	 *
	 * - SEASON
	 * - MOVIE
	 * - NORMAL
	 * - TVSHOW
	 * - COLLECTION
	 * - MISSING (The folder is missing from the destination directory)
	 *
	 */
	enum mediaType: string {
		case SEASON = 'Season';
		case MOVIE = 'Movie';
		case TVSHOW = 'TV Show';
		case COLLECTION = 'Collection';
		case MISSING = "Missing";
		case SKIPPED = "Skipped";
	}
	
	new AddPosterData();
	
	/**
	 * The main function to run the program.
	 *
	 */
	class AddPosterData {
		
		private printLevel $printing;
		private array $config;
		private array $arguments;
		
		/**
		 * Constructor for the script.
		 */
		public function __construct() {
			$this->printing = $this->setPrintingLevel();
			if (file_exists("./config.json")) {
				$this->config = json_decode(file_get_contents("./config.json"), true);
			}
			else {
				exit(line("<red>ERROR: Config file does not exit.</red> "));
			}
			$this->arguments = arg("
		        -h  -help          bool   Displays this message
				-t  -type          str    Select the type of posters to process. Types: movie, tv, collections, seasons, all. Default: all
				-q  -quiet         bool   Run the script silently with no output messages.
				-v  -verbose       bool   Increase verbosity and frequency of messages
				-c  -collection    bool   Disables the processing of Collection files.
				-d  -debug         bool   Do not move or create any files, only print messages.
		    ");
			//Remove the path argument from the arguments array. This is to avoid crashes when looping over the array.
			unset($this->arguments[0]);
			if (in_array(array("-help", "-h"), $_SERVER['argv'])) {
				$this->printHelp();
			}
			//If the argument is not set, assume it is false
			foreach ($this->arguments as $name => $argument) {
				if (!isset($argument['value'])) {
					if ($argument['type'] == "bool") {
						$this->arguments[$name]['value'] = false;
					}
					if ($argument['type'] == "str") {
						$this->arguments[$name]['value'] = match ($name) {
							"type" => "all",
							default => "",
						};
					}
				}
			}
			if ($this->arguments['debug']['value']) {
				$this->printLine("<yellow>WARNING: Debug flag has been set. No files/folders will be moved, deleted or renamed.</yellow>", false);
			}
			if ($this->arguments['collection']['value'] && in_array($this->arguments['type']['value'], array("all", "collections"))) {
				$this->printLine("<yellow>WARNING: Type is " . $this->arguments['type']['value'] . " and collections flag has been enabled. This may cause some unintended behavior.</yellow>", false);
			}
			
			$j = $i = 0;
			$this->printLine("<white>Processing files from these directories: </white>", true);
			//Gets the directories in the source destination.
			$directories = glob($this->config['sourcePath'] . '\*', GLOB_ONLYDIR);
			foreach ($directories as $directory) {
				$j++;
				$this->printLine("<yellow>$j) </yellow><white>" . $directory . "</white>", true);
				$files = array_diff(scandir($directory), array(".", ".."), $this->config['ignored_files']);
				foreach ($files as $file) {
					if (!str_starts_with($file, ".")) {
						$i++;
						$this->printLine("<yellow>\t\t$i)</yellow> " . $this->getColoredMedaTypeString($file) . "\t<white>" . $file . "</white> ", true);
					}
				}
				$i = 0;
			}
			foreach ($directories as $directory) {
				$this->printLine("<cyan>$directory</cyan>", false);
				$files = scandir($directory);
				foreach ($files as $file) {
					if (!str_starts_with($file, ".")) {
						$this->printLine("\t<white>$file (</white>" . $this->getColoredMedaTypeString($file) . "<white>)</white>", false);
						$finalPath = "";
						switch ($this->getMediaType($file)) {
							case mediaType::COLLECTION:
								$finalPath = $this->config['collectionsPath'] . pathinfo($file, PATHINFO_FILENAME);
								if (!file_exists($finalPath)) {
									if (!$this->arguments['debug']['value']) {
										mkdir($finalPath, 0777, true);
									}
									$this->printLine("\t\t<light_yellow>$finalPath</light_yellow><white>not found. Creating...</white>", true);
								}
								if (!$this->arguments['debug']['value']) {
									rename("$directory/$file", "$finalPath/$file");
								}
								$this->printLine("\t\t<white>Moving file...</white>" . ($this->printing == printLevel::VERBOSE ? " from <light_yellow>$directory\\$file</light_yellow> to <light_yellow>$finalPath\$file</light_yellow>" : ""), false);
								goto out;
							case mediaType::MOVIE:
								//Adds a space to the dashes. Some posters have no space which will cause the script to not find certain moves/TV shows. EX: 'Star Wars- The Clone Wars' -> 'Star Wars - The Clone Wars'
								$finalPath = $this->config['moviesPath'] . pathinfo(preg_replace("/(?<!\s)(-)/", " -", $file), PATHINFO_FILENAME);
								goto normal;
							case mediaType::SEASON:
								$tvInfo = $this->getSeasonNumber($file);
								//Adds a space to the dashes. Some posters have no space which will cause the script to not find certain moves/TV shows. EX: 'Star Wars- The Clone Wars' -> 'Star Wars - The Clone Wars'
								$finalPath = $this->config['tvshowsPath'] . preg_replace("/(?<!\s)(-)/", " -", $tvInfo[1]) . "\\" . $tvInfo[3];
								goto normal;
							case mediaType::TVSHOW:
								$finalPath = $this->config['tvshowsPath'] . pathinfo(preg_replace("/(?<!\s)(-)/", " -", $file), PATHINFO_FILENAME);
								goto normal;
							case mediaType::MISSING:
								break;
						}
						//Jump here if the movie is not missing.
						normal:
						if (file_exists($finalPath)) {
							$this->printLine("\t\t<white>Scanning Directory: </white><light_yellow>$finalPath</light_yellow>", true);
							$finalPathFiles = scandir($finalPath);
							foreach ($finalPathFiles as $pathFile) {
								if (!str_starts_with($pathFile, ".")) {
									$this->printLine("\t\t\t<white>-> </white><light_yellow>$pathFile</light_yellow>" . (str_contains($pathFile, "poster") ? "<red> (to remove)</red>" : ""), true);
								}
								if (str_contains($pathFile, "poster")) {
									if (!$this->arguments['debug']['value']) {
										if (!$this->arguments['debug']['value']) {
											unlink("$finalPath/$pathFile");
										}
									}
								}
							}
							if (!$this->arguments['debug']['value']) {
								rename("$directory/$file", "$finalPath/poster.png");
							}
							$this->printLine("\t\t<white>Moving file...</white>" . ($this->printing == printLevel::VERBOSE ? " <white>from</white> <light_yellow>$directory\\$file</light_yellow> <white>to</white> <light_yellow>$finalPath\\poster.png</light_yellow>" : ""), false);
						}
						out:
					}
				}
				if ($this->is_dir_empty($directory)) {
					if (!$this->arguments['debug']['value']) {
						rmdir("$directory/");
					}
				}
				else {
					$this->printLine("<yellow>WARNING: Directory not empty ($directory)<yellow>", false);
				}
			}
			
		}
		
		function printLine(string $toPrint, bool $verbose, $colorText = null, $colorBackground = null, bool $addDate = false, bool $breakLine = true, bool $eraseLine = false): void {
			switch ($this->printing) {
				case printLevel::QUIET:
					return;
				case printLevel::NORMAL:
					if (!$verbose) {
						echo line($toPrint, $colorText, $colorBackground, $addDate, $breakLine, $eraseLine);
					}
					return;
				case printLevel::VERBOSE:
					echo line($toPrint, $colorText, $colorBackground, $addDate, $breakLine, $eraseLine);
					break;
			}
		}
		
		function getMediaType(string $title): mediaType {
			if (!$this->arguments['collection']['value']) {
				if (str_contains($title, "Collection") || str_contains($title, "Universe")) {
					if (in_array($this->arguments['type']['value'], array("all", "collections"))) {
						return mediaType::COLLECTION;
					}
					else{
						return mediaType::SKIPPED;
					}
				}
			}
			if (in_array($this->arguments['type']['value'], array("all", "movies"))) {
				if (file_exists($this->config['moviesPath'] . pathinfo(preg_replace("/(?<!\s)(-)/", " -", $title), PATHINFO_FILENAME))) {
					return mediaType::MOVIE;
				}
			}
			if (in_array($this->arguments['type']['value'], array("all", "seasons"))) {
				if (str_contains($title, "Season") || str_contains($title, "Specials")) {
					$matches = $this->getSeasonNumber($title);
					if (file_exists($this->config['tvshowsPath'] . preg_replace("/(?<!\s)(-)/", " -", $matches[1]) . "\\" . $matches[3])) {
						return mediaType::SEASON;
					}
				}
			}
			if (in_array($this->arguments['type']['value'], array("all", "tv"))) {
				if (file_exists($this->config['tvshowsPath'] . pathinfo(preg_replace("/(?<!\s)(-)/", " -", $title), PATHINFO_FILENAME))) {
					return mediaType::TVSHOW;
				}
			}
			return mediaType::MISSING;
		}
		
		function getColoredMedaTypeString($title): string {
			$mediaType = $this->getMediaType($title);
			return match ($mediaType) {
				mediaType::MISSING => "<red>" . $mediaType->value . "</red>",
				mediaType::MOVIE => "<green>" . $mediaType->value . "</green>",
				mediaType::TVSHOW => "<cyan>" . $mediaType->value . "</cyan>",
				mediaType::SEASON => "<light_cyan>" . $mediaType->value . "</light_cyan>",
				mediaType::COLLECTION => "<blue>" . $mediaType->value . "</blue>",
				mediaType::SKIPPED => "<gray>" . $mediaType->value . "</gray>"
			};
		}
		
		function getSeasonNumber(string $title) {
			preg_match("/(.*)( - )(.*)(\.)/", $title, $matches);
			return $matches;
		}
		
		
		#[NoReturn] function printHelp(): void {
			echo line("<yellow>About this script:\n</yellow>", null, null, false, false);
			echo line("<white>This script is designed to move posters downloaded from ThePosterDB.com and move them to the movie's or TV shows' folder.</white>\n");
			echo line("<white>Currently, the paths are set to:</white>");
			//		echo line("\t<light_cyan>Poster Folder:</light_cyan><white>\t\t$path</white>");
			//		echo line("\t<light_cyan>Movies Folder:</light_cyan><white>\t\t$moviesPath</white>");
			//		echo line("\t<light_cyan>TV Shows Folder:</light_cyan><white>\t$tvPath</white>");
			//		echo line("\t<light_cyan>Collections Folder:</light_cyan><white>\t$collectionsPath</white>\n");
			//
			echo line("<yellow>Usage:</yellow>", null, null, false, false);
			echo line("\t<green>-command</green> <cyan>[arguments]</cyan>");
			
			echo line("<yellow>Options:\n</yellow>", null, null, false, false);
			
			foreach ($this->arguments as $argument) {
				printf("\t%s\t\t%-20s\n", line("<green>-" . $argument['char'] . ", -" . $argument['word'] . "</green>", null, null, false, false), line("<white>" . $argument['help'] . "</white>", null, null, false, false));
			}
			exit();
		}
		
		function is_dir_empty($dir): bool {
			if (!is_readable($dir))
				return false;
			return (count(array_diff(scandir($dir), array('..', '.', "Thumbs.db"))) == 0);
		}
		
		function setPrintingLevel(): printLevel {
			$printingLevel = printLevel::NORMAL;
			if (!isset($this->arguments['verbose']['value']) || !isset($this->arguments['quiet']['value'])) {
				if (isset($this->arguments['quiet']['value'])) {
					if ($this->arguments['quiet']['value']) {
						$printingLevel = printLevel::QUIET;
					}
				}
				if (isset($arguments['verbose']['value'])) {
					if ($arguments['verbose']['value']) {
						$printingLevel = printLevel::VERBOSE;
					}
				}
			}
			else {
				echo line("<red>ERROR: </red> <white>Both -q and -v are set.</white>");
				$this->printHelp();
			}
			return $printingLevel;
		}
	}