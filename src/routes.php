<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

//Rute GET /books/1
$app->get("books/{id}", function(Request $request, Response $response, $args){
$id = $args["id"];
$sql = "SELECT * FROM books WHERE book_id=:id";
$stmt = $this->db->prepare($sql);
$stmt->execute([":id" => $id]);
$result = $stmt->fetch();
return $response->withJson(["status" => "success","data" => $result],200);
});

//Rute GET /books/search
$app->get("/books/search/", function (Request $request, Response $response, $args){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM books WHERE title LIKE '%$keyword%' OR sinopsis LIKE '%$keyword%' OR author LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

//Rute POST /books
$app->post("/books/", function (Request $request, Response $response){

    $new_book = $request->getParsedBody();

    $sql = "INSERT INTO books (title, author, sinopsis) VALUE (:title, :author, :sinopsis)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":title" => $new_book["title"],
        ":author" => $new_book["author"],
        ":sinopsis" => $new_book["sinopsis"]
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

//Rute PUT /books/1
$app->put("/books/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $new_book = $request->getParsedBody();
    $sql = "UPDATE books SET title=:title, author=:author, sinopsis=:sinopsis WHERE book_id=:id";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id" => $id,
        ":title" => $new_book["title"],
        ":author" => $new_book["author"],
        ":sinopsis" => $new_book["sinopsis"]
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

//Rute DELETE /books/1
$app->delete("/books/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "DELETE FROM books WHERE book_id=:id";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id" => $id
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});
