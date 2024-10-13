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

        $req = $this->bd->prepare("INSERT INTO Joueurs_creer (pseudo, ticket) VALUES (:pseudo, :ticket)");
        $req->bindValue(':pseudo', $pseudo);
        $req->bindValue(':ticket', $ticket);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function selectRandomJoueurs_pred($nombre)
    {
        $req = $this->bd->query("SELECT COUNT(*) as count FROM Joueurs_pred");
        $count = $req->fetch(PDO::FETCH_ASSOC)['count'];

        if ($count == 0) {
            $this->populateJoueurs_pred_lots();
        }

        $req = $this->bd->query("SELECT * FROM Joueurs_pred ORDER BY RANDOM() LIMIT $nombre");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function populateJoueurs_pred_lots()
    {
        try {
            $totalPlayers = 100;
            $batchSize = 10;
            $inserted = 0;

            while ($inserted < $totalPlayers) {
                $this->bd->beginTransaction();

                for ($j = 0; $j < $batchSize; $j++) {
                    $pseudo = $this->generateRandomPseudo();
                    $ticket = $this->generateRandomTicket();

                    $req = $this->bd->prepare("INSERT INTO Joueurs_pred (pseudo, ticket) VALUES (:pseudo, :ticket)");
                    $req->bindValue(':pseudo', $pseudo);
                    $req->bindValue(':ticket', $ticket);
                    $req->execute();

                    $req = null;  // Libère la mémoire allouée à la requête
                }

                $this->bd->commit();
                $inserted += $batchSize;

                gc_collect_cycles(); // Libère les caches entre chaque lot
            }
        } catch (PDOException $e) {
            $this->bd->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function selectAllJoueurs_creer()
    {
        $req = $this->bd->query("SELECT * FROM Joueurs_creer ORDER BY pseudo");
        return $req->fetchAll(PDO::FETCH_ASSOC);
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


    public function deleteAllJoueursEnCours()
    {
        $req = $this->bd->prepare("DELETE FROM Joueurs_en_cours");
        $req->execute();
        return (bool)$req->rowCount();
    }

    private function generateRandomPseudo()
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $pseudo = '';

        $letterLength = rand(3, 13);
        for ($i = 0; $i < $letterLength; $i++) {
            $pseudo .= $letters[rand(0, strlen($letters) - 1)];
        }

        $numLength = rand(0, 2);
        for ($i = 0; $i < $numLength; $i++) {
            $pseudo .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        return $pseudo;
    }

    private function generateRandomTicket()
    {
        $numbers = range(1, 49);
        shuffle($numbers);
        $ticketNumbers = array_slice($numbers, 0, 5);
        sort($ticketNumbers);

        $stars = range(1, 9);
        shuffle($stars);
        $ticketStars = array_slice($stars, 0, 2);
        sort($ticketStars);

        return implode('-', $ticketNumbers) . ' | ' . implode('-', $ticketStars);
    }

    public function insertJoueurEnCours($id_joueur, $id_partie, $ticket)
    {
        $req = $this->bd->prepare("INSERT INTO Joueurs_en_cours (id_joueur, id_partie, ticket) VALUES (:id_joueur, :id_partie, :ticket)");
        $req->bindValue(':id_joueur', $id_joueur);
        $req->bindValue(':id_partie', $id_partie);
        $req->bindValue(':ticket', $ticket);
        $req->execute();
    }

    public function selectAllJoueursEnCours()
    {
        $req = $this->bd->query("SELECT * FROM Joueurs_en_cours ORDER BY id_joueur");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

}
