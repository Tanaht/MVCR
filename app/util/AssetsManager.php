<?php
	namespace app\util;

	use app\config\Config;

	class AssetsManager{
		
		public static function getAssetsFolder() {
			return Config::ASSETS_DIRECTORY;
		}

		private static function removeAll($path){
			foreach (glob($path . "/*") as $filename) {
    			AssetsManager::remove($filename);
			}
		}

		public static function remove($path){
			$path = Config::ASSETS_DIRECTORY . $path;

			if(is_dir($path)){
				AssetsManager::removeAll($path);
				rmdir($path);
			}
			else if(is_file($path)){
				unlink($path);
			}
		}

		private static function addDirectory($path){
			if(!file_exists(Config::ASSETS_DIRECTORY . $path))
				mkdir(Config::ASSETS_DIRECTORY . $path, 0777, true);
		}

		public static function addFileJPG($oldPath, $newPath, $filename){
			AssetsManager::addDirectory($newPath);

			move_uploaded_file ($oldPath, Config::ASSETS_DIRECTORY . $newPath . "/" . $filename);

			//rename(Config::ASSETS_DIRECTORY . $newPath . $oldFilename, Config::ASSETS_DIRECTORY . $newPath . $newFilename);
		}

		public static function dupFile($oldPath, $newPath){
			move_uploaded_file ($oldPath, Config::ASSETS_DIRECTORY . $newPath);

			//rename(Config::ASSETS_DIRECTORY . $newPath . $oldFilename, Config::ASSETS_DIRECTORY . $newPath . $newFilename);
		}

		public static function resizeJPG($oldPath, $newPath, $width, $height) {
			$sizeSrc = getImageSize(Config::ASSETS_DIRECTORY . $oldPath);
			if($sizeSrc["mime"] != "image/jpeg") {
				return false;
			}


			$imageSrc = imagecreatefromjpeg(Config::ASSETS_DIRECTORY . $oldPath);
			$imageDst = imagecreatetruecolor($width, $height);

			if(!imagecopyresized($imageDst , $imageSrc , 0 , 0 , 0 , 0 , $width , $height ,  $sizeSrc[0], $sizeSrc[1]))
				return false;

			if(!file_exists($newPath))
				AssetsManager::dupFile($oldPath, $newPath);
			return imagejpeg($imageDst, Config::ASSETS_DIRECTORY . $newPath, 100);
		}
	}
?>