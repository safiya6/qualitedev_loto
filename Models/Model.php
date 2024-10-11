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
     * Ajoute un joueur dans la table Joueurs.
     * @param int $id_joueur
     * @param string $pseudo
     * @param string|null $ticket
     * @return bool
     */
    public function addJoueur($id_joueur, $pseudo, $ticket = null)
    {
        $req = $this->bd->prepare("INSERT INTO Joueurs (id_joueur, pseudo, ticket) VALUES (:id_joueur, :pseudo, :ticket)");
        $req->bindValue(':id_joueur', $id_joueur);
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
    public function getJoueurByPseudo($pseudo)
    {
        $req = $this->bd->prepare("SELECT * FROM Joueurs WHERE pseudo = :pseudo");
        $req->bindValue(':pseudo', $pseudo);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le ticket d'un joueur en fonction de son pseudo.
     * @param string $pseudo
     * @param string $ticket
     * @return bool
     */
    public function updateTicketByPseudo($pseudo, $ticket)
    {
        $req = $this->bd->prepare("UPDATE Joueurs SET ticket = :ticket WHERE pseudo = :pseudo");
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':pseudo', $pseudo);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Ajoute un joueur en cours dans la table Joueurs_en_cours.
     * @param int $id_joueur
     * @param int $id_partie
     * @param string $ticket
     * @param float $gains
     * @return bool
     */
    public function addJoueurEnCours($id_joueur, $id_partie, $ticket, $gains = 0.00)
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
     * Vide la table Joueurs_en_cours.
     * @return bool
     */
    public function clearJoueursEnCours()
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs_en_cours");
        $req->execute();
        return (bool)$req->rowCount();
    }
}

?>
