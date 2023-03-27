<?php

require __DIR__.'/../vendor/autoload.php';

echo "Connecting to Tigris: {$_ENV["TIGRIS_URI"]}\n";

$client = new MongoDB\Client(
  $_ENV["TIGRIS_URI"],
  [
      'username' => $_ENV["TIGRIS_CLIENT_ID"],
      'password' => $_ENV["TIGRIS_CLIENT_SECRET"],
      'authMechanism' => 'PLAIN',
      'ssl' => true
  ],
);

$collection = $client->selectCollection($_ENV["TIGRIS_PROJECT_NAME"], "php");

echo "Dropping collection '{$_ENV["TIGRIS_PROJECT_NAME"]}.php' (command)\n";
$collection->drop(); 

echo "Inserting a single document\n";
$result = $collection->insertOne( [ 'name' => 'MongoDB', 'type' => 'database', 'count'=>1, 'info'=>['x'=>203] ] );
printf("Inserted with ObjectID: %s\n", $result->getInsertedId() );

$insertManyResult = $collection->insertMany([
  [
    'name' => 'MongoDB',
    'type' => 'database',
    'count' => 1,
    'info'=>['x'=>201] 
  ],
  [
    'name' => 'MongoDB',
    'type' => 'database',
    'count' => 1,
    'info'=>['x'=>20] 
  ],
  [
    'name' => 'Tigris',
    'type' => 'database and search platform',
    'count' => 1,
    'info'=>['x'=>20] 
  ],
]);
printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());

echo "Querying using find()\n";
$regex = new MongoDB\BSON\Regex('database', 'i');
$result = $collection->find( [ 'type' => $regex ] );
foreach ($result as $doc) {
  printf("ID: %s, Name: %s\n", $doc['_id'], $doc['name']);
}

/* Aggregation */
// Aggregation not yet supported with Tigris MongoDB compatibility
// printf("Aggregation result: \n");
// $result = $collection->aggregate([
//   [ '$group' => ['_id' => 'null', 'total' => ['$sum' => '$info.x'] ] ],
// ]);
// foreach ($result as $doc) {
//     var_dump($doc);
// }
// printf("Finished.\n")
?>
