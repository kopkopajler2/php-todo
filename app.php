<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

// SQLite connection
$pdo = new PDO('sqlite:todos.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "
    CREATE TABLE IF NOT EXISTS todos (
        id INTEGER PRIMARY KEY,
        category TEXT NOT NULL,
        description TEXT NOT NULL
    )
";

$pdo->exec($query);
// Routes
$app->get('/api/todos', function (Request $request, Response $response, $args) use ($pdo) {
    $stmt = $pdo->query('SELECT * FROM todos');
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($todos));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/todos', function (Request $request, Response $response, $args) use ($pdo) {
    $json = $request->getBody();
    $data = json_decode($json, true);

    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';

    if (empty($category) || empty($description)) {
        $response->getBody()->write(json_encode(['error' => 'Category and description are required']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO todos (category, description) VALUES (?, ?)');
        $stmt->execute([$category, $description]);
        $response->getBody()->write(json_encode(['message' => 'Todo added successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
});

$app->delete('/api/todos/{id}', function (Request $request, Response $response, $args) use ($pdo) {
    $id = $args['id'];
    $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ?');
    $stmt->execute([$id]);
    $response->getBody()->write(json_encode(['message' => 'Todo deleted successfully']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/api/todos/{id}', function (Request $request, Response $response, $args) use ($pdo) {
    $id = $args['id'];
    $json = $request->getBody();
    $data = json_decode($json, true);
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';

    if (empty($category) || empty($description)) {
        $response->getBody()->write(json_encode(['error' => 'Category and description are required']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    try {
        $stmt = $pdo->prepare('UPDATE todos SET category = ?, description = ? WHERE id = ?');
        $stmt->execute([$category, $description, $id]);
        $response->getBody()->write(json_encode(['message' => 'Todo updated successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
});

$app->run();
