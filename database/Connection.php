<?php

final class Connection
{
    private function __construct() { }

    public static function open( $inifile )
    {
        $dbinfo = self::getConfigFile( $inifile );

        if ( !$dbinfo ) {
            throw new Exception( "O arquivo " . "'{$inifile}.ini'" . " não foi encontrado.");
        }

        return self::getDatabaseInfo( $dbinfo );
    }

    public static function getDatabaseInfo( $dbinfo )
    {
        $user  = isset( $dbinfo["user"] ) ? $dbinfo["user"] : NULL;
        $pass  = isset( $dbinfo["pass"] ) ? $dbinfo["pass"] : NULL;
        $name  = isset( $dbinfo["name"] ) ? $dbinfo["name"] : NULL;
        $host  = isset( $dbinfo["host"] ) ? $dbinfo["host"] : NULL;
        $type  = isset( $dbinfo["type"] ) ? $dbinfo["type"] : NULL;
        $port  = isset( $dbinfo["port"] ) ? $dbinfo["port"] : NULL;
        $type  = strtolower( $type );

        switch ( $type ) {

            case "pgsql":
                $port = $port ? $port : "5432";
                $conn = new PDO( "pgsql:dbname={$name};user={$user};password={$pass};host=$host;port={$port}" );
                break;

            case "mysql":
                $port = $port ? $port : "3306";
                $conn = new PDO( "mysql:host={$host};port={$port};dbname={$name}", $user, $pass, [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ] );
                break;

            default:
                throw new Exception( "Driver não encontrado: " . $type );
                break;
        }

        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        return $conn;
    }

    public static function getConfigFile( $inifile )
    {
        $inifile = "config/{$inifile}.ini";

        if ( file_exists( $inifile ) ) {
            return parse_ini_file( $inifile );
        } else {
            return FALSE;
        }
    }
}
