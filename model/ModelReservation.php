
<?php
require_once 'Model.php';

class ModelReservation extends Model {

    protected $idReservation;
    protected $idChambre;
    protected $idUtilisateur;
    protected $dateDebut;
    protected $dateFin;

    protected static $tableName = 'GH_Reservations'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
    protected static $object = 'Reservation'; // Correspond au nom de l'objet à créer
    protected static $primary = 'idReservation'; // Correspond à la clé primaire de la table (pratique pour faire un read())


    public function __construct( $idReservation=NULL, $idChambre=NULL, $idUtilisateur=NULL, $dateDebut=NULL, $dateFin=NULL ){
        if(!is_null($idReservation) && !is_null($idChambre) && !is_null($idUtilisateur) && !is_null($dateDebut) && !is_null($dateFin)){
            $this->idReservation = $idReservation;
            $this->idChambre = $idChambre ;
            $this->idUtilisateur = $idUtilisateur ;
            $this->dateDebut = $dateDebut ;
            $this->dateFin = $dateFin ;

        }

    }

    public static function annulerReservation($idReservation){
        try {
            $sql = 'UPDATE `'.static::$tableName.'` SET `annulee` = :tag_annulee WHERE `idReservation` = :tag_idReservation';

            $data = array(
                'tag_annulee' => true,
                'tag_idReservation' => $idReservation
            );

            $update = Model::$pdo->prepare($sql);
            $update->execute($data);
            return true;
        } catch(PDOException $e) {
            if(Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }

    /* SOME GETTERS */

    public static function getReservationsEnCours($idUtilisateur = null){
        if($idUtilisateur != null){
            $where_clause = "AND idUtilisateur = ".$idUtilisateur;
        } else {
            $where_clause = "";
        }
        try{
            $dateLocal = new DateTime();

            $sql = 'SELECT * FROM '.self::$tableName.' WHERE dateDebut <= :date AND dateFin >= :date AND annulee IS NULL '.$where_clause;
            $rep = Model::$pdo->prepare($sql);

            $values = array(
                'date' => $dateLocal->format('Y-m-d')
            );

            $rep->execute($values);
            $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
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
    public static function getReservationsEnAttente($idUtilisateur = null){
        if($idUtilisateur != null){
            $where_clause = "AND idUtilisateur = ".$idUtilisateur;
        } else {
            $where_clause = "";
        }
        try{
            $dateLocal = new DateTime();

            $sql = 'SELECT * FROM GH_Reservations WHERE dateDebut > :date AND annulee IS NULL '.$where_clause;
            $rep = Model::$pdo->prepare($sql);

            $values = array(
                'date' => $dateLocal->format('Y-m-d')
            );

            $rep->execute($values);

            $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
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
    public static function getReservationsFinis($idUtilisateur = null){
        if($idUtilisateur != null){
            $where_clause = "AND idUtilisateur = ".$idUtilisateur;
        } else {
            $where_clause = "";
        }
        try{
            $dateLocal = new DateTime();

            $sql = "SELECT * FROM GH_Reservations WHERE dateFin < :date AND annulee IS NULL ".$where_clause;
            $rep = Model::$pdo->prepare($sql);

            $values = array(
                'date' => $dateLocal->format('Y-m-d')
            );

            $rep->execute($values);

            $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
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
    public static function getReservationsAnnulee($idUtilisateur = null){
        if($idUtilisateur != null){
            $where_clause = "AND idUtilisateur = ".$idUtilisateur;
        } else {
            $where_clause = "";
        }
        try{
            $sql = 'SELECT * FROM GH_Reservations WHERE annulee = 1 '.$where_clause;
            $rep = Model::$pdo->prepare($sql);

            $rep->execute();

            $rep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
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


    /* SOME SELECTOR */

    public static function selectAllByUser($idUtilisateur){
        try {
            $sql = "SELECT *
                    FROM `GH_Reservations` r
                    WHERE r.idUtilisateur= :tag_idUtilisateur";

            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
                'tag_idUtilisateur' => $idUtilisateur,
            );

            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
            $result = $req_prep->fetchAll();

            return $result;
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            } else {
                echo "Une erreur est survenue ! Merci de réessayer plus tard";
            }
            return false;
            die();
        }
    }
    public static function selectAllPrixByUser($idUtilisateur){
        $Reservations=self::selectAllByUser($idUtilisateur);
        $result=0;
        foreach($Reservations as $reservation){
            $result = $result + $reservation->getPrixTotal();
        }
        return $result;
    }
    public static function selectAllByChambre($idChambre){
        try {
            $sql = "SELECT *
                    FROM `GH_Reservations`
                    WHERE idChambre = :tag_idChambre";

            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
                'tag_idChambre' => $idChambre,
            );

            $req_prep->execute($values);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
            $result = $req_prep->fetchAll();

            return $result;
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            } else {
                echo "Une erreur est survenue ! Merci de réessayer plus tard";
            }
            return false;
            die();
        }
    }


    /* SOME GETTERS FOR DATE AVAILABE */

    /**
     * @return array all the date reserved for a unique chambre
     */
    public static function selectAllDateByChambre($idChambre){
        $result = array();

        foreach (ModelReservation::selectAllByChambre($idChambre) as $reservation){
            $nombreNuits = $reservation->getNombreNuits();
            for ($nombre = 0 ; $nombre < $nombreNuits ; $nombre ++) {
                $dateTime = new DateTime($reservation->get('dateDebut'));
                $dateTime->modify("+".$nombre." day");
                $dateTime = $dateTime->format("d/m/Y");
                array_push($result, $dateTime);
            }
        }

        return $result;
    }


    /* SOME OTHERS GETTERS */

    /**
     * Return the number of day of the reservation
     */
    public function getNombreNuits(){
        try{
            $sql = "SELECT DATEDIFF(:dateFin,:dateDebut)";

            $rep = Model::$pdo->prepare($sql);


            $values = array(
                'dateDebut' => $this->dateDebut,
                'dateFin' => $this->dateFin
            );
            $rep->setFetchMode(PDO::FETCH_UNIQUE);
            $rep->execute($values);

            $tab = $rep->Fetch();

            if($tab[0] < 0) {
                return "#!ERREUR";
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

    /**
     * Return the total price of the reservation, this price of the room plus all the prestations
     * TODO : fixed return for negatives values
     */
    public function getPrixTotal(){
        try{
            $sql = "SELECT c.prixChambre*:tag_nombreJour+IFNULL(SUM(p.prix),0) FROM GH_Chambres c, GH_ReservationsPrestation rp, GH_Prestations p WHERE rp.idPrestation = p.idPrestation AND rp.idReservation = :tag_idReservation AND c.idChambre = :tag_idChambre";

            $rep = Model::$pdo->prepare($sql);

            $values = array(
                'tag_idChambre' => $this->idChambre,
                'tag_idReservation' => $this->idReservation,
                'tag_nombreJour' => $this->getNombreNuits()
            );
            $rep->execute($values);

            $tab = $rep->Fetch();

            return $tab[0];
        } catch(PDOException $e) {
            if (Conf::getDebug()) {
                echo $e->getMessage();
            }
            return false;
            die();
        }
    }




    /** Encode dates for datePicker
     * @param $idChambre the id of the chambre for select all the reservation
     * @return string the json encode format
     */
    public static function encodeDatesForChambre($idChambre){
        return json_encode(modelReservation::selectAllDateByChambre($idChambre));
    }
}
?>
