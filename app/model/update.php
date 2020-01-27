<?php
require_once(CORE . "model.php");

class update extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_league($league_id)
    {
        $a = $this->dh->parse_league_data($league_id);
        $this->db->add_leagues_record($a[0],$a[1],$a[2],$a[3], $a[4]);
    }

    public function make_tables()
    {
        $this->db->make_tables();
    }

    public function add_matchday($league_id)
    {
        $a = $this->dh->parse_matchday_data($league_id);
        foreach($a as $b)
        {
            $this->db->add_matchday_record($b[0],$b[1],$b[2]);
        }
    }

    public function add_matches($league_id)
    {
        $a = $this->dh->parse_matches_data($league_id, null);
        foreach($a as $b)
        {
            $this->db->add_matches_record($b[0],$b[1],$b[2],$b[3],
                $b[4],$b[5],$b[6],$b[7],$b[8], $b[9], $b[10]);
        }
    }

    public function add_teams($league_id)
    {
        $a = $this->dh->parse_teams_data($league_id);
        foreach($a as $b)
        {
            $this->db->add_teams_record($b[0],$b[1],$b[2],$b[3],
                $b[4],$b[5],$b[6]);
        }
    }

    public function update_matches($league_id, $match_day_id, $time_info)
    {
        $is_done = true;
        $a = $this->dh->parse_matches_data($league_id, $match_day_id);
        foreach($a as $b)
        {
            if ((substr($time_info['date'], 5, 5) <= substr($b[3], 5, 5)))
            {
                if ((substr($time_info['time'], 0, 2) >= substr($b[4], 0, 2)) &&
                    (substr($time_info['date'], 8, 2) == substr($b[3], 8, 2)))
                {
                    $status = $b[7] ? 1 : 2;
                    if ($status == 2) $is_done = false;
                }
                else
                {
                    $is_done = false;
                    $status = 0;
                }
                $this->db->update_matches($status, $b[8], $b[9], $b[10], $b[0]);
                self::update_user_bets($b[0], $b[9], $b[10], $status);
            }
        }
        return $is_done;
    }

    public function update_matchday($league_id, $match_day_id, $time_info)
    {
        $is_done = self::update_matches($league_id, $match_day_id, $time_info);
        $this->db->update_matchday_is_done($league_id, $is_done, $match_day_id);
    }

    public function update_current_matchday($league_id, $time_info)
    {
        $match_day_id = $this->db->get_current_matchday($league_id);
        self::update_matchday($league_id, $match_day_id, $time_info);

    }

    public function update_user_bets($match_id, $home_team_score, $away_team_score, $status)
    {
        if ($status == 1)
        {
            $result = ($home_team_score == $away_team_score) ? 1
                : (($home_team_score > $away_team_score) ? 0 : 2);

            $query =
                "
                SELECT * FROM user_bets WHERE match_id = '$match_id'
            ";

            $resource = $this->db->resource($query);
            while ($row = $resource->fetch_assoc()) {
                $id = "{$row['id']}";
                $bet = "{$row['bet']}";
                $update_query = "UPDATE user_bets SET won = ";
                $update_query .= ($bet == $result) ? "true" : "false";
                $update_query .= ", is_done = true ";
                $update_query .= "WHERE id = " . $id;
                $this->db->custom_query($update_query);
            }
        }
    }
}