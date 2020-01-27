<?php include FOOTBALL . "header.php";?>
<?php include FOOTBALL . "sidebar.php";?>
<div class="col">
    <h3 style="text-align:center; font-weight: bold;">The top 10 players</h3>
    <p><br></p>
    <table class="table">
        <tr>
            <th scope="col">Position</th>
            <th scope="col">Username</th>
            <th scope="col">Points</th>
            <th scope="col">Win rate</th>
        </tr>
        <tbody>
                <?php
                $count = 1;
                if ($this->model->get_leaderboard())
                {
                    foreach ($this->model->get_leaderboard() as $arr)
                    {
                        if ($count > 10) break;
                        $str = '<tr>' . '<th scope="row">' . $count . '</th>';
                        $str .= '<td>' . $arr["username"] . '</td>';
                        $str .= '<td>' . ($arr["times_won"] * 3) . '</td>';
                        $str .= '<td>' . (round($arr["win_ratio"], 2)) * 100 . "%" . '</td>' . '</tr>';
                        echo $str;
                        $count++;
                    }
                }
                ?>
        </tbody>
    </table>
</div>
 <?php include FOOTBALL . "footer.php";?>
