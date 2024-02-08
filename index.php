<?php

require_once "classes/Database.php";
require_once "classes/Todo.php";


$error = '';

// Подключение БД
$db = new Database();
$db->getConnect();
$conn = $db->getConnect();

// Добавление в БД задачу
if (isset($_POST['add-task'])) {
  if (empty($_POST['text'])) {
    $error = "Поле не должно быть пустым";
  } else {
    $text = $_POST['text'];
    $todo = new Todo($conn);
    $todo->addTask($text);
    header("Location: /");
  }
}

//Удаление задачи
if (isset($_POST['delete-task'])) {
  $id = $_POST['id'];
  $todo = new Todo($conn);
  $todo->deleteTask($id);
  header("Location: /");
}

// Проверка выполнения задачи
if (isset($_POST['toggle-task-status'])) {
  $id = $_POST['toggle-task-status'];
  $isDone = $_POST['is-done']; // Получаем текущее состояние задачи
  $isDone = $isDone == 1 ? 0 : 1; // Инвертируем состояние задачи
  $todo = new Todo($conn);
  $todo->updateTaskStatus($id, $isDone); // Обновляем состояние задачи
  header("Location: /");
  exit;
}

// Вывод задач из БД
$allTask = new Todo($conn);
$getTask = $allTask->getAllTask();

// Подсчет количества завершенных задач
$completedTasks = $allTask->getCompletedTasks();
$completedTasksCount = count($completedTasks);

$result_arr = count($getTask);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/reset.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <title>Todo</title>
</head>

<body>
  <div class="wrapper">
    <div class="todos-top">
      <a href="/" class="todos-top__logo">
        <img src="/assets/img/roket.svg" alt="">
        <h4>To<span>do</span></h4>
      </a>
    </div>
    <div class="todos-main">
      <form method="post" action="" class="input-form">
        <input type="text" name="text" placeholder="Добавить новую задачу">
        <button type="submit" name="add-task">
          <span>Создать</span>
          <img src="/assets/img/add-icons.svg" alt="">
        </button>
      </form>
      <?php if (!empty($error)) : ?>
        <p class="error"><?= $error; ?></p>
      <?php endif; ?>
      <div class="items-info-task-and-done">
        <div class="task-all-info">
          Задачи созданы
          <span>
            <?= $result_arr ?>
          </span>
        </div>
        <div class="task-all-info task-has-done <?= $completedTasksCount > 0 ? 'completed' : '' ?>">
          Завершенный
          <span>
            <?= $completedTasksCount ?> из <?= $result_arr ?>
          </span>
        </div>
      </div>

      <?php if (empty($getTask)) : ?>
        <div class="empty-items">
          <img src="/assets/img/clipboard.svg" alt="">
          <p>
            <span>У вас еще нет зарегистрированных задач</span> <br>
            Создавайте задачи и организуйте свои дела
          </p>
        </div>
      <?php else : ?>
        <div class="items">
          <ul class="task">
            <?php foreach ($getTask as $task) : ?>
              <li class="item<?= $task['done'] ? ' done' : ''; ?>">
                <form action="" method="post" class="task-info">
                  <button class="text <?= $task['done'] ? 'line-through' : ''; ?>" type="submit" name="toggle-task-status" value="<?= $task['id']; ?>">
                    <input id="check<?= $task['id']; ?>" value="<?= $task['id']; ?>" class="check" type="checkbox" name="id" <?= $task['done'] ? 'checked' : ''; ?>>
                    <label for="check<?= $task['id']; ?>" id="custom-checkbox"></label>
                    <?= $task['text']; ?>
                  </button>
                  <input type="hidden" name="check-task">
                  <input type="hidden" name="is-done" value="<?= $task['done'] ?>">
                </form>
                <form action="" method="post" class="deleted">
                  <button type="submit" name="delete-task">
                    <input type="hidden" value="<?= $task['id'] ?>" name="id">
                    <img src="/assets/img/trash.svg" alt="">
                  </button>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

<script>
  <?php foreach ($getTask as $task) : ?>
    document.getElementById('check<?= $task['id']; ?>').addEventListener('click', function(event) {
      event.preventDefault();
    });
  <?php endforeach; ?>
</script>

</html>