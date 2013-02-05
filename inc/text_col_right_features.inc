          <div class="features">
            
            <div class="header_cufon blue_200"><span class="header_text blue">Features</span></div>
            
            <?php
            $intIdBoxStatus=$con->GetSQLSingleResult("SELECT show_item as item FROM static_texts WHERE id=16");
            if ($intIdBoxStatus==1){
            $strNameOfBox=$con->GetSQLSingleResult("SELECT header as item FROM static_texts WHERE id=16");
            $strNameOfBox = str_replace ("!features: ", "",$strNameOfBox);
            ?>
            <div class="box">
                <?php echo get_static_text(16); ?>
            </div>
            <div class="header features"><span class="header_text"><?php echo $strNameOfBox;?></span></div>
            <div class="space">&nbsp;</div>
            <?php } ?>
            
            <?php
            $intIdBoxStatus=$con->GetSQLSingleResult("SELECT show_item as item FROM static_texts WHERE id=17");
            if ($intIdBoxStatus==1){
            $strNameOfBox=$con->GetSQLSingleResult("SELECT header as item FROM static_texts WHERE id=17");
            $strNameOfBox = str_replace ("!features: ", "",$strNameOfBox);
            ?>
            <div class="box">
                <?php echo get_static_text(17); ?>
            </div>
            <div class="header features"><span class="header_text"><?php echo $strNameOfBox;?></span></div>
            <div class="space">&nbsp;</div>
            <?php } ?>
            
            <?php
            $intIdBoxStatus=$con->GetSQLSingleResult("SELECT show_item as item FROM static_texts WHERE id=18");
            if ($intIdBoxStatus==1){
            $strNameOfBox=$con->GetSQLSingleResult("SELECT header as item FROM static_texts WHERE id=18");
            $strNameOfBox = str_replace ("!features: ", "",$strNameOfBox);
            ?>
            <div class="box">
                <?php echo get_static_text(18); ?>
            </div>
            <div class="header features"><span class="header_text"><?php echo $strNameOfBox;?></span></div>
            <div class="space">&nbsp;</div>
            <?php } ?>
            
            <?php
            $intIdBoxStatus=$con->GetSQLSingleResult("SELECT show_item as item FROM static_texts WHERE id=19");
            if ($intIdBoxStatus==1){
            $strNameOfBox=$con->GetSQLSingleResult("SELECT header as item FROM static_texts WHERE id=19");
            $strNameOfBox = str_replace ("!features: ", "",$strNameOfBox);
            ?>
            <div class="box">
                <?php echo get_static_text(19); ?>
            </div>
            <div class="header features"><span class="header_text"><?php echo $strNameOfBox;?></span></div>
            <div class="space">&nbsp;</div>
            <?php } ?>
            
          </div>