<?php
class database
{
    public function __construct()
    {
        $this->link = new mysqli("","",
            "","");
    }

    public function __destruct()
    {
        mysqli_close($this->link);
    }

    public function custom_query($query)
    {
        mysqli_query($this->link,$query);
    }

    public function resource($query)
    {
        return $this->link->query($query);
    }

    public function link()
    {
        return $this->link;
    }

    public function make_tables()
    {
        $table_queries = array
        (
            0 =>    "
                        CREATE TABLE IF NOT EXISTS leagues(
                        league_id INT PRIMARY KEY,
                        name VARCHAR(30),
                        current_season INT(10),
                        start_date VARCHAR(30),
                        end_date VARCHAR(30),
                        UNIQUE KEY (current_season)
                        )
                    ",
            1 =>    "
                        CREATE TABLE IF NOT EXISTS teams(
                        team_id INT PRIMARY KEY,
                        league_id INT,
                        name VARCHAR(30),
                        short_name VARCHAR(30),
                        tla VARCHAR(10),
                        crest_URL VARCHAR(256),
                        venue VARCHAR(30),
                        FOREIGN KEY (league_id) REFERENCES leagues(league_id)
                        )
                    ",
            2 =>    "
                        CREATE TABLE IF NOT EXISTS matchday(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        season INT(10),
                        match_day INT,
                        start_date VARCHAR(30),
                        is_done TINYINT,
                        UNIQUE KEY (season, match_day),
                        FOREIGN KEY (season) REFERENCES leagues(current_season)             
                        )
                    ",
            3 =>    "
                        CREATE TABLE IF NOT EXISTS matches(
                        id INT PRIMARY KEY,
                        match_day_id INT,
                        season INT,
                        date VARCHAR(30),
                        time VARCHAR(30),
                        home_team_id INT,
                        away_team_id INT,
                        status INT,
                        winner INT,
                        home_team_score INT,
                        away_team_score INT,
                        FOREIGN KEY (season) REFERENCES leagues(current_season),
                        FOREIGN KEY (match_day_id) REFERENCES matchday(id),
                        FOREIGN KEY (home_team_id) REFERENCES teams(team_id),
                        FOREIGN KEY (away_team_id) REFERENCES teams(team_id),
                        FOREIGN KEY (winner) REFERENCES teams(team_id)
                        )
                    ",
            4 =>    "
                        CREATE TABLE IF NOT EXISTS user(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(50) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                        )
                    ",
            5 =>    "
                        CREATE TABLE IF NOT EXISTS user_bets(
                        id INT AUTO_INCREMENT PRIMARY KEY, 
                        user_id INT,
                        match_id INT,
                        bet INT,
                        is_done TINYINT,
                        won TINYINT,
                        UNIQUE KEY (user_id, match_id),
                        FOREIGN KEY (user_id) REFERENCES user(id),
                        FOREIGN KEY (match_id) REFERENCES matches(id)
                        )
                    ",
        );
        for ($i = 0; $i < sizeof($table_queries); $i++)
        {
            self::custom_query($table_queries[$i]);
        }
    }

    public function add_leagues_record($a, $b, $c, $d, $e)
    {
        $query =
            "
                INSERT IGNORE INTO leagues (league_id, name, current_season, start_date, end_date)
                VALUES ('$a','$b','$c','$d', '$e')
            ";
        self::custom_query($query);
    }

    public function add_matchday_record($a, $b, $c)
    {
        $query =
            "
                INSERT IGNORE INTO matchday (season, match_day, start_date) 
                VALUES ('$a','$b','$c')
            ";
        self::custom_query($query);
    }

    public function add_matches_record($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k)
    {
        $match_day_id = self::get_matchday_id($b, $c);
        $query =
            "
                INSERT IGNORE INTO matches (id, match_day_id, season, date, time, home_team_id,
                                            away_team_id, status, winner,
                                            home_team_score, away_team_score) 
                VALUES ('$a','$match_day_id','$b','$d','$e','$f','$g', '$h', NULLIF('$i',''),NULLIF('$j',''),NULLIF('$k',''))                
            ";
        self::custom_query($query);
    }

    public function add_teams_record($a, $b, $c, $d, $e, $f, $g)
    {
        $query =
            "
                INSERT IGNORE INTO teams (league_id, team_id, name, short_name, tla, crest_URL, venue)
                VALUES ('$a','$b','$c','$d','$e','$f','$g')
            ";
        self::custom_query($query);
    }

    public function update_matches($a, $b, $c, $d, $id)
    {
        $query =
            "
                UPDATE matches
                SET
                    status = '$a',
                    winner = NULLIF('$b',''),
                    home_team_score = '$c',
                    away_team_score = '$d'
                WHERE
                    id = '$id'
            ";
        self::custom_query($query);
    }

    public function update_matchday_is_done($league_id, $is_done, $match_day_id)
    {
        $season = self::get_season($league_id);
        $query =
            "
                UPDATE matchday
                SET
                    is_done = '$is_done'
                WHERE 
                    match_day = '$match_day_id'
                AND
                    season = '$season'
            ";
        self::custom_query($query);
    }

    public function update_user_bet($id, $bet)
    {
        $query =
            "
            UPDATE user_bets
            SET
                bet = '$bet'
            WHERE
                id = '$id'
        ";
        self::custom_query($query);
    }

    public function set_user_bet($a, $b, $c)
    {
        $query =
            "
                INSERT IGNORE INTO user_bets (user_id, match_id, bet, is_done)
                VALUES ('$a','$b','$c', false)
            ";
        self::custom_query($query);
    }

    public function get_current_matchday($league_id)
    {
        $season = self::get_season($league_id);
        $query =
            "
                SELECT * FROM matchday
                WHERE season = '$season'
                AND (is_done = false)
                ORDER BY match_day ASC LIMIT 1
                
            ";
        $resource = self::resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $matchday = "{$row['match_day']}";
        }
        return isset($matchday) ? $matchday : null;
    }

    public function get_team_info($team_id, $league_id)
    {
        $query =
            "
                SELECT * FROM teams WHERE (team_id = '$team_id')
                AND (league_id = '$league_id')
            ";
        $resource = self::resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr = array
            (
                "name" => "{$row['name']}",
                "tla" => "{$row['tla']}",
                "crest_URL" => "{$row['crest_URL']}",
            );
        }
        return isset($arr) ? $arr : null;
    }

    public function get_all_leagues()
    {
        $query =
            "
                SELECT * FROM leagues
            ";
        $resource = self::resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $arr[] = array
            (
                "league_id" => "{$row['league_id']}",
                "name" => "{$row['name']}",
                "start_date" => "{$row['start_date']}",
                "end_date" => "{$row['end_date']}"
            );
        }
        return isset($arr) ? $arr : null;
    }

    public function get_matchday_id($season, $matchday)
    {
        $query =
            "
                SELECT * FROM matchday WHERE season = '$season' AND match_day = '$matchday'
            ";
        $resource = self::resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $rv = "{$row['id']}";
        }
        return isset($rv) ? $rv : null;
    }

    public function get_season($league_id)
    {
        $query =
            "
                SELECT * FROM leagues WHERE league_id = '$league_id'
            ";
        $resource = self::resource($query);
        while ($row = $resource->fetch_assoc())
        {
            $rv = "{$row['current_season']}";
        }
        return isset($rv) ? $rv : null;
    }
    private $link;
}