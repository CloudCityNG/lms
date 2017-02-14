<?php

/**
 ==============================================================================

 ==============================================================================
 */

class webservice {

            //           获取课程分类函数
            public static function course_interface ($type , $parent_id , $page_size , $page) {

                    if($type ){
                        if($type == 1){    //一级分类
                            $sql="select id, title, subclass from setup where 1";

                            $total_count_query = api_sql_query_array_assoc( $sql, __FILE__, __LINE__ );
                            $count=count($total_count_query);
                            $count_page = intval($count / $page_size) + 1;
                             if($page_size && $page){
                                        $start=$page_size * ($page-1);
                                        $sql.=" limit $start , $page_size ";
                                }
                            $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                            $classify_rows = array ();
                            while ( $classify_row = Database::fetch_row ( $res ) ) {
                                       $course_type=1;
                                       $classify_name=$classify_row[1];
                                       $classify_id=$classify_row[0];
                                       $list[]=array(
                                                   'type' => $type,
                                                   'data' =>array(
                                                           'type' => $course_type,
                                                           'name' => $classify_name,
                                                           'id'   => $classify_id,
                                                           'page_size' => $page_size,
                                                           'page' => $page,
                                                           'count_page' => $count_page,
                                                           'source' => null
                                                           )
                                               );
                            }
                        }elseif ($type == 2) {    //二级分类
                                $sql="select id, name from course_category where parent_id=0 ";
                                if($parent_id){
                                        $parent_sql=Database::getval("select subclass from setup where id=$parent_id");
                                        $parent_ids=explode(",",$parent_sql,-1);
                                        $sql.="and ( ";
                                        $i=1;
                                        foreach($parent_ids as $value){                
                                                if($i==1){
                                                    $sql.=" id = $value";
                                                    $i++;
                                                }else{
                                                    $sql.=" or id = $value";
                                               }             
                                       }
                                        $sql.=" ) ";
                                }
                                $total_count_query = api_sql_query_array_assoc( $sql, __FILE__, __LINE__ );
                                $count=count($total_count_query);
                                $count_page = intval($count / $page_size) + 1;
                                if($page_size && $page){
                                        $start=$page_size * ($page-1);
                                        $sql.=" limit $start , $page_size ";
                                }
                                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                $classify_rows = array ();
                                while ( $classify_row = Database::fetch_row ( $res ) ) {
                                           $course_type=2;
                                           $classify_name=$classify_row[1];
                                           $classify_id=$classify_row[0];
                                           $list[]=array(
                                                       'type' => $type,
                                                       'data' =>array(
                                                                   'type' => $course_type,
                                                                   'name' => $classify_name,
                                                                   'id'   => $classify_id,
                                                                   'page_size' => $page_size,
                                                                   'page' => $page,
                                                                   'count_page' => $count_page,
                                                                   'source' => null,
                                                 )
                                           );   
                                }
                       }elseif ($type == 3) {    //三级分类
                                $sql="select id, name from course_category where parent_id !=0 ";
                                if($parent_id){
                                       $sql.=" and  parent_id = $parent_id";
                                }
                                $total_count_query = api_sql_query_array_assoc( $sql, __FILE__, __LINE__ );
                                $count=count($total_count_query);
                                $count_page = intval($count / $page_size) + 1;
                                if($page_size && $page){
                                        $start=$page_size * ($page-1);
                                        $sql.=" limit $start , $page_size ";
                                }
                                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                $classify_rows = array ();
                                while ( $classify_row = Database::fetch_row ( $res ) ) {
                                           $course_type = 3;
                                           $classify_name=$classify_row[1];
                                           $classify_id=$classify_row[0];
                                           $list[]=array(
                                                       'type' => $type,
                                                       'data' =>array(
                                                                   'type' => $course_type,
                                                                   'name' => $classify_name,
                                                                   'id'   => $classify_id,
                                                                   'page_size' => $page_size,
                                                                   'page' => $page,
                                                                   'count_page' => $count_page,
                                                                   'source' => null,
                                                 )
                                           );   
                                }
                       }  elseif ($type == 4) {    //课程
                                $sql="select code , title , description2  from course where 1 ";
                                    if($parent_id){
                                       $sql.=" and  category_code = $parent_id";
                                }
                                $total_count_query = api_sql_query_array_assoc( $sql, __FILE__, __LINE__ );
                                $count=count($total_count_query);
                                $count_page = intval($count / $page_size) + 1;
                                if($page_size && $page){
                                        $start=$page_size * ($page-1);
                                        $sql.=" limit $start , $page_size ";
                                }
                                $res = api_sql_query( $sql, __FILE__, __LINE__ );
                                $classify_rows = array ();
                                while ( $classify_row = Database::fetch_row ( $res ) ) {
                                           $course_type =4;
                                           $classify_name = $classify_row[1];
                                           $classify_id = $classify_row[0];
                                           $source = $classify_row[2];
                                           $list[]=array(
                                                       'type' => $type,
                                                       'data' =>array(
                                                           'type' => $course_type,
                                                           'name' => $classify_name,
                                                           'id'   => $classify_id,
                                                           'page_size' => $page_size,
                                                           'page' => $page,
                                                           'count_page' => $count_page,
                                                           'source' => $source,
                                                 )
                                           );   
                                }
                       } 
                       return json_encode($list);  
                }

            }
           
}