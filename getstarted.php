<?php

require __DIR__ . '/../vendor/autoload.php';

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

$databaseCollection = $client->selectCollection($_ENV["TIGRIS_PROJECT_NAME"], "dbs");
$customerCollection = $client->selectCollection($_ENV["TIGRIS_PROJECT_NAME"], "customers");

echo "Dropping collections '{$_ENV["TIGRIS_PROJECT_NAME"]}.php' (command)\n";
$databaseCollection->drop();
$customerCollection->drop();

echo "Inserting a single document\n";
$result = $databaseCollection->insertOne([
  'name' => 'MongoDB',
  'type' => 'database',
  'shards' => 1,
  'capacity_planning_required' => true,
  'price' => [
    'db_writes' => floatval(0.1),
    'db_reads' => floatval(1),
    'search_writes' => floatval(-1),
    'search_reads' => floatval(-1)
  ]
]);
$mongoDBId = $result->getInsertedId();
printf("Inserted with MongoDB with ObjectID: %s\n", $mongoDBId);

$result = $databaseCollection->insertOne([
  'name' => 'Tigris',
  'type' => 'database and search platform',
  'shards' => 0,
  'capacity_planning_required' => false,
  'price' => [
    'db_writes' => floatval(0.1),
    'db_reads' => floatval(0.1),
    'search_writes' => floatval(0.4),
    'search_reads' => floatval(0.4)
  ]
]);
$tigrisId = $result->getInsertedId();
printf("Inserted with Tigris with ObjectID: %s\n", $tigrisId);

$result = $databaseCollection->insertOne([
  'name' => 'Algolia',
  'type' => 'search',
  'shards' => 0,
  'capacity_planning_required' => false,
  'price' => [
    'db_writes' => floatval(-1),
    'db_reads' => floatval(-1),
    'search_writes' => floatval(1),
    'search_reads' => floatval(1)
  ]
]);
$algoliaId = $result->getInsertedId();
printf("Inserted with Algolia with ObjectID: %s\n", $algoliaId);

$dataPlatforms = [$mongoDBId, $tigrisId];

$customersToInsert = [];
for ($index = 0; $index < 200; ++$index) {
  $customersToInsert[] = [
    "name" => "Customer {$index}",
    "database_id" => $dataPlatforms[array_rand($dataPlatforms)],
  ];
}

$insertManyResult = $customerCollection->insertMany($customersToInsert);
printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());

echo "Querying customers using find()\n";
$regex = new MongoDB\BSON\Regex('database', 'i');
$result = $customerCollection->find(['database_id' => $tigrisId]);
foreach ($result as $doc) {
  printf("ID: %s, Name: %s\n", $doc['_id'], $doc['name']);
}

echo "Querying databases using find with regex()\n";
$regex = new MongoDB\BSON\Regex('database', 'i');
$result = $databaseCollection->find(['type' => $regex]);
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
