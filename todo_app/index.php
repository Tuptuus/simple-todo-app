<?php
    session_start();
    include_once "database.php";

    $q = "select * from quests";
    $result = mysqli_query($conn, $q) or die ("query error");
    $alert = "<div class='contPopout'>
            <div class='popout'>
                Czy na pewno usunąć zadanie? <br>
                <a href='index.php?alert=yes'><input type='submit' value='TAK' class='butPopout'></a> 
                <a href='index.php?alert=no'><input type='submit' value='NIE' class='butPopout'></a>
            </div>
            </div>";

    if(isset($_GET['op']))
    {
        $operation = $_GET['op'];
        if($operation == "delete")
        {
            $deleteID = $_GET['id'];
            $_SESSION['deleteID'] = $deleteID;
            echo($alert);
        }

        if($operation == 'edit')
        {
            $edit_id = $_GET['id'];
            $eResult = "select * from quests where quest_id='$edit_id'";
            $edited_row = mysqli_query($conn, $eResult) or die ("error 1 edit");
            $resultEdit = mysqli_fetch_assoc($edited_row);
        }

            
        if($operation == 'confirm')
        {
            $idC = $_GET['id'];
            $qC = "select * from quests where quest_id='$idC'";
            $resultC = mysqli_query($conn, $qC);
            $confirm_row = mysqli_fetch_assoc($resultC);

            if($confirm_row['confirmed'] == 0)
            {
                $qC = "update quests set confirmed=1 where quest_id='$idC'" or die('conf error');
            }
            else
            {
                $qC = "update quests set confirmed=0 where quest_id='$idC'";
            }
            
            $result = mysqli_query($conn, $qC);
            header('location:index.php');
        }
    }

    if(isset($_GET['alert']))
    {
        $alert = $_GET['alert'];
        if($alert == 'yes')
        {
            $deleteID = $_SESSION['deleteID'];
            $qD = "delete from quests where quest_id=$deleteID" or die ('query error');
            $resultDelete = mysqli_query($conn, $qD) or die('result error');
            header('location:index.php');
        }
        else
        {
            header('location:index.php');
        }
    }
    

    if(isset($_POST['op']))
    {
        $operation = $_POST['op'];
        $inQuest = $_POST['inQuest'];

        if($operation == 'Create')
        {
            if($inQuest == '')
            {
                $error = "This label cannot be empty";
            }
            else
            {
                $qInsert = "insert into quests value(null, '$inQuest', 0)";
                $resultInsert = mysqli_query($conn, $qInsert);
                header('location:index.php');
            }
        }

        $editaction = $_POST['eAction'];
        if($editaction == 'Update')
        {
            if($inQuest == '')
            {
                $error = "This label cannot be empty";
            }
            else
            {
                $todo = $_POST['inQuest'];
                $id_edit=$_POST['id'];
                $qUpdate = "update quests set quest='$todo' where quest_id='$id_edit'" or die("query error");
                $resultUpdate = mysqli_query($conn, $qUpdate) or die("update error");
                header('location:index.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quests</title>
    <link rel="stylesheet" href="index_style2.css">
    <link rel="stylesheet" href="css/fontello.css">
</head>
<body>
    <div id="contener">
        <div id="todo">
            <form id="formInput" action="" method="post">
                <input id="inputQuest" placeholder="Wpisz zadanie...." name="inQuest" type="text" value="<?php  if(isset($operation) && $operation=='edit') {echo($resultEdit['quest']);}  ?>">
                <input type="hidden" name="op" value=<?php if(isset($operation) && $operation == 'edit') {echo"Update";} else {echo"Create";} ?>>
                <input type="hidden" name="id" value=<?php if(isset($operation) && $operation == 'edit') {echo($resultEdit['quest_id']);} ?>>
                <input type="submit" name="eAction" id="butQuest" value=<?php if(isset($operation) && $operation == 'edit') {echo("Update");} else {echo('Create');} ?>>
                <p id="alert">
                    <?php
                        if(isset($error))
                        {
                            echo($error);
                        }
                    ?>
                </p>
            </form>
            <ul class="todo_list">
                <?php
                    $isConfirmed = "";
                    while($row = mysqli_fetch_assoc($result))
                    {
                        if($row['confirmed'] == 1)
                        {
                            $isConfirmed = "isConfirmed";
                        }
                        else
                        {
                            $isConfirmed = "";
                        }
                        echo "<li class='list'>
                        <p class='text " .$isConfirmed. "'>" .$row['quest']. "</p>
                            <div class='actions'>
                                <a href='index.php?id=".$row['quest_id']."&op=confirm'><i class='demo-icon icon-ok'></i></a>
                                <a href='index.php?id=".$row['quest_id']."&op=edit'><i class='demo-icon icon-pencil'></i></a>
                                <a href='index.php?id=".$row['quest_id']."&op=delete'><i class='demo-icon icon-trash'></i></a>
                            </div>
                        </li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>