<?php
/** @class: Listing
  * @project: CMS
  * @date: 10-02-2008
  * @version: 1.2.2
  * @author: Martin Formánek
  * @copyright: Martin Formánek
  * @email: martin.formanek@gmail.com
  */
  

class listing {
    var $ListUrl,$ListInterval,$ListSQL,$listType,$listSpace,$listNumber;
    var $error;
    var $con;
    var $ListNumTotal;
  
    //konstruktor
    function listing($con,$ListUrl = "", $ListInterval = 10, $ListSQL = "",$listType=1,$listSpace=" / ",$listNumber=0){
        $this->con = $con;
        $this->ListUrl = $ListUrl;
        $this->ListInterval = $ListInterval;
        $this->ListSQL = $ListSQL;
        $this->listType = $listType;
        $this->listSpace = $listSpace;
        if (Empty($listNumber)) {$listNumber=0;}
        $this->listNumber = $listNumber;
        $this->error[1] = "Chyba !!! Nelze zobrazit listování, nebyl zadán SQL dotaz!\n";
        $this->error[2] = "Chyba !!! Nelze zobrazit listování, chyba databáze(Query)!\n";
        $this->error[3] = "Chyba !!! Nelze zobrazit listování, chyba databáze(Num_Rows)!\n";
        if (Empty($ListSQL)){$this->error(1);}
        else {$this->ListSQL = $ListSQL;}
    }
    
    function updateQuery(){
        if ($this->listNumber==-1){
           return $this->ListSQL;
        }else{
          return $this->ListSQL." LIMIT ".$this->listNumber.",".$this->ListInterval."";
        }
    }
    
    function show_list(){
        $this->ListNumTotal=$this->con->GetQueryNum($this->ListSQL);
        switch ($this->listType){
          
          
          //typ listu 3
          case 3:
           
           if ($this->ListInterval<=$this->ListNumTotal){
           
            //$cout_num=0;
            if ($this->listNumber==-1){$boolAll=true;}else{$boolAll=false;}
            if ($boolAll==false){
              $list_max=9; // POZOR POUZE LICHA CISLA !!!!
              $list_page=(($this->listNumber/$this->ListInterval)+1);
              $last_page=((ceil($this->ListNumTotal/$this->ListInterval)));
              $list_middle=($list_max+1)/2;
              if ($last_page<=$list_max){$list_max=$last_page;}
            }
          
            
            $strPagination= '<div class="newsPagination">';
              
              $strPagination.=  '<span class="inFrame marginRight5">Page '.$list_page.' of '.$list_max.'</span>';
              if ($boolAll==false and $this->ListInterval<=$this->ListNumTotal){
              if ($this->listNumber-$this->ListInterval>=0) $strPagination.= '<a href="'.$this->ListUrl.'list_number='.($this->listNumber-$this->ListInterval).'"  class="inFrame marginRight5" title="Previous">Previous</a>';
            
              if ($this->listNumber==0){
                $list_start=1;
                $list_end=$list_max;
              }else{
            
                if ($list_page<=$list_middle){
                  //start
                  $list_start=1;
                  $list_end=$list_max;
                
                }
                else{
                 if ($list_page>=($last_page-$list_middle)){
                    //konec
                    $list_start=($last_page-$list_max)+1;
                    $list_end=$last_page;
                  }else{
                    //prostredek
                    $list_start=$list_page-($list_middle-1);
                    $list_end=$list_page+($list_middle-1);
                  
                  }
                }
                
              }
              for($i=$list_start;$i<=$list_end;$i++){
                  //if ($list_page==$i) $sel=' class="selected"'; else $sel='';
                  $strPagination.= '<a  class="inFrame marginRight5" href="'.$this->ListUrl.'list_number='.(($this->ListInterval*$i)-$this->ListInterval).'"'.$sel.' title="Page '.$i.'">'.$i.'</a>';
              }
              if ($list_page<$last_page){
                $strPagination.= '<a class="inFrame marginRight5" href="'.$this->ListUrl.'list_number='.($this->listNumber+$this->ListInterval).'" title="Next">Next</a>';
              }
            }
            //if ($this->ListInterval<=$this->ListNumTotal){ 
              //if ($boolAll){
                //echo '<a class="inFrame marginRight5" href="'.$this->ListUrl.'" title="Show all">Show pages</a>';
              //}else{
                //echo '<a class="inFrame marginRight5" href="'.$this->ListUrl.'list_number=-1"  title="Show all">Show all</a>';
              //}
            //}
            
            $strPagination.= '</div>';
            }
            
            
          break;
          
        }
        return $strPagination;
    }
    
     
    
}
?>
