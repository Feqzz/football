<?php
class data_handler
{
    private function curl($url)
    {
        $headers = array();
        $headers[] = "X-Auth-Token: ";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function parse_league_data($league_id)
    {
        $file = DATA . $league_id . "_league_data.json";
        if (file_exists($file))
        {
            $string = file_get_contents($file);
            $json = json_decode($string, true);
        }
        else
        {
            $json = self::get_data_from_api($league_id, null, false);
        }

        $league_id = $json['id'];
        $name = $json['name'];
        $current_season = $json['currentSeason']['id'];
        $start_date = $json['currentSeason']['startDate'];
        $end_date = $json['currentSeason']['endDate'];

        return [$league_id, $name, $current_season, $start_date, $end_date];
    }

    public function parse_matchday_data($league_id)
    {
        $arr = array();
        $counter = 0;
        $json = self::get_data_from_api($league_id, "matches", false);
        foreach($json['matches'] as $json)
        {
            $match_day_id = $json['matchday'];
            if ($counter < $match_day_id)
            {
                $season = $json['season']['id'];
                $date = substr($json['utcDate'], 0, 10);
                $arr[] = [$season, $match_day_id, $date];
                $counter++;
            }
        }
        return $arr;
    }

    public function parse_matches_data($league_id, $match_day_id)
    {
        $arr = array();
        $argument = isset($match_day_id) ? ("matches?matchday=" . $match_day_id) : "matches";
        $json = self::get_data_from_api($league_id, $argument, false);
        foreach($json['matches'] as $json)
        {
            $id = $json['id'];
            $season = $json['season']['id'];
            $match_day = $json['matchday'];
            $date = substr($json['utcDate'], 0, 10);
            $time = substr($json['utcDate'], 11, 8);
            $home_team_id = $json['homeTeam']['id'];
            $away_team_id = $json['awayTeam']['id'];
            $status = ($json['status'] == "FINISHED") ? 1 : 0;
            if ($json['score']['winner'] && $status)
            {
                $winner = ($json['score']['winner'] == "HOME_TEAM") ? $home_team_id : $away_team_id;
            }
            else
            {
                $winner = null;
            }
            $home_team_score = $json['score']['fullTime']['homeTeam'];
            $away_team_score = $json['score']['fullTime']['awayTeam'];
            $arr[] = [$id, $season, $match_day, $date, $time, $home_team_id, $away_team_id,
                $status, $winner, $home_team_score, $away_team_score];
        }
        return $arr;
    }

    public function parse_teams_data($league_id)
    {
        $arr = array();
        $json = self::get_data_from_api($league_id, "teams", false);
        foreach($json['teams'] as $json)
        {
            $team_id = $json['id'];
            $name = $json['name'];
            $short_name = $json['shortName'];
            $tla = $json['tla'];
            $crest_URL = $json['crestUrl'];
            $venue = $json['venue'];
            $arr[] = [$league_id, $team_id, $name, $short_name, $tla, $crest_URL, $venue];
        }
        return $arr;
    }

    public function get_data_from_api($league_id, $argument, $save_file)
    {
        $output_file = DATA . $league_id . "_season_" . $argument . ".json";
        $url = "http://api.football-data.org/v2/competitions/". $league_id;
        if ($argument) $url .= "/" . $argument;
        $result = $this->curl($url);
        if($save_file)
        {
            file_put_contents($output_file, $result, FILE_APPEND);
            return null;
        }
        else
        {
            return json_decode($result, true);
        }
    }
}