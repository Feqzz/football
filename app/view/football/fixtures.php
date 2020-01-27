<?php include FOOTBALL . "header.php";?>
<?php include FOOTBALL . "sidebar.php";?>
<div class="scrollableo">
<div class = col-md-12>
    <h3 style="text-align: center">Matchday <?php echo $matchday = $this->model->matchday;  ?></h3>
    <b><br></b>
    <?php
        $dates_arr = array();
        foreach($this->model->get_fixtures() as $arr) {
        $home_team_arr = $this->model->get_team_info($arr['home_team_id']);
        $away_team_arr = $this->model->get_team_info($arr['away_team_id']);
    ?>
    <h4 class="text-center">
        <?php if(!in_array($arr['date'], $dates_arr))
        {
            array_push($dates_arr,$arr['date']);
            echo $arr['date'];
        }  ?>
    </h4>
    <div class="card text-center" style="width: 48rem;">
        <div class="card-body">
            <h6 class="text-muted card-subtitle mb-2">
                <div class="row">
                    <div class="col-md-2" style="margin: auto">
                        <?php echo $home_team_arr['name'];?>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                        <img src="<?php echo $home_team_arr['crest_URL'] ?>" style="height: 70%; width: 70%;"/>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                        <?php
                        switch ($arr['status'])
                        {
                            case 0:
                                echo substr($arr['time'], 0, 5);
                                break;
                            case 1:
                                echo $arr['home_team_score'] . " : " . $arr['away_team_score'];
                                break;
                            case 2:
                                echo "Live";
                                break;
                        }
                        ?>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                        <img src="<?php echo $away_team_arr['crest_URL'] ?>" style ="height: 70%; width: 70%;"/>
                    </div>
                    <div class="col-md-2" style="margin: auto">
                    <?php echo $away_team_arr['name'];?>
                    </div>
                </div>
            </h6>
            <?php
            $button_titles = ["Home", "Tie", "Away"];
            for ($i = 0; $i < sizeof($button_titles); $i++)
            {
                $did_bet = false;
                $string = '<button class="btn ';
                if($this->model->get_user_bets($_SESSION["user_id"]))
                {
                    foreach ($user_bets = $this->model->get_user_bets($_SESSION["user_id"]) as $a)
                    {
                        if ($a['match_id'] == $arr['id']) {
                            $did_bet = true;
                            $won = boolval($a['won']);
                            $bet = intval($a['bet']);
                            $bet_id = intval($a['id']);
                            break;
                        }
                    }
                }
                if ($did_bet)
                {
                    if ($bet == $i)
                    {
                        if($arr['status'] == 1)
                            $string .= ($won) ? 'btn-success" ' : 'btn-danger"';
                        else
                            $string .= 'btn-info "';
                    }
                    else
                    {
                        $string .= 'btn-light"';
                    }
                }
                else
                {
                    $string .= 'btn-light"';
                }
                if ($arr['status'] > 0)  $string .= ' disabled';
                $string .= ' id="' . $arr['id'] . "bet=" . $i . '" ';
                $string .= " onclick='change_bet(" . $_SESSION["user_id"] . ", " . $arr['id'] . ", " . $i;
                $string .= ($did_bet) ? ", " . $bet_id : "";
                $string .= ")'". '>' . $button_titles[$i] . '</button>' . PHP_EOL;
                echo $string;
            }
            ?>
        </div>
    </div>
        <p><br></p>
    <?php };?>
</div>
</div>
<?php include FOOTBALL . "footer.php";?>