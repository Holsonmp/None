<?php
function move_file($courrier_attache)
	{
		$extension_upload = strtolower(substr( strrchr($courrier_attache['name'], '.') ,1));
		$name = "file-ecourrier-" . time(); // On renomme le fichier en format file
		$nom_fichier = str_replace(' ','',$name).".".$extension_upload;
		$name = "./files/ecourrier/".str_replace('','',$name).".".$extension_upload;  // Là où le fichier sera sauvegarder..
		move_uploaded_file($courrier_attache['tmp_name'],$name);
		return $nom_fichier;
	}
?>
