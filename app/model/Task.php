<?php

namespace App\Model;
use PDO;
use Src\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public function getMainData($user_ids ='', $client_ids ='', $tags ='')
    {
        $sqlOrderBy = " ORDER BY task_id";
        $sqlGroupBy =' GROUP BY task_id';
        $sqlWhere = '';
        $sqlWhere .= ($user_ids != '') ? " WHERE `user_id` IN ($user_ids)" : '';
        $sqlWhere .= ($client_ids != '') ? " WHERE `client_id` IN ($client_ids)" : '';
        $sqlWhere .= ($tags != '') ? " WHERE `tag_id` IN ($tags)" : '';
        $query = $this->conn->prepare("SELECT  `t`.`id` AS task_id, `t`.`name` AS task_name, `u`.`name` AS user_name, u.id AS `user_id`, c.name AS client_name, c.id AS client_id, tg.name AS tag_name, tg.id AS tag_id FROM tasks AS t LEFT OUTER JOIN `users` AS `u` ON `u`.`id`=`t`.`user_id` LEFT OUTER JOIN `clients` AS `c` ON `c`.`id`=`t`.`client_id` LEFT OUTER JOIN `task_tag` AS `tt` ON `t`.`id`=`tt`.`task_id` LEFT OUTER JOIN `tags` AS `tg` ON `tt`.`tag_id`=`tg`.`id` $sqlWhere $sqlOrderBy");
        $query->execute();
        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

 
        foreach($tasks as $task) {
             $data[$task['task_id']]['task_id'] = $task['task_id'];
             $data[$task['task_id']]['task_name'] = $task['task_name'];
             $data[$task['task_id']]['user_id'] = $task['user_id'];
             $data[$task['task_id']]['user_name'] = $task['user_name'];
             $data[$task['task_id']]['client_id'] = $task['client_id'];
             $data[$task['task_id']]['client_name'] = $task['client_name'];
             $data[$task['task_id']]['tags'][] = ['tag_id' => $task['tag_id'], 'tag_name' => $task['tag_name']];
        }

        return $data;
    }

    public function getTaskById($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tasks` WHERE `id` = :id");
        $query->execute([
            ':id' => $id
        ]);

        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function delete($id)
    {
        $queryDeleteTask = $this->conn->prepare("DELETE FROM `tasks` WHERE `id` = :id "); //delete tags too delete from tagsk_tag where id= 
        if ($queryDeleteTask->execute(['id' => $id])) {
                $queryDeleteTaskTags = $this->conn->prepare("DELETE FROM `task_tag` WHERE `task_id` = :task_id ");

                if ($queryDeleteTaskTags->execute(['task_id' => $id])) {
                    return true;
                }
        }
        return false;
    }

    public function save(array $data)
    {
        if (count($data) > 0) {

            $sql = "INSERT INTO `tasks` (`name`, `content`,`user_id`, `client_id`) VALUES(:name, :content, :user_id, :client_id)";
            $queryInsertTask = $this->conn->prepare($sql);

            if ($queryInsertTask->execute([
                'name' => $data['task_name'],
                'content' => $data['task_content'],
                'user_id' => $data['user_id'],
                'client_id' => $data['client_id']
            ])) {
                if (count($data['tag_ids']) > 0) {
                        $taskId = $this->conn->lastInsertId();
                        $values = [];

                        foreach ($data['tag_ids'] as $key => $tag_id) {
                            $values[] = "(:task_id_{$key}, :tag_id_{$key})";
                        }

                        $tagsPreparedStatement = $this->conn->prepare(
                            'INSERT INTO task_tag (`task_id`, `tag_id`) VALUES ' . implode(',', $values)
                        );
                        $params = [];

                        foreach ($data['tag_ids'] as $key => $tag_id) {
                            $params["task_id_{$key}"] = $taskId;
                            $params["tag_id_{$key}"] = $tag_id;
                        }

                        $tagsPreparedStatement->execute($params);
                }
            }
                return true;
            } else {

                return false;
            }
    }

    public function update(array $data)
    {
        if (count($data) > 0) {
            $sql = "UPDATE `tasks` SET `name` = :name, `content` = :content,`user_id` = :user_id, `client_id` = :client_id WHERE id = :id";
            $queryUpdateTask = $this->conn->prepare($sql);
            // var_dump($data['tag_ids']);die;
            if ($queryUpdateTask->execute([
                'name' => $data['task_name'],
                'content' => $data['task_content'],
                'user_id' => $data['user_id'],
                'client_id' => $data['client_id'],
                'id' => $data['id']
                ])) {
                    
                $queryDeleteTaskTags =  $this->conn->prepare("DELETE FROM `task_tag` WHERE `task_id` = :task_id ");
                
                if ($queryDeleteTaskTags->execute(['task_id' => $data['id']])) {

                    $values = [];

                    if (isset($data['tag_ids'])) {

                        foreach ($data['tag_ids'] as $key => $tag_id) {
                            $values[] = "(:task_id_{$key}, :tag_id_{$key})";
                        }

                        $tagsReInsertStatement = $this->conn->prepare('INSERT INTO task_tag (`task_id`, `tag_id`) VALUES ' . implode(',', $values));

                        $params = [];

                        foreach ($data['tag_ids'] as $key => $tag_id) {
                            $params["task_id_{$key}"] = $data['id'];
                            $params["tag_id_{$key}"] = $tag_id;
                        }
                        // echo "<pre>";var_dump($params, $values);die;
                        if ($tagsReInsertStatement->execute($params)) {

                             return true;
                        }
                        return false;      
                    }
                }
            }
        }


    }

}