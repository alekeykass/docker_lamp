<?php
header('Access-Control-Allow-Origin: *');

require_once('./api/Connector.php'); 
$connector = new Connector();

if($_GET['import_projects']){

$curr = json_decode(file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5'));
$curr_object = (object)array();
foreach($curr as $v){
    if($v->base_ccy=="UAH"){
        $curr_object->{($v->ccy=='RUR'?'RUB':$v->ccy)} = ($v->buy+$v->sale)/2;
    }
} 

function do_parse($next=NULL){
    global $connector,$curr_object;

    /* получаем проекты, если $next page равно NULL начинаем с первой страницы */ 
    $projects = $connector->importer->get_projects($next);
  
    foreach($projects->data as $k=>$v)
    { 
        /* Формируем обьект для записи в базу при этом проверяем amount если отсутствует значить 0 */
        $amount = ($v->attributes->budget->amount?$v->attributes->budget->amount:0);
        /* выполняем конвертацию если валюта не UAH*/
        if($amount>0&&$v->attributes->budget->currency!="UAH")
        { 
            $amount = $amount*$curr_object->{$v->attributes->budget->currency}; 
        }
        $data = (object)array(
            'pid'=>$v->id,
            'json_data'=>serialize($v),
            'budget'=>$amount
        );  
        $connector->importer->set_project($data);
        /* В отдельную таблицу запишем skills для дальнейшей фильтрации */ 
        $connector->importer->set_project_skills($v->id,$v->attributes->skills);
    }
    /* Если в полученом обьекте $projects пристутствует ссылка на следующую страницу вызываем do_parse */
    if($projects->links->next){
        return do_parse($projects->links->next);
    } else{
        exit('Finish');
    }

}
/* запустим  */
do_parse();

  
}elseif($_GET['api']=="get_list"){
    $limit = $connector->request->get('limit');
    $page = $connector->request->get('page');
    $data = json_decode( file_get_contents('php://input') );
    
    $budget = ($data->budget?$data->budget:'');
    $skills = ($data->skills?$data->skills:'');
 
    $json = $connector->importer->get_projects_filer(array(
        'limit'     =>$limit,
        'page'      =>$page,
        'budget'    =>$budget,
        'skills'    =>$skills
    ));
    $json_cunt = $connector->importer->get_projects_filer_count(array(
        'budget'    =>$budget,
        'skills'    =>$skills
    ));
    $json_filtered_skills = $connector->importer->get_projects_filered_skills(array(
        'skills'    =>$skills
    )); 
    $pie_skills = array(
        'type_a'=>0,
        'type_b'=>0,
        'type_c'=>0,
        'type_d'=>0
    );
    foreach($json_filtered_skills as $v){
        if($v<=500){
            $pie_skills['type_a']++;
        }
        if($v>500&&$v<=1000){
            $pie_skills['type_b']++;
        }
        if($v>1000&&$v<=5000){
            $pie_skills['type_c']++;
        }
        if($v>5000){
            $pie_skills['type_d']++;
        }
    }
    
    header("Content-type: application/json; charset=UTF-8");
    header("Cache-Control: must-revalidate");
    header("Pragma: no-cache");
    header("Expires: -1");	
 
    echo json_encode(array('data'=>$json,'count'=>$json_cunt,'pie_skills'=>$pie_skills));
}
elseif($_GET['api']=="get_skills"){ 

    $json = $connector->importer->get_skills(); 
    
    header("Content-type: application/json; charset=UTF-8");
    header("Cache-Control: must-revalidate");
    header("Pragma: no-cache");
    header("Expires: -1");	
 
    echo json_encode(array('data'=>$json));
}

?>