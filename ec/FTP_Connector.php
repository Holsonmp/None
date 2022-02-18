<?php
/**
 * Transferer un fichier via FTP
 * 
 * @author H
 */
class Ftp_Connector {
 
    //identifiant de connexion
    private $hftp = null;
 
    /**
     * Se connecter au serveur FTP
     * @param type $ftp_host
     * @param type $ftp_user
     * @param type $ftp_password
     * @return boolean
     */
    public function connect($ftp_host, $ftp_user, $ftp_password) {
        $this->hftp = @ftp_connect($ftp_host);
        $login = @ftp_login($this->hftp, $ftp_user, $ftp_password);
 
        if ($this->hftp == FALSE || $login == FALSE) {
            return FALSE;
        }
        return TRUE;
    }
 
    /**
     * Fermer la connexion FTP
     */
    public function close() {
        ftp_quit($this->hftp);
    }
 
    /**
     * Lister le contenu du repertoire distant
     * @param type $path
     * @return string
     */
    public function getDirContents($path) {
        if ($this->hftp == FALSE) {
            return 'connect first !';
        }
 
        return ftp_nlist($this->hftp, $path);
    }
 
    /**
     * Charger un fichier
     * @param type $file_local
     * @param type $file_src
     * @return string
     */
    public function load($file_local, $file_src) {
        if ($this->hftp == FALSE) {
            return 'connectez-vous d\'abord au FTP!';
        }
 
        $ret = ftp_nb_get($this->hftp, $file_local, $file_src, FTP_BINARY);
        while ($ret == FTP_MOREDATA) {
            $ret = ftp_nb_continue($this->hftp);
        }
        if ($ret != FTP_FINISHED) {
            echo "Téléchargement echouer!";
            exit(1);
        }
    }
 
    /**
     * Uploader un fichier
     * @param type $file_dest
     * @param type $file_local
     * @return string
     */
    public function put($file_dest, $file_local) {
        if ($this->hftp == FALSE) {
            return 'connectez-vous d\'abord au FTP !';
        }
        // Initialisation du chargement
        $ret = ftp_nb_put($this->hftp, $file_dest, $file_local, FTP_BINARY);
        while ($ret == FTP_MOREDATA) {
            $ret = ftp_nb_continue($this->hftp);
        }
        if ($ret != FTP_FINISHED) {
            echo "Échec d'envoie..";
            exit(1);
        }
    }
 
}
?>
