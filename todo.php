<?php
$tasks = json_decode(file_get_contents('tasks.json'), true);

//if empty, make new array
if (!is_array($tasks)) {
    $tasks = [];
}

// new task
/* isset checks if $_POST['task'] is assigned
$_POST contains all data submitted from a form in html with the key being the name of the input
*/
if (isset($_POST['task'])) {
    $newTask = [
        'text' => $_POST['task'],
        'done' => false
    ];
    
    $tasks[] = $newTask; // appends newtask

    file_put_contents('tasks.json', json_encode($tasks, JSON_PRETTY_PRINT));
}

// marks as done
if (isset($_GET['done'])) {
    $id = $_GET['done']; // 'done' is index of task to be marked as done

    if (isset($tasks[$id])) {
        $tasks[$id]['done'] = true;
        $tmp = $tasks[$id];
        unset($tasks[$id]);
        $tasks[] = $tmp;
        file_put_contents('tasks.json', json_encode($tasks, JSON_PRETTY_PRINT));
    }

    header("Location: todo.php");
    exit;
}

// delete task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if (isset($tasks[$id])) {
        unset($tasks[$id]);
        $tasks = array_values(($tasks));
        file_put_contents('tasks.json', json_encode($tasks, JSON_PRETTY_PRINT));
    }

    header("Location: todo.php");
    exit;
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>PHP To-Do List</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h1>My To-Do List</h1>

        <form method="POST">
            <input type="text" name="task" placeholder="New task" required>
            <button type="submit">Add</button>
        </form>

        <ul>
            <?php foreach ($tasks as $index => $task): ?>
                <li class="<?= $task['done'] ? 'done' : 'not-done' ?>">
                    <span class="text">
                        <?php if ($tasks[$index]['done']): ?>
                            <s><?= htmlspecialchars($task['text']) ?></s>
                        <?php else: ?>
                            <?= htmlspecialchars($task['text']) ?>
                        <?php endif; ?>
                    </span>
                    <span class="actions">
                        <?php if ($tasks[$index]['done']): ?>
                            <a class="delete" href="?delete=<?= $index ?>">Delete</a>
                        <?php else: ?>
                            <a href="?done=<?= $index ?>">Done</a>
                            <a class="delete" href="?delete=<?= $index ?>">Delete</a>
                        <?php endif; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </body>
</html>