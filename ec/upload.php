

<?php
    include ("FTP_Connector.php");
    include ("Function_Up.php");
    /*
        Ajouter l'attribut enctype="multipart/form-data"
        Ajouter un input type file, name="courrier_attache"


    */
?>

    <form method="post" class="" action="" enctype="multipart/form-data">
        <input type="file" name="courrier_attache" id="file" />
        

        <input type="submit" value="up"/>

    </form>


<?php 

    /*
        Traitement du formulaire
        Envoi du FIchier via FTP et Sauvegarde Local
        

    */
    $err =0;
    $msg_err = NULL;
    $attach = $_FILES['courrier_attache'];
    $courrier_attache = $_FILES['courrier_attache']['size'];

    /* 
        Verification du fichier: Taille et Extention
    */

    if(!empty($courrier_attache)){
        $maxsize = 100000;
        $extensions_valides = array( 'pdf' , 'doc' , 'docx'); 
        if ($_FILES['courrier_attache']['error'] > 0)
        {
            $err ++;
            $msg_err = "Il y a eu un souci lors de l'envoi de votre fichier";
        }
        if ($_FILES['courrier_attache']['size'] > $maxsize)
        {
            $err ++;
            $msg_err_0 = "Votre fichier pèse beaucoup, la taille recommandée est ". $maxsize ." Ko";
        } 

        $extension_upload = strtolower(substr(strrchr($_FILES['courrier_attache']['name'], '.') ,1));
        if (!in_array($extension_upload,$extensions_valides))
        {
            $err++;
            $msg_err_1 = "Extension du fichier est incorrect.";
        }
    }

    /*
        S'il n'y a pas d'erreur, on envoi le fichier côté serveur à l'aide de la fonction move_file 

    */

    if ($err==0){
        
        $add_file_local = (!empty($courrier_attache))?move_file($_FILES['courrier_attache']):'';


        $ftp_server = '127.0.0.1';
        $ftp_user_name = "root";
        $ftp_user_pass = "h";
        $ftp_user_path = "esolution";

        
        $ftp_connector = new Ftp_Connector();
        //Connexion et identification au FTP
        $b = $ftp_connector->connect($ftp_server, $ftp_user_name, $ftp_user_pass);
        if ($b) {
            /* Lister les Fichier
            $nlist = $ftp_connector->getDirContents('/');
            print_r($nlist);

            // Telecharger un Fichier
            $ftp_connector->load("fichier_local.txt", "/files/fichier_distant.txt"); */
            
            //Uploder un fichier
            $ftp_connector->put("".$ftp_user_path."/files/".$_FILES['courrier_attache']."", $_FILES['courrier_attache']);
            $ftp_connector->close();
        }
        
        exit(0);
        /*
            Une fois que le fichier est envoyé via FTP ou en Local,
            On ajoute son nom à la base de donnée (On peut aussi enregistré le chemin complet..)
            Puis le tour est joué...
        */
        $query=$db->prepare('INSERT INTO courrier_attachement (nom_du_fichier) values (:courrier_attache)');
        $query->bindValue(':courrier_attache', $add_file_local, PDO::PARAM_STR);
        $query->CloseCursor();
    }
?>
