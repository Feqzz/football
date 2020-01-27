<?php
class leaderboard extends model
{
    public function __construct()
    {
        parent::__construct();
        $this->league_id = isset($_SESSION['league']) ? $_SESSION['league']['league_id'] : 2021;
        $this->season = isset($_SESSION['league']) ? $_SESSION['league']['current_season'] : 468;
    }

    public function get_leaderboard()
    {
        $query =
            "
                SELECT m.season, user_id, COALESCE(SUM(won),0) AS times_won,
                       COALESCE(SUM(won) / COUNT(NULLIF(is_done, '')), 0) AS win_ratio
                FROM user_bets
                LEFT JOIN
                (
                    SELECT l.league_id, season, id FROM matches
                    LEFT JOIN
                    (
                        SELECT current_season, league_id FROM leagues
                    ) AS l ON l.current_season = season
                ) AS m ON m.id = match_id
                WHERE m.league_id = '$this->league_id'
                GROUP BY user_id
                LIMIT BY 10
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr[] = array
            (
                "username" => self::get_username("{$row['user_id']}"),
                "times_won" => "{$row['times_won']}",
                "win_ratio" => "{$row['win_ratio']}",
            );
        }
        return isset($arr) ? $arr : null;
    }

    private function get_username($user_id)
    {
        $query = "SELECT username FROM user WHERE id = '$user_id'";
        $resource = $this->db->resource($query);
        return "{$resource->fetch_assoc()['username']}";
    }
    private $league_id;
    private $season;
}