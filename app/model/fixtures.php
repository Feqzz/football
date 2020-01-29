<?php
class fixtures extends model
{
    public function __construct()
    {
        parent::__construct();
        $this->league_id = isset($_SESSION['league']) ? $_SESSION['league']['league_id'] : 2021;
        $this->season = isset($_SESSION['league']) ? $_SESSION['league']['current_season'] : 468;
        $this->matchday = $this->current_matchday();
    }

    private function current_matchday()
    {
        $matchday = $this->db->get_current_matchday($this->league_id);
        return isset($matchday) ? $matchday : null;
    }

    public function get_fixtures()
    {
        $arr = array();
        $query =
            "
                SELECT matchday.match_day, matches.* FROM matches
                LEFT JOIN matchday ON match_day_id = matchday.id
                WHERE matchday.match_day = '$this->matchday' AND matchday.season = '$this->season'
                ORDER BY date, time
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr[] = array
            (
                "id" => "{$row['id']}",
                "date" => self::fix_date("{$row['date']}"),
                "time" => self::fix_time("{$row['time']}"),
                "home_team_id" => "{$row['home_team_id']}",
                "away_team_id" => "{$row['away_team_id']}",
                "status" => "{$row['status']}",
                "winner" => "{$row['winner']}",
                "home_team_score" => "{$row['home_team_score']}",
                "away_team_score" => "{$row['away_team_score']}",
            );
        }
        return $arr;
    }

    public function get_team_info($team_id)
    {
        return $this->db->get_team_info($team_id, $this->league_id);
    }

    public function get_user_bets($user_id)
    {
        $query =
            "
                SELECT m.match_day_id, user_bets.*
                FROM user_bets
                LEFT JOIN
                (
                    SELECT matchday.match_day, matchday.season, matches.* FROM matches
                    LEFT JOIN matchday ON match_day_id = matchday.id
                    WHERE matchday.season = '$this->season'
                )
                AS m ON match_id = m.id
                WHERE m.match_day = '$this->matchday' AND m.season = '$this->season'
                AND user_id = '$user_id'
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr[] = array
            (
                "id" => "{$row['id']}",
                "match_id" => "{$row['match_id']}",
                "bet" => "{$row['bet']}",
                "won" => "{$row['won']}",
            );
        }
        return isset($arr) ? $arr : null;
    }

    public function set_user_bet($user_id, $match_id, $bet, $bet_id)
    {
        ($bet_id) ? $this->db->update_user_bet($bet_id, $bet)
            : $this->db->set_user_bet($user_id, $match_id, $bet);
    }

    private function fix_date($str)
    {
        return date("l, j F", strtotime($str));
    }

    private function fix_time($str)
    {
        return date("H:i:s", strtotime("$str UTC"));
    }
    private $league_id;
    private $season;
    public $matchday;
}