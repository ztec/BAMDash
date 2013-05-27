<?php
require_once __DIR__ . '/../vendor/autoload.php';

$filter = '.*(.*).*';


$app = new Silex\Application();
$app['debug'] = true;
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => __DIR__ . '/../views',
    )
);
$app->get(
    '/',
    function () use ($app, $filter) {
        $dao = new \Ztec\BamDash\Dao\BambooData("http://ci-tools/bamboo/rest/api/latest/");
        $plans = $dao->getAllPlans();


        $allPlans = array();
        foreach ($plans->plans->plan as $plan) {
            $branches = $dao->getBranches($plan->key);
            $builds = $dao->getBuilds($plan->key);
            $status = $dao->getBranchStatus($plan->key);
            $plan->builds = $builds->results->result;
            $plan->status = $status;
            $plan->name = $plan->name. ' master' ;
            $plan->shortName = 'master' ;
            $allPlans[] = $plan;
            foreach ($branches->branches->branch as $branch) {
                if (preg_match('/' . $filter . '/', $branch->name)) {
                    $builds = $dao->getBuilds($branch->key);
                    $status = $dao->getBranchStatus($branch->key);
                    $branch->builds = $builds->results->result;
                    $branch->status = $status;
                    $allPlans[] = $branch;
                }
            }
        }
        return $app['twig']->render(
            'index.twig',
            array(
                'sortage' => false,
                'branches' => $allPlans,
                'time' =>date('F j, Y, g:i:s a')
            )
        );
    }
);
$app->run();