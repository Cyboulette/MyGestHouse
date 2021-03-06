<?php
require_once 'Model.php';

class ModelUtilisateur extends Model {

    // Mettre en protected pour y avoir accès depuis Model
    protected $idUtilisateur;
    protected $emailUtilisateur;
    protected $password;
    protected $prenomUtilisateur;
    protected $nomUtilisateur;
    protected $rang;
    protected $nonce;

    protected static $tableName = 'GH_Utilisateurs'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
    protected static $object = 'utilisateur'; // Correspond au nom de l'objet à créer
    protected static $primary = 'idUtilisateur'; // Correspond à la clé primaire de la table (pratique pour faire un read())

    public function __construct($idUtilisateur = NULL, $emailUtilisateur = NULL, $password = NULL, $prenomUtilisateur = NULL, $nomUtilisateur = NULL, $rang = NULL, $nonce = NULL) {
        if (!is_null($idUtilisateur) && !is_null($emailUtilisateur) && !is_null($password) && !is_null($prenomUtilisateur) && !is_null($nomUtilisateur) && !is_null($rang) && !is_null($nonce)) {
            $this->idUtilisateur = $idUtilisateur;
            $this->emailUtilisateur = $emailUtilisateur;
            $this->password = $password;
            $this->prenom = $prenomUtilisateur;
            $this->nom = $nomUtilisateur;
            $this->rang = $rang;
            $this->nonce = $nonce;
        }
    }

    public static function generateRandomHex() {
      $numbytes = 16;
      $bytes = openssl_random_pseudo_bytes($numbytes); 
      $hex   = bin2hex($bytes);
      return $hex;
    }

    public function validate() {
        try {
          $sql = 'UPDATE `'.self::$tableName.'` SET nonce = NULL WHERE idUtilisateur = :idUtilisateur';
          $validateUser = Model::$pdo->prepare($sql);

          $values = array(
            'idUtilisateur' => $this->idUtilisateur
          );

          $validateUser->execute($values);
          return true;
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }       
    }

    public function getPower() {
        try {
          $sql = 'SELECT * FROM `GH_Rangs` WHERE idRang = :idRang';
          $getRang = Model::$pdo->prepare($sql);

          $values = array(
            'idRang' => $this->rang
          );

          $getRang->execute($values);
          $results_rang = $getRang->fetch();
          if($results_rang != false) {
              if(ControllerUtilisateur::isConnected()) {
                return $results_rang['power'];
              } else {
                 return 0;
              }
          }
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }

    public static function countSelectByRang($idRang){
        try {
            $sql = "SELECT COUNT(*) 
                    FROM `GH_Utilisateurs`
                    WHERE rang = :rang";
            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
              'rang' => $idRang,
            );

            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_NUM);
            $tab = $req_prep->fetch();
            
            if(empty($tab)) {
                return 0;
            }
    
            return $tab[0];
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }

    public static function selectNonValide(){
        try {
            $sql = "SELECT * 
                    FROM `GH_Utilisateurs` 
                    WHERE nonce IS NOT NULL
            ";

            $rep = Model::$pdo->query($sql);

            $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
            $tab = $rep->FetchAll();
            return $tab;
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }

    public static function countSelectNonValide(){
        try {
            $sql = "SELECT COUNT(*) 
                    FROM `GH_Utilisateurs` 
                    WHERE nonce <> ' '
            ";

            $rep = Model::$pdo->query($sql);

            $rep->setFetchMode(PDO::FETCH_NUM);
            $tab = $rep->Fetch();
            
            if(empty($tab)) {
                return 0;
            }
    
            return $tab[0];
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }

    public static function getNombreActifsAndValid() {
        try {
          $sql = 'SELECT COUNT(*) FROM `'.self::$tableName.'` WHERE rang > :rang AND nonce IS NULL';
          $getNombre = Model::$pdo->prepare($sql);

          $values = array(
            'rang' => 1
          );

          $getNombre->execute($values);
          $result_nombre = $getNombre->fetch();
          return $result_nombre[0];
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }       
    }

    // Cette fonction existe deja dans le model Reservation
    // Select toutes les réservations d'un utilisateur
    public static function selectAllRéservation() {
        $idUtilisateur = static::$idUtilisateur;
        $class_name = 'Model'.ucfirst(static::$object);

        try{
            $sql = 'SELECT * FROM GH_reservations WHERE idUtilisateur = '.$idUtilisateur;
            $rep = Model::$pdo->prepare($sql);


            $rep->setFetchMode(PDO::FETCH_CLASS, $class_name);
            $tab = $rep->FetchAll();
            return $tab;
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }


}
?>