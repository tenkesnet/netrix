<?php

namespace Netrix;

use ActiveCollab\SDK\{
    Client,
};
use Netrix\model\Project;

/**
 * Description of ACServer
 *
 * @author bardo
 */
class ACServer {

    private ?Client $client = null;
    private int $projectId = 0;
    private $projects;

    /**
     * 
     * @param string $username
     * @param string $password
     * @throws \ActiveCollab\SDK\Exception
     */
    public function __construct(
            string $username = "",
            string $password = ""
    ) {
        //$this->projects=(object)[];
        try {
            $authenticator = new \ActiveCollab\SDK\Authenticator\Cloud(
                    'Bardo', 'Netrix Application',
                    $username,
                    $password
            );
            $account = $authenticator->getAccounts();
            $id = array_key_first($account);
            $token = $authenticator->issueToken($id);

            $this->client = new Client($token);

            $this->projects = $this->client->get('projects/names')->getJson();
            //var_dump($this->projects);
        } catch (\ActiveCollab\SDK\Exception $exc) {
            throw $exc;
        }
    }

    public function connect() {
        //print_r($client->get('projects/5/tasks')->getJson());
//        $response = $client->post("/projects/5/tasks", [
//                'name' => 'Második feladat',
//                'assignee_id' => 5,
//        ]);
//        print_r($response->getBody());
    }

    public function getTasks(int $id): array {
        $result = array();
        $tasks = $this->client->get("projects/$id/tasks")->getJson();

        foreach ($tasks['tasks'] as $task) {
            $result [] = new model\Task(
                    $task['name'],
                    $task['updated_on'],
                    $task['comments_count']
            );
        }
        return $result;
    }

    /**
     * 
     * @return Project[]
     */
    public function getProjectNames(): array {

        $result = array();
        foreach ($this->projects as $key => $value) {
            $result[] = new Project($key, $value);
        }


        return $result;
    }

}
