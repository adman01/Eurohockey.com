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
          //typ listu 1
          case 1:
            //tlacidlo zpet
            if ($this->listNumber-$this->ListInterval>=0){
              $backward='<a href="'.$this->ListUrl.'list_number='.($this->listNumber-$this->ListInterval).'" title="Přechozí záznam"><-- Přechozí záznam</a>';
            }
            //tlacidlo dopredu
            if ($this->listNumber+$this->ListInterval<=$this->ListNumTotal){
              $forward='<a href="'.$this->ListUrl.'list_number='.($this->listNumber+$this->ListInterval).'" title="Další záznam">Další záznam --></a>';
            }
            if ($this->ListNumTotal>$this->ListInterval){
            echo '<div id="listing1">';
              echo $backward;
              if (!empty($backward) AND !empty($forward)) echo $this->listSpace;
              echo $forward;
              echo ' <div class="clear">&nbsp;</div>';
            echo '</div>';
            }
            
          break;
          
          //typ listu 2
          case 2:
           
          //$cout_num=0;
            $list_max=9; // POZOR POUZE LICHA CISLA !!!!
            $list_page=(($this->listNumber/$this->ListInterval)+1);
            $last_page=((ceil($this->ListNumTotal/$this->ListInterval)));
            $list_middle=($list_max+1)/2;
            if ($last_page<=$list_max){$list_max=$last_page;}
             
          if ($this->ListNumTotal>$this->ListInterval){
            echo '<div id="listing2">';
            echo '<div class="page">Strana:</div>';
            if ($this->listNumber-$this->ListInterval>=0) echo '<a href="'.$this->ListUrl.'list_number='.($this->listNumber-$this->ListInterval).'" class="prev" title="Přechozí záznam">&laquo; Přechozí</a>';
            
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
                if ($list_page==$i) $sel=' class="selected"'; else $sel='';
                echo '<a href="'.$this->ListUrl.'list_number='.(($this->ListInterval*$i)-$this->ListInterval).'"'.$sel.' title="Strana '.$i.'">'.$i.'</a>';
            }
            if ($list_page<$last_page){
              echo '<a href="'.$this->ListUrl.'list_number='.($this->listNumber+$this->ListInterval).'" class="next" title="Další záznam">Další &raquo;></a>';
            }
            echo ' <div class="clear">&nbsp;</div>';
            echo '</div>';
            }
          break;
          
          
          //typ listu 3
          case 3:
           
            //$cout_num=0;
            if ($this->listNumber==-1){$boolAll=true;}else{$boolAll=false;}
            if ($boolAll==false){
              $list_max=9; // POZOR POUZE LICHA CISLA !!!!
              $list_page=(($this->listNumber/$this->ListInterval)+1);
              $last_page=((ceil($this->ListNumTotal/$this->ListInterval)));
              $list_middle=($list_max+1)/2;
              if ($last_page<=$list_max){$list_max=$last_page;}
            }
          
            echo '<tr class="bg">';

            echo '<td colspan="20">';
            echo '<div id="listing3">';
              
              echo '<div id="listing_pages">';
              echo '<a href="'.$this->ListUrl.'list_number=-1" title="Show all"><span><b>'.$this->ListNumTotal.'</b> items total</span></a>';
              if ($boolAll==false and $this->ListInterval<=$this->ListNumTotal){
              if ($this->listNumber-$this->ListInterval>=0) echo '<a href="'.$this->ListUrl.'list_number='.($this->listNumber-$this->ListInterval).'" class="prev" title="Prev">Prev</a>';
            
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
                  if ($list_page==$i) $sel=' class="selected"'; else $sel='';
                  echo '<a href="'.$this->ListUrl.'list_number='.(($this->ListInterval*$i)-$this->ListInterval).'"'.$sel.' title="Page '.$i.'">'.$i.'</a>';
              }
              if ($list_page<$last_page){
                echo '<a href="'.$this->ListUrl.'list_number='.($this->listNumber+$this->ListInterval).'" class="next" title="Next"><span>Next</span></a>';
              }
            }
              echo '</div>';
            if ($this->ListInterval<=$this->ListNumTotal){ 
            echo '<div id="listing_pages_all">';
              if ($boolAll){
                echo '<a href="'.$this->ListUrl.'" class="ico-show" title="Show all"><span>Show pages</span></a>';
              }else{
                echo '<a href="'.$this->ListUrl.'list_number=-1" class="ico-show" title="Show all"><span>Show all</span></a>';
              }
            echo '</div>';
            }
            
            echo '</div>';
            
              echo '</td>';
            echo '</tr>';
            
            
          break;
          
        }
    }
    
}
?>
