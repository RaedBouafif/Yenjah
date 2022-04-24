<?php
include_once "./infoServer.php";
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $dbName, $userName, $passWord);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //if(isset($_GET["types"])){
        //$tab = json_decode($_GET["types"]);
        $tab = array("souris","clavier");
        $data = array();
        for ($i=0 ; $i < count($tab) ; $i++){
            $req= $conn->query("SELECT idTypeSpecifique FROM typespecifique WHERE type=$tab[$i]");
            $idtypespecifique= $req->fetchColumn();
            print_r($idtypespecifique);
            $stmt1 = $conn->prepare("SELECT profile.username,profile.photo as
            profilePhoto,p.titre,p.text,p.dateCreate,p.idPost,p.objectif,p.imagePost,g.type as generalType,s.type as specificType
            from post p , typegeneral g , typespecifique s , profile WHERE profile.idProfile=p.idProfile AND
            p.idTypeGeneral=g.idTypeGeneral AND p.idTypeSpecifique=s.idTypeSpecifique AND p.idTypeSpecifique=? order by p.dateCreate
            desc");
            $stmt1->execute(array(
                $idtypespecifique
            ));
            $result = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            if (isset($_GET["idProfile"])) {
                if ($stmt1->rowCount()){
                    foreach ($result as $key => $value){
                        $stmt2=$conn->prepare("SELECT decision from decision where idProfile=? and idPost=?");
                        $stmt2->execute(array(
                            $_GET["idProfile"],
                            $value["idPost"]
                        ));
                        $decision=$stmt2->fetchColumn();
                        if ($decision){
                            $result[$key]["decision"]=$decision;
                        }else{
                            $result[$key]["decision"]="";
                        }
                    }
                }
            }
            array_push($data, $result);
        }
        
        print_r(json_encode($data));
    //}
?>
