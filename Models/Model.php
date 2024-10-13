<?php
class Model
{
    private $bd;
    private static $instance = null;

    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET NAMES 'utf8'");
    }

    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isDuplicate($pseudo, $ticket, $excludeId = null)
    {
        $query = "SELECT COUNT(*) FROM Joueurs_creer WHERE (pseudo = :pseudo OR ticket = :ticket)";
        if ($excludeId !== null) {
            $query .= " AND id_joueur != :excludeId";
        }
        $req = $this->bd->prepare($query);
        $req->bindValue(':pseudo', $pseudo);
        $req->bindValue(':ticket', $ticket);
        if ($excludeId !== null) {
            $req->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function insertJoueurs_creer($pseudo, $ticket)
    {
        if ($this->isDuplicate($pseudo, $ticket)) {
            return false;
        }
        $this->bd->beginTransaction();
        $req = $this->bd->prepare("INSERT INTO Joueurs_creer (pseudo, ticket) VALUES (:pseudo, :ticket)");
        $req->bindValue(':pseudo', $pseudo);
        $req->bindValue(':ticket', $ticket);
        $success = $req->execute();
        $this->bd->commit();
        return $success;
    }

    public function selectRandomJoueurs_pred($nombre)
    {
        $req = $this->bd->query("SELECT COUNT(*) as count FROM Joueurs_pred");
        $count = $req->fetch(PDO::FETCH_ASSOC)['count'];

        if ($count == 0) {
            $this->populateJoueurs_pred_sql();
        }

        $req = $this->bd->query("SELECT * FROM Joueurs_pred ORDER BY RANDOM() LIMIT $nombre");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function populateJoueurs_pred_sql()
    {
        $sql = "
            INSERT INTO Joueurs_pred (pseudo, ticket)
            SELECT 
                'Joueur_' || g.id || 
                substr('abcdefghijklmnopqrstuvwxyz', floor(random() * 26)::int + 1, 1) || 
                substr('abcdefghijklmnopqrstuvwxyz', floor(random() * 26)::int + 1, 1),
                array_to_string(ARRAY(
                    SELECT (floor(random() * 49) + 1)::int 
                    FROM generate_series(1, 5)
                ), '-') || ' | ' || array_to_string(ARRAY(
                    SELECT (floor(random() * 9) + 1)::int 
                    FROM generate_series(1, 2)
                ), '-')
            FROM generate_series(1, 100) AS g(id)
        ";
        try {
            $this->bd->beginTransaction();
            $this->bd->exec($sql);
            $this->bd->commit();
        } catch (PDOException $e) {
            $this->bd->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function selectAllJoueurs_creer($limit = 100)
    {
        $req = $this->bd->query("SELECT * FROM Joueurs_creer ORDER BY pseudo LIMIT $limit");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTicketJoueurByPseudo($pseudo, $ticket)
    {
        $req = $this->bd->prepare("UPDATE Joueurs SET ticket = :ticket WHERE pseudo = :pseudo");
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':pseudo', $pseudo);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function insertJoueurEnCours($id_joueur, $id_partie, $ticket, $gains = 0.00)
    {
        $this->bd->beginTransaction();
        $req = $this->bd->prepare("INSERT INTO Joueurs_en_cours (id_joueur, id_partie, ticket, gains) VALUES (:id_joueur, :id_partie, :ticket, :gains)");
        $req->bindValue(':id_joueur', $id_joueur);
        $req->bindValue(':id_partie', $id_partie);
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':gains', $gains);
        $success = $req->execute();
        $this->bd->commit();
        return $success;
    }

    public function deleteAllJoueursEnCours()
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs_en_cours");
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function deleteJoueurs_creer($id_joueur)
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs_creer WHERE id_joueur = :id_joueur");
        $req->bindValue(':id_joueur', $id_joueur, PDO::PARAM_INT);
        $req->execute();
    }

    public function updateJoueurs_creer($id_joueur, $pseudo, $ticket)
    {
        if ($this->isDuplicate($pseudo, $ticket, $id_joueur)) {
            return false;
        }
        $req = $this->bd->prepare("UPDATE Joueurs_creer SET pseudo = :pseudo, ticket = :ticket WHERE id_joueur = :id_joueur");
        $req->bindValue(':pseudo', $pseudo);
        $req->bindValue(':ticket', $ticket);
        $req->bindValue(':id_joueur', $id_joueur, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }
}
?>
