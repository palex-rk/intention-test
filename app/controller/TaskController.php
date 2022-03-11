<?php

namespace App\Controller;

use App\Model\Task;
use Src\Controller;
use Src\Model;

class TaskController extends Controller
{
    protected $task;

    public function __construct(Model $model, Task $task)
    {
        $this->task = $task;
        parent::__construct($model);
    }

    public function index()
    {
        $tasks = $this->task->getMainData(); // get all tasks related data
    
        $this->view('tasks\index.twig', ['tasks' => $tasks, 'title' => 'Tasks view title']);
    }

    public function tasksAll()
    {
        $tasks = $this->task->getMainData();
        $this->view('tasks\tasksAll.twig', ['tasks' => $tasks]);
    }
    
    public function create()
    {
        $data['users'] = $this->dbh->all('users');
        $data['clients'] = $this->dbh->all('clients');
        $data['tags'] = $this->dbh->all('tags');

        $this->view('tasks\create.twig', ['title' => 'Create new task', 'data' => $data]);
    }

    public function show()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']); // this should be done on other way
 
        $id = array_pop($uri);
        $data['task'] = $this->task->getTaskById($id);
        $data['users'] = $this->dbh->all('users');
        $data['clients'] = $this->dbh->all('clients');
        $data['tags'] = $this->dbh->all('tags');

        $this->view('tasks\show.twig', ['data' => $data, 'title' => 'Tasks view']);
    }

    public function search()
    {
        $userIds = $clientIds = $tags = '';

        foreach ($_POST as $key => $value) {

            if ($key == "user_id") {
                $userIds = implode(', ', $value);
            } elseif ($key == "client_id") {
                $clientIds = implode(', ', $value);
            } elseif ($key == "tag_id") {
                $tags = implode(', ', $value);
            }
        }

        $tasks = $this->task->getMainData($userIds, $clientIds, $tags);

        $this->view('tasks\index.twig', ['tasks' => $tasks, 'title' => 'Tasks filtered']);

    }

    public function save()
    {
        if (!empty($_POST['task_name']) && !empty($_POST['task_content']) && $_POST['user_id'] && $_POST['client_id'] ) {

            $task['task_name'] = strip_tags($_POST['task_name']);
            $task['task_content'] = strip_tags($_POST['task_content']);
            $task['user_id'] = (int) ($_POST['user_id']);
            $task['client_id'] = (int) ($_POST['client_id']);
            $task['tag_ids'] = array_map('intval', $_POST['tag_ids']);

            ($this->task->save($task)) ? header("Location: /task/index?message_type=success&message=Task saved Successfuly.") : header("Location: task/index?message_type=danger");
        } else {
            header("Location: /task/index?message_type=danger&message=Invalid arguments");
        }
}

    public function update()
    {
        if (!empty($_POST['task_name']) && !empty($_POST['task_content']) && $_POST['user_id'] && $_POST['client_id'] ) {
            
            $task['id'] = (int) strip_tags($_POST['task_id']);
            $task['task_name'] = strip_tags($_POST['task_name']);
            $task['task_content'] = strip_tags($_POST['task_content']);
            $task['user_id'] = (int) ($_POST['user_id']);
            $task['client_id'] = (int) ($_POST['client_id']);
            
            if (is_array($_POST["tag_ids"])) {
                $task['tag_ids'] = array_map('intval', $_POST['tag_ids']);
            }
// var_dump($task);die;
            if ($this->task->update($task)) {
                header("Location: /task/index?message_type=success&message=Task saved Successfuly.");
            } 
                
        } else {
            header("Location: /task/index?message_type=danger&message=Invalid arguments");
        }
        header("Location: /task/index?message_type=success&message=Task saved Successfuly.");
    }

    public function delete()
    {
        $id = (int) $_POST['task_id'];

        if ($this->task->delete($id)) {
            $location = "/task/tasksAll?message_type=success&message=Deleted Successfuly.";
            header("Location: $location ");
        } else {
            $location = "/task/tasksAll?message_type=danger?message=Something went wrong.";
            header("Location: $location ");
        }
    }
}