<?php
include_once "./infoServer.php";
if (isset($_GET["types"])) {
    $connect = new PDO("mysql:host=" . $host . ";dbname=" . $dbName, $userName, $passWord);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tab = json_decode($_GET["types"]);
    $result = array();
    if (!$tab) {
        $req = $connect->query("SELECT profile.username,profile.photo as
        profilePhoto,p.titre,p.text,p.dateCreate,p.idPost,p.objectif,p.imagePost,g.type as generalType,s.type as specificType
        from post p , typegeneral g , typespecifique s , profile WHERE profile.idProfile=p.idProfile AND
        p.idTypeGeneral=g.idTypeGeneral AND p.idTypeSpecifique=s.idTypeSpecifique order by p.dateCreate
        desc");
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $stmt = $connect->query("SELECT decision from decision where idProfile='$_GET[idProfile]' and idPost='$data[idPost]'");
            $stmt2 = $connect->query("SELECT count(*) from decision where idPost='$data[idPost]'");
            $nbrDesc = $stmt2->fetchColumn();
            $decision = $stmt->fetchColumn();
            if ($stmt->rowCount()) $data["decision"] = $decision;
            else $data["decision"] = "";
            if ($stmt2->rowCount()) $data["nrbDecision"] = $nbrDesc;
            else $data["nbrDecision"] = "";
            $result[] = $data;
        }
    } else {
        for ($i = 0; $i < count($tab); $i++) {
            $req = $connect->query("SELECT idTypeSpecifique FROM typespecifique WHERE type='$tab[$i]'");
            $idtypespecifique = $req->fetchColumn();
            $req = $connect->prepare("SELECT profile.username,profile.photo as
        profilePhoto,p.titre,p.text,p.dateCreate,p.idPost,p.objectif,p.imagePost,g.type as generalType,s.type as specificType
        from post p , typegeneral g , typespecifique s , profile WHERE profile.idProfile=p.idProfile AND
        p.idTypeGeneral=g.idTypeGeneral AND p.idTypeSpecifique=s.idTypeSpecifique AND p.idTypeSpecifique=? order by p.dateCreate
        desc");
            $req->execute(array($idtypespecifique));
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $stmt = $connect->query("SELECT decision from decision where idProfile='$_GET[idProfile]' and idPost='$data[idPost]'");
                $stmt2 = $connect->query("SELECT count(*) from decision where idPost='$data[idPost]'");
                $nbrDesc = $stmt2->fetchColumn();
                $decision = $stmt->fetchColumn();
                if ($stmt->rowCount()) $data["decision"] = $decision;
                else $data["decision"] = "";
                if ($stmt2->rowCount()) $data["nrbDecision"] = $nbrDesc;
                else $data["nbrDecision"] = "";
                $result[] = $data;
            }
        }
    }
    print_r(json_encode($result));
}