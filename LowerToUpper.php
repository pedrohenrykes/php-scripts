<?php

require_once "database/Connection.php";

try {

    $table = "**TABLE_NAME**";
    $column = "**COLUMN_NAME**";

    $conn = Connection::open( "database" );

    $objects = $conn->query( "SELECT * FROM {$table}" );

    foreach ( $objects as $value ) {

        $sql = "UPDATE {$table} SET {$column}='" . mb_strtoupper( $value[ $column ], "UTF-8" ) . "' WHERE id={$value['id']}";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }

    $conn = null;

    echo "Registros atualizados com sucesso!";

} catch( PDOException $e ) {

    echo $sql . "<br>" . $e->getMessage();

}
