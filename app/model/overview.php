<?php
class overview extends model
{
    public function __construct()
    {
        parent::__construct();
        $this->league_id = isset($_SESSION['league']) ? $_SESSION['league']['league_id'] : 2021;
        $this->season = isset($_SESSION['league']) ? $_SESSION['league']['current_season'] : 468;
    }

    public function get_latest_bets($user_id)
    {
        $query =
            "
                SELECT user_bets.* FROM user_bets
                LEFT JOIN matches ON matches.id = match_id
                WHERE matches.season = '$this->season'
                AND is_done = true
                AND user_id = '$user_id'
                ORDER BY matches.date ASC, matches.time DESC
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr[] = array
            (
                'match_id' => "{$row['match_id']}",
                'won' => "{$row['won']}",
            );
        }
        return isset($arr) ? $arr : null;
    }

    public function get_bets_data($bets)
    {
        if (empty($bets))
        {
            $arr = array
            (
                'points' => 0,
                'win_ratio'=> 0
            );
        }
        else
        {
            $win_counter = 0;
            foreach ($bets as $a) {
                if ($a['won'])
                    $win_counter++;
            }
            $arr = array
            (
                'points' => ($win_counter * 3),
                'win_ratio' => round(($win_counter / sizeof($bets)), 2)
            );
        }
        return $arr;
    }

    public function get_match_info($match_id)
    {
        $query =
            "
                SELECT * FROM matches
                WHERE id = '$match_id'
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr = array
            (
                'home_team_id' => "{$row['home_team_id']}",
                'away_team_id' => "{$row['away_team_id']}",
                'home_team_score' => "{$row['home_team_score']}",
                'away_team_score' => "{$row['away_team_score']}"
            );
        }
        return isset($arr) ? $arr : null;
    }

    public function get_team_info($match_id)
    {
        return $this->db->get_team_info($match_id, $this->league_id);
    }
    private $league_id;
}