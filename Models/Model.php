<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET NAMES 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Insère un joueur dans la table Joueurs.
     * @param string $pseudo
     * @param string|null $ticket
     * @return bool
     */
    public function insertJoueur($pseudo, $ticket)
    {
        $req = $this->bd->prepare("INSERT INTO Joueurs (pseudo, ticket) VALUES (:pseudo, :ticket)");
        $req->bindValue(':pseudo', $pseudo);
        $req->bindValue(':ticket', $ticket);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Sélectionne aléatoirement un nombre donné de joueurs.
     * @param int $nombre
     * @return array
     */
    public function selectRandomJoueurs($nombre)
    {
        $req = $this->bd->query("SELECT * FROM Joueurs ORDER BY RANDOM() LIMIT $nombre");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sélectionne un joueur en fonction de son pseudo.
     * @param string $pseudo
     * @return array|null
     */
    public function selectJoueurByPseudo($pseudo)
    {
        $req = $this->bd->prepare("SELECT * FROM Joueurs WHERE pseudo = :pseudo");
        $req->bindValue(':pseudo', $pseudo);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Sélectionne tous les joueurs de la table Joueurs, ordonnés par pseudo.
     * @return array
     */
    public function selectAllJoueurs()
    {
        $req = $this->bd->query("SELECT * FROM Joueurs ORDER BY pseudo");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le ticket d'un joueur en fonction de son pseudo.
     * @param string $pseudo
     * @param string $ticket
     * @return bool
     */
    public function updateTicketJoueurByPseudo($pseudo, $ticket)
    {
        $req = $this->bd->prepare("UPDATE Joueurs SET ticket = :ticket WHERE pseudo = :pseudo");
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':pseudo', $pseudo);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Insère un joueur en cours dans la table Joueurs_en_cours.
     * @param int $id_joueur
     * @param int $id_partie
     * @param string $ticket
     * @param float $gains
     * @return bool
     */
    public function insertJoueurEnCours($id_joueur, $id_partie, $ticket, $gains = 0.00)
    {
        $req = $this->bd->prepare("INSERT INTO Joueurs_en_cours (id_joueur, id_partie, ticket, gains) VALUES (:id_joueur, :id_partie, :ticket, :gains)");
        $req->bindValue(':id_joueur', $id_joueur);
        $req->bindValue(':id_partie', $id_partie);
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':gains', $gains);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Supprime toutes les lignes de la table Joueurs_en_cours.
     * @return bool
     */
    public function deleteAllJoueursEnCours()
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs_en_cours");
        $req->execute();
        return (bool)$req->rowCount();
    }

        public function deleteJoueur($id_joueur)
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs WHERE id_joueur = :id_joueur");
        $req->bindValue(':id_joueur', $id_joueur, PDO::PARAM_INT);
        $req->execute();
    }

}

?>
