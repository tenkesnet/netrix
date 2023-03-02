<?php

require './vendor/autoload.php';

use Netrix\ACServer;
use Netrix\Template;

$template = new Template();

session_start();

if (empty($_SESSION['acserver']) && (filter_input(INPUT_GET, 'login') || filter_input(INPUT_GET, 'project'))) {
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    if ($username && $password) {
        try {
            $server = new ACServer(
                    $username,
                    $password
            );
            $_SESSION['acserver'] = serialize($server);
        } catch (Exception $exc) {
            echo "Nem sikerült az Active Collab bejelentkezés";
            return;
        }
    } else {
        header("Location: /index.php");
        exit();
    }
}
if (empty($server)) {
    $server = unserialize($_SESSION['acserver']);
}

if (filter_input(INPUT_GET, 'project')) {
    $projectId = filter_input(INPUT_GET, 'project');
    $tasks = $server->getTasks($projectId);
    $render = "<table class='table table-hover'>";
    $render .= "<th>Feladat név</th><th>Módosítás dátuma</th><th>Hozzászólások száma</th>";
    usort($tasks, [Netrix\model\Task::class, "descSort"]);
    foreach ($tasks as $task) {
        $render .= "<tr><td>" . $task->name . "</td><td>" . date('Y.m.d H:i:s',$task->updated_on). "</td><td>".$task->comments_count."</tr>";
    }
    $render .= "</table>";
    $template->renderMain($render);
    exit();
}

if (!filter_input(INPUT_GET, 'login') && !filter_input(INPUT_GET, 'logout')) {
    $template->renderLogin();
    exit();
}

if (filter_input(INPUT_GET, 'logout')) {
    $template->renderLogin();
    echo "<p>Logout....</p>";
    $_SESSION = array();
    session_destroy();
    exit();
}

$projects = $server->getProjectNames();
$result = "<p>Projectek:</p><ul>";
foreach ($projects as $project) {
    $result .= "<li><a href='/index.php?project=$project->id'>$project->name</a></li>";
}
$result .= "</ul>";

$template->renderMain($result);

