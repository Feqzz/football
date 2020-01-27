<?php
class choose_league extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_league_info($league_id)
    {
        $query =
            "
                SELECT * FROM leagues
                WHERE league_id = '$league_id'
            ";
        $resource = $this->db->resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr = array
            (
                "league_id" => "{$row['league_id']}",
                "name" => "{$row['name']}",
                "current_season" =>"{$row['current_season']}",
            );
        }
        return isset($arr) ? $arr : null;
    }
}