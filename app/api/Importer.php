<?php

require_once('Connector.php');

class Importer extends Connector
{
    /*  API запрос к freelancehunt */
    public function get_projects($page=NULL){
        $headers = array(
            "Content-type: application/json",
            'Authorization: Bearer '.$this->config->token
        );
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, ($page?$page:$this->config->api_url));  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);   
    
        $server_output = curl_exec($ch);  
        return json_decode($server_output);
    }
    /* Добавляем прект либо обновляем если такой существует */
    public function set_project($data){
        $rows = $this->db->query('SELECT * FROM __testtable WHERE pid=? LIMIT 1',$data->pid);
         
        if(!$rows->lengths)
        {
            $query = $this->db->placehold("INSERT INTO __testtable SET ?%", $data);  
            return $this->db->query($query); 
        }else{
            $query = $this->db->placehold("UPDATE __testtable SET ?% WHERE pid=?", $data, $data->pid);
		    return $this->db->query($query);
        }
    }
    /* Обновляем таблицу скилов  */
    public function set_project_skills($project_id,$data){
        /* очищаем таблицу скилов по проекту */
        $query = $this->db->placehold("DELETE FROM __skills WHERE project_id=?", $project_id);
		$this->db->query($query); 
        
        foreach($data as $k=>$v)
        { 
            $arr = array(
                'skill_id'=>$v->id,
                'project_id'=>$project_id,
                'skill_name'=>$v->name
            );
            $query = $this->db->placehold("INSERT INTO __skills SET ?%", $arr);   
            $this->db->query($query);
        }
    }
    public function get_skills(){ 
        $this->db->query('SELECT skill_id, skill_name FROM __skills  GROUP BY `skill_id` ORDER BY `skill_id`');
        $rows = $this->db->results();  
        return $rows;
    }
    /* Делаем выборку проектов */
    public function get_projects_filer($filter=array()){ 

        $limit = 10;
        $page = 1;
        $budget_min = '';
        $budget_max = '';
        $skills = '';

        if(!empty($filter['page']))
			$page = max(1, intval($filter['page']));
		
        if(!empty($filter['limit']))
			$limit = max(1, intval($filter['limit']));

        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page-1)*$limit, $limit);

        if(!empty($filter['budget'])) 
            if(!empty($filter['budget']->min))
                $budget_min = $this->db->placehold('AND tt.budget>?', intval($filter['budget']->min));
            if(!empty($filter['budget']->max))
                $budget_max = $this->db->placehold('AND tt.budget<?', intval($filter['budget']->max));

        if(!empty($filter['skills'])){
            $skills = $this->db->placehold('INNER JOIN __skills s ON s.project_id = tt.pid AND s.skill_id in(?@)', (array)$filter['skills']);
        }

        $query = $this->db->placehold("SELECT 
                            tt.id,
                            tt.pid,
                            tt.date,
                            tt.budget,
                            tt.json_data  
                          FROM __testtable tt 
                                  $skills  
                                  WHERE 1  
                                  $budget_min
                                  $budget_max
                                  $sql_limit");  
        $this->db->query($query);
        $rows = $this->db->results();  
        foreach($rows as $v){ 
            $v->json_data = unserialize($v->json_data);
        } 
        return $rows;
    }
    public function get_projects_filer_count($filter=array()){ 
 
        $budget_min = '';
        $budget_max = '';
        $skills = '';
  
        if(!empty($filter['budget'])) 
            if(!empty($filter['budget']->min))
                $budget_min = $this->db->placehold('AND tt.budget>?', intval($filter['budget']->min));
            if(!empty($filter['budget']->max))
                $budget_max = $this->db->placehold('AND tt.budget<?', intval($filter['budget']->max));

        if(!empty($filter['skills'])){
            $skills = $this->db->placehold('INNER JOIN __skills s ON s.project_id = tt.pid AND s.skill_id in(?@)', (array)$filter['skills']);
        }

        $query = $this->db->placehold("SELECT 
                            count(distinct tt.id) as count
                          FROM __testtable tt
                                $skills
                                  WHERE 1  
                                  $budget_min
                                  $budget_max");
                                  
        $this->db->query($query);
        $rows = $this->db->result('count'); 
        return intval($rows);
    }
    public function get_projects_filered_skills($filter=array()){ 
 
        $skills = '';
  
        if(!empty($filter['skills'])){
            $skills = $this->db->placehold('INNER JOIN __skills s ON s.project_id = tt.pid AND s.skill_id in(?@)', (array)$filter['skills']);
        }

        $query = $this->db->placehold("SELECT 
                            tt.budget 
                          FROM __testtable tt 
                                  $skills ");  
        $this->db->query($query);
        $rows = $this->db->results('budget');  
        return $rows;
    }
}

?>