<!DOCTYPE html>
<html>
<head>
    <title>My Docker To-Do App</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; }
        input { padding: 8px; width: 70%; }
        button { padding: 8px 15px; background: #0db7ed; color: white; border: none; cursor: pointer; }
        li { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🐳 Docker To-Do App</h1>

    <form method="POST">
        <input type="text" name="task" placeholder="Enter a task..." required>
        <button type="submit">Add</button>
    </form>

    <?php
    $host = 'db';
    $dbname = 'tododb';
    $user = 'root';
    $pass = 'secret';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS todos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            task VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Insert new task
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
            $stmt = $pdo->prepare("INSERT INTO todos (task) VALUES (?)");
            $stmt->execute([$_POST['task']]);
        }

        // Fetch all tasks
        $todos = $pdo->query("SELECT * FROM todos ORDER BY created_at DESC")->fetchAll();

        echo "<h3>Tasks:</h3><ul>";
        foreach ($todos as $todo) {
            echo "<li>" . htmlspecialchars($todo['task']) . "</li>";
        }
        echo "</ul>";

    } catch (PDOException $e) {
        echo "<p style='color:red'>DB Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>