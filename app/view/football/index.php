<?php include FOOTBALL . "header.php";?>
<?php
require_once(CORE . "database.php");
$db = new database();
$league_arr = $db->get_all_leagues();
?>
<div class = col-md-12>
    <h3 style="text-align: center">Choose a league</h3>
    <div class="row">
        <?php foreach ($league_arr as $arr) {?>
        <div class="col" style="text-align: center">
            <a href="#" style="text-decoration: none" onclick="choose_league(<?php echo $arr['league_id'];?>)">
                <div class="card border-0">
                    <div class="card-body">
                        <img src="/img/<?php echo $arr['league_id'];?>.svg" style="height:250px;width:250px;">
                        <h3 class="text-muted card-subtitle mb-2"><?php echo $arr['name'];?></h3>
                        <h6 class="text-muted card-subtitle mb-2"><?php echo substr($arr['start_date'],0,4) . " - " . substr($arr['end_date'],0,4);?></h6>
                    </div>
                </div>
            </a>
        </div>
        <?php }; ?>
    </div>
</div>

<?php include FOOTBALL . "footer.php";?>
