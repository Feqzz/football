<?php include FOOTBALL . "header.php";?>
<?php include FOOTBALL . "sidebar.php";?>
<?php
$bets_arr = $this->model->get_latest_bets($_SESSION["user_id"]);
$data_arr = $this->model->get_bets_data($bets_arr);
?>
<div class="col-6">
    <div class="col-9">
        <h3 style="text-align: center"><?php echo empty($bets_arr) ? "You haven't won/lost any bets yet" : "Your latest bets";?></h3>
        <b><br></b>
        <?php
        if(!empty($bets_arr))
        foreach($bets_arr as $arr) {
            $match_info_arr = $this->model->get_match_info($arr['match_id']);
            $home_team_arr = $this->model->get_team_info($match_info_arr['home_team_id']);
            $away_team_arr = $this->model->get_team_info($match_info_arr['away_team_id']);
        ?>
        <div class="card text-center <?php echo $arr['won'] ? "bg-success" : "bg-danger";?>" style="width: 24rem;">
        <div class="card-body">
            <h6 class="text-<?php echo $arr['won'] ? "muted" : "white";?> card-subtitle md-1">
                <div class="row">
                    <div class="col-md-2" style="margin: auto">
                        <img src="<?php echo $home_team_arr['crest_URL']; ?>" style="height: 70%; width: 70%;"/>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                        <?php
                            echo $match_info_arr['home_team_score'] . " : " . $match_info_arr['away_team_score'];
                        ?>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                        <img src="<?php echo $away_team_arr['crest_URL']; ?>" style ="height: 70%; width: 70%;"/>
                    </div>
                </div>
            </h6>
        </div>
        </div>
        <p><br></p>
    <?php };?>
    </div>
</div>
<div class="col-3">
    <h4>Points: <?php echo $data_arr['points']; ?> </h4>
    <h4>Win rate: <?php echo ($data_arr['win_ratio'] * 100); ?>%</h4>
</div>
<?php include FOOTBALL . "footer.php";?>
